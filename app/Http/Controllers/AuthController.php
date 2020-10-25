<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Modules\Auth\Services\RegisterService;
use App\Modules\Auth\Structures\NewUserStructure;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    private RegisterService $registerService;

    public function __construct(RegisterService $registerService)
    {
        $this->registerService = $registerService;
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

    public function getUser()
    {
        /** @var User $user */
        $user = Auth::user();

        return $user ? $user->toRpc() : null;
    }
}
