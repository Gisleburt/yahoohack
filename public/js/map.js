/**
 * MapHack class
 * @param config
 * @constructor
 */

MapHack = function(config) {

	this.map = null;
	this.mapName = 'map';
	this.queryName = 'query';

	this.setConfig(config);

}

MapHack.prototype.setConfig = function(config) {
	if(config.mapName) this.mapName = config.mapName;
	if(config.queryName) this.queryName = config.queryName;
}

MapHack.prototype.createMap = function () {

	this.map = new OpenLayers.Map(this.mapName);
	this.map.addLayer(new OpenLayers.Layer.OSM());

	var lonLat = new OpenLayers.LonLat( -0.1279688 ,51.5077286 )
		.transform(
			new OpenLayers.Projection("EPSG:4326"), // transform from WGS 1984
			this.map.getProjectionObject() // to Spherical Mercator Projection
		);

	var zoom=16;

	this.map.setCenter (lonLat, zoom);
}

MapHack.prototype.search = function () {
	var query = document.getElementById(this.queryName).value;
	setHistory("?q="+query);
	this.searchFor(query);
}

MapHack.prototype.searchFor = function(query) {
	var queryElement = document.getElementById(this.queryName);
	if(query && query != queryElement.value)
		queryElement.value = query;
}
