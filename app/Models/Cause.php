<?php

namespace App\Models;

use App\Models\Submission;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cause extends Model
{
    use HasFactory;

    /**
     * The submissions that belong to this cause
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     **/
    public function submissions()
    {
        return $this->belongsToMany(Submission::class);
    }
}
