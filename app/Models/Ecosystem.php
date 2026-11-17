<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ecosystem extends Model
{
    protected $guarded = [];

    protected function casts(): array
    {
        return ['content' => 'array'];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function organisms()
    {
        return $this->belongsToMany(Organism::class);
    }
}
