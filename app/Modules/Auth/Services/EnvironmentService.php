<?php

namespace App\Modules\Auth\Services;

use App\Models\Environment;
use App\Models\User;
use App\Modules\Advertisements\Exceptions\UserActivationException;
use App\Modules\Auth\Repositories\UserRepository;

class EnvironmentService
{
    public UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

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

    /**
     * @param int $userId user that will be deactivated/activated
     * @param User $user
     * @return bool
     * @throws UserActivationException
     */
    public function toggleActive(int $userId, User $user): bool
    {
        // Get user
        $userToToggle = $this->userRepository->getById($userId);
        if (!$userToToggle) {
            throw new UserActivationException('User not found');
        }

        if ($userToToggle->environment_id !== $user->environment_id) {
            throw new UserActivationException('Access denied');
        }

        $userToToggle->is_active = !$userToToggle->is_active;

        return $userToToggle->update();
    }
}
