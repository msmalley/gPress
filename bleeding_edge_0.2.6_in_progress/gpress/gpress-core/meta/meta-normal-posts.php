<div class="gpress_places_meta_control">

	<?php
    
	global $post;
	
        $this_map_id = '_gpress_posts';
	$meta = get_post_meta($post->ID,'_gpress_posts',TRUE);
	
	$gpress_post_lat = get_post_meta($post->ID,'geo_latitude',TRUE);
	$gpress_post_lng = get_post_meta($post->ID,'geo_longitude',TRUE);
	
	if(empty($gpress_post_lat)) {
		$empty_latlng = true;
	}else{
		if(empty($gpress_post_lng)) {
			$empty_latlng = true;	
		}
	}
	
	$this_map_position = ''.$gpress_post_lat.', '.$gpress_post_lng.'';
	
        $gpress_meta = get_post_meta($post->ID,'_gpress_posts',TRUE);
	$this_map_type = $gpress_meta['type'];
        $this_map_zoom = $gpress_meta['zoom'];
	
	if(empty($this_map_type)) {
		$this_map_type = get_site_option('gp_map_type','ROADMAP');
	}
	if(empty($this_map_zoom)) {
		$this_map_zoom = get_site_option('gp_map_zoom','13');
	}
    
        //gpress_geoform($this_map_id, $this_map_type, $this_map_zoom, $this_map_position, $empty_latlng);
        $marker_get_args = array(
            'meta_type'     => 'post',
            'meta_key'      => 'event',
            'meta_id'       => false,
            'polygon'       => false,
            'lat'           => false,
            'lng'           => false,
            'distance'      => false,
            'order_by'      => false,
            'add_content'   => true
        );
        $markers = gpress_get_markers($marker_get_args);
        $gpress_map_args = array(
            'map_id'        => '_gpress_meta',
            'is_geoform'    => true,
            'markers'       => $markers
        );
        gpress_map($gpress_map_args); exit;
    
    ?>
 
</div>