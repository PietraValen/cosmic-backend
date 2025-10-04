<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Auth\AuthenticationException;
use Throwable;

class Handler extends ExceptionHandler
{
    protected function unauthenticated($request, AuthenticationException $exception)
{
    // Se for API, retorna JSON 401
    // if ($request->expectsJson() || $request->is('api/*')) {
        // return response()->json(['error' => 'Token não fornecido ou inválido'], 401);
    // }
    return response()->json(['error' => 'Token não fornecido ou inválido'], 401);
    // Para rotas web, mantém redirecionamento
    // return redirect()->guest(route('login'));
}
}
