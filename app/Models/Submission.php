<?php

namespace App\Models;

use App\Models\Cause;
use App\Models\Client;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Submission extends Model
{
    use HasFactory;

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
