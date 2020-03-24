// base city (Autun)
let city = [46.952,4.28109];
let distance = 400;

/**
 * création et placement de la roue de l'attente,
 * en javaScript natif. un peu plus long à écrire que jQuery,
 * mais on a le contrôle total et pas dépendant d'un fichier de plusieurs kilomètres.
 * on est aussi indépendant niveau CSS, pas besoin d'en rajouter dans 'css/styles.css' !
 */
function getDestin(){
    let documentMapSelector = document.querySelector('#map');

    let divProcess = document.createElement('div');
    divProcess.style.position = 'relative';
    divProcess.style.backgroundColor = 'rgba(0,0,0,0.7)';
    divProcess.style.width = '100%';
    divProcess.style.height = '100%';
    divProcess.style.zIndex = 999;
    documentMapSelector.prepend(divProcess);

    let imgProcess = document.createElement('img');
    imgProcess.src = "../images/ajax-loader.svg";
    imgProcess.style.position = 'absolute';
    imgProcess.style.top = '50%'
    imgProcess.style.left = '50%'
    imgProcess.style.transform = 'translate(-50%, -50%)';
    documentMapSelector.firstElementChild.prepend(imgProcess);
}
// appel direct de la roue du destin
getDestin();

/**
 * attente du chargement complet du DOM avant de lancer les requêttes,
 * sinon le site attends toutes les réponses aux promesses avant de charger le footer
 */
window.onload = () => {

    /**
     * 'roue du destin'
     * la commande pour effacer la petite image qui fait patienter
     * on est sensés l'envoyer au bon moment !
     */
    function getRemoveDestin(){
        // return new Promise(function(resolve, reject){
            // resolve(documentMapSelector.removeChild(documentMapSelector.firstElementChild));
            let documentMapSelector = document.querySelector('#map');
            documentMapSelector.removeChild(documentMapSelector.firstElementChild);
            console.info('.then(getDatabase)->terminée->roue du destin virée');
            // reject('error getRemoveDestin(), weirdo non ?')
        // })
    }

    /**
     * fonction asynchrone : s'executera quand ça aura le temps.
     * peut retourner une promesse d'echec ou réussite.
     * Ajax requêtte à une API (javaScript natif, tu vois c'est pas plus long que le jQuery),
     * conversion à la volée city -> en latitude & longitude
     * ~ amélioration possible, envoyer d'un côŧé l'url et de l'autre le data="q="
     * ~ amélioration possible : rechercher aussi par code postal
     * @param {string} url
     * @return {xmlhttp.responseText} object
     */
    function getAjax(url){
        return new Promise(function(resolve, reject){
            // gestion de la promesse
            let xmlhttp = new XMLHttpRequest();

            // pas de function ()=> ici !
            xmlhttp.onreadystatechange = () => {
                if(xmlhttp.readyState == 4){
                    if(xmlhttp.status == 200){
                        resolve(xmlhttp.responseText);
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
     * création d'une case image trop stylée
     * @param {string} vehiculeFirstname
     * @param {string} vehiculePictureFirst
     * @param {int} postcode
     * @param {string} city
     */
    function definePopup(vehiculeFirstname, vehiculePictureFirst, postcode, city) {
        // console.log(vehiculeFirstname,vehiculePictureFirst,postcode,city);
        console.log(vehiculePictureFirst);
        var popupText =
            // "<b>Location Description: </b>"+entry[2]+"<br>"+
            // "<b>Work Date: </b>"+entry[3]+"<br>"+
            // "<b>Graffiti Type: </b>"+entry[5]+"<br>"+
            // "<b>Graffiti Material: </b>"+entry[6]+"<br>"+
            "<b>Nom du propriétaire: </b>"+vehiculeFirstname+"<br>"+
            //"<b>Image: </b><a href='"+entry[0]+"' target=\"_blank\">"+"<img src='"+entry[0]+"&previewImage=true'</img></a>";
            // if exist
            "<b>Image: </b><img src='../images/pictures/"+
            vehiculePictureFirst+
            "'&previewImage='true' style='width:100px;height:50px;'</img>"+
            "<br>"+
            postcode + ' ' + city
        ;
        return popupText;
    }
    /**
     * appel à l'intérieur cette fonction, la fonction getAjaxGeo()
     * qui elle même convertit les villes en informations latitude & longitude
     * et en passant dépose une popup avec des informations
     */
    function getCity(){

        // si city est vide, on fuit la fonction, c'est un bug qui n'est pas sensé être vu que l'on déclare une ville de base.
        if(city == ''){
            return;
        }

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

            // annonce le lancement de la recherche
            console.log("%c📛STOP📛", "color: red; font-size: 25pt;");

            // notre groupe de layers sera dans `markers`
            var markers = L.markerClusterGroup();

            // conversion response -> javaScriptObject && demarage double boucle infernale
            Object.entries(JSON.parse(response).returnVehicles).forEach( ([key, returnVehicle]) => {
                getAjax(`https://nominatim.openstreetmap.org/search?q=${returnVehicle.city}&format=json&addressdetails=1&limit=1&polygon_svg=1`)
                .then(response2 => {

                    // une fois que l'on a fait toutes les requêttes `key`, on boucle sur les données renvoyées par nominatim
                    // Object.entries renvoit un tableau : [zero] reste toujours à zéro, et [data] contient la donnée.
                    for (let [zero, data] of Object.entries(JSON.parse(response2))) {

                        console.log('search n.' + key + ' :: ' + returnVehicle.city + '->' + data.address.city);

                        // skip if user not define a city
                        if(returnVehicle.city == null) return;

                        // creation du marker, avec sa popup en fonction et ajout dans les markers
                        let marker = L.marker([data.lat, data.lon])
                            .bindPopup(definePopup(
                                returnVehicle.firstname,
                                returnVehicle.picture[0],
                                data.address.postcode,
                                data.address.city
                            ));
                        markers.addLayer(marker);

                    }

                });

            });

            // on centre la carte sur le cercle, placement de tous les markers, y compris les groupés, sur la map
            map.fitBounds(circle.getBounds());
            map.addLayer(markers);
        })

    }

    // création de la map de base. name est utile pour plus tard. permettra de ne pas supprimer cette couche.
    let map = L.map('map').setView(city, 10);
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

    // appel de la première recherche (automatique)
    getCity();

    // EventListener on change ChampCity
    champCity.addEventListener("change", function(){
        getAjax(`https://nominatim.openstreetmap.org/search?q=${this.value}&format=json&addressdetails=1&limit=1&polygon_svg=1`)
            .then(response => {
                // on convertit la réponse en objet javaScript
                let data = JSON.parse(response);
                // on stocke les coordonnées dans ville
                city = [data[0].lat, data[0].lon];
                console.info('sélection lieux : ' + data[0].display_name + ' ' + city);
                // on centre la carte sur la ville
                map.panTo(city);
        });
    });

    // EventListener : quand on modifie la distance
    champDistance.addEventListener("change", function(){
        // on récupère la distance choisie
        distance = this.value;
        console.log('distance : ' + distance + ' km');

        // on écrit cette valeur sur la page
        valueDistance.innerText = distance + 'km';

        getCity();

    });

    // suppression de la roue de destin, quand tout le script à été chargé (à améliorer)
    getRemoveDestin();

} // EOF (End Of File, fin de la de la fonction principale englobante.)