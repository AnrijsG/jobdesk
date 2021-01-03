<?php

namespace App\Modules\Advertisements\Service;

use App\Models\AdvertisementModel;
use App\Models\AdvertisementReply;
use App\Models\Environment;
use App\Models\EnvironmentMeta;
use App\Models\User;
use App\Modules\Advertisements\Exceptions\AdvertisementApplicationSubmissionException;
use App\Modules\Advertisements\Exceptions\AdvertisementSaveException;
use App\Modules\Advertisements\Repositories\AdvertisementReplyRepository;
use App\Modules\Advertisements\Repositories\AdvertisementRepository;
use App\Modules\Advertisements\Structures\AdvertisementQueryItem;
use App\Modules\Auth\Repositories\EnvironmentRepository;

class AdvertisementService
{
    private AdvertisementRepository $repository;
    private EnvironmentRepository $environmentRepository;
    private AdvertisementReplyRepository $advertisementReplyRepository;

    public function __construct(
        AdvertisementRepository $repository,
        EnvironmentRepository $environmentRepository,
        AdvertisementReplyRepository $advertisementReplyRepository
    ) {
        $this->repository = $repository;
        $this->environmentRepository = $environmentRepository;
        $this->advertisementReplyRepository = $advertisementReplyRepository;
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
    public function save(array $newItemData, User $user): AdvertisementModel
    {
        $advertisement = null;
        if (isset($newItemData['advertisementId'])) {
            $advertisement = $this->repository->getById($newItemData['advertisementId']);
        }

        $newItem = AdvertisementModel::fromArray($newItemData, $advertisement);
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

    /**
     * @param int $advertisementId
     * @param User $user
     * @param string $coverLetter
     * @throws AdvertisementApplicationSubmissionException
     */
    public function submitApplication(int $advertisementId, User $user, string $coverLetter)
    {
        // Validation
        if ($user->environment->role !== Environment::ROLE_APPLIER) {
            throw new AdvertisementApplicationSubmissionException('Invalid request');
        }

        if (!$coverLetter) {
            throw new AdvertisementApplicationSubmissionException('Missing cover letter');
        }

        /** @var EnvironmentMeta|null $cvFile */
        $cvFile = $this->environmentRepository->getMetaRow($user->environment_id, EnvironmentMeta::KEY_CV_FILENAME)->first();
        if (!$cvFile) {
            throw new AdvertisementApplicationSubmissionException('User missing CV');
        }

        $hasUserSubmittedToSameAdvertisementBefore = $this->advertisementReplyRepository->getByUserAndAdvertisement($user->id, $advertisementId);
        if ($hasUserSubmittedToSameAdvertisementBefore) {
            throw new AdvertisementApplicationSubmissionException('You have already applied for this position in the past');
        }

        // Save application
        $application = new AdvertisementReply;
        $application->cv_download_url = e($cvFile->value);
        $application->cover_letter = e($coverLetter);
        $application->advertisement_id = $advertisementId;
        $application->user_id = $user->id;

        $application->save();
    }

    /**
     * @param int $advertisementId
     * @param int $environmentId id of user creating request
     * @return AdvertisementReply[]
     */
    public function getApplicants(int $advertisementId, int $environmentId): array
    {
        $advertisement = $this->repository->getById($advertisementId);

        // validate that user has permission to view applications for given advertisement
        if (!$advertisement || $advertisement->environment->id !== $environmentId) {
            abort(403, 'Access denied');
        }

        return $this->advertisementReplyRepository->getByAdvertisement($advertisementId);
    }
}
