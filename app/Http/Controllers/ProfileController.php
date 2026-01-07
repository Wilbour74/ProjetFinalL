<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;
use App\Models\User;

class ProfileController extends Controller
{
    /**
     * Display the user's public profile.
     */
    public function show(string $username): View
    {
        $user = User::where('username', $username)
            ->withCount(['posts', 'comments'])
            ->firstOrFail();

        $posts = $user->posts()
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(12, ['*'], 'posts_page');

        $comments = $user->comments()
            ->with(['user', 'posts', 'parent'])
            ->orderBy('created_at', 'desc')
            ->paginate(10, ['*'], 'comments_page');

        return view('profile.show', compact('user', 'posts', 'comments'));
    }

    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();

        // Mettre à jour les champs classiques
        $user->fill($request->validated());

        // Vérifier si l'email a changé
        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        // Gérer l'avatar
        if ($request->hasFile('avatar')) {
            // Supprimer l'ancien avatar si existant
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }

            // Stocker le nouveau fichier et enregistrer le chemin
            $path = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $path;
        }

        $user->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
