<?php

namespace App\Http\Controllers;

use App\Http\Resources\AtmosphereResource;
use App\Models\Client;
use Illuminate\Http\Request;

class AtmosphereController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @OA\Get(
     *      path="/atmosphere/{urlkey}",
     *      operationId="getClientSubmissionsLatest",
     *      tags={"Atmosphere"},
     *      summary="Get the latest submission for a client",
     *      description="Returns a single record data",
     *      @OA\Parameter(
     *          name="urlkey",
     *          description="Client urlkey",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/AtmosphereResource")
     *       ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Unknown Client",
     *      ),
     * )
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request, string $urlkey)
    {
        $client = Client::where('urlkey', '=', $urlkey)->firstOrFail();

        $submission = $client->submissions()->latest()->firstOr(function () {
            return response(null, 204);
        });

        return new AtmosphereResource($submission);
    }
}
