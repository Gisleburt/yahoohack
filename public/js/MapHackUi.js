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
		this.addModule(module.name, module.icon);
	}
}

MapHackUi.prototype.addModule = function(name, icon) {
	var text = name;
	var name = this.mapHack.parseModuleName(name);
	if(!this.getModule(name)) {
		var liNode = document.createElement("li");
		liNode.id = "module_"+name;
		var imageNode = document.createElement("img");
		imageNode.src = "/img/"+icon;
		var textNode = document.createTextNode(text);
		liNode.appendChild(imageNode);
		liNode.appendChild(textNode);
		document.getElementById(this.resultsName).appendChild(liNode);
	}
}

MapHackUi.prototype.getModule = function(name) {
	return document.getElementById("module_"+name);
}

MapHackUi.prototype.setResults =function(module_name) {
	module_name = this.mapHack.parseModuleName(module_name);
	this.clearResults(module_name);
	var ulNode = document.createElement("ul");
        ulNode.id = "module_"+module_name+"_results";

	for(var i in this.mapHack.results[module_name]) {
		var result = this.mapHack.results[module_name][i];
		var liNode = document.createElement("li");
		var textNode = document.createTextNode(result.name);
		if(result.thumbnail) {
			var imageNode = document.createElement("img");
                	imageNode.src = result.thumbnail;
			imageNode.class = "thumbnail";
			liNode.appendChild(imageNode);
		}
		liNode.appendChild(textNode);
		ulNode.appendChild(liNode);
		this.getModule(module_name).appendChild(ulNode);
		this.mapHack.addMarker(result.longitude, result.latitude);
	}
}

MapHackUi.prototype.clearResults = function(module_name) {
	var node = document.getElementById("module_"+module_name+"_results");
	if (node) 
		node.parentNode.removeChild(node);
	
}
