<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use phpDocumentor\Reflection\PseudoTypes\True_;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'lastname' => 'adminLastName',
            'firstname' => 'adminFirstName',
            'email' => 'admin@admin.com',
            'email_verified_at' => now(),
            'password' => '$2y$10$O3mQoeTL5yiMtUZXoviUv.M6ga9eWgrIVochtUiiexiabIUBTDOtK', // admin
            'is_admin' => true,
            'picture' => null,
            'remember_token' => Str::random(10),
        ]);

        User::factory()->count(5)->create();
    }
}
