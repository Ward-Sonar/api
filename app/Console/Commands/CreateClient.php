<?php

namespace App\Console\Commands;

use App\Models\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class CreateClient extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ws:createClient {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new Client (Ward) and return the access token';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $name = $this->argument('name');
        if (strlen($name) > 254) {
            $this->error('Max length for Client name is 254');
            return 1;
        }
        $secret = Str::random(60);
        Client::factory()->create([
            'name' => $name,
            'secret' => hash('sha256', $secret),
        ]);
        $this->info("Created Client $name");
        $this->info("Authorization Token: $secret");
        return 0;
    }
}
