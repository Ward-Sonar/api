<?php

namespace App\Http\Controllers;

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
     *      summary="Get the shift median submission for a client",
     *      description="Returns the median atmosphere and direction",
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
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(
     *                  property="data",
     *                  type="object",
     *                  @OA\Property(
     *                      property="attributes",
     *                      type="object",
     *                      @OA\Property(
     *                          property="atmosphere",
     *                          ref="#/components/schemas/Submission/properties/atmosphere"
     *                      ),
     *                      @OA\Property(
     *                          property="direction",
     *                          ref="#/components/schemas/Submission/properties/direction"
     *                      ),
     *                      @OA\Property(
     *                          property="datetime",
     *                          ref="#/components/schemas/Submission/properties/created_at"
     *                      )
     *                  )
     *              )
     *          )
     *       ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Unknown Client",
     *      ),
     *      security={
     *         {"clientSecret": {}}
     *     }
     * )
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request, string $urlkey)
    {
        $client = Client::where('urlkey', '=', $urlkey)->firstOrFail();

        $shiftSubmissions = $client->completedShiftSubmissions();

        if ($shiftSubmissions->isEmpty()) {
            return response(null, 204);
        }

        $atmosphere = $shiftSubmissions->pluck('atmosphere')->median();
        $direction = $shiftSubmissions->pluck('direction')->median();

        return response()->json([
            'attributes' => [
                'atmosphere' => $atmosphere,
                'direction' => $direction,
                'datetime' => now()->toDateTimeString(),
            ],
        ]);
    }
}
