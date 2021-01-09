<?php

namespace Tests\Unit;

use App\Models\AdvertisementModel;
use App\Modules\Advertisements\Repositories\AdvertisementRepository;
use App\Modules\Advertisements\Service\AdvertisementCleanupService;
use Mockery;
use PHPUnit\Framework\TestCase;

class AdvertisementCleanupServiceTest extends TestCase
{
    public AdvertisementRepository $repository;

    public AdvertisementCleanupService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = Mockery::mock(AdvertisementRepository::class);

        $this->service = new AdvertisementCleanupService($this->repository);
    }

    /** @doesNotPerformAssertions  */
    public function testCleanup_withValidData_shouldSucceed()
    {
        $advertisementWithFutureExpiry = self::getAdvertisement(date('Y-m-d') + 1000);
        $advertisementThatShouldExpire = self::getAdvertisement(date('Y-m-d') - 1000);

        $this->repository->shouldReceive('getActive')
            ->andReturn([$advertisementWithFutureExpiry, $advertisementThatShouldExpire])
            ->once();

        $advertisementThatShouldExpire->is_active = false;

        // Important that it gets called only once, so we know that the active one doesn't get re-saved
        $this->repository->shouldReceive('saveObject')
            ->withArgs([$advertisementThatShouldExpire])
            ->once();

        $this->service->cleanup();
    }

    private static function getAdvertisement(int $expiryTimestamp): AdvertisementModel
    {
        $advertisement = Mockery::mock(AdvertisementModel::class)
            ->makePartial()
            ->shouldIgnoreMissing(true);

        $advertisement->expiration_date = $expiryTimestamp;
        $advertisement->is_active = true;

        return $advertisement;
    }
}
