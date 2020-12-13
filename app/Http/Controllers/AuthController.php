<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Modules\Auth\Services\EnvironmentService;
use App\Modules\Auth\Services\RegisterService;
use App\Modules\Auth\Structures\NewUserStructure;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    private RegisterService $registerService;
    private EnvironmentService $environmentService;

    public function __construct(RegisterService $registerService, EnvironmentService $environmentService)
    {
        $this->registerService = $registerService;
        $this->environmentService = $environmentService;
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            return true;
        }

        throw new AuthenticationException('Invalid email or password!');
    }

    public function register(Request $request)
    {
        $newUserData = $request->only('name', 'email', 'password', 'role', 'additionalData');

        $newUserObj = NewUserStructure::fromArray($newUserData);
        $user = $this->registerService->register($newUserObj);

        Auth::login($user);
    }

    public function logout()
    {
        Auth::logout();
    }

    public function getUser(Request $request)
    {
        $user = $request->user();

        return $user ? $user->toRpc() : null;
    }

    public function getEnvironmentUsers(Request $request)
    {
        $user = $request->user();
        if (!$user) {
            return null;
        }

        return array_map(fn(User $user) => $user->toRpc(), $user->environment->users->all());
    }

    public function resetRegistrationHash(Request $request)
    {
        $user = $request->user();

        return $this->environmentService->resetRegistrationHash($user->environment);
    }

    public function deleteRegistrationHash(Request $request)
    {
        $user = $request->user();

        return $this->environmentService->deleteRegistrationHash($user->environment);
    }

    public function getRegistrationHash(Request $request)
    {
        $user = $request->user();

        return $user->environment->registration_hash;
    }
}
