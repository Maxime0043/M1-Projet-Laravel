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
    /**
     * Permet d'afficher l'ensemble des personnes ayant fait une demande d'inscription.
     *
     * @return void
     */
    public function index()
    {
        // Si l'utilisateur connecté n'est pas un administrateur, on le redirige vers la page des formations
        if (!auth()->user()->is_admin) {
            return redirect()->route('formations-list');
        }

        $signup_requests = SignUpRequest::orderBy('created_at', 'ASC')->get();
        return view('admin.request-list', compact('signup_requests'));
    }

    /**
     * Permet d'ajouter une personne ayant fait une demande d'inscription en tant qu'untilisateur dans la base de données.
     *
     * @param int $id
     * @return void
     */
    public function store($id)
    {
        // Si l'utilisateur connecté n'est pas un administrateur, on le redirige vers la page de son compte
        if (!auth()->user()->is_admin)
            return redirect()->route('dashboard');

        // On récupère la personne ayant fait la demande
        $signup_request = SignUpRequest::find($id);
        $password = $this->generatePassword();

        // On crée un utilisateur avec ses informations
        $user = User::create([
            'firstname' => $signup_request->firstname,
            'lastname'  => $signup_request->lastname,
            'email'     => $signup_request->email,
            'password'  => Hash::make($password),
            'is_admin'  => false,
            'picture'   => null,
        ]);

        // On lui envoie un mail avec ses informations de connexion
        Mail::to($signup_request->email)->send(new RegisteredUserMail([
            'email'     => $signup_request->email,
            'lastname'  => $signup_request->lastname,
            'firstname' => $signup_request->firstname,
            'password'  => $password,
        ]));

        // On supprime la demande d'inscription de la base de données
        $signup_request->delete();

        return back()->with('userAdded', [
            'firstname' => $user->firstname,
            'lastname'  => $user->lastname,
        ]);
    }

    /**
     * Permet de supprimer une demande d'inscription de la base de données
     *
     * @param int $id
     * @return void
     */
    public function delete($id)
    {
        // Si l'utilisateur connecté n'est pas un administrateur, on le redirige vers la page de son compte
        if (!auth()->user()->is_admin)
            return redirect()->route('dashboard');

        // On récupère la personne ayant fait la demande
        $signup_request = SignUpRequest::find($id);

        $firstname = $signup_request->firstname;
        $lastname = $signup_request->lastname;

        // On supprime la demande de la base de données
        $signup_request->delete();

        return back()->with('userDeleted', [
            'firstname' => $firstname,
            'lastname'  => $lastname,
        ]);
    }

    /**
     * Permet de générer aléatoirement un mot de passe avec une suite de caractères.
     *
     * @return void
     */
    private function generatePassword()
    {
        $chars = '1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZabcefghijklmnopqrstuvwxyz&*#?!';
        return substr(str_shuffle($chars), 0, 8);
    }
}
