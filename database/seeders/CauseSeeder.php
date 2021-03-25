<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CauseSeeder extends Seeder
{
    /**
     * Causes.
     *
     * @var array
     */
    protected $causes = [
        1 => 'The staff',
        2 => 'The other patients',
        3 => 'How I\'m feeling',
        4 => 'The ward environment',
        5 => 'Option 5',
        6 => 'Option 6',
        7 => 'Other',
    ];

    /**
     * Run the database seeds.
     */
    public function run()
    {
        DB::table('causes')
            ->upsert(collect($this->causes)->map(function ($text, $id) {
                return [
                    'id' => $id,
                    'text' => $text,
                ];
            })->all(), ['id'], ['text']);
    }
}
