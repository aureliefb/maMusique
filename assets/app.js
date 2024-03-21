/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.css';

alert('ici');

$("a[data-link]").click(function() {
    // on récupère les éléments de la balise a
    let link = $(this).attr('data-link');
    console.log(link);
});