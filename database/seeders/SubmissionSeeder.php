<?php

namespace Database\Seeders;

use App\Models\Client;
use Faker\Generator;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class SubmissionSeeder extends Seeder
{
    /**
     * Demo Submissions data.
     *
     * @var array
     */
    protected $submissions = [
        ['02/02/2021 08:00', -1, 0, 6, 'Supplementary comments 1'],
        ['02/02/2021 08:02', -2, -1, 5, 'Supplementary comments 2'],
        ['02/02/2021 08:04', 2, 1, 5, 'Supplementary comments 3'],
        ['02/02/2021 08:06', 0, 0, 7, 'Supplementary comments 4'],
        ['02/02/2021 08:08', 2, 1, 7, 'Supplementary comments 5'],
        ['02/02/2021 08:10', -1, 0, 4, 'Supplementary comments 6'],
        ['02/02/2021 08:12', 2, 1, 4, 'Supplementary comments 7'],
        ['02/02/2021 08:14', 0, 1, 7, 'Supplementary comments 8'],
        ['02/02/2021 08:16', -1, 1, 1, 'Supplementary comments 9'],
        ['02/02/2021 08:18', -1, 1, 6, 'Supplementary comments 10'],
        ['02/03/2021 08:00', 2, 1, 1, 'Supplementary comments 11'],
        ['02/03/2021 08:02', -2, 1, 1, 'Supplementary comments 12'],
        ['02/03/2021 08:04', 1, 1, 1, 'Supplementary comments 13'],
        ['02/03/2021 08:06', 1, 1, 7, 'Supplementary comments 14'],
        ['02/03/2021 08:08', 0, 1, 3, 'Supplementary comments 15'],
        ['02/03/2021 08:10', -1, -1, 3, 'Supplementary comments 16'],
        ['02/03/2021 08:12', -1, 1, 6, 'Supplementary comments 17'],
        ['02/03/2021 08:14', 0, 1, 5, 'Supplementary comments 18'],
        ['02/03/2021 08:16', 0, 0, 5, 'Supplementary comments 19'],
        ['02/04/2021 08:18', -2, 1, 5, 'Supplementary comments 20'],
        ['02/04/2021 08:00', -2, 0, 4, 'Supplementary comments 21'],
        ['02/04/2021 08:02', -2, -1, 3, 'Supplementary comments 22'],
        ['02/04/2021 08:04', 1, -1, 3, 'Supplementary comments 23'],
        ['02/04/2021 08:06', 1, 1, 5, 'Supplementary comments 24'],
        ['02/04/2021 08:08', 2, 0, 1, 'Supplementary comments 25'],
        ['02/04/2021 08:10', 0, 0, 5, 'Supplementary comments 26'],
        ['02/04/2021 08:12', 1, -1, 5, 'Supplementary comments 27'],
        ['02/04/2021 08:14', 1, 0, 7, 'Supplementary comments 28'],
        ['02/04/2021 08:16', 0, 0, 1, 'Supplementary comments 29'],
        ['02/05/2021 08:18', -2, 0, 5, 'Supplementary comments 30'],
        ['02/05/2021 08:00', -1, 1, 7, 'Supplementary comments 31'],
        ['02/05/2021 08:02', -2, 0, 3, 'Supplementary comments 32'],
        ['02/05/2021 08:04', 2, 0, 3, 'Supplementary comments 33'],
        ['02/05/2021 08:06', -2, 1, 1, 'Supplementary comments 34'],
        ['02/05/2021 08:08', -2, 1, 3, 'Supplementary comments 35'],
        ['02/05/2021 08:10', -2, 1, 4, 'Supplementary comments 36'],
        ['02/05/2021 08:12', 2, 1, 6, 'Supplementary comments 37'],
        ['02/05/2021 08:14', 0, 1, 4, 'Supplementary comments 38'],
        ['02/05/2021 08:16', -1, 0, 6, 'Supplementary comments 39'],
        ['02/06/2021 08:18', -1, 1, 7, 'Supplementary comments 40'],
        ['02/06/2021 08:00', -1, 1, 4, 'Supplementary comments 41'],
        ['02/06/2021 08:02', 2, 1, 4, 'Supplementary comments 42'],
        ['02/06/2021 08:04', -2, -1, 6, 'Supplementary comments 43'],
        ['02/06/2021 08:06', 1, 1, 4, 'Supplementary comments 44'],
        ['02/06/2021 08:08', 0, -1, 1, 'Supplementary comments 45'],
        ['02/06/2021 08:10', 0, -1, 5, 'Supplementary comments 46'],
        ['02/06/2021 08:12', -1, 1, 4, 'Supplementary comments 47'],
        ['02/06/2021 08:14', 2, 1, 1, 'Supplementary comments 48'],
        ['02/06/2021 08:16', 0, 1, 6, 'Supplementary comments 49'],
    ];

    /**
     * Generate Shift data.
     *
     * @param App\Models\Client $client
     * @return array
     */
    public function generateShiftSubmissions(Client $client)
    {
        $faker = new Generator();

        $submissions = [];

        $submissionTime = $client->shiftStart->clone();

        while ($submissionTime->lessThan($client->shiftEnd)) {
            $submissions[] = [
                $submissionTime->toDateTimeString(),
                mt_rand(-2, 2),
                mt_rand(-1, 1),
                mt_rand(1, 7),
                $faker->sentence(),
            ];
            $submissionTime->addMinutes(mt_rand(1, 10));
        }

        return $submissions;
    }

    /**
     * Run the database seeds.
     */
    public function run()
    {
        $clients = Client::all();

        foreach ($clients as $client) {
            $submissions = $this->generateShiftSubmissions($client);
            collect($submissions)->each(function ($submission) use ($client) {
                $submission_id = DB::table('submissions')
                    ->insertGetId([
                        'atmosphere' => $submission[1],
                        'direction' => $submission[2],
                        'comment' => $submission[4],
                        'client_id' => $client->id,
                        'abandoned' => false,
                        'created_at' => (new Carbon($submission[0]))->toDateTimeString(),
                    ]);

                DB::table('cause_submission')
                    ->insert([
                        'submission_id' => $submission_id,
                        'cause_id' => $submission[3],
                    ]);
            });
        }
    }
}
