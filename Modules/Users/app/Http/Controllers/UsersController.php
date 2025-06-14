<?php

namespace Modules\Users\Http\Controllers;

use Modules\Users\Models\User;
use App\Http\Controllers\Controller;
use Modules\Users\Transformers\UserResource;
use Modules\Users\Http\Requests\LoginRequest;
use Modules\Users\Http\Requests\RegisterRequest;

class UsersController extends Controller
{


    public function register(RegisterRequest $request)
    {
        $data = $request->validated();
        $data['password'] = is_string($request->password) ? bcrypt($request->password) : null;
        $data['account_type'] = 'user';
        $user = User::create($data);
        $token = auth('api')->login($user);
        $user->save();
        return $this->respondWithToken($token, $user, 'user registered successfully');
    }

    public function login(LoginRequest $request)
    {
        $token =  auth('api')->attempt($request->all(), true);
        if (!$token) {
            return $this->respondInvaliedCredentials();
        }
        $user = auth('api')->user();

        return $this->respondWithToken($token, $user);
    }


    public function me()
    {
        return $this->respondWithUserData(auth('api')->user());
    }

    public function logout()
    {
        auth('api')->logout();
        return lynx()
            ->message('user loged out successfully')
            ->response();
    }

    public function refresh()
    {
        $user = auth('api')->user();
        return $this->respondWithToken(auth('api')->refresh(), $user);
    }


    protected function respondWithToken($token, $user, $message = null)
    {
        $message = $message ?? 'user login success';

        return lynx()
            ->data([
                'token' => $token,
                'user' => new UserResource($user),
            ])
            ->message($message)
            ->response();
    }

    protected function respondWithUserData($user, $message = null)
    {
        $message = $message ?? 'user data get successfully';
        return lynx()
            ->data([
                'user' => new UserResource($user),
            ])
            ->message($message)
            ->response();
    }

    protected function respondInvaliedCredentials()
    {
        return lynx()
            ->status(404)
            ->message('user login failed')
            ->response();
    }
}
