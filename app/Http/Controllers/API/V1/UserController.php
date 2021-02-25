<?php

namespace App\Http\Controllers\API\V1;

use DB;
use Auth;

use App\Models\User;

use App\Http\Controllers\Controller;

use App\Http\Requests\User\LoginRequest;

use App\Http\Resources\UserResource;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function login(LoginRequest $request)
    {
        if (!$user = User::where(DB::raw('LOWER(email)'), mb_strtolower($request->input('email')))->first()) {
            return $this->getResponse('Пользователь не найден', 404);
        }

        if (!$user->checkPassword($request->input('password'))) {
            return $this->getResponse('Пароль неверный', 403);
        }

        return response()->json($user->createAuthToken());
    }

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();

        return response()->json(['status' => 'success']);
    }

    public function show()
    {
        return response(UserResource::make(Auth::user()));
    }
}
