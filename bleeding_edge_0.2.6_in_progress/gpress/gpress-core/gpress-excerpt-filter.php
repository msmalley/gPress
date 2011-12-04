<?php

function gpress_excerpt_filter($content) {
	
	global $post;
	$add_map = false;
	$places_taxonomy = __( 'place', 'gpress' );
	// Existing Post Info (if editing post)
	$gpress_post_type = $post->post_type;
	$gpress_post_id = $post->ID;
	$gpress_post_title = $post->post_title;	
	$gpress_content = $post->post_content;
	$gpress_excerpt = $post->post_excerpt;
	if(empty($gpress_excerpt)) {
		$this_content = gpress_trim_me($gpress_content, '155', '<a href="'.$post->guid.'" title="'.$gpress_post_title.'">[...]</a>');
	}else{
		$this_content = $gpress_excerpt;
	}

	// gPress Options
        $default_map_height = get_site_option('gp_map_height','450');
        $default_map_type = get_site_option('gp_map_type','ROADMAP');
        $default_map_zoom = get_site_option('gp_map_zoom','13');
	
	// POSTS
	if($gpress_post_type == 'post') {
		$gpress_map_id = '_gpress_posts';
		$geo_public = get_post_meta($gpress_post_id,'geo_public',TRUE);
		$geo_latitude = get_post_meta($gpress_post_id,'geo_latitude',TRUE);
		$geo_longitude = get_post_meta($gpress_post_id,'geo_longitude',TRUE);
		$gpress_map_position = ''.$geo_latitude.', '.$geo_longitude.'';
		if($geo_public == 1) {
			$add_map = true;
		}
	}
	
	// PLACES
	if($gpress_post_type == $places_taxonomy) {
		$gpress_map_id = '_gpress_places';
		$meta = get_post_meta($gpress_post_id,$gpress_map_id,TRUE);
		$gpress_map_position = $meta['latlng'];
		$gpress_map_type = $meta['type'];
		$gpress_map_zoom = $meta['zoom'];
		$add_map = true;
	}
	
	// Final check for empty fields...
	if(empty($gpress_map_type)) {
		$gpress_map_type = $gpress_default_map_type;
	}
	if(empty($gpress_map_zoom)) {
		$gpress_map_zoom = $gpress_default_map_zoom;
	}
	
	// ADD MAP
	if($add_map) {
		
		// Map Settings for Places
		$map_settings = array(
			'map_id' 		=> $gpress_map_id,
			'map_height' 	=> $gpress_map_height,
			'map_type' 		=> $gpress_map_type,
			'map_zoom' 		=> $gpress_map_zoom,
			'map_position' 	=> $gpress_map_position,
			'post_type' 	=> $gpress_post_type,
			'post_id' 		=> $gpress_post_id,
			'widget_id' 	=> false,
			'place_id' 		=> $gpress_post_id,
			'marker_icon' 	=> $gpress_icon_url,
			'marker_shadow' => $gpress_shadow_url,
			'marker_title' 	=> $gpress_post_title,
			'marker_url' 	=> false,
			'description' 	=> $this_content
		);
		
	}	
	
	$show_map = true;
	
	if($show_map) {
	
		// DISPLAY MAP AND CREDITS
		if($gpress_post_type == $places_taxonomy) {
			ob_start();
			gpress_add_map($map_settings);
			$content = ob_get_clean();
			return $content;
		}elseif($gpress_post_type == 'post') {
			if($geo_public == 1) {
				ob_start();
				echo $content;
				gpress_add_map($map_settings);
				$content = ob_get_clean();
				return $content;
			}else{
				return $content;
			}
		}else{
			return $content;	
		}
	
	} else {
		return $content;
	}
}

?>