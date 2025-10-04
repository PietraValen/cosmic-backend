<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        try {
            if (!$token = auth()->attempt($credentials)) {
                return response()->json(['error' => 'Credenciais inválidas'], 401);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'Erro ao gerar token'], 500);
        }

        return $this->respondWithToken($token);
    }

    public function logout()
    {
        try {
            auth()->logout();
            return response()->json(['message' => 'Logout realizado com sucesso']);
        } catch (JWTException $e) {
            return response()->json(['error' => 'Erro ao fazer logout'], 500);
        }
    }

    public function me()
    {
        try {
            return response()->json(auth()->user());
        } catch (JWTException $e) {
            return response()->json(['error' => 'Token inválido ou expirado'], 401);
        }
    }

    public function refresh()
    {
        try {
            $token = auth()->refresh();
            return $this->respondWithToken($token);
        } catch (JWTException $e) {
            return response()->json(['error' => 'Não foi possível atualizar o token'], 401);
        }
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60
        ]);
    }
}
