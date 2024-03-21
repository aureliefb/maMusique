/*
* page admin LIEUX
*/
// affichage lieux sur la carte avec coordo GPS
let latitude = document.getElementById('latitude').value;
let longitude = document.getElementById('longitude').value;

if(latitude !== '' && longitude !== '') {
    document.addEventListener("DOMContentLoaded", initMap);
    let coordo = {};
    coordo['latitude'] = latitude;
    coordo['longitude'] = longitude;
    //console.log(coordo);

    initMap();
} else {
    console.log('pas de coordo');
}

var carteLieu = null;

// https://nouvelle-techno.fr/articles/pas-a-pas-inserer-une-carte-openstreetmap-sur-votre-site
function initMap() {
    var iconBase = '/../../images/icons/';
    carteLieu = L.map('mapAdminLieux').setView([latitude, longitude], 0);
    carteLieu.setZoom(16);
    L.tileLayer('https://{s}.tile.openstreetmap.fr/osmfr/{z}/{x}/{y}.png', {
        // Il est toujours bien de laisser le lien vers la source des données
        attribution: 'données © <a href="//osm.org/copyright">OpenStreetMap</a>/ODbL - rendu <a href="//openstreetmap.fr">OSM France</a>',
        minZoom: 1,
        maxZoom: 20
    }).addTo(carteLieu);

    var iconSel = L.icon({
        iconUrl: iconBase+"location-yellow.png",
        iconSize: [40, 40],
        iconAnchor: [25, 50],
        popupAnchor: [-3, -45],
    });

    var marker1 = L.marker([latitude, longitude], { icon: iconSel, zIndexOffset: 1000 }).addTo(carteLieu);
    marker1.bindPopup('ville');

}





