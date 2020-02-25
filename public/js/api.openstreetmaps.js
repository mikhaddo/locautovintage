/**
 * on déclare ça ici pour y avoir le droit partout, et pas seulement dans la condition
 */
var map = null;
var lat = 46.952;
var lng = 4.28109;

let city = distance = circle = "";

/**
 * attente du chargement complet du DOM avant de lancer les requêttes,
 * sinon le site attends toutes les réponses aux promesses avant de charger le footer
 * // old ::
 * //     window.addEventListener("DOMContentLoaded", function(event){
 * //         console.log('DOM entièrement chargé et analysé' + event);
 * //     })
 */
window.onload = () => {

    /**
     * création et placement de la roue de l'attente,
     * en javaScript natif. un peu plus long à écrire que jQuery,
     * mais on a le contrôle total et pas dépendant d'un fichier de plusieurs kilomètres.
     * on est aussi indépendant niveau CSS, pas besoin d'en rajouter dans 'css/styles.css' !
     */
    let documentMapSelector = document.querySelector('#map');

    let divProcess = document.createElement('div');
    divProcess.style.position = 'relative';
    divProcess.style.backgroundColor = 'rgba(0,0,0,0.7)';
    divProcess.style.width = '100%';
    divProcess.style.height = '100%';
    documentMapSelector.prepend(divProcess);

    let imgProcess = document.createElement('img');
    imgProcess.src = "../images/ajax-loader.svg";
    imgProcess.style.position = 'absolute';
    imgProcess.style.top = '50%'
    imgProcess.style.left = '50%'
    imgProcess.style.transform = 'translate(-50%, -50%)';
    documentMapSelector.firstElementChild.prepend(imgProcess);

    /**
     * fonction asynchrone : s'executera quand ça aura le temps.
     * peut retourner une promesse d'echec ou réussite.
     * Ajax requêtte à une API (javaScript natif, tu vois c'est pas plus long),
     * conversion à la volée city -> en latitude & longitude
     * ~ amélioration possible, envoyer d'un côŧé l'url et de l'autre le data="q="
     * ~ amélioration possible : rechercher aussi par code postal
     * @param {string} url
     */
    function getAjax(url){
        return new Promise(function(resolve, reject){
            // gestion de la promesse
            let xmlhttp = new XMLHttpRequest();

            // pas de function ()=> ici !
            xmlhttp.onreadystatechange = function(){
                if(xmlhttp.readyState == 4){
                    if(xmlhttp.status == 200){
                        // resolve(JSON.parse(xmlhttp.response));
                        resolve(xmlhttp.responseText)
                    } else {
                        reject(xmlhttp.status);
                    }
                }
            }

            // la connexion à été interrompue
            xmlhttp.onerror = function(error){
                reject(error);
            }

            // envoit d'arguments
            xmlhttp.open("GET",url,true);
            xmlhttp.send(null);
        })
    }

    /**
     * 'roue du destin'
     * la commande pour effacer la petite image qui fait patienter
     * on est sensés l'envoyer au bon moment !
     * ~ améliorations possibles : pourquoi c'est en asynchrone ?
     */
    function getRemoveDestin(){
        return new Promise(function(resolve, reject){
            resolve(documentMapSelector.removeChild(documentMapSelector.firstElementChild));
            console.info('.then(getDatabase)->terminée->roue du destin virée');
            reject('error getRemoveDestin(), weirdo non ?')
        })
    }

    /**
     * création et affichage de la map grâce à notre leaflet
     * name : pour plus tard, permettra de ne pas surpprimer cette couche
     */
    let map = L.map('map').setView([lat, lng], 10);
    L.tileLayer('https://{s}.tile.openstreetmap.fr/osmfr/{z}/{x}/{y}.png', {
        attribution: 'donn&eacute;es &copy; <a href="//osm.org/copyright">OpenStreetMap</a>/ODbL - rendu <a href="//openstreetmap.fr">OSM France</a>',
        minZoom: 1,
        maxZoom: 19,
        name: 'tiles'
    }).addTo(map);

    // gestion des champs front-end pour choisir des villes & distances !
    let champCity = document.getElementById('champ-city');
    let champDistance = document.getElementById('champ-distance');
    let valueDistance = document.getElementById('value-distance');

    // petit front pour demander de remplir les champs correctement
    let champFood = document.getElementById('champ-food');
    let champFoodBool = false;
    champFood.style.border = 'red 1px dashed';
    champFood.style.backgroundColor = 'yellow';

    champCity.addEventListener("change", function(){
        champFoodBool = true;
        getAjax(`https://nominatim.openstreetmap.org/search?q=${this.value}&format=json&addressdetails=1&limit=1&polygon_svg=1`).then(response => {
            // on convertit la réponse en objet javaScript
            let data = JSON.parse(response);
            // on stocke les coordonnées dans ville
            city = [data[0].lat, data[0].lon];
            console.info('sélection lieux : ' + data[0].display_name + ' ' + city);
            // on centre la carte sur la ville
            map.panTo(city);
        });
    });

    /**
     * lancement de la function getDatabase(),
     * qui permet de récupérer les informations de la base de donnée
     * création de la map de base
     * appel à l'intérieur cette fonction, la fonction getAjaxGeo()
     * qui elle même convertit les villes en informations latitude & longitude
     * et en passant dépose une popup avec des informations
     * // Promise.all([getDatabase(),getAjax()]).then(([response1, response2]) => {})
     * ~ quand c'est fini ça doit remove la roue du destin, à améliorer stp.
     */
    champDistance.addEventListener("change", function(){
        // on récupère la distance choisie
        distance = this.value;
        console.log('distance : ' + distance + ' km');

        // on écrit cette valeur sur la page
        valueDistance.innerText = distance + 'km';

        // en plus faut pas sélectionner trop vite si le ajax rame, car il trouve pas la ville !
        if(champFoodBool){
            champFood.style.border = 'none';
            champFood.style.backgroundColor = 'transparent';
            champFood.innerText = 'champs remplis : merci !'
        }

        // ici nous chercherons les agences correspondantes à la localisation
        if(city != ""){
            // améliorations possibles : récupérer seulement en fonction de la ville et distance choisie
            getAjax('/autos-disponibles/json').then(response => {

                // on supprime toutes les couches de la carte
                map.eachLayer(function(layer){
                    if(layer.options.name != 'tiles') map.removeLayer(layer);
                });

                // on trace un cercle correspondant à la distance souhaitée
                let circle = L.circle(city, {
                    color: "#839c49",
                    fillColor: "#08c08c",
                    fillOpacity: 0.3,
                    radius: distance * 100
                }).addTo(map);

                // on convertit la réponse en Object javaScript
                let data = JSON.parse(response)

                // recréation d'un objet javaScript, pour les données sur les véhicules
                console.info(data);
                let objVehicles = {
                    firstname:[],
                    picture:[]
                };
                for( i=0 ; i < data.returnVehicles.length ; i++){
                    objVehicles.firstname.push(data.returnVehicles[i].firstname);
                    objVehicles.picture.push(data.returnVehicles[i].picture);
                }
                console.log(objVehicles);

                // création d'une case image trop stylée
                function definePopup(objVehicles) {
                    var popupText =
                                // "<b>Location Description: </b>"+entry[2]+"<br>"+
                                // "<b>Work Date: </b>"+entry[3]+"<br>"+
                                // "<b>Graffiti Type: </b>"+entry[5]+"<br>"+
                                // "<b>Graffiti Material: </b>"+entry[6]+"<br>"+
                                "<b>Nom du propriétaire: </b>"+objVehicles.firstname[j]+"<br>"+
                                //"<b>Image: </b><a href='"+entry[0]+"' target=\"_blank\">"+"<img src='"+entry[0]+"&previewImage=true'</img></a>";
                                // if exist
                                "<b>Image: </b><img src='../images/pictures/"+
                                objVehicles.picture[j][0]+
                                "'&previewImage='true' style='width:100px;height:50px;'</img>";
                    return popupText;
                }

                // 'j' nous servira pour incrémenter un compteur à la fin de chaque boucles de données.
                let j=0;
                for( i=0 ; i < data.returnVehicles.length ; i++){
                    getAjax(`https://nominatim.openstreetmap.org/search?q=${data.returnVehicles[i].city}&format=json&addressdetails=1&limit=1&polygon_svg=1`)
                    .then(response2 => {

                        // on boucle sur les données
                        Object.entries(JSON.parse(response2)).forEach(agence => {
                            // on crée le marqueur
                            let marker = L.marker([agence[1].lat, agence[1].lon]).addTo(map);
                            marker.bindPopup(definePopup(objVehicles));
                            console.log(agence);
                            console.log(response2);
                            j++;
                        });

                    });
                }

                // on centre la carte sur le cercle
                let bounds = circle.getBounds();
                map.fitBounds(bounds);
            });
        }

    });

} // EOF (End Of File, fin de la de la fonction principale englobante.)