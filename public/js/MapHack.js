/**
 * MapHack class
 * @param config
 * @constructor
 */

MapHack = function(config) {

	this.mapName = 'map';
	this.queryName = 'query';
	this.modules = null;

	this._map = null;
	this._searchUrl = '/search';

	this.longitude = -0.0;
	this.latitude = 51.477222;
	this.zoom = 10;

	this.setConfig(config);

}

/**
 * Set properties of this object (can not set properties beginning with _)
 * @param config
 */
MapHack.prototype.setConfig = function(config) {
	for(var property in config) {
		if(this.hasOwnProperty(property) && property.charAt(0) != '_')
			this[property] = config[property];
	}
}

/**
 * Create the map in the map element.
 * Set the map element in the constructor or set config
 */
MapHack.prototype.createMap = function () {

	this._map = new OpenLayers.Map(this.mapName);
	this._map.addLayer(new OpenLayers.Layer.OSM());

	this.setLocation({
		longitude: this.longitude,
		latitude: this.latitude,
		zoom: this.zoom
	});
/**/
}


/**
 * Prepare to search for something by updating the history
 */
MapHack.prototype.search = function () {
	var query = document.getElementById(this.queryName).value;
	setHistory("?q="+query);
	this.searchFor(query);
}

/**
 * Search for a term
 * @param query
 */
MapHack.prototype.searchFor = function(query) {
	var queryElement = document.getElementById(this.queryName);
	if(query && query != queryElement.value)
		queryElement.value = query;
	this.getLocation(query, 'location');
}

/**
 * Send the search to a given module
 * @param query
 * @param module
 */
MapHack.prototype.getLocation = function(query, module) {
	var url = this._searchUrl+"?q="+query+"&m="+module;
	$.ajax(url, {context:this}).done(this.setLocationAndQueryModules);
}

MapHack.prototype.queryModule = function(module) {
	var url = this._searchUrl+"?lat="+this.latitude+"&lon="+this.longitude+"&m="+module;
}

MapHack.prototype.setLocationAndQueryModules = function(locationData) {
	var location = this.parsePosition(locationData);
	if(location) {
		this.setLocation(location);
		this.modules;
	}
}

MapHack.prototype.queryModules = function() {
	for(i in this.modules) {

	}
}

MapHack.prototype.parsePosition = function(response) {
	if(response.hasOwnProperty('result')
			&& response.result.hasOwnProperty('coordinates')
			&& response.result.coordinates.longitude) {
		return {
			longitude: response.result.coordinates.longitude,
			latitude: response.result.coordinates.latitude,
			zoom: this.radiusToZoom(response.result.coordinates.radius)
		};
	}
}

MapHack.prototype.setLocation = function(location) {
	this.longitude = location.longitude;
	this.latitude  = location.latitude;
	this.zoom      = location.zoom;

	var lonLat = new OpenLayers.LonLat( this.longitude , this.latitude )
		.transform(
			new OpenLayers.Projection("EPSG:4326"), // transform from WGS 1984
			this._map.getProjectionObject() // to Spherical Mercator Projection
		);

	this._map.setCenter (lonLat, this.zoom);
}

MapHack.prototype.radiusToZoom = function(radius) {
	var width = document.getElementById(this.mapName).offsetWidth;
	var height = document.getElementById(this.mapName).offsetHeight;
	var size = width < height ? width : height;
	var metersPerPixel = (radius * 2) /size;

	if(metersPerPixel > 78206) return 0;
	if(metersPerPixel > 39103) return 1;
	if(metersPerPixel > 19551) return 2;
	if(metersPerPixel > 9776) return 3;
	if(metersPerPixel > 4888) return 4;
	if(metersPerPixel > 2444) return 5;
	if(metersPerPixel > 1222) return 6;
	if(metersPerPixel > 610.984) return 7;
	if(metersPerPixel > 305.492) return 8;
	if(metersPerPixel > 152.746) return 9;
	if(metersPerPixel > 76.373) return 10;
	if(metersPerPixel > 38.187) return 11;
	if(metersPerPixel > 19.093) return 12;
	if(metersPerPixel > 9.547) return 13;
	if(metersPerPixel > 4.773) return 14;
	if(metersPerPixel > 2.387) return 15;
	if(metersPerPixel > 1.193) return 16;
	if(metersPerPixel > 0.596) return 17;
}