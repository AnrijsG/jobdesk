<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Arr;

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
 * @property-read EnvironmentMeta[] $meta
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

    public function meta()
    {
        return $this->hasMany(EnvironmentMeta::class);
    }

    /**
     * @return string|null
     */
    public function getLogoUrl(): ?string
    {
        $metaEntry = Arr::first(
            $this->meta,
            fn(EnvironmentMeta $meta) => $meta->key = EnvironmentMeta::KEY_COMPANY_LOGO_FILE
        );

        return isset($metaEntry->value) ? "/logo/{$metaEntry->value}" : null;
    }

    public function toRpc(): array
    {
        return [
            'environmentId' => $this->id,
            'registrationHash' => $this->registration_hash,
            'role' => $this->role,
            'companyName' => $this->company_name,
            'logoUrl' => $this->getLogoUrl(),
        ];
    }
}
