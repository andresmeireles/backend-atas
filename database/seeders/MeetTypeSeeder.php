<?php

namespace Database\Seeders;

use App\Models\MeetType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MeetTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        MeetType::factory()->create([
            'name' => MeetType::SACRAMENTAL
        ]);

        MeetType::factory()->create([
            'name' => MeetType::SACRAMENTAL_TESTIMONY
        ]);
    }
}
