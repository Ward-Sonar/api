<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     description="Client Model",
 *     title="Client Model",
 *     required={"name", "secret", "urlkey"},
 *     @OA\Xml(
 *         name="Client"
 *     ),
 *
 *     @OA\Property(
 *         property="id",
 *         title="id",
 *         description="Client ID",
 *         type="integer",
 *         format="int64",
 *         example=1
 *     ),
 *
 *      @OA\Property(
 *         property="name",
 *         title="name",
 *         description="The name of the ward",
 *     ),
 *
 *      @OA\Property(
 *         property="secret",
 *         title="secret",
 *         description="The string used to validate each ward (client)",
 *     ),
 *
 *      @OA\Property(
 *         property="urlkey",
 *         title="urlkey",
 *         description="The string used to identify each ward (client)",
 *     ),
 *
 *     @OA\Property(
 *         property="created_at",
 *         title="created_at",
 *         description="Model Creation timestamp",
 *         type="string",
 *         format="date-time",
 *         readOnly="true"
 *     ),
 *
 *     @OA\Property(
 *         property="updated_at",
 *         title="updated_at",
 *         description="Model Updated timestamp",
 *         type="string",
 *         format="date-time",
 *         readOnly="true"
 *     ),
 *     @OA\Property(
 *         property="deleted_at",
 *         title="deleted_at",
 *         description="Model Deleted timestamp",
 *         type="string",
 *         format="date-time",
 *         readOnly="true"
 *     )
 * )
 */
class Client extends Model
{
    use HasFactory;

    /**
     * The submissions that belong to this client.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function submissions()
    {
        return $this->hasMany(Submission::class);
    }
}
