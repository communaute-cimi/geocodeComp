function initMap(osmUrl) {

    map = L.map('map').setView([46.509, 2.197], 6);

    L.tileLayer(osmUrl + '{z}/{x}/{y}.png', {
      maxZoom : 19,
      attribution : 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, '
                  + '<a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, '
    }).addTo(map);

}

/**
 * GeocodeGoogle
 * 
 * Put marker after google geocoding using this URL http://maps.google.com/maps/api/geocode/json
 * Datas are the same as 
 * 
 * @param {} jsonParams
 */

function geocodeGoogle(jsonParams) {
    var addr = $("#adresse").val();
    var cpVille = $("#cpVille").val();
  
  jsonParams.addr = addr;
  jsonParams.cpVille = cpVille;
    
    $.getJSON('proxy/' + jsonParams.proxyPhp, jsonParams, function(datas) {
    checkGeocodeDatas(datas);
    var geocodeItem = datas.results[0];
    // récupérer le premier élément
  
    var lon = geocodeItem.geometry.location.lng;
    var lat = geocodeItem.geometry.location.lat;
  
    marker = L.circle([lat, lon], 10,  { color: jsonParams.iconColor}).addTo(map);
    marker = L.marker([lat, lon],{icon: L.AwesomeMarkers.icon({icon:'map-marker',markerColor:jsonParams.iconColor})}).addTo(map);
    
    // Fit bounds specified in viewport
    var southWest = L.latLng(geocodeItem.geometry.viewport.southwest.lat,
           geocodeItem.geometry.viewport.southwest.lng);
    var northEast = L.latLng(geocodeItem.geometry.viewport.northeast.lat,
           geocodeItem.geometry.viewport.northeast.lng);
  
    var bounds = L.latLngBounds(southWest, northEast);
  
    map.fitBounds(bounds);
  
    popupText = "<div class='popupGeocodeTitle'>" + jsonParams.name + "</div>"
        + "<b>formatted_address</b>="
        + geocodeItem.formatted_address
        + "<br/>"
        + "<b>geometry.location_type</b>=" 
        + geocodeItem.geometry.location_type
        + "<br/>"
        + "<b>types</b>=" 
        + geocodeItem.types[0]
        + "<br/>"
        + "<b>status</b>="
        + datas.status
    
    marker.bindPopup(popupText).openPopup();
    });
}

function geocodeIdeesLibres(jsonParams) {
  var dtStart = $.now();
  var addr = $("#adresse").val();
  var cpVille = $("#cpVille").val();
  
  jsonParams.addr = addr;
  jsonParams.cpVille = cpVille;

  $.getJSON('proxy/' + jsonParams.proxyPhp, jsonParams, function(datas) {

    checkGeocodeDatas(datas);
    
    var geocodeItem = datas.response.docs[0];
    // récupérer le premier élément
  
    var lon = geocodeItem.lng;
    var lat = geocodeItem.lat;
    
    // var myIcon = L.divIcon({iconSize:L.point(50, 50), className: 'my-div-icon', html: '<b>Nomin</b>'});
    marker = L.circle([lat, lon], 10,  { color: jsonParams.iconColor}).addTo(map);
    marker = L.marker([lat, lon],{icon: L.AwesomeMarkers.icon({icon:'map-marker',markerColor:jsonParams.iconColor})}).addTo(map);
    // marker = L.marker([lat, lon],{icon: myIcon}).addTo(map);
    // marker = L.circle([lat, lon], 5,  { color: 'red'}).addTo(map);
    
    var timelaps = ($.now() - dtStart) /3600;
    
    popupText = "<div class='popupGeocodeTitle'>IdeesLibres.org</div>"
        + "Numéro : "
        + geocodeItem.num
        + "<br/>"
        + "Rue : "
        + geocodeItem.rue.replace(/'/g, "\\'")
        + "<br/>"
        + "Commune : "
        + geocodeItem.commune
        + "<br/>"
        + "Code postal : "
        + geocodeItem.codepostal
        + "<br/>"
        + "Temps : "
        + timelaps.toString().substr(0,5);
        
    marker.bindPopup(popupText).openPopup();
    
    var myLayer = L.geoJson().addTo(map);
    
    map.setView([lat, lon], 16);
    });
}

function geocodeNominatim(jsonParams) {

    var addr = $("#adresse").val();
    var cpVille = $("#cpVille").val();
  
  jsonParams.addr = addr;
  jsonParams.cpVille = cpVille;

    $.getJSON('proxy/' + jsonParams.proxyPhp, jsonParams, function(datas) {

    checkGeocodeDatas(datas);
    
    var geocodeItem = datas[0];
    // récupérer le premier élément
  
    var lon = geocodeItem.lon;
    var lat = geocodeItem.lat;
    
    // var myIcon = L.divIcon({iconSize:L.point(50, 50), className: 'my-div-icon', html: '<b>Nomin</b>'});
    marker = L.circle([lat, lon], 10,  { color: jsonParams.iconColor}).addTo(map);
    marker = L.marker([lat, lon],{icon: L.AwesomeMarkers.icon({icon:'map-marker',markerColor:jsonParams.iconColor})}).addTo(map);
    // marker = L.marker([lat, lon],{icon: myIcon}).addTo(map);
    // marker = L.circle([lat, lon], 5,  { color: 'red'}).addTo(map);
    
    popupText = "<div class='popupGeocodeTitle'>" + jsonParams.name + "</div>"
        + "type="
        + geocodeItem.type
        + "<br/>"
        + "importance="
        + geocodeItem.importance
        + "<br/>"
        + "osm_type="
        + geocodeItem.osm_type
        + "<br/>"
        + "osm_id="
        + geocodeItem.osm_id
  
    marker.bindPopup(popupText).openPopup();
    
    map.setView([lat, lon]);
  
    var southWest = L.latLng(geocodeItem.boundingbox[0],
           geocodeItem.boundingbox[2]);
    var northEast = L.latLng(geocodeItem.boundingbox[1],
           geocodeItem.boundingbox[3]);
    var bounds = L.latLngBounds(southWest, northEast);
    var myLayer = L.geoJson().addTo(map);
  
    map.fitBounds(bounds);
    });
}
/**
 * Invoke and display geocode pin
 * For Photon geocoder
 * 
 * @param {} jsonParams
 * 
 */

function geocodePhoton(jsonParams) {
  var dtStart = $.now();    
  var addr = $("#adresse").val();
  var cpVille = $("#cpVille").val();
    
  jsonParams.addr = addr;
  jsonParams.cpVille = cpVille;

  $.getJSON('proxy/' + jsonParams.proxyPhp, jsonParams, function(datas) {
    checkGeocodeDatas(datas);
    
    var geocodeItem = datas.features[0];
  
    var lon = geocodeItem.geometry.coordinates[0];
    var lat = geocodeItem.geometry.coordinates[1];
  
    // var myIcon = L.divIcon({iconSize:L.point(50, 50), className: 'my-div-icon', html: '<b>Nomin</b>'});
    marker = L.circle([lat, lon], 10,  { color: jsonParams.iconColor}).addTo(map);
    marker = L.marker([lat, lon],{icon: L.AwesomeMarkers.icon({icon:'map-marker',markerColor:jsonParams.iconColor})}).addTo(map);
    // marker = L.marker([lat, lon],{icon: myIcon}).addTo(map);
    // marker = L.circle([lat, lon], 5,  { color: 'red'}).addTo(map);
    
    // Response looks like
    // {"type": "FeatureCollection", "features": [{"type": "Feature", "properties": {"osm_id": 333146, "postcode": "91580", "name": "\u00c9tr\u00e9chy", "country": "France", "osm_value": "administrative", "osm_key": "boundary"}, "geometry": {"type": "Point", "coordinates": [2.1909175, 48.4930895]}}]}
    
    var timelaps = ($.now() - dtStart) /3600;
    
    popupText = "<div class='popupGeocodeTitle'>" + jsonParams.name + "</div>"
        + "type="
        + geocodeItem.type
        + "<br/>"
        + "osm_value=" + geocodeItem.properties.osm_value + "<br/>" 
        + "osm_id=" + geocodeItem.properties.osm_id
        + "temps=" + timelaps.toString().substr(0,5) + "<br/>"   
  
    marker.bindPopup(popupText).openPopup();
    
    map.setView([lat, lon]);
    
    var myLayer = L.geoJson().addTo(map);
  
    map.fitBounds(bounds);
  });
}

function geocodeSocleRest(jsonParams) {
    var addr = $("#adresse").val();
    var cpVille = $("#cpVille").val();
  
  jsonParams.addr = addr;
  jsonParams.cpVille = cpVille;

    $.getJSON('proxy/' + jsonParams.proxyPhp, jsonParams, function(datas) {
    checkGeocodeDatas(datas);
    var address = datas.candidates[0];
  
    console.log(datas.candidates[0]);
  
    var lon = address.location.x;
    var lat = address.location.y;
    
    marker = L.circle([lat, lon], 10,  { color: jsonParams.iconColor}).addTo(map);
    marker = L.marker([lat, lon],{icon: L.AwesomeMarkers.icon({icon:'map-marker', markerColor:jsonParams.iconColor})}).addTo(map);
    // marker = L.marker([lat, lon]).addTo(map);
  
    popupText = "<div class='popupGeocodeTitle'>" + jsonParams.name + "</div>"
        + "<div class='popupGeocodeSection'>Premiere Adresse</div>"
        + "Addr_type="
        + address.attributes.Addr_type
        + "<br/>"
        + "Loc_name="
        + address.attributes.Loc_name
        + "<br/>"
        + "Postal="
        + address.attributes.Postal
        + "<br/>"
        + "User_fld="
        + address.attributes.User_fld
        + "<br/>"
        + "score="
        + address.score
        + "<br/>"           
        + "<div class='popupGeocodeSection'>Spatial référence</div>"
        + "wkid="
        + datas.spatialReference.wkid
        + "<br/>"
        + "latestWkid="
        + datas.spatialReference.latestWkid
  
    marker.bindPopup(popupText).openPopup();
  
    map.setView([lat, lon], 15);
    });
}

/**
 * 
 * 
 * @param {} jsonParams
 */

function geocodeJDONREF(jsonParams) {
  var dtStart = $.now();
    
    var typesGeocode = {
    1: "Plaque",
    2: "Interpolation à la plaque",
    3: "Interpolation métrique du troncon",
    4: "Centroide du troncon",
    5: "Centroide de la voie",
    6: "Commune (ou arrondissement)",
    7: "Département",
    8: "Pays"
    }
    
    var addr = $("#adresse").val();
    var cpVille = $("#cpVille").val();

  jsonParams.addr = addr;
  jsonParams.cpVille = cpVille;

    
    $.getJSON('proxy/' + jsonParams.proxyPhp, jsonParams, function(datas) {
    checkGeocodeDatas(datas);
  
    var lon = datas.geocode.propositions.x;
    var lat = datas.geocode.propositions.y;
    
    marker = L.circle([lat, lon], 10,  { color: jsonParams.iconColor}).addTo(map);
    marker = L.marker([lat, lon],{icon: L.AwesomeMarkers.icon({icon:'map-marker', markerColor:jsonParams.iconColor})}).addTo(map);
    
    valide_score = "";
    if(datas.valide_scores !== undefined) {
        valide_score = "<b>Scores</b> : " + datas.valide_scores + "<br />";
    }
    
    var timelaps = ($.now() - dtStart) /3600;
    
    popupText = "<div class='popupGeocodeTitle'>"+ jsonParams.name + "</div>"
        + "<div class='popupGeocodeSection'>Validation</div>"
        + "<b>Note</b>="
        + datas.valide.note
        + "<br/>"
        + "<b>Propositions</b>="
        + datas.total
        + "<br/>"
        + valide_score
        + "INSSE="
        + datas.valide.ids[5]
        + "<br/>"
        + "Service="
        + datas.valide.service
        + "<br/>"       
        + "<div style='background-color:#cccccc;font-weight:bold;'>Géocodage</div>"
        + "<b>type</b>=" + typesGeocode[datas.geocode.propositions.type] + " ["  + datas.geocode.propositions.type + "]<br/>"
        + "projection=" + datas.geocode.propositions.projection + "<br/>" 
        + "service=" + datas.geocode.propositions.service + "<br/>" 
        + "temps=" + timelaps.toString().substr(0,5) + "<br/>" 
  
    marker.bindPopup(popupText).openPopup();
  
    map.setView([lat, lon], 15);
    });
}

/*
 * Verify datas (after AJAX call) stop script in 2 cases
 */
function checkGeocodeDatas(datas) {
  if (datas.length == 0) {
      alert("Aucune réponse (datas.length==0)");
      return 0;
  }
  
  if(datas.error == 1) {
    alert(":: ERREUR ::\n\nEchec du géocodage, motif : " + datas.message);
    return 0;
  }
}
