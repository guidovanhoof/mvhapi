<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiAuthController extends Controller
{
    public function createToken(Request $request): JsonResponse
    {
        $credentials = $request->validate(
            [
                'email' => 'required|email',
                'password' => 'required',
            ]
        );
        if (Auth::attempt($credentials)) {
            return response()->json(
                [
                    "token" => [
                        "type" => "Bearer",
                        "token" => $this->getUserToken($credentials),
                    ]
                ],
                200
            );
        }
        return response()->json(
            ["message" => "Combinatie email/wachtwoord ongeldig!"],
            401
        );
    }

    private function getUserToken(array $credentials)
    {
        $user = User::where("email", $credentials["email"])->first();
        return $user->createToken("auth-sanctum")->plainTextToken;
    }
}
