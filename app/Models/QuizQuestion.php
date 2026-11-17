<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuizQuestion extends Model
{
    protected $guarded = [];

    protected $hidden = ['answer', 'explanation'];

    protected function casts(): array
    {
        return ['options' => 'array'];
    }
}
