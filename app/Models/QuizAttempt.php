<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuizAttempt extends Model
{
    protected $guarded = [];

    protected function casts(): array
    {
        return ['answers' => 'array'];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
