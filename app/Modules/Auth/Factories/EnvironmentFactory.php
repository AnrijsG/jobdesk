<?php


namespace App\Modules\Auth\Factories;


use App\Models\Environment;

class EnvironmentFactory
{
    public static function getEnvironment(string $role, string $companyName = ''): Environment
    {
        $environment = new Environment;
        $environment->role = $role;
        $environment->company_name = $companyName;

        return $environment;
    }
}
