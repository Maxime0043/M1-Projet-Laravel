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
    /**
     * Permet d'afficher la page où sont présentes les informations de l'utilisateur connecté.
     *
     * @return void
     */
    public function index()
    {
        return view('dashboard');
    }

    /**
     * Permet de modifier l'image de l'utilisateur connecté.
     *
     * @param UserUpdatePictureRequest $request
     * @return void
     */
    public function updatePicture(UserUpdatePictureRequest $request)
    {
        // On récupère les informations entrées par l'utilisateur
        $params = $request->validated();

        // On récupère l'utilisateur connecté
        $user = User::find(Auth::user()->id);

        // Si l'image de profil existe et que ce n'est une URL, alors on supprime l'image précédente
        if ($user->picture != null)
            if (Storage::exists("public/$user->picture"))
                Storage::delete("public/$user->picture");

        // On sauvegarde la nouvelle image
        $file = Storage::put('public', $params['picture']);
        $params['picture'] = substr($file, 7);

        // On modifie le chemin de l'image de profil dans la base de données
        $user->update($params);

        return back()->with('pictureHasChanged', true);;
    }

    /**
     * Permet de modifier le mot de passe de l'utilisateur connecté.
     *
     * @param UserUpdatePasswordRequest $request
     * @return void
     */
    public function updatePassword(UserUpdatePasswordRequest $request)
    {
        // On récupère les informations entrées par l'utilisateur
        $params = $request->validated();

        // On récupère l'utilisateur connecté et on lui change son mot de passe dans le base de données en le cryptant
        $user = User::find(Auth::user()->id);
        $user->update([
            'password'  => Hash::make($params['password'])
        ]);

        return back()->with('passwordHasChanged', true);
    }
}
