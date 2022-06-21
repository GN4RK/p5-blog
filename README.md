# Créer votre premier blog en PHP

## Information sur le projet

Projet numéro 5 de la formation OpenClassrooms : [Développeur d'application - PHP / Symfony](https://openclassrooms.com/fr/paths/59-developpeur-dapplication-php-symfony)

## Description du besoin

Voici la liste des pages qui devront être accessibles depuis votre site web :

- la page d'accueil
- la page listant l’ensemble des blog posts
- la page affichant un blog post
- la page permettant d’ajouter un blog post
- la page permettant de modifier un blog post
- les pages permettant de modifier/supprimer un blog post
- les pages de connexion/enregistrement des utilisateurs.

Vous développerez une partie administration qui devra être accessible uniquement aux utilisateurs inscrits et validés.

Les pages d’administration seront donc accessibles sur conditions et vous veillerez à la sécurité de la partie administration.

Sur la page d’accueil, il faudra présenter les informations suivantes :

- votre nom et votre prénom
- une photo et/ou un logo
- une phrase d’accroche qui vous ressemble (exemple : “Martin Durand, le développeur qu’il vous faut !”)
- un menu permettant de naviguer parmi l’ensemble des pages de votre site web
- un formulaire de contact (à la soumission de ce formulaire, un e-mail avec toutes ces informations vous sera envoyé) avec les champs suivants :
    - nom/prénom,
    - e-mail de contact,
    - message,
- un lien vers votre CV au format PDF
- et l’ensemble des liens vers les réseaux sociaux où l’on peut vous suivre (GitHub, LinkedIn, Twitter…).

Sur la page listant tous les blogs posts (du plus récent au plus ancien), il faut afficher les informations suivantes pour chaque blog post :

- le titre
- la date de dernière modification
- le chapô
- et un lien vers le blog post.

Sur la page présentant le détail d’un blog post, il faut afficher les informations suivantes :

- le titre
- le chapô
- le contenu
- l’auteur
- la date de dernière mise à jour
- le formulaire permettant d’ajouter un commentaire (soumis pour validation)
- les listes des commentaires validés et publiés.

Sur la page permettant de modifier un blog post, l’utilisateur a la possibilité de modifier les champs titre, chapô, auteur et contenu.

Dans le footer menu, il doit figurer un lien pour accéder à l’administration du blog.

## Installation du projet

- mettre en place la base de données (fichier database/blog.sql) en modifiant la ligne de l'admin (ligne 130 du fichier SQL)
- cloner le projet
- configurer le projet

## Configuration

### Base de données

Modifier la ligne 130 du fichier avant l'import de la base :
```SQL
(1, '_NAME_', '_FIRST_NAME_', 'admin', '_EMAIL_', 'validated', '$2y$10$r/G3DjbqcJOgESo7ZIMJz..Kk7.DRVWhqPi3FF64vWJHS.OIviPDq'),
```
Le mot de passe par défaut est "testest".

Changer les informations de connexion à la base dans le fichier src/model/Manager.php ligne 18
```PHP
$db = new \PDO('mysql:host=localhost;dbname=blog;charset=utf8', 'root', '');
```

### .htacces
Changer le RewriteBase en mettant le sous dossier où le projet est installé
```apache
RewriteBase /subfolder/
```

### config.json
Modifier le sous dossier ainsi que l'url de base du site
```json
{
    "baseFolder": "subFolder",
    "baseURL": "http://baseUrl"
}
```

## Badge Codacy

[![Codacy Badge](https://api.codacy.com/project/badge/Grade/a9aab17db62b44b4b05938ec110f652c)](https://app.codacy.com/gh/GN4RK/p5-blog?utm_source=github.com&utm_medium=referral&utm_content=GN4RK/p5-blog&utm_campaign=Badge_Grade_Settings)