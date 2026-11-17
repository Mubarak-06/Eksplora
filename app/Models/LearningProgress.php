<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LearningProgress extends Model
{
    protected $table = 'user_learning_progress';

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
