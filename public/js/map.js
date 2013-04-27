
function createMap(map, mapElementName) {
	map = new OpenLayers.Map(mapElementName);
	map.addLayer(new OpenLayers.Layer.OSM());

	var lonLat = new OpenLayers.LonLat( -0.1279688 ,51.5077286 )
		.transform(
			new OpenLayers.Projection("EPSG:4326"), // transform from WGS 1984
			map.getProjectionObject() // to Spherical Mercator Projection
		);

	var zoom=16;

	//var markers = new OpenLayers.Layer.Markers( "Markers" );
	//map.addLayer(markers);

	//markers.addMarker(new OpenLayers.Marker(lonLat));

	map.setCenter (lonLat, zoom);
}

function overlayElement(map, element) {
	map.addLayer()
}

function search(element) {
	var query = element.query.value;
	setHistory("?q="+query);
}

function setHistory(location) {
	console.log(location);
	history.pushState(null, null, location)
}