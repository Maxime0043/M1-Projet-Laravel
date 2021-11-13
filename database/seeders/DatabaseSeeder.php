<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            UserSeeder::class,
            FormationSeeder::class,
            ChapterSeeder::class,
            CategorySeeder::class,
            TypeSeeder::class,
            FormationCategorySeeder::class,
            FormationTypeSeeder::class,
            SignUpRequestSeeder::class
        ]);
    }
}
