<?php

namespace App\Services;

use App\Models\AttackLog;
use App\Models\ManualAction;
use App\Models\Rule;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class AttackDetector
{
    protected array $rules = [];
    protected array $whitelist = [];
    protected bool $active;

    public function __construct()
    {
        $this->loadRules();
        $this->loadWhitelist();
        $this->active = Setting::where('key', 'ids_status')->value('value') !== 'inactive';
    }

    protected function loadRules(): void
    {
        $cached = Cache::get('ids_rules');
        if ($cached !== null) {
            $this->rules = $cached;
            return;
        }

        $this->rules = Rule::all()->toArray();
        Cache::put('ids_rules', $this->rules, 300);
    }

    protected function loadWhitelist(): void
    {
        $whitelistRaw = Setting::where('key', 'whitelist')->value('value') ?? '127.0.0.1,::1';
        $this->whitelist = array_map('trim', explode(',', $whitelistRaw));
    }

    public function inspect(Request $request): ?array
    {
        if (!$this->active) return null;

        $clientIp = $request->ip();

        $scanTargets = array_merge(
            [$request->fullUrl()],
            [$request->path()],
            $request->query(),
            $this->flattenInput($request->all()),
            [$request->userAgent() ?? ''],
        );

        $rawString = implode(' ', array_map(function ($val) {
            return is_array($val) ? implode(' ', $val) : (string) $val;
        }, $scanTargets));

        $rawString = urldecode($rawString);

        foreach ($this->rules as $rule) {
            $pola = $rule['pola'];
            if (stripos($rawString, $pola) !== false) {
                $action = $this->determineAction($rule['kategori']);

                // Whitelisted IPs: still log but don't block
                if (in_array($clientIp, $this->whitelist)) {
                    $action = 'Logged';
                }

                return [
                    'ip_address' => $clientIp,
                    'kategori' => $rule['kategori'],
                    'pola_terdeteksi' => $pola,
                    'endpoint' => $request->path(),
                    'origin' => $this->resolveOrigin($clientIp),
                    'risk_level' => $this->determineRisk($rule['kategori'], $pola),
                    'action_taken' => $action,
                    'user_agent' => $request->userAgent() ?? '-',
                    'method' => $request->method(),
                ];
            }
        }

        return null;
    }

    public function logAttack(array $data): AttackLog
    {
        return AttackLog::create($data);
    }

    public function isBlockedIp(string $ip): bool
    {
        $cached = Cache::get('ids_blocked_ips');
        if ($cached !== null) {
            return in_array($ip, $cached);
        }

        $blocked = ManualAction::where('action_type', 'block')
            ->orWhere('action_type', 'drop')
            ->pluck('ip_address')
            ->toArray();
        Cache::put('ids_blocked_ips', $blocked, 60);

        return in_array($ip, $blocked);
    }

    protected function determineRisk(string $kategori, string $pola): string
    {
        $criticalPatterns = ['DROP TABLE', 'UNION SELECT', 'eval(', 'cmd.exe', '../etc/passwd', '..\\..\\windows', 'base64_decode('];
        $highPatterns = ['OR 1=1', '<script>', 'javascript:alert', '.env', 'wget http://', 'INSERT INTO'];
        $mediumPatterns = ['WAITFOR DELAY', 'onerror=', 'phpmyadmin/', 'document.cookie'];

        $polaUpper = strtoupper($pola);

        foreach ($criticalPatterns as $cp) {
            if (str_contains($polaUpper, strtoupper($cp))) return 'Critical';
        }
        foreach ($highPatterns as $hp) {
            if (str_contains($polaUpper, strtoupper($hp))) return 'High';
        }
        foreach ($mediumPatterns as $mp) {
            if (str_contains($polaUpper, strtoupper($mp))) return 'Medium';
        }

        return 'Low';
    }

    protected function determineAction(string $kategori): string
    {
        $autoBlock = Cache::get('ids_auto_block');
        if ($autoBlock === null) {
            $autoBlock = Setting::where('key', 'auto_block')->value('value');
            Cache::put('ids_auto_block', $autoBlock, 300);
        }
        if ($autoBlock === 'true') {
            return in_array($kategori, ['SQL Injection', 'XSS', 'Path Traversal']) ? 'Blocked' : 'Logged';
        }
        return 'Logged';
    }

    protected function resolveOrigin(string $ip): string
    {
        if (str_starts_with($ip, '192.168.')) return 'Local Network';
        if (str_starts_with($ip, '10.')) return 'Private Network';
        if (str_starts_with($ip, '172.')) return 'Private Network';
        if ($ip === '127.0.0.1' || $ip === '::1') return 'Localhost';
        return 'External';
    }

    protected function flattenInput(array $input, string $prefix = ''): array
    {
        $result = [];
        foreach ($input as $key => $value) {
            $newKey = $prefix ? "{$prefix}.{$key}" : $key;
            if (is_array($value)) {
                $result = array_merge($result, $this->flattenInput($value, $newKey));
            } else {
                $result[$newKey] = $value;
            }
        }
        return $result;
    }
}
