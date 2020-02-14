# locautovintage
rental old collection vehicles
PROJET LOC'AUTO VINTAGE (autos de 1950 à 1990)
Site mettant en relation des propriétaires d'autos anciennes et des amateurs de balades vintage.
- [#locautovintage](#locautovintage)
    - [#reconstruction-de-votre-projet](#reconstruction-de-votre-projet)
    - [#améliorations-possibles](#améliorations-possibles)
        - [#version-110--front-end-repair](#version-110--front-end-repair)
        - [#version-100--you-got-another-midnight-commit-](#version-100--you-got-another-midnight-commit-)
        - [#version-070beta--the-fiche-technique-du-véhicule-is-rétroversing-](#version-070beta--the-fiche-technique-du-véhicule-is-rétroversing-)
            - [#version-067minor](#version-067minor)
            - [#version-065minor](#version-065minor)
        - [#version-060beta--first-workin-pre-release](#version-060beta--first-workin-pre-release)
        - [#version-050beta--nothin-on-twenty](#version-050beta--nothin-on-twenty)
    - [#cahier-des-charges](#cahier-des-charges)
    - [#utilisation-terminal-git](#utilisation-terminal-git)
        - [#contributors](#contributors)

## reconstruction de votre projet
requis :
- php 7.4.2
- mySQL laragon|wamp || mariaDB (régler sur `.env`)
- composer
- symfony 4.*

clonage du dépot (ou si déjà fait : `git pull`)
```bash
git clone https://github.com/mikhaddo/locautovintage
```

la première fois qu'on rétroverse notre projet on rentre dans le bon dossier et installe les dépendances de symfony
```bash
cd locautovintage/
ls
composer install
```

ensuite on crée une base de donnée si on en a pas déjà, mais tu l'as peut être gardée sur ton http://127.0.0.1/phpmyadmin avec ta base de donnée de laragone
```bash
symfony console doctrine:database:create
```

maintenant c'est la reconstruction de la base si ça foire pas, car si ça foire c'est d'abord (attention cette commande remove)
```bash
rm src/Migrations/*.php
```

et voilà la reconstruction en question
```bash
php bin/console make:migration
php bin/console doctrine:migrations:migrate
```

et enfin on génère les fixtures pour avoir quelques users et quelques autos de base dont l'admin.
ce serait dommage de ne pas les utiliser, et en plus hydrater la base de donnée à la main avec phpmyadmin,
c'est une catastrophe sans nom.
```bash
php bin/console doctrine:fixtures:load
```

pour démarrer le serveur intégré de symfony
``` bash
symfony serve
```
et maitenant ça se passe sur http://127.0.0.1:8000

## améliorations possibles
- header background-color dégradé
- formulaire de recherche
- js/visionnette.js
- map-interractive : meilleure intégration
- page d'administration pour gestion véhicules et utilisateurs
- commentaire pour un véhicule en base de donnée
- commentaires d'utilisateurs à la volée avec un formulaire javaScript
- site en PROD, en sous domaine

### [version 1.1.0](../../releases/tag/v1.1.0) :: front-end repair
> front-end -> the-end !
- index && footer okay
- improvement of CSS : 64lines of 265line back old days !
- incorporation of Bootstrap-class in jQuery visionnette 'viewer.js'
- and best of all : all pages 100% valid W3C !
- but front-end is always little ugly.

### [version 1.0.0](../../releases/tag/v1.0.0) :: You got another Midnight commit' !
> this is a big day ! the website is finish. now we can only bugfixes some littles details.
- add fixtures without bug : 3 users and 6 automobiles by default
- repair interactive-map
- user can modify this password
- user can modify his profile without absolutely put some phone_number
- formulaires are protected for not outerpass the limits on the database

### [version 0.7.0beta](../../releases/tag/v0.7.0beta) :: the fiche technique du véhicule is rétroversing !
> can modifying the 'fiche technique du véhicule' and 'téléversing' five pictures. but not remove, gonna be forever online.

#### version 0.6.7minor
- connected user email on navbar
- page contact with contactForm working ! but not sending real mail.

#### version 0.6.5minor
- allow modifications user profile.
- no add/rm vehicules pictures for now
- 1h of bootstrap magic

### [version 0.6.0beta](../../releases/tag/v0.6.0beta) :: first workin' pre-release
> register new user, add vehicle with one picture

### [version 0.5.0beta](../../releases/tag/v0.5.0beta) :: nothin' on twenty'
> fonctionnel : rien/20

## cahier des charges
```cahier
3) Arborescence – Plan du site :

- Page d'accueil

	- la collection des autos

		- le détail d’une auto

	- inscription

	- connexion

		- affichage/modification du profil

	- contact

    	- Convention de nommage des photos :

Le nom des photos est construit en concaténant les informations suivantes :
ID_OWNER + BRAND + MODEL + ‘-’ + n° de la photo de 1 à 5 suivi de l’extension ‘.JPG’.

Exemples : ‘2bmw2002-3.jpg’, ‘5jaguarxjs-1.jpg’

Respect des normes UX/UI Design :
	Visible sur les moteurs de recherche grâce à une conception SEO Friendly.
    	Compatible pour un affichage optimal sur toutes les tailles d’écran.
    	Conforme à l’image de l’entreprise et rassurant.
    	Efficace en facilitant la navigation et la recherche d’informations.
    	Disponible 24h/24 et sans erreur 404.

Respect des normes Responsive Design.

Utilise l’architecture MVC (Modèle-Vue-Contrôleur).


```

## utilisation terminal git
```bash
    git status
    git add .
    git status
    git commit -m "voici mon commit les amis"
    git status
    git push

    git config --global user.{name,email} {"aloe",aloe.vera@hotmail.com} ## (là :: c:\users\utilisateurs\.gitconfig)
    git fetch origin ## (peut être ici il faudra modifier la diff entre ton(tes) fichier(s), et celui du serveur)
    modify files !
    git merge origin/master -m 'je merge ma petite fusion, et ça va bien se passer'
    git push
```

### contributors
* __Jean-Philippe__ <https://github.com/jean-philippeG>
* __Brian__ <https://github.com/Britrvl>
* __Thierry__ <https://github.com/mikhaddo>
* __~~Murat~~__ <https://github.com/Murat389>

| Tech           | Thierry | Jean-Philippe | Brian | ~~Murat~~ |
| -------------- | :-----: | :-----------: | :---: | :-------: |
| git            | **X**   |               |       |           |
| MarkDown       | **X**   |               | **X** |           |
| cahier-charges |         | **X**         |       |           |
| presentation   |         | **X**         |       |           |
| mySQL/Symfony  |         | **X**         |       |           |
| HTML           | **X**   |               | **X** |           |
| CSS            | **X**   |               | **X** |           |
| Bootstrap      | **X**   |               |       |           |
| javaScript     | **X**   |               |       |           |
| jQuery         | **X**   |               |       |           |
| PHP            | **X**   |               |       |           |
| Symfony        | **X**   |               | **X** |           |

![powered-by](https://web.archive.org/web/20061209091918im_/http://www.elroubio.net/nouveaute/phpinup_gpl_7.jpg)
