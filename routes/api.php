<?php

use App\Http\Controllers\Api\DetectorController;
use App\Http\Controllers\Api\EventTypeController;
use App\Http\Controllers\Api\GlitchController;
use App\Http\Controllers\Api\GlitchTypeController;
use App\Http\Controllers\Api\GravitationalWaveEventController;
use App\Http\Controllers\Api\ObservatoryController;
use App\Http\Controllers\Api\ProjectStatisticsController;
use App\Http\Controllers\Api\ScientificDiscoveryController;
use App\Http\Controllers\Api\UserClassificationController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\UserStatsController;
use App\Http\Controllers\AuthController;
use App\Http\Middleware\JwtMiddleware;
use Illuminate\Support\Facades\Route;

//Autenticação
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::middleware([JwtMiddleware::class])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
});

// Detectores
Route::middleware([JwtMiddleware::class])->group(function () {
    Route::get('/detectors', [DetectorController::class, 'index']);
    Route::post('/detectors', [DetectorController::class, 'store']);
    Route::get('/detectors/{id}', [DetectorController::class, 'show']);
    Route::put('/detectors/{id}', [DetectorController::class, 'update']);
    Route::delete('/detectors/{id}', [DetectorController::class, 'destroy']);
});

// Glitches
Route::middleware([JwtMiddleware::class])->group(function () {
    Route::get('/glitches', [GlitchController::class, 'index']);
    Route::post('/glitches', [GlitchController::class, 'store']);
    Route::get('/glitches/{id}', [GlitchController::class, 'show']);
    Route::put('/glitches/{id}', [GlitchController::class, 'update']);
    Route::delete('/glitches/{id}', [GlitchController::class, 'destroy']);
});

// Tipos de Glitches
Route::middleware([JwtMiddleware::class])->group(function () {
    Route::get('/glitch-types', [GlitchTypeController::class, 'index']);
    Route::post('/glitch-types', [GlitchTypeController::class, 'store']);
    Route::get('/glitch-types/{id}', [GlitchTypeController::class, 'show']);
    Route::put('/glitch-types/{id}', [GlitchTypeController::class, 'update']);
    Route::delete('/glitch-types/{id}', [GlitchTypeController::class, 'destroy']);
});

// Eventos de ondas gravitacionais
Route::middleware([JwtMiddleware::class])->group(function () {
    Route::get('/events', [GravitationalWaveEventController::class, 'index']);
    Route::post('/events', [GravitationalWaveEventController::class, 'store']);
    Route::get('/events/{id}', [GravitationalWaveEventController::class, 'show']);
    Route::put('/events/{id}', [GravitationalWaveEventController::class, 'update']);
    Route::delete('/events/{id}', [GravitationalWaveEventController::class, 'destroy']);
});

// Tipos de evento de ondas gravitacionais
Route::middleware([JwtMiddleware::class])->group(function () {
    Route::get('/event-types', [EventTypeController::class, 'index']);
    Route::post('/event-types', [EventTypeController::class, 'store']);
    Route::get('/event-types/{id}', [EventTypeController::class, 'show']);
    Route::put('/event-types/{id}', [EventTypeController::class, 'update']);
    Route::delete('/event-types/{id}', [EventTypeController::class, 'destroy']);
});

// Observatórios
Route::middleware([JwtMiddleware::class])->group(function () {
    Route::get('/observatories', [ObservatoryController::class, 'index']);
    Route::post('/observatories', [ObservatoryController::class, 'store']);
    Route::get('/observatories/{id}', [ObservatoryController::class, 'show']);
    Route::put('/observatories/{id}', [ObservatoryController::class, 'update']);
    Route::delete('/observatories/{id}', [ObservatoryController::class, 'destroy']);
});

// Estatísticas de projetos
Route::middleware([JwtMiddleware::class])->group(function () {
    Route::get('/project-statistics', [ProjectStatisticsController::class, 'index']);
    Route::post('/project-statistics', [ProjectStatisticsController::class, 'store']);
    Route::get('/project-statistics/{id}', [ProjectStatisticsController::class, 'show']);
    Route::put('/project-statistics/{id}', [ProjectStatisticsController::class, 'update']);
    Route::delete('/project-statistics/{id}', [ProjectStatisticsController::class, 'destroy']);
});

// Descobertas científicas
Route::middleware([JwtMiddleware::class])->group(function () {
    Route::get('/scientific-discoveries', [ScientificDiscoveryController::class, 'index']);
    Route::post('/scientific-discoveries', [ScientificDiscoveryController::class, 'store']);
    Route::get('/scientific-discoveries/{id}', [ScientificDiscoveryController::class, 'show']);
    Route::put('/scientific-discoveries/{id}', [ScientificDiscoveryController::class, 'update']);
    Route::delete('/scientific-discoveries/{id}', [ScientificDiscoveryController::class, 'destroy']);
});

// Classificação de usuários
Route::middleware([JwtMiddleware::class])->group(function () {
    Route::get('/user-classifications', [UserClassificationController::class, 'index']);
    Route::post('/user-classifications', [UserClassificationController::class, 'store']);
    Route::get('/user-classifications/{id}', [UserClassificationController::class, 'show']);
    Route::put('/user-classifications/{id}', [UserClassificationController::class, 'update']);
    Route::delete('/user-classifications/{id}', [UserClassificationController::class, 'destroy']);
});

// Usuários
Route::middleware([JwtMiddleware::class])->group(function () {
    Route::get('/users', [UserController::class, 'index']);
    Route::post('/users', [UserController::class, 'store']);
    Route::get('/users/{id}', [UserController::class, 'show']);
    Route::put('/users/{id}', [UserController::class, 'update']);
    Route::delete('/users/{id}', [UserController::class, 'destroy']);
});

// Estatísticas de usuários
Route::middleware([JwtMiddleware::class])->group(function () {
    Route::get('/user-stats', [UserStatsController::class, 'index']);
    Route::post('/user-stats', [UserStatsController::class, 'store']);
    Route::get('/user-stats/{id}', [UserStatsController::class, 'show']);
    Route::put('/user-stats/{id}', [UserStatsController::class, 'update']);
    Route::delete('/user-stats/{id}', [UserStatsController::class, 'destroy']);
});
