<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        $user = $request->user()->loadMissing('roles');

        return view('profile.edit', [
            'user' => $user,
        ]);
    }

    /**
     * Update the user's profile information (only name is editable; email and role are read-only).
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $user->fill([
            'name' => $request->validated('name'),
        ]);
        $user->save();

        return Redirect::route('profile.edit')
            ->with('status', 'profile-updated')
            ->with('success', 'Nama profil berhasil diperbarui.');
    }

    /**
     * Prevent self-deletion from the shared profile page.
     */
    public function destroy(Request $request): RedirectResponse
    {
        abort(403, 'Penghapusan akun tidak diizinkan melalui halaman profil.');
    }
}
