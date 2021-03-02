<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Jetstream\HasTeams;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use HasTeams;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'username',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
    * The accessors to append to the model's array form.
    *
    * @var array
    */
    protected $appends = [
        'profile_photo_url',
    ];

    /**
     * Get the goeAreas for the user.
     */
    public function geoAreas(): HasMany
    {
        return $this->hasMany(GeoArea::class);
    }

    /**
     * Get the locale for the user.
     */
    public function locale(): HasOne
    {
        return $this->hasOne(Locale::class);
    }

    /**
     * Get the userLogs for the user.
     */
    public function userLogs(): HasMany
    {
        return $this->hasMany(UserLog::class);
    }

    /**
     * Get the userRegistrationRequests for the user.
     */
    public function userRegistrationRequests(): HasMany
    {
        return $this->hasMany(UserRegistrationRequest::class);
    }

    /**
     * Get the userProfiles for the user.
     */
    public function userProfiles(): HasMany
    {
        return $this->hasMany(UserProfile::class);
    }

    /**
     * Get the userEntities for the user.
     */
    public function userEntities(): HasMany
    {
        return $this->hasMany(UserEntity::class);
    }

    /**
     * Get the objectInstances for the user.
     */
    public function objectInstances(): HasMany
    {
        return $this->hasMany(ObjectInstance::class);
    }

    /**
     * Get the objectInstanceSharingTypes for the user.
     */
    public function objectInstanceSharingTypes(): HasMany
    {
        return $this->hasMany(ObjectInstanceSharingType::class);
    }

    /**
     * Get the simulationManagers for the user.
     */
    public function simulationManagers(): HasMany
    {
        return $this->hasMany(SimulationManager::class);
    }

    /**
     * Get the simulationManagerSharingTypes for the user.
     */
    public function simulationManagerSharingTypes(): HasMany
    {
        return $this->hasMany(SimulationManagerSharingType::class);
    }
}
