/**
 * MapHackModule class
 * @param config
 * @constructor
 */

MapHackModule = function(config) {

	this.name = 'location';
	this._numResults = 0;
	this._results = [];
	this.setConfig(config);

}

/**
 * Set properties of this object (can not set properties beginning with _)
 * @param config
 */
MapHackModule.prototype.setConfig = function(config) {
	for(var property in config) {
		if(this.hasOwnProperty(property) && property..charAt(0) != '_')
			this[property] = config[property];
	}
}

