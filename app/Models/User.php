<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Laravel\Jetstream\HasProfilePhoto;
use App\Notifications\CustomPasswordReset; // Import the custom notification

class User extends Authenticatable implements MustVerifyEmail, CanResetPasswordContract
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;
    use TwoFactorAuthenticatable;
    use HasRoles;
    use CanResetPassword;
    use HasProfilePhoto;

    protected $guard_name = 'web';

    protected $fillable = [
        'name',
        'email',
        'password',
        'profile_photo_path'
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    protected $appends = [
        'profile_photo_url',
    ];

    protected $dates = [
        'password_changed_at',
        // other dates...
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    /**
     * Override the default password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new CustomPasswordReset($token));
    }
}
