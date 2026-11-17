<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LearningRule extends Model
{
    protected $primaryKey = 'key';

    public $incrementing = false;

    protected $keyType = 'string';

    public $timestamps = false;

    protected $guarded = [];

    public static function valueFor(string $key): int
    {
        return (int) static::where('key', $key)->firstOrFail()->value;
    }
}
