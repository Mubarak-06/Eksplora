<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuizPuzzle extends Model
{
    protected $guarded = [];

    public function ecosystem()
    {
        return $this->belongsTo(Ecosystem::class);
    }
}
