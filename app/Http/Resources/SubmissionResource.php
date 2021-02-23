<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     @OA\Xml(
 *         name="SubmissionResource"
 *     ),
 *     @OA\Property(
 *         property="type",
 *         type="string"
 *     ),
 *     @OA\Property(
 *          property="id",
 *          ref="#/components/schemas/Submission/properties/id"
 *     ),
 *     @OA\Property(
 *         property="attributes",
 *         type="object",
 *         @OA\Property(
 *              property="atmosphere",
 *              ref="#/components/schemas/Submission/properties/atmosphere"
 *          ),
 *         @OA\Property(
 *              property="direction",
 *              ref="#/components/schemas/Submission/properties/direction"
 *          ),
 *         @OA\Property(
 *              property="comment",
 *              ref="#/components/schemas/Submission/properties/comment"
 *          ),
 *         @OA\Property(
 *              property="abandoned",
 *              ref="#/components/schemas/Submission/properties/abandoned"
 *          ),
 *     ),
 *     @OA\Property(
 *         property="relationships",
 *         type="object",
 *         @OA\Property(
 *              property="client",
 *              ref="#/components/schemas/ClientResource"
 *          ),
 *     ),
 *     @OA\Property(
 *         property="meta",
 *         type="object",
 *         @OA\Property(
 *              property="created_at",
 *              ref="#/components/schemas/Submission/properties/created_at"
 *          ),
 *         @OA\Property(
 *              property="updated_at",
 *              ref="#/components/schemas/Submission/properties/updated_at"
 *          )
 *      )
 * )
 */
class SubmissionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'type' => 'submission',
            'id' => $this->id,
            'attributes' => [
                'atmosphere' => $this->atmosphere,
                'direction' => $this->direction,
                'comment' => $this->comment,
                'abandoned' => $this->abandoned,
            ],
            'relationships' => [
                'client' => new ClientResource($this->client),
            ],
            'meta' => [
                'created_at' => $this->created_at,
                'updated_at' => $this->updated_at,
            ],
        ];
    }
}
