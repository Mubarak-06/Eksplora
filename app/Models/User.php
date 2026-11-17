<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    public function progress()
    {
        return $this->hasMany(LearningProgress::class);
    }

    public function attempts()
    {
        return $this->hasMany(QuizAttempt::class);
    }

    public function points()
    {
        return $this->hasMany(PointTransaction::class);
    }

    public function badges()
    {
        return $this->belongsToMany(Badge::class, 'user_badges')->withTimestamps();
    }

    public function getTotalPointsAttribute(): int
    {
        return (int) $this->points()->sum('points');
    }

    public function getXpStepAttribute(): int
    {
        return LearningRule::valueFor('level_step');
    }

    public function getLevelAttribute(): int
    {
        return intdiv($this->total_points, $this->xp_step) + 1;
    }

    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
