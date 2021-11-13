<?php

namespace Database\Seeders;

use App\Models\FormationType;
use Illuminate\Database\Seeder;

class FormationTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        FormationType::factory()->count(30)->create();
    }
}
