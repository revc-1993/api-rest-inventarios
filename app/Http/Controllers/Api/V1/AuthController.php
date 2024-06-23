<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\UserTokenResource;
use app\Http\Responses\Api\ErrorResponse;
use app\Http\Responses\Api\SuccessfulResponse;
use Exception;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|confirmed|min:8',
            ]);

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            $token = $user->createToken('auth_token')->plainTextToken;

            return SuccessfulResponse::response("User created successfully", 201, $token);
        } catch (Exception $e) {
            return ErrorResponse::response("Error en el registro de usuario: " . $e->getMessage(), 500);
        }
    }

    public function login(Request $request)
    {
        try {
            if (!Auth::attempt($request->only('email', 'password'))) {
                return ErrorResponse::response("Invalid login details", 401);
            }

            $user = User::authenticateSession($request['email'])->firstOrFail();

            $token = $user->createToken('auth_token')->plainTextToken;

            return SuccessfulResponse::response("User created successfully", 201, $token, $user);
        } catch (Exception $e) {
            return ErrorResponse::response("Error de inicio de sesión: " . $e->getMessage(), 500);
        }
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return SuccessfulResponse::response("Logged out successfully");
    }
}
