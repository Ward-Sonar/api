<?php

namespace App\Models;

use App\Models\Cause;
use App\Models\Client;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     description="Submission Model",
 *     title="Submission Model",
 *     required={"client_id", "abandoned"},
 *     @OA\Xml(
 *         name="Submission"
 *     )
 * )
 */
class Submission extends Model
{
    use HasFactory;

    /**
     * @OA\Property(
     *     title="atmosphere",
     *     description="Ward atmosphere state",
     *     format="int64",
     *     enum={-2,-1,0,1,2},
     * )
     */

    /**
     * The client that this submission belongs to
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * The causes that belong to this submission
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     **/
    public function causes()
    {
        return $this->belongsToMany(Cause::class);
    }
}
