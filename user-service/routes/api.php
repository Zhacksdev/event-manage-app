<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

// CRUD User (Provider)
Route::apiResource('users', UserController::class);

// Consumer: ambil riwayat registrasi user
Route::get('users/{id}/registrations', [UserController::class, 'getRegistrations']);
