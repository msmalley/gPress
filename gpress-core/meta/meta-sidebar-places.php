<div class="gpress_places_meta_control">

    <?php 
	
	global $post;
	
	$gpress_map_id = '_gpress_places';
	$meta = get_post_meta($post->ID,'_gpress_places',TRUE);

	$this_map_type = $meta['type'];
	$this_map_zoom = $meta['zoom'];
	
	$default_map_height = get_site_option('gp_map_height','450');
	if(empty($this_map_type)) {
		$default_map_type = get_site_option('gp_map_type','ROADMAP');
		$meta['type'] = $default_map_type;
	}
	if(empty($this_map_zoom)) {
		$default_map_zoom = get_site_option('gp_map_zoom','13');;
		$meta['zoom'] = $default_map_zoom;
	}
	
	include( GPRESS_DIR . '/gpress-core/meta/meta-sidebar-content.php');
	
	?>
 
</div>