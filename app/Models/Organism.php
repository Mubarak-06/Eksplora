<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Organism extends Model
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

    public function ecosystems()
    {
        return $this->belongsToMany(Ecosystem::class);
    }
}
