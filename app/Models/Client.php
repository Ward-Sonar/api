<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

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

    const SHIFT_MORNING_START = [8, 0, 0];
    const SHIFT_AFTERNOON_START = [12, 0, 0];
    const SHIFT_EVENING_START = [18, 0, 0];

    /**
     * The submissions that belong to this client.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function submissions()
    {
        return $this->hasMany(Submission::class);
    }

    /**
     * The submissions that belong to this client.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function completedShiftSubmissions()
    {
        return $this->submissions
            ->where('abandoned', false)
            ->whereBetween('created_at', [$this->shiftStart, $this->shiftEnd]);
    }

    /**
     * Mutator to find the shift start time.
     *
     * @param Carbon\Carbon
     * @param mixed|null $now
     * @return Carbon
     */
    public function getShiftStartAttribute($now = null)
    {
        $shiftTimes = $this->getShiftTimes($now);

        return $shiftTimes['start'];
    }

    /**
     * Mutator to find the shift end time.
     *
     * @param Carbon\Carbon
     * @param mixed|null $now
     * @return Carbon
     */
    public function getShiftEndAttribute($now = null)
    {
        $shiftTimes = $this->getShiftTimes($now);

        return $shiftTimes['end'];
    }

    /**
     * Mutator to find the shift end time.
     *
     * @param Carbon\Carbon
     * @param mixed|null $now
     * @return Carbon
     */
    public function getShiftTimes($now = null)
    {
        $now = $now ?: Carbon::now();
        $shifts = [
            $now->clone()->setTime(...self::SHIFT_MORNING_START),
            $now->clone()->setTime(...self::SHIFT_AFTERNOON_START),
            $now->clone()->setTime(...self::SHIFT_EVENING_START),
        ];

        foreach ($shifts as $index => $startTime) {
            $nextShiftStartTime = $index < 2 ? $shifts[$index + 1] : $now->clone()->addDay()->setTime(...self::SHIFT_MORNING_START);
            if ($now->greaterThanOrEqualTo($startTime) && $now->lessThan($nextShiftStartTime)) {
                $shiftTimes = [
                    'start' => $startTime,
                    'end' => $nextShiftStartTime,
                ];
            }
        }

        return $shiftTimes;
    }
}
