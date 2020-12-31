<?php

namespace App\Modules\Auth\Repositories;

use App\Models\Environment;
use App\Models\EnvironmentMeta;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\DB;
use \Illuminate\Database\Query\Builder;

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
     * @return Builder
     */
    public function getMetaRow(int $environmentId, string $key): Builder
    {
        return DB::table(EnvironmentMeta::tableName())
            ->where(
                [
                    'environment_id' => $environmentId,
                    'key' => $key,
                ]
            );
    }
}
