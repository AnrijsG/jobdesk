<?php

namespace App\Modules\Auth\Services;

use App\Models\Environment;

class EnvironmentService
{
    public function resetRegistrationHash(Environment $environment): string
    {
        $newHash = md5(microtime());

        $environment->registration_hash = $newHash;
        $environment->update();

        return $newHash;
    }
}
