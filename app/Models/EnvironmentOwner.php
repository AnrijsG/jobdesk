<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class EnvironmentOwner
 * @package App\Models
 *
 * @property int $id
 * @property int $user_id
 * @property int $environment_id
 * @property int $created_at
 * @property int $updated_at
 */
class EnvironmentOwner extends Model
{
    protected $table = 'environment_owners';
}
