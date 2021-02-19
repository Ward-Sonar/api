<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     title="Store Submission Request",
 *     description="Store submission request body data",
 *     required={"abandoned"},
 *     type="object"
 * )
 */
class StoreSubmissionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @OA\RequestBody(
     *     request="Submission",
     *     description="Submission object to be stored",
     *     required=true,
     *     @OA\JsonContent(ref="#/components/schemas/Submission"),
     * )
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
        ];
    }
}
