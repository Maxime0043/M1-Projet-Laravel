<?php

namespace Database\Seeders;

use App\Models\SignUpRequest;
use Illuminate\Database\Seeder;

class SignUpRequestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        SignUpRequest::factory()->count(10)->create();
    }
}
