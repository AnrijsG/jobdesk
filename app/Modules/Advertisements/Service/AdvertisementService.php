<?php


namespace App\Modules\Advertisements\Service;


use App\Models\AdvertisementModel;
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
}
