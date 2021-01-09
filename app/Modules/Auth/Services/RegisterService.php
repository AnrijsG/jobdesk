<?php


namespace App\Modules\Auth\Services;


use App\Exceptions\InvalidEnvironmentRoleException;
use App\Exceptions\InvalidNewUserPropertiesException;
use App\Models\Environment;
use App\Models\User;
use App\Modules\Auth\Factories\EnvironmentFactory;
use App\Modules\Auth\Repositories\EnvironmentRepository;
use App\Modules\Auth\Repositories\UserRepository;
use App\Modules\Auth\Structures\NewUserStructure;
use Exception;
use Illuminate\Support\Facades\DB;

class RegisterService
{
    private UserRepository $userRepository;
    private EnvironmentRepository $environmentRepository;

    public function __construct(UserRepository $userRepository, EnvironmentRepository $environmentRepository)
    {
        $this->userRepository = $userRepository;
        $this->environmentRepository = $environmentRepository;
    }

    public function register(NewUserStructure $user): User
    {
        $this->failIfInvalidRole($user->role);

        $this->failIfEmailNotUnique($user->email);

        try {
            DB::beginTransaction();

            $environment = null;

            $registrationHash = $user->additionalData['registrationHash'] ?? null;
            if ($registrationHash) {
                $environment = $this->environmentRepository->getByRegistrationHash($registrationHash);

                if (!$environment) {
                    throw new InvalidNewUserPropertiesException('Invalid registration hash');
                }
            }

            if (!$environment) {
                $environment = $this->generateNewEnvironment($user);
            }

            $newUser = new User;
            $newUser->name = $user->name;
            $newUser->email = $user->email;
            $newUser->password = $user->password;
            $newUser->environment_id = $environment->id;

            $this->userRepository->saveObject($newUser);
        } catch (Exception $e) {
            DB::rollBack();

            throw $e;
        }

        DB::commit();

        $newUser->refresh();

        if ($registrationHash) {
            $this->afterRegister($environment, $registrationHash);
        }

        return $newUser;
    }

    private function failIfEmailNotUnique(string $email)
    {
        $user = $this->userRepository->getUserByEmail($email);
        if ($user) {
            throw new InvalidNewUserPropertiesException('Email taken');
        }
    }

    private function failIfInvalidRole(string $role)
    {
        if (!in_array($role, Environment::PUBLIC_ROLES)) {
            throw new InvalidEnvironmentRoleException();
        }
    }

    /**
     * @param NewUserStructure $user
     * @return Environment
     * @throws InvalidNewUserPropertiesException
     */
    private function generateNewEnvironment(NewUserStructure $user): Environment
    {
        switch ($user->role) {
            case Environment::ROLE_ADVERTISER:
                $environment = EnvironmentFactory::getEnvironment($user->role, $user->additionalData['companyName'] ?? null);
                break;
            default:
                $environment = EnvironmentFactory::getEnvironment($user->role);
        }

        $this->environmentRepository->saveObject($environment);

        return $environment;
    }

    /**
     * @param Environment|null $environment
     * @param string $registrationHash
     */
    private function afterRegister(?Environment $environment, string $registrationHash): void
    {
        // Clean up registration hash if used
        if ($environment->registration_hash === $registrationHash) {
            $environment->registration_hash = null;

            $environment->update();
        }
    }
}
