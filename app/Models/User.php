<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        static::creating(function (User $user) {
            $user->last_activity = now();
        });
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];

    public function isSuperAdmin()
    {
        return $this->super_admin != null ? true : false;
    }

    /**
     * Gets the users the currect user follows
     */
    public function follows(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_follows', 'user_id', 'follows_id')->withTimestamps();
    }

    /**
     * Gets the users that follows the current user
     */
    public function followers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_follows', 'follows_id');
    }

    /**
     * Gets the users the currect user blocks
     */
    public function blocks(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_blocks', 'user_id', 'blocks_id')->withTimestamps();
    }

    /**
     * Gets the users that blocks the current user
     */
    public function blockers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_blocks', 'blocks_id');
    }

    /**
     * Gets the users the currect user viewed
     */
    public function views(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'profile_views', 'user_id', 'viewed_id')->withTimestamps();
    }

    /**
     * Gets the users that viewed the current user
     */
    public function viewers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'profile_views', 'viewed_id');
    }
}
