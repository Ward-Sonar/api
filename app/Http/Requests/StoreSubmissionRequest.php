<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     title="Store Submission Request",
 *     description="Store submission request body data",
 *     required={"abandoned"},
 *     type="object",
 *     @OA\Property(
 *         property="data",
 *         type="object",
 *         @OA\Property(
 *             property="type",
 *             type="string"
 *         ),
 *         @OA\Property(
 *             property="attributes",
 *             type="object",
 *
 *             @OA\Property(
 *                 property="atmosphere",
 *                 ref="#/components/schemas/Submission/properties/atmosphere"
 *             ),
 *
 *             @OA\Property(
 *                 property="direction",
 *                 ref="#/components/schemas/Submission/properties/direction"
 *             ),
 *
 *             @OA\Property(
 *                 property="comment",
 *                 ref="#/components/schemas/Submission/properties/comment"
 *             ),
 *
 *             @OA\Property(
 *                 property="abandoned",
 *                 ref="#/components/schemas/Submission/properties/abandoned"
 *             )
 *         ),
 *         @OA\Property(
 *             property="relationships",
 *             type="object",
 *             @OA\Property(
 *                 property="causes",
 *                 type="array",
 *                  @OA\Items(
 *                      type="integer",
 *                      format="int64"
 *                  )
 *             )
 *         )
 *     )
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
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'data.attributes.atmosphere' => 'nullable|integer|between:-2,2',
            'data.attributes.direction' => 'nullable|integer|between:-1,1',
            'data.attributes.comment' => 'nullable|string|max:140',
            'data.attributes.abandoned' => 'required|boolean',
            'data.relationships.causes.*' => 'nullable|exists:causes,id',
        ];
    }
}
