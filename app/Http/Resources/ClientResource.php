<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     @OA\Xml(
 *         name="ClientResource"
 *     ),
 *     @OA\Property(
 *         property="type",
 *         type="string"
 *     ),
 *     @OA\Property(
 *          property="id",
 *          ref="#/components/schemas/Client/properties/id"
 *     ),
 *     @OA\Property(
 *         property="attributes",
 *         type="object",
 *         @OA\Property(
 *              property="name",
 *              ref="#/components/schemas/Client/properties/name"
 *          ),
 *         @OA\Property(
 *              property="urlkey",
 *              ref="#/components/schemas/Client/properties/urlkey"
 *          ),
 *     ),
 *     @OA\Property(
 *         property="meta",
 *         type="object",
 *         @OA\Property(
 *              property="created_at",
 *              ref="#/components/schemas/Client/properties/created_at"
 *          ),
 *         @OA\Property(
 *              property="updated_at",
 *              ref="#/components/schemas/Client/properties/updated_at"
 *          )
 *      )
 * )
 */
class ClientResource extends JsonResource
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
            'type' => 'client',
            'id' => $this->id,
            'attributes' => [
                'name' => $this->name,
                'urlkey' => $this->urlkey,
            ],
            'meta' => [
                'created_at' => $this->created_at,
                'updated_at' => $this->updated_at,
            ],
        ];
    }
}
