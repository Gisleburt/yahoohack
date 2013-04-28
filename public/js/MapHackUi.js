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

MapHackUi.prototype.setResults =function(module_name) {
	this.clearResults(module_name);
	var ulNode = document.createElement("ul");
        ulNode.id = "module_"+module_name+"_results";

	for(var i in this.mapHack.results[module_name]) {
		var result = this.mapHack.results[module_name][i];
		var liNode = document.createElement("li");
		var textNode = document.createTextNode(result.name);
		liNode.appendChild(textNode);
		ulNode.appendChild(liNode);
		this.getModule(module_name).appendChild(ulNode);
	}
	//console.log(results);
}

MapHackUi.prototype.clearResults = function(module_name) {
	var node = document.getElementById("module_"+module_name+"_results");
	if (node) 
		node.parentNode.removeChild(node);
	
}
