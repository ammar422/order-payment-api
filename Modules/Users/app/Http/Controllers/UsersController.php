<?php

namespace Modules\Users\Http\Controllers;

use Modules\Users\Models\User;
use App\Http\Controllers\Controller;
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
        return $this->respondWithToken($token, $user, __('users::auth.register'));
    }

    public function login()
    {
        $credentials = request(['email', 'password']);

        if (! $token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }


    public function me()
    {
        return response()->json(auth()->user());
    }


    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }


    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }


    protected function respondWithToken($token, $user)
    {
        return response()->json([
            'access_token' => $token,
            'user' => $user,
        ]);
    }
}
