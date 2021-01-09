<?php

namespace App\Modules\Advertisements\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AdvertisementModel;
use App\Models\AdvertisementReply;
use App\Models\Environment;
use App\Modules\Advertisements\Exceptions\AdvertisementApplicationSubmissionException;
use App\Modules\Advertisements\Exceptions\AdvertisementDeleteException;
use App\Modules\Advertisements\Repositories\JobCategoryRepository;
use App\Modules\Advertisements\Service\AdvertisementService;
use App\Modules\Advertisements\Structures\AdvertisementQueryItem;
use Illuminate\Http\Request;
use \App\Modules\Advertisements\Exceptions\AdvertisementSaveException;
use Illuminate\Validation\UnauthorizedException;
use Throwable;

class AdvertisementRpcController extends Controller
{
    private AdvertisementService $service;

    public function __construct(AdvertisementService $service)
    {
        $this->service = $service;
    }

    public function getAdvertisements(Request $request)
    {;
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

    /**
     * @param Request $request
     * @return AdvertisementModel
     * @throws AdvertisementSaveException
     */
    public function saveAdvertisement(Request $request)
    {
        $advertisementData = $request->input('advertisementData');

        $environmentId = $request->user()->environment->id;
        $advertisementData['environmentId'] = $environmentId;

        $user = $request->user();

        return $this->service->save($advertisementData, $user);
    }

    public function deleteAdvertisement(Request $request)
    {
        try {
            return $this->service->delete($request->input('advertisementId'), $request->user());
        } catch (AdvertisementDeleteException $e) {
            throw $e;
        } catch (Throwable $e) {
            abort(500, 'Something went wrong, please try again later');
        }
    }

    /**
     * @param Request $request
     */
    public function submitApplication(Request $request)
    {
        $user = $request->user();

        $advertisementId = $request->input('advertisementId');
        $coverLetter = $request->input('coverLetter');

        try {
            $this->service->submitApplication(
                $advertisementId,
                $user->environment_id,
                $user->environment->role,
                $user->id,
                $coverLetter
            );
        } catch (AdvertisementApplicationSubmissionException $e) {
            report($e);

            abort(500, $e->getMessage());
        } catch (Throwable $e) {
            report($e);

            abort(500, 'Something went wrong, please try again later');
        }
    }

    public function getAppliableAdvertisements(Request $request)
    {
        $environment = $request->user()->environment;

        $advertisementQueryItem = new AdvertisementQueryItem;
        $advertisementQueryItem->environmentId = $environment->id;
        $advertisementQueryItem->onlyAppliableAdvertisements = true;
        $advertisementQueryItem->onlyActive = false;

        $advertisements = $this->service->search($advertisementQueryItem);
        if ($advertisements) {
            $advertisements = array_map(
                fn($advertisement) => AdvertisementModel::fromArray(get_object_vars($advertisement))->toRpc(),
                $advertisements
            );
        }

        return $advertisements ?? [];
    }

    public function getApplicants(Request $request)
    {
        /**
         * Environment of user creating request
         * @var Environment|null $environment
         */
        $environment = $request->user()->environment;
        if (!$environment) {
            throw new UnauthorizedException();
        }

        // Make sure array key numbering starts at 0
        return array_values(
            array_map(
                fn(AdvertisementReply $advertisementReply) => $advertisementReply->toRpc(),
                $this->service->getApplicants($request->input('advertisementId'), $environment->id)
            )
        );
    }

    public function getJobCategories()
    {
        return JobCategoryRepository::ALL_CATEGORIES;
    }
}
