<?php

namespace Database\Seeders;

use App\Models\Client;
use Faker\Generator;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SubmissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $faker = new Generator();
        $client_secret = Str::random(60);

        $client = Client::factory()->create([
            'name' => 'Ward ' . $faker->firstName,
            'secret' => hash('sha256', $client_secret),
        ]);

        $this->command->info("{$client->name} Client Created. Secret: $client_secret UrlKey: {$client->urlkey}");
    }
}
