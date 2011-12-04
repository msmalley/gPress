<?php

$map_id = '_gpress_tinymce';
$this_height = '220';
$map_zoom = '13';
$map_type = 'ROADMAP';

?>

<style>
/* THESE MAP OPTION OVERWRITE DEFAULT SETTINGS */
#mapCanvas<?php echo $map_id; ?> {
	height:<?php echo $this_height; ?>px;
}
</style>

<script type="text/javascript">
	var GPRESS_DIR = '<?php echo GPRESS_DIR; ?>';
	var GPRESS_URL = '<?php echo GPRESS_URL; ?>';
</script>

<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
<script type="text/javascript" src="http://www.google.com/jsapi"></script>
<script type="text/javascript">
var geocoder<?php echo $map_id; ?>, map<?php echo $map_id; ?>, marker<?php echo $map_id; ?>;

function geocodePosition<?php echo $map_id; ?>(pos) {
  geocoder<?php echo $map_id; ?>.geocode({
	latLng: pos
  }, function(responses) {
	if (responses && responses.length > 0) {
	  updateMarkerAddress<?php echo $map_id; ?>(responses[0].formatted_address);
	} else {
	  updateMarkerAddress<?php echo $map_id; ?>('Cannot determine address at this location.');
	}
  });
}

function updateMarkerStatus<?php echo $map_id; ?>(str) {
  document.getElementById('markerStatus<?php echo $map_id; ?>').innerHTML = str;
}

function updateMarkerPosition<?php echo $map_id; ?>(latLng) {
  document.getElementById('map-position').value = [
	latLng.lat(),
	latLng.lng()
  ].join(', ');
}

function updateMarkerAddress<?php echo $map_id; ?>(str) {
  document.getElementById('address<?php echo $map_id; ?>').value = str;
}

function initialize<?php echo $map_id; ?>() {
	
	geocoder<?php echo $map_id; ?> = new google.maps.Geocoder();
  
	if(google.loader.ClientLocation){
		var latLng = new google.maps.LatLng(google.loader.ClientLocation.latitude, google.loader.ClientLocation.longitude);
	}else{
		var latLng = new google.maps.LatLng(0, 0);
	}

	map<?php echo $map_id; ?> = new google.maps.Map(document.getElementById('mapCanvas<?php echo $map_id; ?>'), {
		zoom: <?php echo $map_zoom ?>,
		center: latLng,
		mapTypeId: google.maps.MapTypeId.<?php echo $map_type ?>
	  });
	marker<?php echo $map_id; ?> = new google.maps.Marker({
		position: latLng,
		title: 'Drag Me',
		map: map<?php echo $map_id; ?>,
		draggable: true
	});
	// Update current position info.
	updateMarkerPosition<?php echo $map_id; ?>(latLng);
	geocodePosition<?php echo $map_id; ?>(latLng);
	
	// Add dragging event listeners.
	google.maps.event.addListener(marker<?php echo $map_id; ?>, 'dragstart', function() {
	updateMarkerAddress<?php echo $map_id; ?>('Dragging...');
	});
	
	google.maps.event.addListener(marker<?php echo $map_id; ?>, 'drag', function() {
	updateMarkerStatus<?php echo $map_id; ?>('Dragging...');
	updateMarkerPosition<?php echo $map_id; ?>(marker<?php echo $map_id; ?>.getPosition());
	});
	
	google.maps.event.addListener(marker<?php echo $map_id; ?>, 'dragend', function() {
	updateMarkerStatus<?php echo $map_id; ?>('Drag ended');
	geocodePosition<?php echo $map_id; ?>(marker<?php echo $map_id; ?>.getPosition());
	});
	
}

function getFormattedLocation<?php echo $map_id; ?>() {
  if (google.loader.ClientLocation.address.country_code == "US" &&
	google.loader.ClientLocation.address.region) {
	return google.loader.ClientLocation.address.city + ", " 
		+ google.loader.ClientLocation.address.region.toUpperCase();
  } else {
	return  google.loader.ClientLocation.address.city + ", "
		+ google.loader.ClientLocation.address.country_code;
  }
}
		
function codeAddress<?php echo $map_id; ?>() {
  var address = document.getElementById('search_address<?php echo $map_id; ?>').value;
  
	if (geocoder<?php echo $map_id; ?>) {
	  geocoder<?php echo $map_id; ?>.geocode( { 'address': address}, function(results, status) {
		if (status == google.maps.GeocoderStatus.OK) {
			map<?php echo $map_id; ?>.setCenter(results[0].geometry.location);
			marker<?php echo $map_id; ?>.setPosition(results[0].geometry.location);
			geocodePosition<?php echo $map_id; ?>(results[0].geometry.location);
			updateMarkerPosition<?php echo $map_id; ?>(results[0].geometry.location);
		} else {
			
			if (status == "ZERO_RESULTS") {
				alert("Sorry, but the address specified cannot be found...");
			} else if (status == "OVER_QUERY_LIMIT") {
				alert("Sorry, but you have exceeded your query limit quota...");
			} else if (status == "REQUEST_DENIED") {
				alert("Sorry, but for some reason, Google denied your request...");
			} else if (status == "INVALID_REQUEST") {
				alert("Sorry, but for some reason, something seems to have gone wrong...");
			} else {
				alert("Geocode was not successful for the following reason: " + status);
			}
				
		}
	  });
	}
}

jQuery(document).ready(function(){
	jQuery('#search_address<?php echo $map_id; ?>').keypress(function(event){
										if(event.keyCode == 13){
											codeAddress<?php echo $map_id; ?>();
											return false;
										}
									 });
});

google.maps.event.addDomListener(window, 'load', initialize<?php echo $map_id; ?>);

</script>

<div id="mapFrame<?php echo $map_id; ?>" class="map_frame gpress_mapframe">     
<div style="clear:both; float:left; width:100%; margin-bottom:15px;">  

	<div style="float:left; margin-bottom:20px; width:100%;" class="other_stuff<?php echo $map_id; ?> gpress_otherstuff">
		<input id="search_address<?php echo $map_id; ?>" name="search_address<?php echo $map_id; ?>" type="textbox" value="" class="search_input">
		<input type="button" value="SEARCH" onclick="codeAddress<?php echo $map_id; ?>();" class="search_button">
	</div>
		
	<div id="mapCanvas<?php echo $map_id; ?>" class="gpress_mapcanvas"></div>
			
	<div class="other_stuff<?php echo $map_id; ?> gpress_otherstuff" style="display:none">
		<input id="map-position" class="gpress_locationvalue gpress" type="textbox" name="position" style="width:100%; float:left; margin:25px 0 10px;">
	</div>
    
    <label for="marker-title">Marker Title:</label>
    <input type="text" id="marker-title" class="gpress" name="title" />
			
</div>
</div>

<div class="gpress_meta_boxes other_stuff<?php echo $map_id; ?> gpress_otherstuff" style="display:none !important;">
    <div id="infoPanel<?php echo $map_id; ?>" class="gpress_infopanel">
        <div id="leftColumn<?php echo $map_id; ?>" class="gpress_leftcolumn">
            <b>Closest address:</b>
            <textarea id="address<?php echo $map_id; ?>" class="gpress_address" name="<?php echo $map_id; ?>[address]" value="<?php if(!empty($meta['address'])) echo $meta['address']; ?>" class="closest_address"></textarea>
        </div>
        <div id="middleColumn<?php echo $map_id; ?>" class="gpress_middlecolumn">&nbsp;</div>
        <div id="rightColumn<?php echo $map_id; ?>" class="gpress_rightcolumn">
            <b>Marker status:</b>
            <div id="markerStatus<?php echo $map_id; ?>"><i>Click and drag the marker.</i></div>
        </div>
    </div>
</div>