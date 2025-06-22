<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ConfigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $configs = [
            [
                'name' => 'sensor_phone_no',
                'description' => 'Phone number on sensor',
                'k_value' => '256782150448'
            ],
            [
                'name' => 'sensor_id',
                'description' => 'Sensor ID',
                'k_value' => '1'
            ],
            #mocked temp
            [
                'name' => 'm_temp',
                'description' => 'M Temperature',
                'k_value' => '28'
            ],
                [
                    'name' => 'm_temp_variation',
                    'description' => 'M Temperature Variation',
                    'k_value' => '1'
                ],
        ];

        foreach ($configs as $config) {
            \App\Models\Config::updateOrCreate(
                ['name' => $config['name']],
                [
                    'description' => $config['description'],
                    'k_value' => $config['k_value']
                ]
            );
        }
    }
}
