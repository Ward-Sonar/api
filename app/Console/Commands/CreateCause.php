<?php

namespace App\Console\Commands;

use App\Models\Cause;
use Illuminate\Console\Command;

class CreateCause extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ws:createCause';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add a cause for a change to ward atmosphere';

    /**
     * Create a new command instance.
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
        $text = $this->ask('Describe the cause of a change in ward atmosphere');
        if (mb_strlen($text) > 254) {
            $this->error('Max length for cause is 254');

            return 1;
        }
        $cause = Cause::factory()->create([
            'text' => $text,
        ]);
        $this->info("Added cause: $text with ID: {$cause->id}");

        return 0;
    }
}
