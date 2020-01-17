$('.viewer-event').click(function(){
    // On crée une div avec la classe overlay contenant :
    // une balise img dont la src utilise le nom de l'image stocké dans le data-img de la vignette cliquée
    // une balise div avec la classe close-button et un "X" dedans
    $('body').prepend(`
        <div class="overlay">
            <img src="` + $(this).data('img') + `">
            <div class="close-button">X</div>
        </div>
    `);

    $('body').css('overflow', 'hidden');

    // Ecouteur d'évènement click sur le "X"
    $('.close-button').click(function(){

        // Suppression de l'overlay (et tout ce qu'il contient)
        $('.overlay').remove();

        $('body').css('overflow', 'auto');
    });
});