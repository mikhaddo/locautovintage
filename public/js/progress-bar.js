/**
 * progress-bar
 * @by nouvelle-techno.fr
 */


// wait full page loading before execution
// window.onload = () => {

    // autonomy of the script
    document.body.prepend(document.createElement('div'))
    document.body.firstElementChild.id = 'progress';

    divProgress = document.getElementById('progress');
    divProgress.style.position = 'fixed';
    divProgress.style.top = divProgress.style.left = divProgress.style.width = '0';
    divProgress.style.height = '5px';
    divProgress.style.zIndex = '9';
    divProgress.style.backgroundColor = '#839c49';

    // eventlistener on scroll
    window.addEventListener('scroll', () => {

        // calcul 'utility' height
        let height = document.documentElement.scrollHeight - window.innerHeight;
        // retrieve window width
        let width = document.documentElement.clientWidth;

        // vertical position
        let position = window.scrollY;

        // calcul barre : position/height to have 0.00/1 -> 1/1
        let barre = (position / height) * width;

        // modification CSS
        divProgress.style.width = barre+'px';

        // debug purposes
        // console.log('position : ' + position/height);
        // console.log('barre : ' + barre+'px');
    });
// };