<?php

namespace Database\Seeders;

use App\Models\Config;
use Illuminate\Database\Seeder;

class ConfigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Config::factory()->create([
            'name' => Config::MIN_APP_VERSION,
            'value' => '1.0.0'
        ]);
    }
}
