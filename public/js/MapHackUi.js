/**
 * MapHackUi class
 * @param config
 * @constructor
 */

MapHackUi = function(config) {

	this.mapName = 'map';
	this.queryName = 'query';
	this.resultsName = 'results';

	this.mapHack = null;

	this.setConfig(config);

}

/**
 * Set properties of this object (can not set properties beginning with _)
 * @param config
 */
MapHackUi.prototype.setConfig = function(config) {
	for(var property in config) {
		if(this.hasOwnProperty(property) && property.charAt(0) != '_')
			this[property] = config[property];
	}
}

MapHackUi.prototype.setModules = function() {
	for(var i in this.mapHack.modules) {
		var module = this.mapHack.modules[i];
		this.addModule(this.mapHack.parseModuleName(module.name), module.icon);
	}
}

MapHackUi.prototype.addModule = function(name, icon) {
	if(!this.getModule(name)) {
		var liNode = document.createElement("li");
		liNode.id = "module_"+name;
		var imageNode = document.createElement("img");
		imageNode.src = "/img/"+icon;
		var textNode = document.createTextNode(name);
		liNode.appendChild(imageNode);
		liNode.appendChild(textNode);
		document.getElementById(this.resultsName).appendChild(liNode);
	}
}

MapHackUi.prototype.getModule = function(name) {
	return document.getElementById("module_"+name);
}

MapHackUi.prototype.setResults =function(module_name, results) {
	console.log(module_name);
	console.log(results);
}