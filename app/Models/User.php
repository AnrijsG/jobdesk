<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;

/**
 * Class User
 * @package App\Models
 *
 * @property int $id
 * @property string $email
 * @property string $name
 * @property string $password
 * @property int $environment_id
 *
 * @property-read Environment $environment
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    public function save(array $options = [])
    {
        // hash password
        $this->password = Hash::make($this->password);

        return parent::save($options);
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'environment_id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function toRpc(): array
    {
        return [
            'userId' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'environment' => $this->environment->toRpc(),
        ];
    }

    public function environment()
    {
        return $this->belongsTo(Environment::class);
    }
}
