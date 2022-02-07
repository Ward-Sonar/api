<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run()
    {
        $this->call(CauseSeeder::class);
        $this->call(ClientSeeder::class);
        $this->call(SubmissionSeeder::class);
    }
}
