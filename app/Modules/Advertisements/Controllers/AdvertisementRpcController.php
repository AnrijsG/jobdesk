<?php

namespace App\Modules\Advertisements\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AdvertisementModel;
use App\Modules\Advertisements\Repositories\JobCategoryRepository;
use App\Modules\Advertisements\Service\AdvertisementService;
use App\Modules\Advertisements\Structures\AdvertisementQueryItem;
use Illuminate\Http\Request;

class AdvertisementRpcController extends Controller
{
    private AdvertisementService $service;

    public function __construct(AdvertisementService $service)
    {
        $this->service = $service;
    }

    public function getAdvertisements(Request $request)
    {
        $filters = $request->only('searchItem');

        $searchCriteria = AdvertisementQueryItem::fromArray($filters['searchItem']);

        if ($filters['searchItem']['withCurrentEnvironmentId']) {
            $environmentId = $request->user()->environment->id;
            $searchCriteria->environmentId = $environmentId;
        }

        $advertisements = $this->service->search($searchCriteria);
        if ($advertisements) {
            $advertisements = array_map(
                fn($advertisement) => AdvertisementModel::fromArray(get_object_vars($advertisement))->toRpc(),
                $advertisements
            );
        }

        return $advertisements;
    }

    public function saveAdvertisement(Request $request)
    {
        $advertisementData = $request->input('advertisementData');

        $environmentId = $request->user()->environment->id;
        $advertisementData['environmentId'] = $environmentId;

        return $this->service->create($advertisementData);
    }

    public function getJobCategories()
    {
        return JobCategoryRepository::ALL_CATEGORIES;
    }
}
