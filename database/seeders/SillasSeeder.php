<?php

namespace Database\Seeders;

use App\Models\Sillas;
use Illuminate\Database\Seeder;

class SillasSeeder extends Seeder
{
    public function run(): void
    {
        Sillas::factory()->count(10)->create();
    }
}
