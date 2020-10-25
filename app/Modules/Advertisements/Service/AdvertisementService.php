<?php


namespace App\Modules\Advertisements\Service;


use App\Models\AdvertisementModel;
use App\Modules\Advertisements\Repositories\AdvertisementRepository;

class AdvertisementService
{
    private AdvertisementRepository $repository;

    public function __construct(AdvertisementRepository $repository) {
        $this->repository = $repository;
    }

    /**
     * @param string $category
     * @param int $limit
     * @return AdvertisementModel[]
     */
    public function find(string $category = '', int $limit = 0): array
    {
        return $this->repository->getAll($category, $limit);
    }
}
