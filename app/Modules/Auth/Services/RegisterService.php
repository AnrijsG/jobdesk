<?php


namespace App\Modules\Auth\Services;


use App\Exceptions\InvalidEnvironmentRoleException;
use App\Models\Environment;
use App\Models\User;
use App\Modules\Auth\Factories\EnvironmentFactory;
use App\Modules\Auth\Repositories\UserRepository;
use App\Modules\Auth\Structures\NewUserStructure;
use Illuminate\Support\Facades\DB;

class RegisterService
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function register(NewUserStructure $user): User
    {
        $this->failIfInvalidRole($user->role);

        $this->failIfEmailNotUnique($user->email);

        try {
            DB::beginTransaction();

            switch ($user->role) {
                case Environment::ROLE_ADVERTISER:
                    $environment = EnvironmentFactory::getEnvironment($user->role, $user->additionalData['companyName']);
                    break;
                default:
                    $environment = EnvironmentFactory::getEnvironment($user->role);
            }

            $environment->save();

            $newUser = new User;
            $newUser->name = $user->name;
            $newUser->email = $user->email;
            $newUser->password = $user->password;
            $newUser->environment_id = $environment->id;

            $newUser->save();
        } catch (\Exception $e) {
            DB::rollBack();

            throw $e;
        }

        DB::commit();

        return $newUser;
    }

    private function failIfEmailNotUnique(string $email)
    {
        $user = $this->userRepository->getUserByEmail($email);
        if ($user) {
            throw new InvalidEnvironmentRoleException();
        }
    }

    private function failIfInvalidRole(string $role)
    {
        if (!in_array($role, Environment::PUBLIC_ROLES)) {
            throw new InvalidEnvironmentRoleException();
        }
    }
}
