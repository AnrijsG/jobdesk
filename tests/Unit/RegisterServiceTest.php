<?php

namespace Tests\Unit;

use App\Exceptions\InvalidEnvironmentRoleException;
use App\Exceptions\InvalidNewUserPropertiesException;
use App\Models\Environment;
use App\Models\User;
use App\Modules\Auth\Repositories\EnvironmentRepository;
use App\Modules\Auth\Repositories\UserRepository;
use App\Modules\Auth\Services\RegisterService;
use App\Modules\Auth\Structures\NewUserStructure;
use Illuminate\Support\Facades\DB;
use Mockery;
use PHPUnit\Framework\TestCase;

class RegisterServiceTest extends TestCase
{
    private UserRepository $userRepository;
    private EnvironmentRepository $environmentRepository;

    public RegisterService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->userRepository = Mockery::mock(UserRepository::class);
        $this->environmentRepository = Mockery::mock(EnvironmentRepository::class);

        $this->service = new RegisterService($this->userRepository, $this->environmentRepository);
    }

    public function testRegister_withExistingEmail_expectException()
    {
        // Set up
        $newUserItem = new NewUserStructure();
        $newUserItem->name = 'Name Surname';
        $newUserItem->email = 'name@user.com';
        $newUserItem->password = 'password';
        $newUserItem->role = Environment::ROLE_APPLIER;

        // Assertions
        DB::shouldReceive('beginTransaction');

        $this->userRepository
            ->shouldReceive('getUserByEmail')
            ->withArgs([$newUserItem->email])
            ->andReturn(new User());

        $this->expectException(InvalidNewUserPropertiesException::class);

        DB::shouldReceive('rollBack');

        $this->service->register($newUserItem);
    }

    /** @doesNotPerformAssertions  */
    public function testRegister_withApplierData_shouldSucceed()
    {
        // Set up
        $newUserItem = new NewUserStructure();
        $newUserItem->name = 'Name Surname';
        $newUserItem->email = 'name@user.com';
        $newUserItem->password = 'password';
        $newUserItem->role = Environment::ROLE_APPLIER;

        // Assertions
        DB::shouldReceive('beginTransaction');

        $this->userRepository
            ->shouldReceive('getUserByEmail')
            ->withArgs([$newUserItem->email])
            ->andReturn(null);

        $this->environmentRepository->shouldReceive('saveObject')->andReturn(true)->once();

        $this->userRepository->shouldReceive('saveObject')->andReturn(true)->once();

        DB::shouldReceive('commit');

        $this->service->register($newUserItem);
    }

    public function testRegister_withInvalidRole_expectException()
    {
        // Set up
        $newUserItem = new NewUserStructure();
        $newUserItem->name = 'Name Surname';
        $newUserItem->email = 'name@user.com';
        $newUserItem->password = 'password';
        $newUserItem->role = 'Invalid Role';

        // Assertions
        DB::shouldReceive('beginTransaction');

        $this->expectException(InvalidEnvironmentRoleException::class);

        DB::shouldReceive('rollBack');

        $this->service->register($newUserItem);
    }

    /** @doesNotPerformAssertions  */
    public function testRegister_withAdvertiserData_shouldSucceed()
    {
        // Set up
        $newUserItem = new NewUserStructure();
        $newUserItem->name = 'Name Surname';
        $newUserItem->email = 'name@user.com';
        $newUserItem->password = 'password';
        $newUserItem->role = Environment::ROLE_ADVERTISER;
        $newUserItem->additionalData = [
            'companyName' => 'Test company'
        ];

        // Assertions
        DB::shouldReceive('beginTransaction');

        $this->userRepository
            ->shouldReceive('getUserByEmail')
            ->withArgs([$newUserItem->email])
            ->andReturn(null);

        $this->environmentRepository->shouldReceive('saveObject')->andReturn(true)->once();

        $this->userRepository->shouldReceive('saveObject')->andReturn(true)->once();

        DB::shouldReceive('commit');

        $this->service->register($newUserItem);
    }

    public function testRegister_withoutCompanyName_expectException()
    {
        // Set up
        $newUserItem = new NewUserStructure();
        $newUserItem->name = 'Name Surname';
        $newUserItem->email = 'name@user.com';
        $newUserItem->password = 'password';
        $newUserItem->role = Environment::ROLE_ADVERTISER;
        $newUserItem->additionalData = []; // Unset company name

        // Assertions
        DB::shouldReceive('beginTransaction');

        $this->userRepository
            ->shouldReceive('getUserByEmail')
            ->withArgs([$newUserItem->email])
            ->andReturn(null);

        $this->expectException(InvalidNewUserPropertiesException::class);

        DB::shouldReceive('rollback');

        $this->service->register($newUserItem);
    }

    public function testRegister_withInvalidRegistrationHash_expectException()
    {
        // Set up
        $newUserItem = new NewUserStructure();
        $newUserItem->name = 'Name Surname';
        $newUserItem->email = 'name@user.com';
        $newUserItem->password = 'password';
        $newUserItem->role = Environment::ROLE_ADVERTISER;
        $newUserItem->additionalData = [
            'registrationHash' => 'invalid data',
        ];

        // Assertions
        DB::shouldReceive('beginTransaction');

        $this->userRepository
            ->shouldReceive('getUserByEmail')
            ->withArgs([$newUserItem->email])
            ->andReturn(null);

        $this->environmentRepository
            ->shouldReceive('getByRegistrationHash')
            ->andReturn(null)
            ->once();

        $this->expectException(InvalidNewUserPropertiesException::class);

        DB::shouldReceive('rollback');

        $this->service->register($newUserItem);
    }
}
