<div class="gpress_places_meta_control">

	<?php
	
	global $post;
    
        $this_map_id = '_gpress_places';
	$meta = get_post_meta($post->ID,'_gpress_places',TRUE);
	
	$this_map_position = $meta['latlng'];
        $this_map_type = $meta['type'];
        $this_map_zoom = $meta['zoom'];
	
	if(empty($this_map_type)) {
		$this_map_type = get_site_option('gp_map_type','ROADMAP');
	}
	if(empty($this_map_zoom)) {
		$this_map_zoom = get_site_option('gp_map_zoom','13');
	}
    
    gpress_geoform($this_map_id, $this_map_type, $this_map_zoom, $this_map_position, false);
    
    ?>
 
</div>