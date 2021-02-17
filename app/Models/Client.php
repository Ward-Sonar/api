<?php

namespace App\Models;

use App\Models\Submission;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    /**
     * The submissions that belong to this client
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function submissions()
    {
        return $this->hasMany(Submission::class);
    }
}
