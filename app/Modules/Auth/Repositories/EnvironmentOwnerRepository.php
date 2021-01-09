<?php

namespace App\Modules\Auth\Repositories;

use App\Models\EnvironmentOwner;
use App\Repositories\BaseRepository;

class EnvironmentOwnerRepository extends BaseRepository
{
    protected function tableName(): string
    {
        return 'environment_owners';
    }

    protected function model(): string
    {
        return EnvironmentOwner::class;
    }

    public function getByUserId(int $userId): ?EnvironmentOwner
    {
        return $this->all()->where('user_id', $userId)->first();
    }
}
