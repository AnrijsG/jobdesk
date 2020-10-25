<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

/**
 * Class Environment
 * @package App\Models
 *
 * @property int $id
 * @property string $role
 * @property string $company_name
 * @property string $created_at
 * @property string $updated_at
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

    public function toRpc()
    {
        return [
            'environmentId' => $this->id,
            'role' => $this->role,
            'companyName' => $this->company_name,
        ];
    }
}
