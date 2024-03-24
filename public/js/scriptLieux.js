/*
* page admin LIEUX
*/
// affichage lieux sur la carte avec coordo GPS
let all_coordo_concerts = [];
let all_coordo_festivals = [];
$('#arrConcerts #oneConcert').map(function() {
    // console.log( $(this).data('gps') );
let data = {};
    let concert = {};
    let festival = {};
    concert[$(this).data('info')] = $(this).data('gps');

    if($(this).find('#festival').text() != '') {
        //console.log( $(this).find('#festival').data('infos') );
        festival[$(this).find('#festival').data('infos')] = $(this).data('gps');
        all_coordo_festivals.push(festival);
    }
    all_coordo_concerts.push(concert);
});

/*console.log(all_coordo_concerts);
console.log(all_coordo_festivals);*/
let latitude = '';
let longitude = '';
let festivals = [];
for(var i = 0 ; i < all_coordo_festivals.length ; i++) {
    //console.log(all_coordo_festivals[i]);
    let artist = '';
    let fest = {};
    let coordo = {};
    $.each(all_coordo_festivals[i], function(key, coordo_gps) {
        latitude = coordo_gps.split('#')[0];
        longitude = coordo_gps.split('#')[1];
        nom = key.split('#')[0];
        ville = key.split('#')[1];
        lieu = key.split('#')[2];
        date_deb = key.split('#')[3];
        date_fin = key.split('#')[4];

        coordo['latitude'] = latitude;
        coordo['longitude'] = longitude;

        fest['nom'] = nom;
        fest['ville'] = ville;
        fest['lieu'] = lieu;
        fest['date_deb'] = date_deb;
        fest['date_fin'] = date_fin;
        fest['coordo'] = coordo;
        festivals.push(fest);
    });
}
//console.log(festivals);

let concerts = [];
for(var i = 0 ; i < all_coordo_concerts.length ; i++) {
    //console.log(all_coordo[i]);
    let artist = '';
    let artists = {};
    let coordo = {};
    $.each(all_coordo_concerts[i], function(key, gps) {
        latitude = gps.split('#')[0];
        longitude = gps.split('#')[1];
        artist = key.split('#')[0];
        city = key.split('#')[1];
        lieu = key.split('#')[2];
        date = key.split('#')[3];

        coordo['latitude'] = latitude;
        coordo['longitude'] = longitude;

        artists['nom'] = artist;
        artists['city'] = city;
        artists['lieu'] = lieu;
        artists['date'] = date;
        artists['coordo'] = coordo;
        concerts.push(artists);
    });
}


if(carteLieu != null) {
    carteLieu.remove();
}
initMap(concerts, festivals);

var carteLieu = null;
var markerClusters; // Servira à stocker les groupes de marqueurs

// https://nouvelle-techno.fr/articles/pas-a-pas-inserer-une-carte-openstreetmap-sur-votre-site
function initMap(concerts, festivals) {
   //console.log(concerts);
   //console.log(festivals);
   var iconBase = '/../../images/icons/';
   carteLieu = L.map('mapLieux').setView([latitude, longitude], 0);
   carteLieu.setZoom(6);
   markerClusters = L.markerClusterGroup(); // Nous initialisons les groupes de marqueurs
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

    // icone bleu
    for(festival in festivals) {
        // console.log(festivals[festival]);
        let annee = festivals[festival].date_deb.substring(0,4);
        let infos_festival = '<b>' +festivals[festival].nom + ' - ' +annee+'</b><br/>'
            + ' ('+festivals[festival].lieu+' - '+festivals[festival].ville+')';

        var marker = L.marker([festivals[festival].coordo.latitude, festivals[festival].coordo.longitude], { icon: icons, zIndexOffset: 1000 });
        marker.addTo(carteLieu);
        marker.bindPopup(infos_festival);
        markerClusters.addLayer(marker);
    }
    carteLieu.addLayer(markerClusters); // marqueurs groupés pour plusieurs events au même endroit

    // icone jaune
    for(concert in concerts) {
        // console.log(concerts[concert]);
        let infos_concert = '<b>' +concerts[concert].nom + ' - ' +concerts[concert].date+'</b><br/>'
            + ' ('+concerts[concert].lieu+' - '+concerts[concert].city+')';
        var marker1 = L.marker([concerts[concert].coordo.latitude, concerts[concert].coordo.longitude], { icon: iconSel, zIndexOffset: 100 });
        marker1.addTo(carteLieu);
        marker1.bindPopup(infos_concert);
    }

}





