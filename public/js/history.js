window.addEventListener("popstate", function(e) {
	var query = null;
	var queryVars = getUrlVars();
	if(queryVars.hasOwnProperty('q'))
		query = decodeURIComponent(queryVars.q);
	if(query && mapHack) {
		document.getElementById('queryBox').value = query;
		mapHack.searchFor(query);
	}
});

function setHistory(location) {
	history.pushState(null, null, location)
}

function getUrlVars() {
	var vars = [], hash;
	var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
	for(var i = 0; i < hashes.length; i++)
	{
		hash = hashes[i].split('=');
		vars.push(hash[0]);
		vars[hash[0]] = hash[1];
	}
	return vars;
}
