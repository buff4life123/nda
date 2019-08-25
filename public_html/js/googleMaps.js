
function myMap() {
  var mapCanvas = document.getElementById("themap")
  var companyName = mapCanvas.dataset.companyName;

  var coords  =  mapCanvas.dataset.coords;
  var coordsCenter = coords.split(",");
  const lat = parseFloat(coordsCenter[0]);
  const lon = parseFloat(coordsCenter[1]);
  var myCenter = new google.maps.LatLng(lat, lon);

  var coords2  =  mapCanvas.dataset.coords2;
  var coordsSplit2 = coords2.split(",");
  const lat2 = parseFloat(coordsSplit2[0]);
  const lon2 = parseFloat(coordsSplit2[1]);
  

  
  var mapOptions = {
    center: myCenter,
    // scrollwheel: false,
    // draggable: false,    
    zoom: 16,
    // panControl: true,
    // zoomControl: true,
    // mapTypeControl: false,
    // scaleControl: false,
    // streetViewControl: false,
    // overviewMapControl: false,
    // rotateControl: false,
  }

  var map = new google.maps.Map(mapCanvas, mapOptions)

  // var marker = new google.maps.Marker({position:myCenter})

  // marker.setMap(map)

  // google.maps.event.addListener(marker,"click",function() {
  // map.setZoom(17)
  // map.setCenter(marker.getPosition())
  // })


  // var infowindow = new google.maps.InfoWindow({
    // content: companyName
  // })
  // infowindow.open(map,marker)
  
  //-----------------------------------------------------------
  
  var infowindow = new google.maps.InfoWindow({
	content: companyName
  });
  var markers = [[lat,lon], [lat2, lon2]];
  var marker, i;
  for (i = 0; i < markers.length; i++) {  
	marker = new google.maps.Marker({
	position: new google.maps.LatLng(markers[i][0], markers[i][1]),
	map: map
	});
	
	 google.maps.event.addListener(marker, 'click', (function(marker, i) {
        return function() {
          infowindow.setContent(markers[i][0]);
          infowindow.open(map, marker);
        }
      })(marker, i));
  
  }
  //-----------------------------------------------------------
}

myMap();


$( window ).on( "load", function() { 

  var images =  $( "#themap" ).find('img').attr("alt", "google maps api icon");
  // var allListElements = $( ".gm-fullscreen-control" );
  // console.log(allListElements);
})

