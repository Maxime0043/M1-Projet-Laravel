<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserUpdatePasswordRequest;
use App\Http\Requests\UserUpdatePictureRequest;
use App\Mail\RegisteredUserMail;
use App\Models\SignUpRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class DashboardController extends Controller
{
    public function index()
    {
        return view('dashboard');
    }

    public function updatePicture(UserUpdatePictureRequest $request)
    {
        $params = $request->validated();

        $user = User::find(Auth::user()->id);

        if ($user->picture != null)
            if (Storage::exists("public/$user->picture"))
                Storage::delete("public/$user->picture");

        $file = Storage::put('public', $params['picture']);
        $params['picture'] = substr($file, 7);

        $user->update($params);

        return back()->with('pictureHasChanged', true);;
    }

    public function updatePassword(UserUpdatePasswordRequest $request)
    {
        $params = $request->validated();

        $user = User::find(Auth::user()->id);
        $user->update([
            'password'  => Hash::make($params['password'])
        ]);

        return back()->with('passwordHasChanged', true);
    }
}
