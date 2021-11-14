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
    /**
     * Permet d'afficher la liste des utilisateurs présents dans la base de données.
     *
     * @return void
     */
    public function index()
    {
        // Si l'utilisateur connecté n'est pas un administrateur, on le redirige vers la page des formations
        if (!auth()->user()->is_admin) {
            return redirect()->route('formations-list');
        }

        $users = User::where('is_admin', false)->get();
        return view('admin.user-list', compact('users'));
    }

    /**
     * Permet d'afficher les informations de l'utilisateur courant.
     *
     * @param int $id
     * @return void
     */
    public function detailsUser($id)
    {
        // Si l'utilisateur connecté n'est pas un administrateur, on le redirige vers la page des formations
        if (!auth()->user()->is_admin) {
            return redirect()->route('formations-list');
        }

        // On récupère l'utilisateur courant
        $user = User::find($id);

        return view('admin.user-details', compact('user'));
    }

    /**
     * Permet de modifier les informations de l'utilisateur courant.
     *
     * @param int $id
     * @param UserUpdateRequest $request
     * @return void
     */
    public function updateUser($id, UserUpdateRequest $request)
    {
        // Si l'utilisateur connecté n'est pas un administrateur, on le redirige vers la page des formations
        if (!auth()->user()->is_admin) {
            return redirect()->route('formations-list');
        }

        // On récupère les informations entrées par l'administrateur
        $params = $request->validated();

        // On récupère l'utilisateur courant et on lui modifie ses informations dans la base de données
        $user = User::find($id);
        $user->update($params);

        return back()->with('informationsHasChanged', true);
    }

    public function updateEmail($id, UserUpdateEmailRequest $request)
    {
        // Si l'utilisateur connecté n'est pas un administrateur, on le redirige vers la page des formations
        if (!auth()->user()->is_admin) {
            return redirect()->route('formations-list');
        }

        // On récupère les informations entrées par l'administrateur
        $params = $request->validated();

        // On récupère l'utilisateur courant et on lui modifie son email dans la base de données
        $user = User::find($id);
        $user->update($params);

        return back()->with('emailHasChanged', true);
    }

    public function updatePassword($id, UserUpdatePasswordRequest $request)
    {
        // Si l'utilisateur connecté n'est pas un administrateur, on le redirige vers la page des formations
        if (!auth()->user()->is_admin) {
            return redirect()->route('formations-list');
        }

        // On récupère les informations entrées par l'administrateur
        $params = $request->validated();

        // On récupère l'utilisateur courant et on lui modifie son mot de passe dans la base de données
        $user = User::find($id);
        $user->update([
            'password'  => Hash::make($params['password'])
        ]);

        return back()->with('passwordHasChanged', true);
    }

    public function deleteUserImage($id)
    {
        // Si l'utilisateur connecté n'est pas un administrateur, on le redirige vers la page des formations
        if (!auth()->user()->is_admin) {
            return redirect()->route('formations-list');
        }

        // On récupère l'utilisateur courant
        $user = User::find($id);

        // Si l'utilisateur possède une image de profil et que ce n'est pas une URL, alors on supprime l'image
        if ($user->picture != null) {
            if (!filter_var($user->picture, FILTER_VALIDATE_URL)) {
                if (Storage::exists("public/$user->picture")) {
                    Storage::delete("public/$user->picture");
                }
            }
        }

        // On définit à NULL le chemin de l'image de profil de l'utilisateur dans la base de données
        $user->picture = null;
        $user->save();

        return back();
    }

    /**
     * Permet de supprimer un utilisateur de la base de données.
     *
     * @param [type] $id
     * @return void
     */
    public function deleteUser($id)
    {
        // Si l'utilisateur connecté n'est pas un administrateur, on le redirige vers la page des formations
        if (!auth()->user()->is_admin) {
            return redirect()->route('formations-list');
        }

        // On récupère l'utilisateur courant
        $user = User::find($id);

        $firstname = $user->firstname;
        $lastname = $user->lastname;

        // Si l'utilisateur possède une image de profil et que ce n'est pas une URL, alors on supprime l'image
        if ($user->picture != null) {
            if (!filter_var($user->picture, FILTER_VALIDATE_URL)) {
                if (Storage::exists("public/$user->picture")) {
                    Storage::delete("public/$user->picture");
                }
            }
        }

        // On parcours l'ensemble des formations de l'utilisateur
        foreach ($user->formations as $formation) {
            // Si la formation possède une image de couverture et que ce n'est pas une URL, alors on supprime l'image
            if ($formation->picture != null) {
                if (!filter_var($formation->picture, FILTER_VALIDATE_URL)) {
                    if (Storage::exists("public/$formation->picture")) {
                        Storage::delete("public/$formation->picture");
                    }
                }
            }

            // On supprime tous les chapitres de la formation
            $formation->chapters()->delete();
            // On supprime les liens entre la formation et les types et catégories
            $formation->categories()->detach();
            $formation->types()->detach();
        }

        // On supprime les formations de l'utilisateur
        $user->formations()->delete();
        // On supprime l'utilisateur
        $user->delete();

        return back()->with('userDeleted', [
            'firstname' => $firstname,
            'lastname'  => $lastname,
        ]);
    }
}
