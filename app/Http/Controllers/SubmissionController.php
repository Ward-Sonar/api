<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSubmissionRequest;
use App\Http\Resources\SubmissionResource;
use App\Models\Client;
use App\Models\Submission;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SubmissionController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @OA\Post(
     *      path="/submission",
     *      operationId="storeSubmission",
     *      tags={"Submission"},
     *      summary="Store new submission",
     *      description="Creates a record and returns new record data",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/StoreSubmissionRequest")
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/SubmissionResource")
     *       ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated"
     *      ),
     *      security={
     *         {"clientSecret": {}}
     *     }
     * )
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(StoreSubmissionRequest $request)
    {
        $secret = Str::substr($request->header('AUTHORIZATION'), 7);
        $client = Client::where('secret', '=', hash('sha256', $secret))->first();
        $submission = $client->submissions()->create($request->validated()['data']['attributes']);
        $causes = $request->validated()['data']['relationships']['causes'] ?? null;
        if ($causes) {
            $submission->causes()->attach($causes);
        }
        $submission->load('client');

        return new SubmissionResource($submission);
    }
}
