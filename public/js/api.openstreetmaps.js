/**
 * on déclare ça ici pour y avoir le droit partout, et pas seulement dans la condition
 */
var map = null;
var lat = 46.952;
var lng = 4.28109;

/**
 * attente du chargement complet du DOM avant de lancer les requêttes,
 * sinon le site attends toutes les réponses aux promesses avant de charger le footer
 * // old ::
 * //     window.addEventListener("DOMContentLoaded", (event) => {
 * //         console.log('DOM entièrement chargé et analysé' + event);
 * //     })
 * // function() <- non fleché
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
    // marche pô "{{ asset('images/ajax-loader.svg') }}"
    // écrit en chemin relatif par rapport au .js
    imgProcess.src = "../images/ajax-loader.svg";
    imgProcess.style.position = 'absolute';
    imgProcess.style.top = '50%'
    imgProcess.style.left = '50%'
    imgProcess.style.transform = 'translate(-50%, -50%)';
    documentMapSelector.firstElementChild.prepend(imgProcess);

    /**
     * fonction asynchrone : s'executera quand ça aura le temps.
     * peut retourner une promesse d'echec ou réussite.
     * Fetch les données JSON de la base de donnée pour récupérer les villes
     * Équivalent à ajax.
     */
    async function getDatabase() {
        return new Promise(function(resolve, reject){
            fetch('/test-json/', {
                headers: {'Content-Type': 'application/json'},
            }).then((response) => {
                resolve(response.json());
            }).catch((error) => {
                reject('Error-catched:', error);
            });
        })
    }

    /**
     * fonction asynchrone : s'executera quand ça aura le temps.
     * peut retourner une promesse d'echec ou réussite.
     * Ajax requêtte à une API (javaScript natif, tu vois c'est pas plus long),
     * conversion à la volée city -> en latitude & longitude
     * ~ amélioration possible, envoyer d'un côŧé l'url et de l'autre le data="q="
     * ~ amélioration possible : rechercher aussi par code postal
     */
    async function getAjaxGeo(city){
        return new Promise(function(resolve, reject){
            let xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = () => {
                if(xmlhttp.readyState == 4){
                    if(xmlhttp.status == 200){
                        resolve(JSON.parse(xmlhttp.responseText));
                    } else {
                        reject(xmlhttp.statusText);
                    }
                }
            }

            xmlhttp.onerror = function(error){
                reject(error);
            }

            // requête asynchrone url="https://nominatim.openstreetmap.org/search", type="GET", data="q="+city+"&format=geojson"
            xmlhttp.open("GET","https://nominatim.openstreetmap.org/search?q=" + city + "&format=geojson", true);
            xmlhttp.send(null);
        })
    }

    /**
     * 'roue du destin'
     * la commande pour effacer la petite image qui fait patienter
     * on est sensés l'envoyer au bon moment !
     * ~ améliorations possibles : pourquoi c'est en asynchrone ?
     */
    async function getRemoveDestin(){
        return new Promise(function(resolve, reject){
            resolve(documentMapSelector.removeChild(documentMapSelector.firstElementChild));
            console.info('.then(getDatabase)->terminée->roue du destin virée');
            reject('error getRemoveDestin(), weirdo non ?')
        })
    }

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
    getDatabase().then(markers => {
        console.info('getDatabase');

        /**
         * création et affichage de la map grâce à notre leaflet
         * // old ::
         * // déclaré en non->object ?
         * // map = L.map('map').setView([lat,lng],14);
         */
        map = new L.map('map').setView(new L.LatLng(lat, lng), 11);
        L.tileLayer('https://{s}.tile.openstreetmap.fr/osmfr/{z}/{x}/{y}.png', {
            attribution: 'donn&eacute;es &copy; <a href="//osm.org/copyright">OpenStreetMap</a>/ODbL - rendu <a href="//openstreetmap.fr">OSM France</a>',
            minZoom: 1,
            maxZoom: 19,
        }).addTo(map);

        /**
         * pour toutes les villes récupérées dans le tableau cities
         * on appel la function getAjaxGeo() et on l'inclut sur la map.
         * avec son popup (on pense à développer)
         * // old ::
         * // for(city of cities){ console.log(city) ) // ne donne pas le bon city.
         * // this.cities.forEach(function(city){});
         * // .bindPopup(tempMessages[i++])
         * ~ l'utilité de 'this.' ?
         */
        var returnVehiclesAdress = [];
        var markersLayers = L.markerClusterGroup();
        for(i=0;i<markers.returnVehicles.length;i++){

            // what time is it ? it is not workin'o'clock
            //var varReturnVehicles = markers.returnVehicles[i];

            getAjaxGeo(markers.returnVehicles[i]['city']).then((responseText) => {

                    console.info('getAjaxGeo :: ' + responseText.features[0].geometry.coordinates);
                    var varMarker = L.marker([responseText.features[0].geometry.coordinates[1],responseText.features[0].geometry.coordinates[0]])
                        //.addTo(map)
                        // le bind poppup doit afficher l'image récupérée depuis la database
                        // en tout cas on ne peut pas rappeller son objet ci-dessous
                        // markers a disparu, maintenant c'est response ou alo

                        .bindPopup(responseText.features[0].properties.display_name + '<br>Propriétaire du véhicule :: <br>' + returnVehiclesAdress.firstname )
                    ;
                markersLayers.addLayer(varMarkers)
                returnVehiclesAdress.push(varMarker);

            }).catch(error => {
                console.error('Error \'getAjaxGeo\' ! possibilité mauvais nom de ville.');
                console.dir(error);
            });
        }

    }).catch(error => {
        console.error('Error \'getDatabase\' fiston !');
        console.dir(error);
    }).then(() => {
        // supression de la roue du destin, même si c'est pas au bon endroit !
        getRemoveDestin();
        var groupMarkers = new L.featureGroup(returnVehiclesAdress);
        carte.fitBounds(groupMarkers.getBounds().pad(0.5));
        carte.addLayer(varMarkers);
    });

// EOF (End Of File, fin de la de la fonction principale englobante.)
}
