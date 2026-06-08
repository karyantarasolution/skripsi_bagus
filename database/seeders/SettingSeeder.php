<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            [
                'key' => 'system_name',
                'value' => 'IDS Monitoring - Keamanan Jaringan',
            ],
            [
                'key' => 'server_ip',
                'value' => '192.168.1.100',
            ],
            [
                'key' => 'threshold_high',
                'value' => '10', // jumlah percobaan sebelum dianggap serangan
            ],
            [
                'key' => 'notification_email',
                'value' => 'security@example.com',
            ],
            [
                'key' => 'auto_block',
                'value' => 'true',
            ],
            [
                'key' => 'block_duration',
                'value' => '3600', // dalam detik (1 jam)
            ],
            [
                'key' => 'log_retention_days',
                'value' => '30',
            ],
            [
                'key' => 'telegram_bot_token',
                'value' => '1234567890:ABCdefGHIjklMNOpqrsTUVwxyz',
            ],
            [
                'key' => 'telegram_chat_id',
                'value' => '-123456789',
            ],
            [
                'key' => 'slack_webhook',
                'value' => '',
            ],
        ];

        foreach ($settings as $setting) {
            Setting::create($setting);
        }
    }
}
