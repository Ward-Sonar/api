<?php

namespace App\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use PDOException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register()
    {

        /**
         * Authentication Exception.
         */
        $this->reportable(function (AuthenticationException $e) {
            return response()->json([
                'errors' => [
                    'error' => [
                        'message' => 'Cannot Authenticate: ' . $e->getMessage(),
                    ],
                ],
            ], 401);
        });

        /**
         * Authorisation Exception.
         */
        $this->reportable(function (AuthorizationException $e) {
            return response()->json([
                'errors' => [
                    'error' => [
                        'message' => 'Unauthorized: ' . $e->getMessage(),
                    ],
                ],
            ], 403);
        });

        /**
         * Route Not Found Exception.
         */
        $this->reportable(function (NotFoundHttpException $e) {
            $request = request();

            return response()->json([
                'errors' => [
                    'error' => [
                        'message' => 'Route ' . $request->method() . ' to ' . $request->path() . ' not found: ' . $e->getMessage(),
                    ],
                ],
            ], 404);
        });

        /**
         * Method Not Found Exception.
         */
        $this->reportable(function (MethodNotAllowedHttpException $e) {
            $request = request();

            return response()->json([
                'errors' => [
                    'error' => [
                        'message' => 'Route ' . $request->method() . ' to ' . $request->path() . ' not found: ' . $e->getMessage(),
                    ],
                ],
            ], 404);
        });

        /**
         * Model Not Found Exception.
         */
        $this->reportable(function (ModelNotFoundException $e) {
            return response()->json([
                'errors' => [
                    'error' => [
                        'message' => 'Resource not found: ' . $e->getMessage(),
                    ],
                ],
            ], 404);
        });

        /**
         * Asset Not Found Exception.
         */
        $this->reportable(function (FileNotFoundException $e) {
            return response()->json([
                'errors' => [
                    'error' => [
                        'message' => 'File Asset not found: ' . $e->getMessage(),
                    ],
                ],
            ], 404);
        });

        /**
         * Validation Exception.
         */
        $this->reportable(function (ValidationException $e) {
            return response()->json([
                'errors' => $e->errors(),
            ], 422);
        });

        /**
         * Database Exception.
         */
        $this->reportable(function (PDOException $e) {
            return response()->json([
                'errors' => [
                    'error' => [
                        'message' => $e->getMessage(),
                    ],
                ],
            ], 422);
        });
    }
}
