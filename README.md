# Créer votre premier blog en PHP

## Information sur le projet

Projet numéro 5 de la formation OpenClassrooms : [Développeur d'application - PHP / Symfony](https://openclassrooms.com/fr/paths/59-developpeur-dapplication-php-symfony)

## Description du besoin

Voici la liste des pages qui devront être accessibles depuis votre site web :

- la page d'accueil ;
- la page listant l’ensemble des blog posts ;
- la page affichant un blog post ;
- la page permettant d’ajouter un blog post ;
- la page permettant de modifier un blog post ;
- les pages permettant de modifier/supprimer un blog post ;
- les pages de connexion/enregistrement des utilisateurs.

Vous développerez une partie administration qui devra être accessible uniquement aux utilisateurs inscrits et validés.

Les pages d’administration seront donc accessibles sur conditions et vous veillerez à la sécurité de la partie administration.

Sur la page d’accueil, il faudra présenter les informations suivantes :

- votre nom et votre prénom ;
- une photo et/ou un logo ;
- une phrase d’accroche qui vous ressemble (exemple : “Martin Durand, le développeur qu’il vous faut !”) ;
- un menu permettant de naviguer parmi l’ensemble des pages de votre site web ;
- un formulaire de contact (à la soumission de ce formulaire, un e-mail avec toutes ces informations vous sera envoyé) avec les champs suivants :
- - nom/prénom,
- - e-mail de contact,
- - message,
- un lien vers votre CV au format PDF ;
- et l’ensemble des liens vers les réseaux sociaux où l’on peut vous suivre (GitHub, LinkedIn, Twitter…).

Sur la page listant tous les blogs posts (du plus récent au plus ancien), il faut afficher les informations suivantes pour chaque blog post :

- le titre ;
- la date de dernière modification ;
- le chapô ;
- et un lien vers le blog post.

Sur la page présentant le détail d’un blog post, il faut afficher les informations suivantes :

- le titre ;
- le chapô ;
- le contenu ;
- l’auteur ;
- la date de dernière mise à jour ;
- le formulaire permettant d’ajouter un commentaire (soumis pour validation) ;
- les listes des commentaires validés et publiés.

Sur la page permettant de modifier un blog post, l’utilisateur a la possibilité de modifier les champs titre, chapô, auteur et contenu.

Dans le footer menu, il doit figurer un lien pour accéder à l’administration du blog.


[![Codacy Badge](https://api.codacy.com/project/badge/Grade/a9aab17db62b44b4b05938ec110f652c)](https://app.codacy.com/gh/GN4RK/p5-blog?utm_source=github.com&utm_medium=referral&utm_content=GN4RK/p5-blog&utm_campaign=Badge_Grade_Settings)

## Config

### .htacces
Set your rewrite base
```apache
RewriteBase /subfolder/
```

### config.json
```json
{
    "baseFolder": "subFolder",
    "baseURL": "http://baseUrl"
}
```