<?php


namespace App\Modules\Auth\Factories;


use App\Exceptions\InvalidNewUserPropertiesException;
use App\Models\Environment;

class EnvironmentFactory
{
    public static function getEnvironment(string $role, ?string $companyName = null): Environment
    {
        if ($role === Environment::ROLE_ADVERTISER && !$companyName) {
            throw new InvalidNewUserPropertiesException('Company name must be set');
        }

        $environment = new Environment;
        $environment->role = $role;
        $environment->company_name = $companyName;

        return $environment;
    }
}
