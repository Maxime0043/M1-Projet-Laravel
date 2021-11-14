# **Projet Laravel M1 - Plateforme de Formations Sigma**

## À propos du projet

Pour ce projet, je devais réaliser une application WEB pour une entreprise fictive nommée **Sigma**.
Il m'était donc demandé de créer une plateforme de formations en ligne, où seront présents différents types d'utilisateurs.

Les différents utilisateurs sont séparés en 3 catégories :

-   Les "**visiteurs**" qui ont la possibilité de parcourir toutes les formations ainsi que les différents chapitres qui les composent.
-   Les "**formateurs**" qui ont eux la possibilié de se connecter sur le site, de créer des formations et d'administrer toutes celles qu'ils ont créé.
-   L' "**administrateur**" qui a le pouvoir tout administrer sur le site, que ce soit les utilisateurs ou les formations.

Si vous voulez connaître plus en détail le sujet du projet, je vous invite à aller consulter [ce document](https://maxime-pinna.alwaysdata.net/laravel/projet_sigma_M1/Sujet_Laravel_-_M1_WEB_-_2021___2022.pdf).

Par ailleurs, concernant ce projet, j'ai décidé de créer ma base de données à l'aide du modèle conceptuel ci-dessous:

![modele](/mcd.jpg)

## Les erreurs rencontrées durant le développement

Durant le développement de ce projet, je n'ai rencontré qu'une seule erreur.

Ce problème survient lorsqu'en tant que formateur, on essaie de modifier le contenu d'un chapitre, qui a été généré par l'outil Faker de Laravel. Lorsqu'on modifie le contenu d'un chapitre, la modification ne s'effectue pas dans la base de données. Cependant, aucune erreur n'est renvoyé suite cette modification.

Par ailleurs, si un formateur crée un chapitre lui-même et qu'il modifie son contenu par la suite, les changement auront bien été pris en compte.

## Initialisation du projet

Pour pouvoir faire fonctionner ce projet de votre coté, il vous faudra suivre les différentes étapes ci-dessous.

Avant toute chose, il vous faudra vérifier que vous avez bien installer **PHP** (>= 7.4.24) et **composer** sur votre machine.
Si ce n'est pas le cas, installez-les.

-   PHP: [lien de téléchargement](https://windows.php.net/download/)
-   Composer: [lien de téléchargemen](https://getcomposer.org/download/)

Une fois la précédente étape vérifiée, il vous faudra créer une base de données MySQL.

Ensuite, vous devrez dupliquer le fichier nommé "**.env.example**" dans le même répertoire sous le nom "**.env**".
Dans ce fichier, vous devrez y renseigner les informations de connexion à la base de données que vous avez précédemment créé, ainsi que les informations de connexion au serveur de mail SMTP que vous utilisez.

> Exemple des informations à ajouter dans le fichier "**.env**" :

```.env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nom_base_de_donnees
DB_USERNAME=utilisateur
DB_PASSWORD=mot_de_passe

MAIL_MAILER=smtp
MAIL_HOST=smtp.exemple.com
MAIL_PORT=2525
MAIL_USERNAME=utilisateur
MAIL_PASSWORD=mot_de_passe
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=null
MAIL_FROM_NAME="nom"
```

Puis, pour initialiser votre projet, et donc importer tous les modules nécessaires à son bon fonctionnement, vous devrez ouvrir un terminal de commande, vous placer dans le dossier du projet et entrer la commande :

```bash
composer install
```

Pour finir, pour rendre les fichiers du dossier "**storage/app/public**" accessibles depuis le Web, vous devez créer un lien symbolique de "**public/storage**" vers "**storage/app/public**". Pour cela, entrez la commande suivante :

```bash
php artisan storage:link
```
