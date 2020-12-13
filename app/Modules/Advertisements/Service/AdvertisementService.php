<?php


namespace App\Modules\Advertisements\Service;


use App\Models\AdvertisementModel;
use App\Models\User;
use App\Modules\Advertisements\Exceptions\AdvertisementSaveException;
use App\Modules\Advertisements\Repositories\AdvertisementRepository;
use App\Modules\Advertisements\Structures\AdvertisementQueryItem;

class AdvertisementService
{
    private AdvertisementRepository $repository;

    public function __construct(AdvertisementRepository $repository) {
        $this->repository = $repository;
    }

    /**
     * @param AdvertisementQueryItem $queryItem
     * @return AdvertisementModel[]
     */
    public function search(AdvertisementQueryItem $queryItem): array
    {
        return $this->repository->find($queryItem);
    }

    /**
     * @param array $newItemData
     * @param User $user
     * @return AdvertisementModel
     * @throws AdvertisementSaveException
     */
    public function create(array $newItemData, User $user): AdvertisementModel
    {
        $newItem = AdvertisementModel::fromArray($newItemData);
        if ($newItem->id) {
            $this->failIfUserNotAdvertisementOwner($newItem, $user);
        }

        $this->repository->save($newItem);

        return $newItem;
    }

    public function failIfUserNotAdvertisementOwner(AdvertisementModel $advertisement, User $user)
    {
        // TODO: Unit test
        if ($advertisement->environment_id !== $user->environment_id) {
            throw new AdvertisementSaveException('Unauthorised action');
        }
    }
}
