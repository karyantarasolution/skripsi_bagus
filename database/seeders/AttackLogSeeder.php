<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AttackLog;

class AttackLogSeeder extends Seeder
{
    public function run()
    {
        $data = [
           
            
        ];

        foreach ($data as $item) {
            AttackLog::create($item);
        }
    }
}