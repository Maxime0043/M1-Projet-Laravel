<!DOCTYPE html>
<html lang="fr">
    <head>

        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">

        <title>Demande d'Inscription</title>
    </head>

    <body>

        <p>Bonjour,</p>
        <br>
        <p>Vous avez reçu une nouvelle demande d'inscription.</p>
        <br>
        <p>Voici les informations concernant l'utilisateur :</p>

        <ul>
            <li>Email: {{ $email }}</li>
            <li>Nom: {{ $lastname }}</li>
            <li>Prénom: {{ $firstname }}</li>
        </ul>

        <br>
        <p>Si vous voulez accepter sa demande, veuillez cliquer sur le lien suivant: <a href="localhost:8000/dashboard">Accepter la demande.</a></p>
    </body>
</html>
