<?php

namespace App\Http\Controllers;

use App\Mail\RegisteredUserMail;
use App\Models\SignUpRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class SignupRequestController extends Controller
{
    public function index()
    {
        if (!auth()->user()->is_admin) {
            return redirect()->route('formations-list');
        }

        $signup_requests = SignUpRequest::orderBy('created_at', 'ASC')->get();
        return view('admin.request-list', compact('signup_requests'));
    }

    public function store($id)
    {
        if (!auth()->user()->is_admin)
            return redirect()->route('dashboard');

        $signup_request = SignUpRequest::find($id);
        $password = $this->generatePassword();

        $user = User::create([
            'firstname' => $signup_request->firstname,
            'lastname'  => $signup_request->lastname,
            'email'     => $signup_request->email,
            'password'  => Hash::make($password),
            'is_admin'  => false,
            'picture'   => null,
        ]);

        Mail::to($signup_request->email)->send(new RegisteredUserMail([
            'email'     => $signup_request->email,
            'lastname'  => $signup_request->lastname,
            'firstname' => $signup_request->firstname,
            'password'  => $password,
        ]));

        $signup_request->delete();

        return back()->with('userAdded', [
            'firstname' => $user->firstname,
            'lastname'  => $user->lastname,
        ]);
    }

    public function delete($id)
    {
        if (!auth()->user()->is_admin)
            return redirect()->route('dashboard');

        $signup_request = SignUpRequest::find($id);

        $firstname = $signup_request->firstname;
        $lastname = $signup_request->lastname;

        $signup_request->delete();

        return back()->with('userDeleted', [
            'firstname' => $firstname,
            'lastname'  => $lastname,
        ]);
    }

    private function generatePassword()
    {
        $chars = '1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZabcefghijklmnopqrstuvwxyz&*#?!';
        return substr(str_shuffle($chars), 0, 8);
    }
}
