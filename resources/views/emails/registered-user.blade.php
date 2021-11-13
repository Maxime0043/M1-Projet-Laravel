<!DOCTYPE html>
<html lang="fr">
    <head>

        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">

        <title>Demande d'Inscription</title>
    </head>

    <body>

        <p>Bonjour {{ Str::upper($lastname) }} {{ Str::title($firstname) }},</p>
        <br>
        <p>Votre compte a été créé avec succès.</p>
        <p>Voici vos identifiants pour vous connecter :</p>

        <ul>
            <li>Email: {{ $email }}</li>
            <li>Mot de passe: {{ $password }}</li>
        </ul>

        <br>
        <p>Bonne journée,</p>
        <p>L'équipe Sigma.</p>
    </body>
</html>
