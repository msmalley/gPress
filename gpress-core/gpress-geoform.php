<?php

function gpress_geoform($map_id, $map_type, $map_zoom, $map_position, $is_empty, $hide_inputs = false, $is_geo_settings = false) {
	
    global $post, $blog_id;

    $original_blog_id = $blog_id;
    if(is_multisite()) {
        switch_to_blog(1);
            $gpress_upload_url = get_bloginfo('url').'/wp-content/gpress/'.$original_blog_id;
        restore_current_blog();
    }else{
        $gpress_upload_url = get_bloginfo('url').'/wp-content/gpress/'.$original_blog_id;
    }

    $meta = get_post_meta($post->ID,$map_id,TRUE);
    $gpress_post_type = $post->post_type;
    $geo_latitude = get_post_meta($post->ID,'geo_latitude',TRUE);
    $geo_longitude = get_post_meta($post->ID,'geo_longitude',TRUE);
    $geo_latlng = ''.$geo_latitude.', '.$geo_longitude.'';

    $this_map_height = $meta['height'];
    $default_map_location_string = get_option('gp_map_location','San Fransisco');

    // gPress Post Markers
    $marker_posts_icon_url = get_site_option('gp_marker_url_posts');
    $marker_posts_shadow_url = get_site_option('gp_shadow_url_posts');
    $marker_posts_shadow_url = get_site_option('gp_shadow_url_posts');
    if(!empty($marker_posts_icon_url)) {
        $default_marker_icon_post = $marker_posts_icon_url;
    }else{
        if(!empty($marker_posts_icon_file)) {
            $default_marker_icon_post = $gpress_upload_url.'/'.$marker_posts_icon_file;
        } else {
            $default_marker_icon_post = GPRESS_URL.'/gpress-core/images/markers/post.png';
        }
    }
    if(!empty($marker_posts_shadow_url)) {
        $default_marker_shadow_post = $marker_posts_shadow_url;
    }else{
        if(!empty($marker_posts_shadow_file)) {
            $default_marker_shadow_post = $gpress_upload_url.'/'.$marker_posts_shadow_file;
        } else {
            $default_marker_shadow_post = GPRESS_URL.'/gpress-core/images/markers/bg.png';
        }
    }

    // gPress Place Markers
    $marker_places_icon_url = get_site_option('gp_marker_url_places');
    $marker_places_shadow_url = get_site_option('gp_shadow_url_places');
    if(!empty($marker_places_icon_url)) {
        $default_marker_icon_place = $marker_places_icon_url;
    }else{
        if(!empty($marker_places_icon_file)) {
            $default_marker_icon_place = $gpress_upload_url.'/'.$marker_places_icon_file;
        } else {
            $default_marker_icon_place = GPRESS_URL.'/gpress-core/images/markers/place.png';
        }
    }
    if(!empty($marker_places_shadow_url)) {
        $default_marker_shadow_place = $marker_places_shadow_url;
    }else{
        if(!empty($marker_places_shadow_file)) {
            $default_marker_shadow_place = $gpress_upload_url.'/'.$marker_places_shadow_file;
        } else {
            $default_marker_shadow_place = GPRESS_URL.'/gpress-core/images/markers/bg.png';
        }
    }

    // CHECK FOR CUSTOM AD-HOC MARKERS
    $adhoc_marker_icon_url = $meta['icon_url'];
    $adhoc_marker_shadow_url = $meta['shadow_url'];
    if(!empty($adhoc_marker_icon_url)) {
        $adhoc_marker_icon = $adhoc_marker_icon_url;
    }else{
        if(!empty($adhoc_marker_icon_file)) {
            $adhoc_marker_icon = $gpress_upload_url.'/'.$adhoc_marker_icon_file;
        }
    }
    if(!empty($adhoc_marker_shadow_url)) {
        $adhoc_marker_shadow = $adhoc_marker_shadow_url;
    }else{
        if(!empty($adhoc_marker_shadow_file)) {
            $adhoc_marker_shadow = $gpress_upload_url.'/'.$adhoc_marker_shadow_file;
        }
    }

    // Define which Markers to use
    if($gpress_post_type == 'post') {
        $default_marker_icon = $default_marker_icon_post;
        $default_marker_shadow = $default_marker_shadow_post;
    }else{
        $default_marker_icon = $default_marker_icon_place;
        $default_marker_shadow = $default_marker_shadow_place;
    }
    if(!empty($adhoc_marker_icon)) {
        $this_marker_icon = $adhoc_marker_icon;
    }else{
        $this_marker_icon = $default_marker_icon;
    }
    if(!empty($adhoc_marker_shadow)) {
        $this_marker_shadow = $adhoc_marker_shadow;
    }else{
        $this_marker_shadow = $default_marker_shadow;
    }
    // END OF MARKERS

    $default_map_height = get_site_option('gp_map_height','450');
    if(empty($this_map_height)) {
        $this_height = $default_map_height;
    }else{
        $this_height = $this_map_height;
    }

    if($is_geo_settings) {
        $gpress_post_type = 'bp_geo_settings';
    }

    ?>
    
    <style>
    /* THESE MAP OPTION OVERWRITE DEFAULT SETTINGS */
    #mapCanvas<?php echo $map_id; ?> {
            height:<?php echo $this_height; ?>px;
    }
    .gpress_otherstuff,
    .gpress_otherstuff span.gpress-label {
            clear:both;
            display:block;
            width:100%;
            margin:5px 0 15px;
    }
    </style>

    <script type="text/javascript">
            var GPRESS_DIR = '<?php echo GPRESS_DIR; ?>';
            var GPRESS_URL = '<?php echo GPRESS_URL; ?>';
    </script>

    <script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
    <script type="text/javascript" src="http://code.google.com/apis/gears/gears_init.js"></script>
    <script type="text/javascript" src="http://www.google.com/jsapi"></script>
    <script type="text/javascript">
    var map<?php echo $map_id; ?>, marker<?php echo $map_id; ?>;
    var geocoder<?php echo $map_id; ?> = new google.maps.Geocoder();

    function geocodePosition<?php echo $map_id; ?>(pos) {
        geocoder<?php echo $map_id; ?>.geocode({
            latLng: pos
        }, function(responses) {
            if (responses && responses.length > 0) {
                updateMarkerAddress<?php echo $map_id; ?>(responses[0].formatted_address);
            }else{
                updateMarkerAddress<?php echo $map_id; ?>('Cannot determine address at this location.');
            }
        });
    }
        
    function updateMarkerStatus<?php echo $map_id; ?>(str) {
      document.getElementById('markerStatus<?php echo $map_id; ?>').innerHTML = str;
    }

    function updateMarkerPosition<?php echo $map_id; ?>(latLng) {
      document.getElementById('location_value<?php echo $map_id; ?>').value = [
        latLng.lat(),
        latLng.lng()
      ].join(', ');
            }

    function updateMarkerAddress<?php echo $map_id; ?>(str) {
      document.getElementById('address<?php echo $map_id; ?>').value = str;
    }

    function gpress_get_coordinates_from_address<?php echo $map_id; ?>(address,this_function){
        if(address){
            if (geocoder<?php echo $map_id; ?>) {
                geocoder<?php echo $map_id; ?>.geocode( { 'address': address}, function(results, status) {
                    if (status == google.maps.GeocoderStatus.OK) {
                        newLatLng = ''+results[0].geometry.location.za+','+results[0].geometry.location.Ba+'';
                        this_function(newLatLng);
                    }
                });
            }
        }
    }
      
    function initialize<?php echo $map_id; ?>() {
        <?php
        global $current_user;
        get_currentuserinfo();
        $user_id = $current_user->ID;
        $user_position = get_user_meta( $user_id, 'gp_user_location', true);
        if(!empty($user_position)){
        ?>
            var default_coordinates = '<?php echo $user_position; ?>';
        <?php }else { ?>
            var default_coordinates = '37.766505,-122.181015';
        <?php } ?>
        var default_location_text = '<?php echo $default_map_location_string; ?>';
        var default_location_array = default_coordinates.split(',');
        var default_lat = default_location_array[0];
        var default_long = default_location_array[1];
        var default_location = new google.maps.LatLng(default_lat,default_long);
        <?php if(($gpress_post_type == 'post')&&(!empty($geo_latlng))) { ?>
            var this_location = new google.maps.LatLng(<?php echo $geo_latlng; ?>);
        <?php } else { ?>
            <?php if(($is_geo_settings)&&(!empty($map_position))) { ?>
                var this_location = new google.maps.LatLng(<?php echo $map_position; ?>);
            <?php } else { ?>
                <?php if(!empty($meta['latlng'])) { ?>
                    var this_location = new google.maps.LatLng(<?php echo $meta['latlng']; ?>);
                <?php } else { ?>
                    var this_location = false;
                <?php } ?>
            <?php } ?>
        <?php } ?>
        if(this_location){
            var latLng = this_location;
            var use_geocodcer = false;
        }else{
            var latLng = default_location;
            var use_geocoder = true;
        }

        var image<?php echo $map_id; ?> = new google.maps.MarkerImage('<?php echo $this_marker_icon; ?>',
            new google.maps.Size(30, 30),
            new google.maps.Point(0, 0),
            new google.maps.Point(21, 22));

        var shadow<?php echo $map_id; ?> = new google.maps.MarkerImage('<?php echo $this_marker_shadow; ?>',
            new google.maps.Size(40, 40),
            new google.maps.Point(0, 0),
            new google.maps.Point(26, 27));

        map<?php echo $map_id; ?> = new google.maps.Map(document.getElementById('mapCanvas<?php echo $map_id; ?>'), {
            zoom: <?php echo $map_zoom ?>,
            center: latLng,
            mapTypeId: google.maps.MapTypeId.<?php echo $map_type ?>
        });

        /* COLLECT GEO-LOCATION OF USER */
        /* FIRST TRY W3C METHOD */
        if(use_geocoder){
            if(navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    latLng = new google.maps.LatLng(position.coords.latitude,position.coords.longitude);
                    map<?php echo $map_id; ?>.setCenter(latLng);
                    marker<?php echo $map_id; ?>.setPosition(latLng);
                    /* NEED TO UPDATE INPUTS */
                    codeAddress<?php echo $map_id; ?>(''+position.coords.latitude+','+position.coords.longitude+'');
                }, function() {
                    /* ELSE USE DEFAULT */
                    if(default_location_text){
                        newCoordinates = gpress_get_coordinates_from_address<?php echo $map_id; ?>(default_location_text,function(newLatLng){
                            codeAddress<?php echo $map_id; ?>(default_location_text);
                            latLng = new google.maps.LatLng(newCoordinates);
                            map<?php echo $map_id; ?>.setCenter(latLng);
                        });

                    }else{
                        map<?php echo $map_id; ?>.setCenter(default_location);
                        latLng = default_location;
                    }
                });
            } else if (google.gears) {
                /* THEN TRY GOOGLE GEARS METHOD */
                var geo = google.gears.factory.create('beta.geolocation');
                geo.getCurrentPosition(function(position) {
                    latLng = new google.maps.LatLng(position.latitude,position.longitude);
                    map<?php echo $map_id; ?>.setCenter(latLng);
                    marker<?php echo $map_id; ?>.setPosition(latLng);
                    /* NEED TO UPDATE INPUTS */
                    codeAddress<?php echo $map_id; ?>(''+position.latitude+','+position.longitude+'');
                }, function() {
                    /* ELSE USE DEFAULT */
                    map<?php echo $map_id; ?>.setCenter(default_location);
                    latLng = default_location;
                });
            } else {
                /* ELSE USE DEFAULT */
                map<?php echo $map_id; ?>.setCenter(default_location);
                latLng = default_location;
                if(!default_location_text){
                    codeAddress<?php echo $map_id; ?>(default_location_text);
                }
            }
        }else{
            map<?php echo $map_id; ?>.setCenter(latLng);
        }

        marker<?php echo $map_id; ?> = new google.maps.Marker({
            <?php if(!empty($this_marker_icon)) { ?>
                icon: image<?php echo $map_id; ?>,
            <?php } if(!empty($this_marker_shadow)) { ?>
                shadow: shadow<?php echo $map_id; ?>,
            <?php } ?>
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

    function codeAddress<?php echo $map_id; ?>(use_specific_value) {
        if(!use_specific_value){
            var address = document.getElementById('search_address<?php echo $map_id; ?>').value;
        }else{
            var address = use_specific_value;
        }

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
        
        <div class="other_stuff<?php echo $map_id; ?> gpress_otherstuff">
        	<table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td width="100%">
                    	<div class="gpress_input_wrapper"><input id="search_address<?php echo $map_id; ?>" name="search_address<?php echo $map_id; ?>" type="textbox" value=""></div>
                    </td>
                    <td width="110px">
			            <input type="button" value="<?php _e('Search', 'gpress'); ?>" onclick="codeAddress<?php echo $map_id; ?>();">
                    </td>
                </tr>
            </table>

        </div>
            
       <div class="gpress_map_frame"><div id="mapCanvas<?php echo $map_id; ?>" class="gpress_mapcanvas"></div></div>
                
        <div class="other_stuff<?php echo $map_id; ?> gpress_otherstuff">
            
            <?php if($is_geo_settings) { ?>
                <input id="location_value<?php echo $map_id; ?>" class="gpress_locationvalue" type="hidden" name="geo_settings_latlng" value="<?php if(!empty($meta['latlng'])) echo $meta['latlng']; ?>" style="<?php if($hide_inputs) { echo 'display:none;'; } ?>">
            <?php } else { ?>            
                <input id="location_value<?php echo $map_id; ?>" class="gpress_locationvalue" type="hidden" name="<?php echo $map_id; ?>[latlng]" value="<?php if(!empty($meta['latlng'])) echo $meta['latlng']; ?>" style="<?php if($hide_inputs) { echo 'display:none;'; } ?>">
            <?php } ?>
            
        </div>
            
	</div>
	
	<div class="gpress_meta_boxes other_stuff<?php echo $map_id; ?> gpress_otherstuff">
		<div id="infoPanel<?php echo $map_id; ?>" class="gpress_infopanel">
			<div id="leftColumn<?php echo $map_id; ?>" class="gpress_leftcolumn">
				<span class="gpress-label"><b><?php echo __('Closest address:', 'gpress'); ?></b></span>
                
                <?php if($is_geo_settings) { ?>
					<textarea id="address<?php echo $map_id; ?>" class="gpress_address" name="geo_settings_closest_address" value="<?php if(!empty($meta['address'])) echo $meta['address']; ?>" class="closest_address"></textarea>                
                <?php } else { ?>
					<textarea id="address<?php echo $map_id; ?>" class="gpress_address" name="<?php echo $map_id; ?>[address]" value="<?php if(!empty($meta['address'])) echo $meta['address']; ?>" class="closest_address"></textarea>
                <?php } ?>
                
			</div>
			<div id="middleColumn<?php echo $map_id; ?>" class="gpress_middlecolumn">&nbsp;</div>
			<div id="rightColumn<?php echo $map_id; ?>" class="gpress_rightcolumn" style="<?php if($hide_inputs) { echo 'display:none;'; } ?>">
				<b><?php echo __('Marker status:', 'gpress'); ?></b>
				<div id="markerStatus<?php echo $map_id; ?>"><i><?php echo __('Click and drag the marker.', 'gpress'); ?></i></div>
			</div>
		</div>
	</div>
    
    <?php
	
}

?>