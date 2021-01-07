<?php

namespace App\Modules\Auth\Repositories;

use App\Models\Environment;
use App\Models\EnvironmentMeta;
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

    public function getById(int $environmentId): ?Environment
    {
        return $this->all()->where('id', $environmentId)->first();
    }

    /**
     * @param int $environmentId
     * @param string $key
     * @return EnvironmentMeta|null
     */
    public function getMetaRow(int $environmentId, string $key): ?EnvironmentMeta
    {
        return EnvironmentMeta::query()
            ->where(
                [
                    'environment_id' => $environmentId,
                    'key' => $key,
                ]
            )
            ->first();
    }
}
