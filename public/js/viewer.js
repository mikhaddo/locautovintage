/**
 * ici, en jQuery le style est géré directement avec les 'class bootstrap'.
 * voir visionnette.js pour prendre conscience
 * d'un véritable programme de visionnage en javaScript
 */
$('.viewer-event').click(function(){
    // On crée une div avec la classe overlay contenant :
    // une balise img dont la src utilise le nom de l'image stocké dans le data-img de la vignette cliquée
    // une balise div avec la classe close-button et un "X" dedans
    $('body').prepend(`
        <div class="h-100 w-100 position-fixed d-flex justify-content-center align-items-center overlay-black" id="jquery-visionnette">
            <div class="w-75">
                <img class="w-100" src="` + $(this).data('img') + `">
            </div>
            <div class="h-75 w-75 position-fixed d-flex justify-content-end">
                <button class="align-self-start btn btn-large btn-primary" id="jquery-close-button">X</button>
            </div>
        </div>
    `);

    // Désactivation du scroll de la page
    $('body').css('overflow', 'hidden');

    // Ecouteur d'évènement click sur le "X" grâce à son ID
    $('#jquery-close-button').click(function(){

        // Suppression de l'overlay (et tout ce qu'il contient) grâce à son ID
        $('#jquery-visionnette').remove();

        // Réactivation du scroll de la page
        $('body').css('overflow', 'auto');
    });
});