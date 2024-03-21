/*
* page admin LIEUX
*/
// affichage lieux sur la carte avec coordo GPS
let all_coordo = [];
$('#arrConcerts #oneConcert').map(function() {
    // console.log( $(this).data('gps') );
    let concert = {};
    concert[$(this).data('info')] = $(this).data('gps');
    all_coordo.push(concert);
});
// console.log(all_coordo);

let latitude = '';
let longitude = '';
let concerts = [];
for(var i = 0 ; i < all_coordo.length ; i++) {
    //console.log(all_coordo[i]);
    let artist = '';
    let artists = {};
    let coordo = {};
    $.each(all_coordo[i], function(key, gps) {
        // key = infos concert
        // gps = coordo
        latitude = gps.split('#')[0];
        longitude = gps.split('#')[1];
        artist = key.split('#')[0];
        city = key.split('#')[1];
        annee = key.split('#')[2];

        coordo['latitude'] = latitude;
        coordo['longitude'] = longitude;

        artists['nom'] = artist;
        artists['city'] = city;
        artists['coordo'] = coordo;
        concerts.push(artists);
    });
}
if(carteLieu != null) {
    carteLieu.remove();
}
initMap(concerts);

var carteLieu = null;

// https://nouvelle-techno.fr/articles/pas-a-pas-inserer-une-carte-openstreetmap-sur-votre-site
function initMap(concerts) {
    //console.log(concerts);
   var iconBase = '/../../images/icons/';
   carteLieu = L.map('mapLieux').setView([latitude, longitude], 0);
   carteLieu.setZoom(6);
   L.tileLayer('https://{s}.tile.openstreetmap.fr/osmfr/{z}/{x}/{y}.png', {
      // Il est toujours bien de laisser le lien vers la source des données
      attribution: 'données © <a href="//osm.org/copyright">OpenStreetMap</a>/ODbL - rendu <a href="//openstreetmap.fr">OSM France</a>',
      minZoom: 0,
      maxZoom: 12
   }).addTo(carteLieu);

   var iconSel = L.icon({
      iconUrl: iconBase+"location-yellow.png",
      iconSize: [40, 40],
      iconAnchor: [25, 50],
      popupAnchor: [-3, -45],
   });

    // paramétrage des autres icones
    var icons = L.icon({
        iconUrl: iconBase+"location-blue.png",
        iconSize: [40, 40],
        iconAnchor: [25, 50],
        popupAnchor: [-3, -45],
    });

    var marker = L.marker([latitude, longitude], { icon: iconSel, zIndexOffset: 1000 }).addTo(carteLieu);
    marker.bindPopup('ville');

   for(ville in concerts) {
       // console.log(concerts[ville]);
       var marker1 = L.marker([concerts[ville].coordo.latitude, concerts[ville].coordo.longitude], { icon: icons, zIndexOffset: 1000 }).addTo(carteLieu);
       marker1.bindPopup(concerts[ville].nom);
   }

}





