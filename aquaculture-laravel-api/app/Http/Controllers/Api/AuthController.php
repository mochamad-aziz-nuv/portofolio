<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    use ApiResponseTrait;

    public function login(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email'    => 'required|email',
            'password' => 'required|string',
            'device_name' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validasi gagal', $validator->errors()->toArray(), 422);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return $this->sendError('Kredensial login tidak cocok', ['email' => ['Email atau password salah']], 401);
        }

        $deviceName = $request->device_name ?? 'Flutter Mobile App';
        $token = $user->createToken($deviceName)->plainTextToken;

        return $this->sendResponse([
            'user' => new UserResource($user),
            'token' => $token,
            'token_type' => 'Bearer',
        ], 'Login berhasil');
    }

    public function profile(Request $request): JsonResponse
    {
        return $this->sendResponse(
            new UserResource($request->user()),
            'Data profil user berhasil diambil'
        );
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return $this->sendResponse(null, 'Berhasil logout dan token dicabut');
    }
}
