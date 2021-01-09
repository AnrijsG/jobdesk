<?php

namespace App\Modules\Advertisements\Service;

use App\Modules\Advertisements\Repositories\AdvertisementRepository;

class AdvertisementCleanupService
{
    private AdvertisementRepository $repository;

    public function __construct(AdvertisementRepository $repository)
    {
        $this->repository = $repository;
    }

    public function cleanup()
    {
        $currentDate = date('Y-m-d');
        $advertisements = $this->repository->getActive();

        foreach ($advertisements as $advertisement) {
            if ($advertisement->expiration_date <= $currentDate) {
                $advertisement->is_active = false;

                $this->repository->saveObject($advertisement);
            }
        }
    }
}
