<?php

namespace App\Modules\Advertisements\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AdvertisementModel;
use App\Modules\Advertisements\Repositories\AdvertisementRepository;
use App\Modules\Advertisements\Service\AdvertisementService;
use App\Modules\Advertisements\Structures\AdvertisementQueryItem;
use Illuminate\Http\Request;

class AdvertisementRpcController extends Controller
{
    public function getAdvertisements(Request $request)
    {
        $filters = $request->only('category');
        $advertisementService = (new AdvertisementService(new AdvertisementRepository));

        $searchCriteria = new AdvertisementQueryItem;
        $searchCriteria->category = $filters['category'] ?? '';
        $searchCriteria->limit = 10;

        $advertisements = $advertisementService->search($searchCriteria);
        if ($advertisements) {
            $advertisements = array_map(
                fn($advertisement) => AdvertisementModel::fromArray(get_object_vars($advertisement))->toRpc(),
                $advertisements
            );
        }

        return $advertisements;
    }

    public function getAdvertisementsByEnvironmentId(Request $request)
    {
        $environmentId = $request->user()->environment->id;
        $advertisementService = (new AdvertisementService(new AdvertisementRepository));

        $searchCriteria = new AdvertisementQueryItem;
        $searchCriteria->environmentId = $environmentId;

        $advertisements = $advertisementService->search($searchCriteria);
        if ($advertisements) {
            $advertisements = array_map(
                fn($advertisement) => AdvertisementModel::fromArray(get_object_vars($advertisement))->toRpc(),
                $advertisements
            );
        }

        return $advertisements;
    }
}
