<?php

namespace Tests\Unit;

use App\Models\AdvertisementModel;
use App\Models\AdvertisementReply;
use App\Models\Environment;
use App\Models\EnvironmentMeta;
use App\Models\User;
use App\Modules\Advertisements\Exceptions\AdvertisementSaveException;
use App\Modules\Advertisements\Exceptions\DuplicateSubmissionException;
use App\Modules\Advertisements\Exceptions\EmptyCoverLetterException;
use App\Modules\Advertisements\Exceptions\InsufficientEnvironmentRoleException;
use App\Modules\Advertisements\Exceptions\MissingCvException;
use App\Modules\Advertisements\Factories\AdvertisementFactory;
use App\Modules\Advertisements\Repositories\AdvertisementReplyRepository;
use App\Modules\Advertisements\Repositories\AdvertisementRepository;
use App\Modules\Advertisements\Service\AdvertisementService;
use App\Modules\Auth\Repositories\EnvironmentRepository;
use Mockery;
use PHPUnit\Framework\TestCase;

class AdvertisementServiceTest extends TestCase
{
    private AdvertisementRepository $mockRepository;
    private EnvironmentRepository $mockEnvironmentRepository;
    private AdvertisementReplyRepository $mockAdvertisementReplyRepository;

    private AdvertisementFactory $mockFactory;

    private AdvertisementService $service;

    protected function setUp(): void
    {
        $this->mockRepository = Mockery::mock(AdvertisementRepository::class);
        $this->mockEnvironmentRepository = Mockery::mock(EnvironmentRepository::class);
        $this->mockAdvertisementReplyRepository = Mockery::mock(AdvertisementReplyRepository::class);
        $this->mockFactory = Mockery::mock(AdvertisementFactory::class);

        $this->service = new AdvertisementService(
            $this->mockRepository,
            $this->mockEnvironmentRepository,
            $this->mockAdvertisementReplyRepository,
            $this->mockFactory
        );

        parent::setUp();
    }

    /** @doesNotPerformAssertions */
    public function testSubmitApplication_withValidRequest_shouldSucceed()
    {
        $advertisementId = 1;
        $environmentId = 1;
        $environmentRole = Environment::ROLE_APPLIER;
        $userId = 1;
        $coverLetter = 'cover letter';

        $mockEnvironmentMeta = Mockery::mock(EnvironmentMeta::class)->makePartial()->shouldIgnoreMissing(true);

        $this->mockEnvironmentRepository->shouldReceive('getMetaRow')
            ->withArgs([$environmentId, EnvironmentMeta::KEY_CV_FILENAME])
            ->andReturn($mockEnvironmentMeta);

        $this->mockAdvertisementReplyRepository->shouldReceive('getByUserAndAdvertisement')
            ->withArgs([$userId, $advertisementId])
            ->andReturn(null);

        $this->mockAdvertisementReplyRepository->shouldReceive('saveObject')->andReturn(true);

        $this->service->submitApplication($advertisementId, $environmentId, $environmentRole, $userId, $coverLetter);
    }

    public function testSubmitApplication_withIncorrectRole_expectException()
    {
        $advertisementId = 1;
        $environmentId = 1;
        $environmentRole = Environment::ROLE_ADVERTISER;
        $userId = 1;
        $coverLetter = 'cover letter';

        $this->expectException(InsufficientEnvironmentRoleException::class);

        $this->service->submitApplication($advertisementId, $environmentId, $environmentRole, $userId, $coverLetter);
    }

    public function testSubmitApplication_withEmptyCoverLetter_expectException()
    {
        $advertisementId = 1;
        $environmentId = 1;
        $environmentRole = Environment::ROLE_APPLIER;
        $userId = 1;
        $coverLetter = '';

        $this->expectException(EmptyCoverLetterException::class);

        $this->service->submitApplication($advertisementId, $environmentId, $environmentRole, $userId, $coverLetter);
    }

    public function testSubmitApplication_withMissingCv_expectException()
    {
        $advertisementId = 1;
        $environmentId = 1;
        $environmentRole = Environment::ROLE_APPLIER;
        $userId = 1;
        $coverLetter = 'cover letter';

        $this->mockEnvironmentRepository->shouldReceive('getMetaRow')
            ->withArgs([$environmentId, EnvironmentMeta::KEY_CV_FILENAME])
            ->andReturn(null);

        $this->expectException(MissingCvException::class);

        $this->service->submitApplication($advertisementId, $environmentId, $environmentRole, $userId, $coverLetter);
    }

    public function testSubmitApplication_withDuplicateSubmission_expectException()
    {
        $advertisementId = 1;
        $environmentId = 1;
        $environmentRole = Environment::ROLE_APPLIER;
        $userId = 1;
        $coverLetter = 'cover letter';

        $mockEnvironmentMeta = Mockery::mock(EnvironmentMeta::class)->makePartial()->shouldIgnoreMissing(true);
        $mockAdvertisementReply = Mockery::mock(AdvertisementReply::class)->makePartial()->shouldIgnoreMissing(true);

        $this->mockEnvironmentRepository->shouldReceive('getMetaRow')
            ->withArgs([$environmentId, EnvironmentMeta::KEY_CV_FILENAME])
            ->andReturn($mockEnvironmentMeta);

        $this->mockAdvertisementReplyRepository->shouldReceive('getByUserAndAdvertisement')
            ->withArgs([$userId, $advertisementId])
            ->andReturn($mockAdvertisementReply);

        $this->expectException(DuplicateSubmissionException::class);

        $this->service->submitApplication($advertisementId, $environmentId, $environmentRole, $userId, $coverLetter);
    }

    /** @doesNotPerformAssertions */
    public function testSave_withValidData_shouldSucceed()
    {
        // set up
        $userEnvironment = 1;

        $newItemData = [
            'advertisementId' => 1,
            'environmentId' => $userEnvironment,
        ];

        $user = Mockery::mock(User::class)->makePartial()->shouldIgnoreMissing(true);
        $user->environment_id = $userEnvironment;

        $advertisementToReturn = Mockery::mock(AdvertisementModel::class)->makePartial()->shouldIgnoreMissing(true);
        $advertisementToReturn->id = 1;
        $advertisementToReturn->environment_id = $userEnvironment;

        $newAdvertisement = $advertisementToReturn; // Not important

        // assertions
        $this->mockRepository->shouldReceive('getById')
            ->withArgs([$newItemData['advertisementId']])
            ->andReturn($advertisementToReturn);

        $this->mockFactory->shouldReceive('fromArray')
            ->withArgs([$newItemData, $advertisementToReturn])
            ->andReturn($newAdvertisement)->once();

        $this->mockRepository->shouldReceive('save')->andReturn(true);

        $this->service->save($newItemData, $user);
    }

    public function testSave_withInvalidEnvironment_expectException()
    {
        // set up
        $userEnvironment = 1;
        $advertisementEnvironment = 2;

        $newItemData = [
            'advertisementId' => 1,
            'environmentId' => $advertisementEnvironment,
        ];

        $user = Mockery::mock(User::class)->makePartial()->shouldIgnoreMissing(true);
        $user->environment_id = $userEnvironment;

        $advertisementToReturn = Mockery::mock(AdvertisementModel::class)->makePartial()->shouldIgnoreMissing(true);
        $advertisementToReturn->id = 1;
        $advertisementToReturn->environment_id = $advertisementEnvironment;

        $newAdvertisement = $advertisementToReturn; // Not important

        // assertions
        $this->mockRepository->shouldReceive('getById')
            ->withArgs([$newItemData['advertisementId']])
            ->andReturn($advertisementToReturn)
            ->once();

        $this->mockFactory->shouldReceive('fromArray')
            ->withArgs([$newItemData, $advertisementToReturn])
            ->andReturn($newAdvertisement)
            ->once();

        $this->expectException(AdvertisementSaveException::class);

        $this->service->save($newItemData, $user);
    }
}
