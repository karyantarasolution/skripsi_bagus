<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;

class NetworkScanner
{
    protected $subnet;
    protected $localIp;

    public function __construct()
    {
        $this->localIp = $this->getLocalIp();
        $this->subnet = $this->getSubnet();
    }

    public function getLocalIp(): string
    {
        if ($this->localIp) return $this->localIp;

        $os = PHP_OS_FAMILY;
        if ($os === 'Windows') {
            $output = shell_exec('ipconfig 2>nul');
            if (preg_match_all('/IPv4 Address[^\r\n]*:\s*([0-9]+\.[0-9]+\.[0-9]+\.[0-9]+)/i', $output, $matches)) {
                foreach ($matches[1] as $ip) {
                    if ($ip !== '127.0.0.1') {
                        $this->localIp = $ip;
                        return $ip;
                    }
                }
            }
        } else {
            $output = shell_exec("hostname -I 2>/dev/null");
            if ($output) {
                $ips = explode(' ', trim($output));
                foreach ($ips as $ip) {
                    if ($ip !== '127.0.0.1' && filter_var($ip, FILTER_VALIDATE_IP)) {
                        $this->localIp = $ip;
                        return $ip;
                    }
                }
            }
        }

        $this->localIp = '127.0.0.1';
        return '127.0.0.1';
    }

    public function getSubnet(): string
    {
        if ($this->subnet) return $this->subnet;

        $ip = $this->getLocalIp();
        $parts = explode('.', $ip);
        if (count($parts) === 4) {
            $this->subnet = $parts[0] . '.' . $parts[1] . '.' . $parts[2];
            return $this->subnet;
        }
        return '192.168.1';
    }

    public function getArpTable(): array
    {
        $os = PHP_OS_FAMILY;
        $devices = [];

        if ($os === 'Windows') {
            $output = shell_exec('arp -a 2>nul');
            if ($output) {
                preg_match_all('/(\d+\.\d+\.\d+\.\d+)\s+([\w-]{17})\s+(\w+)/', $output, $matches);
                for ($i = 0; $i < count($matches[1]); $i++) {
                    $ip = $matches[1][$i];
                    $mac = strtoupper(str_replace('-', ':', $matches[2][$i]));

                    if ($this->isInvalidIp($ip) || $this->isBroadcastOrMulticast($mac)) {
                        continue;
                    }

                    $devices[] = [
                        'ip' => $ip,
                        'mac' => $mac,
                        'type' => $this->classifyDevice($matches[3][$i]),
                        'status' => 'active',
                    ];
                }
            }
        } else {
            $output = shell_exec('arp -a 2>/dev/null');
            if ($output) {
                preg_match_all('/\((\d+\.\d+\.\d+\.\d+)\)\s+at\s+([\w:]+)/', $output, $matches);
                for ($i = 0; $i < count($matches[1]); $i++) {
                    $ip = $matches[1][$i];
                    $mac = strtoupper($matches[2][$i]);

                    if ($this->isInvalidIp($ip) || $this->isBroadcastOrMulticast($mac)) {
                        continue;
                    }

                    $devices[] = [
                        'ip' => $ip,
                        'mac' => $mac,
                        'type' => 'Perangkat Jaringan',
                        'status' => 'active',
                    ];
                }
            }
        }

        return $devices;
    }

    protected function isInvalidIp(string $ip): bool
    {
        if ($ip === '255.255.255.255') return true;
        if (str_ends_with($ip, '.255')) return true;
        $parts = explode('.', $ip);
        if (count($parts) === 4 && $parts[0] >= 224) return true;
        return false;
    }

    protected function isBroadcastOrMulticast(string $mac): bool
    {
        if ($mac === 'FF:FF:FF:FF:FF:FF') return true;
        if (str_starts_with($mac, '01:00:5E')) return true;
        if (str_starts_with($mac, '01:00:5C')) return true;
        if (str_starts_with($mac, '01:00:5D')) return true;
        if (str_starts_with($mac, '33:33:')) return true;
        return false;
    }

    public function scanNetwork(): array
    {
        $cacheKey = 'network_scan_results';
        $cached = Cache::get($cacheKey);
        if ($cached) return $cached;

        $devices = [];
        $arpDevices = $this->getArpTable();
        $seenIps = [];

        foreach ($arpDevices as $arp) {
            $ip = $arp['ip'];
            $seenIps[] = $ip;
            $devices[] = [
                'ip' => $ip,
                'mac' => $arp['mac'] ?? '-',
                'hostname' => $this->getHostname($ip) ?? '-',
                'type' => $arp['type'] ?? 'Perangkat Jaringan',
                'status' => 'active',
            ];
        }

        $localIp = $this->getLocalIp();
        if (!in_array($localIp, $seenIps)) {
            $devices[] = [
                'ip' => $localIp,
                'mac' => '-',
                'hostname' => gethostname() ?? '-',
                'type' => 'Server IDS',
                'status' => 'active',
            ];
        }

        $devices = collect($devices)->sortBy('ip')->values()->toArray();

        Cache::put($cacheKey, $devices, 30);

        return $devices;
    }

    public function fullScan(): array
    {
        $cacheKey = 'network_scan_full';
        $cached = Cache::get($cacheKey);
        if ($cached) return $cached;

        $devices = [];
        $seenIps = [];

        $gateway = $this->getGateway();
        $os = PHP_OS_FAMILY;

        if ($os === 'Windows') {
            shell_exec("ping -n 1 -w 500 $gateway >nul 2>&1");
            shell_exec("ping -n 1 -w 500 255.255.255.255 >nul 2>&1");
        } else {
            shell_exec("ping -c 1 -W 1 $gateway >/dev/null 2>&1");
        }

        sleep(1);

        $arpDevices = $this->getArpTable();
        $arpMap = collect($arpDevices)->keyBy('ip');

        foreach ($arpDevices as $arp) {
            $ip = $arp['ip'];
            $seenIps[] = $ip;
            $devices[] = [
                'ip' => $ip,
                'mac' => $arp['mac'] ?? '-',
                'hostname' => '-',
                'type' => $arp['type'] ?? 'Perangkat Jaringan',
                'status' => 'active',
            ];
        }

        $localIp = $this->getLocalIp();
        if (!in_array($localIp, $seenIps)) {
            $devices[] = [
                'ip' => $localIp,
                'mac' => '-',
                'hostname' => gethostname() ?? '-',
                'type' => 'Server IDS',
                'status' => 'active',
            ];
            $seenIps[] = $localIp;
        }

        if (!in_array($gateway, $seenIps)) {
            $devices[] = [
                'ip' => $gateway,
                'mac' => '-',
                'hostname' => 'Gateway',
                'type' => 'Gateway/Router',
                'status' => 'active',
            ];
        }

        $devices = collect($devices)->sortBy('ip')->values()->toArray();

        Cache::put($cacheKey, $devices, 30);

        return $devices;
    }

    protected function getMacFromIp(string $ip): ?string
    {
        $os = PHP_OS_FAMILY;
        if ($os === 'Windows') {
            $output = shell_exec("arp -a $ip 2>nul");
            if (preg_match('/(' . preg_quote($ip) . ')\s+([\w-]{17})/', $output, $m)) {
                return strtoupper(str_replace('-', ':', $m[2]));
            }
        }
        return null;
    }

    protected function getHostname(string $ip): ?string
    {
        $result = shell_exec("nslookup $ip 2>nul");
        if (preg_match('/name\s*=\s*(.+)/i', $result ?? '', $m)) {
            return trim(rtrim($m[1], '.'));
        }
        return null;
    }

    protected function classifyDevice(string $type): string
    {
        $type = strtolower(trim($type));
        return match(true) {
            str_contains($type, 'dynamic') => 'Dinamis',
            str_contains($type, 'static') => 'Statis',
            default => 'Perangkat Jaringan',
        };
    }

    protected function guessDeviceType(string $ip, ?string $mac): string
    {
        if (!$mac || $mac === '-') return 'Perangkat Tidak Dikenal';

        $prefix = substr($mac, 0, 8);
        $vendorMap = [
            '00:50:56' => 'VMware',
            '00:0C:29' => 'VMware',
            '08:00:27' => 'VirtualBox',
            '52:54:00' => 'QEMU/KVM',
            '00:15:5D' => 'Hyper-V',
            'B8:27:EB' => 'Raspberry Pi',
            'DC:A6:32' => 'Raspberry Pi',
            'E4:5F:01' => 'Raspberry Pi',
            '3C:22:FB' => 'Apple',
            'A4:83:E7' => 'Apple',
            'F0:18:98' => 'Apple',
            '00:1A:2B' => 'Apple',
            'AC:BC:32' => 'Apple',
            'A4:5E:60' => 'Apple',
            '28:6C:B6' => 'Xiaomi',
            '64:CC:2E' => 'Xiaomi',
            '7C:1D:D9' => 'Xiaomi',
            '20:0C:C8' => 'Xiaomi',
            '00:12:1C' => 'ZTE',
            '58:7F:66' => 'Huawei',
            'CC:53:B5' => 'Huawei',
            'E0:24:7F' => 'Huawei',
            '48:46:FB' => 'Huawei',
            '00:E0:4C' => 'Realtek',
            '50:EB:F6' => 'ASRock',
            '00:25:22' => 'Dell',
            'F8:BC:12' => 'Dell',
            '18:66:DA' => 'Dell',
            '00:1E:4F' => 'Dell',
            'B0:83:FE' => 'Dell',
            '30:D0:42' => 'HP',
            '10:60:4B' => 'HP',
            '3C:4A:92' => 'HP',
            '64:51:06' => 'HP',
            '94:57:A5' => 'Netgear',
            '20:E5:2A' => 'Netgear',
            'A4:2B:B0' => 'TP-Link',
            '50:C7:BF' => 'TP-Link',
            '14:CC:20' => 'TP-Link',
            '60:32:B1' => 'TP-Link',
            '00:23:CD' => 'Huawei',
            '04:BD:88' => 'Samsung',
            '00:15:99' => 'Samsung',
            'C0:97:27' => 'Samsung',
            '30:CD:A7' => 'Samsung',
            '00:0E:8F' => 'Samsung',
            'BC:14:01' => 'Samsung',
            '00:16:32' => 'Samsung',
            '8C:F5:A3' => 'Samsung',
        ];

        foreach ($vendorMap as $vendorPrefix => $vendor) {
            if (str_starts_with($prefix, $vendorPrefix)) {
                return $vendor;
            }
        }

        if (str_starts_with($ip, '192.168.')) return 'Perangkat Local';
        return 'Perangkat Jaringan';
    }

    public function getNetworkInfo(): array
    {
        return [
            'local_ip' => $this->getLocalIp(),
            'subnet' => $this->getSubnet() . '.0/24',
            'gateway' => $this->getGateway(),
            'scan_time' => now()->format('d/m/Y H:i:s'),
        ];
    }

    protected function getGateway(): string
    {
        $os = PHP_OS_FAMILY;
        if ($os === 'Windows') {
            $output = shell_exec('ipconfig 2>nul');
            if (preg_match('/Default Gateway[^\r\n]*:\s*(\d+\.\d+\.\d+\.\d+)/i', $output, $m)) {
                return $m[1];
            }
        }
        $subnet = $this->getSubnet();
        return $subnet . '.1';
    }
}
