<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Mail\RegisterUserMail;
use App\Models\SignUpRequest;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(RegisterRequest $request)
    {
        $request->validated();

        SignUpRequest::create([
            'email'     => $request->email,
            'lastname'  => $request->lastname,
            'firstname' => $request->firstname
        ]);

        $params = array();
        $params['email'] = $request->email;
        $params['lastname'] = $request->lastname;
        $params['firstname'] = $request->firstname;

        Mail::to('administrator@sigma-test.com')->send(new RegisterUserMail($params));

        return view('auth.register')->with('requestSent', true);
    }
}
