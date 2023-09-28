<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Interface\UserRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    private $user_repository;

    public function __construct(UserRepositoryInterface $userRepositoryInterface)
    {
        $this->user_repository = $userRepositoryInterface;
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response(['errors' => $validator->errors()->all()], 422);
        }

        $user = $this->user_repository->findUserByEmail($request->email);

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'email' => ['The provided credentials are incorrect.'],
            ], 400);
        }

        $personalAcceessToken = $user->createToken('web', ['user-read', 'user-update', 'user-delete'], Carbon::now()->addDays(6));

        return response()->json([
            'user' => $user,
            'token' => $personalAcceessToken->plainTextToken,
            'expires_at' => strtotime($personalAcceessToken->accessToken->expires_at)
        ]);
    }

    public function logout()
    {
        request()->user()->tokens()->delete();
        return response()->json([
            'message' => 'user has been logout'
        ], 200);
    }
}
