<?php

namespace App\Modules\Auth\Repositories;

use App\Models\Environment;
use App\Repositories\BaseRepository;

class EnvironmentRepository extends BaseRepository
{
    protected function tableName(): string
    {
        return 'environments';
    }

    protected function model(): string
    {
        return Environment::class;
    }

    public function getByRegistrationHash(string $registrationHash): ?Environment
    {
        return $this->all()->where('registration_hash', $registrationHash)->first();
    }
}
