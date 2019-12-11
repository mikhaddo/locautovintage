# locautovintage
rental old collection vehicles

PROJET LOC'AUTO VINTAGE (autos de 1950 à 1990) --> site modèle : classicautoloc[dot]com

Site mettant en relation des propriétaires d'autos anciennes et des amateurs de balades vintage.

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
	git merge origin/master -m 'je merge ma petite fusion, et ça va bien se passer' ## --amend (à tester)	

}

## todo
- [X] retrouvailles sur le discord game !
- [ ] cahier des charges
- [ ] page html de squelette bootstrap avec menu, couleurs, footer, classes
- [ ] wiki : technos utilisées, par qui ; functionalitées site ; /etc/..

### contributors
* __Jean-Philippe__ <https://github.com/jean-philippeG>
* __Brian__ <https://github.com/Britrvl>
* __Thierry__ <https://github.com/mikhaddo>
* __Murat__ <https://github.com/Murat389>

### table
First Header | Second Header
------------ | -------------
aloe | verae

#### rnd()
![powered-by](https://web.archive.org/web/20061209091918im_/http://www.elroubio.net/nouveaute/phpinup_gpl_7.jpg)
