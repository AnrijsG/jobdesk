<?php

namespace App\Modules\Auth\Repositories;

use App\Models\Environment;
use App\Models\EnvironmentMeta;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\DB;

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

    public function getCv(int $environmentId)
    {
        return DB::table(EnvironmentMeta::tableName())
            ->where(
                [
                    'environment_id' => $environmentId,
                    'key' => EnvironmentMeta::KEY_CV_FILENAME,
                ]
            );
    }
}
