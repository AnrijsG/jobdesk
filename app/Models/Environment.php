<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Environment
 * @package App\Models
 *
 * @property int $id
 * @property string $registration_hash
 * @property string $role
 * @property string $company_name
 * @property string $created_at
 * @property string $updated_at
 *
 * @property-read User[] $users
 */
class Environment extends Model
{
    public const ROLE_APPLIER = 'applier';
    public const ROLE_ADVERTISER = 'advertiser';
    public const ROLE_ADMIN = 'admin';

    public const PUBLIC_ROLES = [
        self::ROLE_ADVERTISER,
        self::ROLE_APPLIER,
    ];

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function toRpc()
    {
        return [
            'environmentId' => $this->id,
            'registrationHash' => $this->registration_hash,
            'role' => $this->role,
            'companyName' => $this->company_name,
        ];
    }
}
