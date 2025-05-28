<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Log;
use Laravel\WorkOS\Http\Requests\AuthKitAuthenticationRequest;
use Laravel\WorkOS\Http\Requests\AuthKitLoginRequest;
use Laravel\WorkOS\Http\Requests\AuthKitLogoutRequest;

Route::get('login', function (AuthKitLoginRequest $request) {
    return $request->redirect();
})->middleware(['guest'])->name('login');

Route::get('authenticate', function (AuthKitAuthenticationRequest $request) {
    // Check if this is a direct access (no proper OAuth flow)
    $stateFromUrl = json_decode($request->query('state'), true)['state'] ?? null;
    $stateFromSession = session('state');
    $code = $request->query('code');
    
    Log::info('OAuth Callback Attempt', [
        'state_from_url' => $stateFromUrl,
        'state_from_session' => $stateFromSession,
        'has_code' => !empty($code),
        'session_id' => session()->getId()
    ]);
    
    // If no state in session, this is likely a direct access
    if (is_null($stateFromSession)) {
        Log::warning('Direct access to OAuth callback detected');
        
        return response()->view('auth.callback-error', [
            'message' => 'Invalid OAuth flow. Please start the authentication process by visiting the login page.',
            'login_url' => route('login')
        ], 400);
    }
    
    try {
        return tap(to_route('home'), fn () => $request->authenticate());
    } catch (\Exception $e) {
        Log::error('OAuth authentication failed', [
            'error' => $e->getMessage(),
            'code' => $code,
            'state' => $stateFromUrl
        ]);
        
        return response()->view('auth.callback-error', [
            'message' => 'Authentication failed: ' . $e->getMessage(),
            'login_url' => route('login')
        ], 500);
    }
})->name('auth.callback');

Route::post('logout', function (AuthKitLogoutRequest $request) {
    return $request->logout();
})->middleware(['auth'])->name('logout');
