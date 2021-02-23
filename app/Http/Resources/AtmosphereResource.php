<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     @OA\Xml(
 *         name="AtmosphereResource"
 *     ),
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
 *             @OA\Property(
 *                 property="atmosphere",
 *                 ref="#/components/schemas/Submission/properties/atmosphere"
 *             ),
 *             @OA\Property(
 *                 property="datetime",
 *                 ref="#/components/schemas/Submission/properties/created_at"
 *             )
 *         )
 *     )
 * )
 */
class AtmosphereResource extends JsonResource
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
            'type' => 'atmosphere',
            'attributes' => [
                'atmosphere' => $this->atmosphere,
                'datetime' => $this->created_at,
            ],
        ];
    }
}
