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

    public function deleteRegistrationHash(Environment $environment): bool
    {
        $environment->registration_hash = null;

        return $environment->update();
    }

    /**
     * @param Environment $environment
     * @param string $website
     * @return bool
     */
    public function setCompanyWebsite(Environment $environment, string $website): bool
    {
        if (!$website) {
            $environment->company_website = null;
        } else {
            $environment->company_website = e($website);
        }

        return $environment->update();
    }
}
