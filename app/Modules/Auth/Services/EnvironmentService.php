<?php

namespace App\Modules\Auth\Services;

use App\Models\Environment;
use App\Models\EnvironmentOwner;
use App\Models\User;
use App\Modules\Advertisements\Exceptions\UserActivationException;
use App\Modules\Advertisements\Exceptions\UserOwnershipTransferException;
use App\Modules\Auth\Repositories\EnvironmentOwnerRepository;
use App\Modules\Auth\Repositories\UserRepository;

class EnvironmentService
{
    public UserRepository $userRepository;
    public EnvironmentOwnerRepository $ownerRepository;

    public function __construct(UserRepository $userRepository, EnvironmentOwnerRepository $ownerRepository)
    {
        $this->userRepository = $userRepository;
        $this->ownerRepository = $ownerRepository;
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

        if (!$user->isEnvironmentOwner() || $userToToggle->isEnvironmentOwner()) {
            throw new UserActivationException('Access denied');
        }

        $userToToggle->is_active = !$userToToggle->is_active;

        return $userToToggle->update();
    }

    /**
     * @param int $userId user that will have action inflicted
     * @param User $user user requesting toggle
     * @return bool
     * @throws UserOwnershipTransferException
     */
    public function toggleOwnership(int $userId, User $user): bool
    {
        if ($userId === $user->id) {
            throw new UserOwnershipTransferException('Access denied');
        }

        if (!$user->isEnvironmentOwner()) {
            throw new UserOwnershipTransferException('Access denied');
        }

        // Get user
        $userToToggle = $this->userRepository->getById($userId);
        if (!$userToToggle) {
            throw new UserOwnershipTransferException('User not found');
        }

        if ($userToToggle->isEnvironmentOwner()) {
            return $this->ownerRepository->getByUserId($userId)->delete();
        }

        $newOwner = new EnvironmentOwner();
        $newOwner->user_id = $userId;
        $newOwner->environment_id = $user->environment_id;

        return $newOwner->save();
    }
}
