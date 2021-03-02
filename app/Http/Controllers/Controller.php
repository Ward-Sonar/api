<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    /**
     * @OA\Info(
     *      version="1.0.0",
     *      title="WardSonar API",
     *      description="API for wardsonar.co.uk",
     *      @OA\Contact(
     *          email="mike@ayup.agency"
     *      ),
     *     @OA\License(
     *         name="Apache 2.0",
     *         url="http://www.apache.org/licenses/LICENSE-2.0.html"
     *     )
     * )
     *
     *  @OA\Server(
     *      url="http://0.0.0.0/api/v1/",
     *      description="Development Server"
     *  )
     *
     *  @OA\Server(
     *      url="https://api.staging.wardsonar.co.uk/api/v1/",
     *      description="Staging Server"
     *  )
     *
     *  @OA\Server(
     *      url="https://api.wardsonar.co.uk/api/v1/",
     *      description="Production Server"
     *  )
     *
     *  @OA\Tag(
     *     name="WardSonar",
     *     description="API Endpoints for wardsonar.co.uk",
     * )
     *
     * @OA\SecurityScheme(
     *     type="apiKey",
     *     in="header",
     *     securityScheme="Bearer Token",
     *     name="AUTHORIZATION"
     * )"
     */
    use AuthorizesRequests;
    use DispatchesJobs;
    use ValidatesRequests;
}
