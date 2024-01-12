

	// geolocation:
	
	function do_geolocate() {

		$.geolocation.get({win: storeMyPosition, fail: noLocation});		
		
	}
	function storeMyPosition(position) {
		// alert("Your position is " + position.coords.latitude + ", " + position.coords.longitude);
		if ($('#map1')) load_map('map1', position.coords.latitude, position.coords.longitude, 12);
		goexe("home","geo_save",position.coords.latitude,position.coords.longitude);
	}
	function noLocation(error) {
		alert("Geolocalizzazione automatica non disponibile (exit code: " + error.code + ")");
	}

//...................................

// leaflet-js maps api :


function load_map (div_name, lat, lon, zum) {

	mp = L.map(div_name).setView([lat, lon], zum);
    
	mp.attributionControl.setPrefix('');

	mapLink = '<a href="https://openstreetmap.org" target="_new" >OpenStreetMap</a>';
	
	L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; ' + mapLink + ' Contributors',
            maxZoom: 17,
		}).addTo(mp);

	return mp;
}

function add_marker(map_object, lat, lon, testo, mIcon, mColor, mShape, options=false) {
	// es: add_marker(map1, 45.410, 9.000, "", "fa-coffee","green","star");
	
	var markerIcon = L.ExtraMarkers.icon({
		icon: mIcon,
		markerColor: mColor,
		shape: mShape,
		prefix: 'fa'
	});

	/*
	if (testo) mk = new L.marker([lat, lon], {icon: markerIcon}).bindPopup(testo).addTo(map_object);
	else mk = new L.marker([lat, lon], {icon: markerIcon}).addTo(map_object);
	*/

	def_options = {icon: markerIcon};

	if (options) opt = Object.assign(def_options, options);
	else opt = Object.assign(def_options);

	if (testo) mk = new L.marker([lat, lon], opt).bindPopup(testo).addTo(map_object);
	else mk = new L.marker([lat, lon], opt).addTo(map_object);

	return mk;
}	


// end