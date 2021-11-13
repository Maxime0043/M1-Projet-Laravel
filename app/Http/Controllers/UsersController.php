<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserUpdateEmailRequest;
use App\Http\Requests\UserUpdatePasswordRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UsersController extends Controller
{
    public function index()
    {
        if (!auth()->user()->is_admin) {
            return redirect()->route('formations-list');
        }

        $users = User::where('is_admin', false)->get();
        return view('admin.user-list', compact('users'));
    }

    public function detailsUser($id)
    {
        if (!auth()->user()->is_admin) {
            return redirect()->route('formations-list');
        }

        $user = User::find($id);

        return view('admin.user-details', compact('user'));
    }

    public function updateUser($id, UserUpdateRequest $request)
    {
        if (!auth()->user()->is_admin) {
            return redirect()->route('formations-list');
        }

        $params = $request->validated();

        $user = User::find($id);
        $user->update($params);

        return back()->with('informationsHasChanged', true);
    }

    public function updateEmail($id, UserUpdateEmailRequest $request)
    {
        if (!auth()->user()->is_admin) {
            return redirect()->route('formations-list');
        }

        $params = $request->validated();

        $user = User::find($id);
        $user->update($params);

        return back()->with('emailHasChanged', true);
    }

    public function updatePassword($id, UserUpdatePasswordRequest $request)
    {
        if (!auth()->user()->is_admin) {
            return redirect()->route('formations-list');
        }

        $params = $request->validated();

        $user = User::find($id);
        $user->update([
            'password'  => Hash::make($params['password'])
        ]);

        return back()->with('passwordHasChanged', true);
    }

    public function deleteUserImage($id)
    {
        if (!auth()->user()->is_admin) {
            return redirect()->route('formations-list');
        }

        $user = User::find($id);

        if ($user->picture != null) {
            if (!filter_var($user->picture, FILTER_VALIDATE_URL)) {
                if (Storage::exists("public/$user->picture")) {
                    Storage::delete("public/$user->picture");
                }
            }
        }

        $user->picture = null;
        $user->save();

        return back();
    }

    public function deleteUser($id)
    {
        if (!auth()->user()->is_admin) {
            return redirect()->route('formations-list');
        }

        $user = User::find($id);

        $firstname = $user->firstname;
        $lastname = $user->lastname;

        if ($user->picture != null) {
            if (!filter_var($user->picture, FILTER_VALIDATE_URL)) {
                if (Storage::exists("public/$user->picture")) {
                    Storage::delete("public/$user->picture");
                }
            }
        }

        foreach ($user->formations as $formation) {
            if ($formation->picture != null) {
                if (!filter_var($formation->picture, FILTER_VALIDATE_URL)) {
                    if (Storage::exists("public/$formation->picture")) {
                        Storage::delete("public/$formation->picture");
                    }
                }
            }

            $formation->chapters()->delete();
            $formation->categories()->detach();
            $formation->types()->detach();
        }

        $user->formations()->delete();
        $user->delete();

        return back()->with('userDeleted', [
            'firstname' => $firstname,
            'lastname'  => $lastname,
        ]);
    }
}
