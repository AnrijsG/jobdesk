<?php

namespace App\Modules\Advertisements\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AdvertisementModel;
use App\Modules\Advertisements\Repositories\AdvertisementRepository;
use App\Modules\Advertisements\Service\AdvertisementService;
use Illuminate\Http\Request;

class AdvertisementRpcController extends Controller
{
    public function getAdvertisements(Request $request)
    {
        $filters = $request->only('category');
        $advertisementService = (new AdvertisementService(new AdvertisementRepository));

        $advertisements = $advertisementService->find($filters['category'] ?? '', 10);
        if ($advertisements) {
            $advertisements = array_map(
                fn($advertisement) => AdvertisementModel::fromArray(get_object_vars($advertisement))->toRpc(),
                $advertisements
            );
        }

        return $advertisements;
    }
}
