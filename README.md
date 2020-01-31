# locautovintage
rental old collection vehicles
PROJET LOC'AUTO VINTAGE (autos de 1950 à 1990)
Site mettant en relation des propriétaires d'autos anciennes et des amateurs de balades vintage.


## reconstruction de votre projet
requis :
    - php 7.4.2
    - mySQL laragon|wamp || mariaDB (régler sur `.env`)
    - composer
    - symfony 4.*

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
```bash
php bin/console doctrine:fixtures:load
```

pour démarrer le serveur intégré de symfony
``` bash
symfony serve
```
et maitenant ça se passe sur http://127.0.0.1:8000

## améliorations possibles
- FA icones
- logo rotatif
- header background-color dégradé
- formulaire de recherche
- meilleures fixtures
- js/visionnette.js
- map-interractive : meilleure intégration
- site en PROD, en sous domaine

#### version 0.6.5
> allow modifications user profile.
> no add/rm vehicules pictures for now
> 1h of bootstrap magic

### [version 0.6.0beta](../../releases/tag/v0.6.0beta) :: first workin' pre-release
> register new user, add vehicle with one picture

### [version 0.5.0beta](../../releases/tag/v0.5.0beta) :: nothin' on twenty'
> fonctionnel : rien/20

## cahier des charges
```cahier
Fiche inscription propriétaire :
	- mail
	- mot de passe (x2)
    Saisie de la fiche d'inscription :
    - nom
    - prénom
    - ville
    - mail
    - téléphone
    - nom de l'assurance
    - mon assurance couvre la location à titre onéreux (O/N)
    Auto :
    - marque
    - modèle
    - année
```

## utilisation terminal git
require 'git clone https://github.com/mikhaddo/locautovintage.git && git pull'

if(working){
    git status
    git add .
    git status
    git commit -m "voici mon commit les amis"
    git status
    git push
} else {

    git config --global user.{name,email} {"aloe",aloe.vera@hotmail.com} ## (là :: c:\users\utilisateurs\.gitconfig)
    git fetch origin ## (peut être ici il faudra modifier la diff entre ton(tes) fichier(s), et celui du serveur)
    git merge origin/master -m 'je merge ma petite fusion, et ça va bien se passer' ##
}

## todo
- [X] retrouvailles sur le discord game !
- [ ] cahier des charges:
    - [ ] améliorer lisibilité
    - [ ] liste des technologies utilisées
    - [ ] cahier des charges en pdf
    - [ ] présentation type 'powerpoint'
- [ ] page html de squelette bootstrap avec menu, couleurs, footer, classes
- [ ] wiki : technos utilisées, par qui ; functionalitées site ; /etc/..

### contributors
* __Jean-Philippe__ <https://github.com/jean-philippeG>
* __Brian__ <https://github.com/Britrvl>
* __Thierry__ <https://github.com/mikhaddo>
* __~~Murat~~__ <https://github.com/Murat389>

### table
First Header | Second Header
------------ | -------------
aloe | verae

#### rnd()
![powered-by](https://web.archive.org/web/20061209091918im_/http://www.elroubio.net/nouveaute/phpinup_gpl_7.jpg)
