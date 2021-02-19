<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AtmosphereController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @OA\Get(
     *      path="/atmosphere/{urlkey}",
     *      operationId="getClientSubmissionsLatest",
     *      tags={"Submission"},
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
     *          @OA\JsonContent(ref="#/components/schemas/Submission")
     *       ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     * )
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        //
    }
}
