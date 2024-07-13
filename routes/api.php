<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ReportController;

Route::post('/login', function (Request $request) {
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    $user = User::where('email', $request->email)->first();

    if (!$user || !Hash::check($request->password, $user->password)) {
        return response()->json(['message' => 'Invalid credentials'], 401);
    }

    // Revoke all tokens
    $user->tokens()->delete();

    // Create new token
    $token = $user->createToken('auth-token')->plainTextToken;

    return response()->json(['token' => $token], 200);
});



Route::middleware(['auth:sanctum', 'single.device'])->group(function () {
    Route::apiResource('posts', PostController::class);
    Route::post('reports', [ReportController::class, 'store']);
});

Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
    Route::get('reports', [ReportController::class, 'index']);
});
