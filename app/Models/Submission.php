<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     description="Submission Model",
 *     title="Submission Model",
 *     required={"client_id", "abandoned"},
 *     @OA\Xml(
 *         name="Submission"
 *     ),
 *
 *     @OA\Property(
 *         property="id",
 *         title="id",
 *         description="Submission ID",
 *         type="integer",
 *         format="int64",
 *         example=1
 *     ),
 *
 *     @OA\Property(
 *         property="atmosphere",
 *         title="atmosphere",
 *         description="Ward atmosphere state",
 *         type="integer",
 *         format="int64",
 *         enum={-2,-1,0,1,2}
 *     ),
 *
 *     @OA\Property(
 *         property="direction",
 *         title="direction",
 *         description="Is the ward atmosphere getting better, worse or the same? between -1 and +1",
 *         type="integer",
 *         format="int64",
 *         enum={-1,0,1}
 *     ),
 *
 *     @OA\Property(
 *         property="comment",
 *         title="comment",
 *         type="string",
 *         description="An open format comment to accompany each submission"
 *     ),
 *
 *     @OA\Property(
 *         property="abandoned",
 *         title="abandoned",
 *         description="A flag to indicate if the submission was completed",
 *         type="boolean"
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
 *     )
 * )
 */
class Submission extends Model
{
    use HasFactory;

    /**
     * Attributes to be cast.
     *
     * @var array
     */
    protected $casts = [
        'atmosphere' => 'integer',
        'direction' => 'integer',
    ];

    /**
     * Mass assignable attributes.
     *
     * @var array
     */
    protected $fillable = [
        'atmosphere',
        'direction',
        'comment',
        'abandoned',
    ];

    /**
     * The client that this submission belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * The causes that belong to this submission.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function causes()
    {
        return $this->belongsToMany(Cause::class);
    }
}
