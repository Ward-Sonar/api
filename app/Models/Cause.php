<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     description="Cause Model",
 *     title="Cause Model",
 *     required={"text"},
 *     @OA\Xml(
 *         name="Cause"
 *     ),
 *
 *     @OA\Property(
 *         property="id",
 *         title="id",
 *         description="Cause ID",
 *         type="integer",
 *         format="int64",
 *         example=1
 *     ),
 *
 *      @OA\Property(
 *         property="text",
 *         title="text",
 *         description="Description of cause",
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
class Cause extends Model
{
    use HasFactory;

    /**
     * The submissions that belong to this cause.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function submissions()
    {
        return $this->belongsToMany(Submission::class);
    }
}
