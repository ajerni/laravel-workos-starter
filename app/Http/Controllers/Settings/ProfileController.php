<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;
use Laravel\WorkOS\Http\Requests\AuthKitAccountDeletionRequest;
use WorkOS\UserManagement;

class ProfileController extends Controller
{
    /**
     * Show the user's profile settings page.
     */
    public function edit(Request $request): Response
    {
        return Inertia::render('settings/Profile', [
            'status' => $request->session()->get('status'),
        ]);
    }

    /**
     * Update the user's profile settings.
     */
    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        $request->user()->update(['name' => $request->name]);

        // Sync name changes to WorkOS
        if ($request->user()->wasChanged('name')) {
            $this->syncUserToWorkOS($request->user());
        }

        return to_route('profile.edit');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(AuthKitAccountDeletionRequest $request): RedirectResponse
    {
        return $request->delete(
            using: fn (User $user) => $user->delete()
        );
    }

    /**
     * Sync user profile changes to WorkOS
     */
    private function syncUserToWorkOS($user): void
    {
        try {
            // Configure WorkOS SDK
            \WorkOS\WorkOS::setApiKey(config('services.workos.secret'));
            \WorkOS\WorkOS::setClientId(config('services.workos.client_id'));

            // Split the name into first and last name
            $nameParts = explode(' ', trim($user->name), 2);
            $firstName = $nameParts[0] ?? '';
            $lastName = $nameParts[1] ?? '';

            // Get the WorkOS user ID from the user's profile using the stored workos_id
            if ($user->workos_id) {
                // Update the user in WorkOS using the stored workos_id
                $userManagement = new UserManagement;
                $userManagement->updateUser(
                    $user->workos_id,
                    $firstName,
                    $lastName
                );

                Log::info('Successfully synced user to WorkOS', [
                    'user_id' => $user->id,
                    'workos_user_id' => $user->workos_id,
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                ]);
            } else {
                Log::warning('User does not have a WorkOS ID, skipping sync', [
                    'user_id' => $user->id,
                    'email' => $user->email,
                ]);
            }
        } catch (\Exception $e) {
            // Log the error but don't fail the local update
            Log::error('Failed to sync user to WorkOS: '.$e->getMessage(), [
                'user_id' => $user->id,
                'email' => $user->email,
                'exception' => $e->getTraceAsString(),
            ]);
        }
    }
}
