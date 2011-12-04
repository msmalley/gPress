<?php

// GEO-RSS
$use_georss = get_site_option('gp_module_rss','enabled');
if($use_georss == 'enabled') {
	// RSS 2
	add_action('rss2_ns', 'gpress_rss_ns');
	add_action('rss2_head', 'gpress_rss_header');
	add_action('rss2_item', 'gpress_rss_latlng'); 
	add_filter('the_excerpt_rss', 'gpress_rss_content'); 
	// RSS 1
	add_action('rss_head', 'gpress_rss_header');
	add_action('rss_item', 'gpress_rss_latlng'); 
	// RSS RDF
	add_action('rdf_ns', 'gpress_rss_ns');
	add_action('rdf_header', 'gpress_rss_header');
	add_action('rdf_item', 'gpress_rss_latlng'); 
	// RSS ATOM
	add_action('atom_ns', 'gpress_rss_ns');
	add_action('atom_head', 'gpress_rss_header');
	add_action('atom_entry', 'gpress_rss_latlng'); 
}

function gpress_rss_ns() {
	echo 'xmlns:geo="http://www.w3.org/2003/01/geo/wgs84_pos#" xmlns:georss="http://www.georss.org/georss"';
}
function gpress_rss_header() {
	global $wp;
	$places_taxonomy = __( 'place', 'gpress' );
	$new_query_vars = array_merge($wp->query_vars, array( 'post_type' => array('post', $places_taxonomy) ));
	query_posts( $new_query_vars );
}
function gpress_rss_content($output) {
	global $post;
	$places_taxonomy = __( 'place', 'gpress' );
	if($post->post_type == $places_taxonomy) {
		$meta = get_post_meta($post->ID,'_gpress_places',TRUE);
		$gpress_place_description = $meta['description'];
		$thumb_array = wp_get_attachment_image_src(get_post_thumbnail_id( $post->ID ), 'post-thumbnail' );
		$thumb_src = $thumb_array[0];
		$post_title = $post->post_title;
		$post_address = $meta['address'];
		$output_translation = sprintf(__('%s is located near %s', 'gpress'), $post_title, $post_address);
		$output = '<p>'.$output_translation.'</p>';
		if(!empty($thumb_src)) {
			$output .= '<p><img src="'.$thumb_src.'" /></p>';	
		}
		if(!empty($gpress_place_description)) {
			$output .= '<p><strong>'.__('Description:', 'gpress').'</strong> '.$meta['description'].'</p>';
		}
	}
	
	return $output;
}
function gpress_rss_latlng() {
	global $post;
	$places_taxonomy = __( 'place', 'gpress' );
	if($post->post_type == $places_taxonomy) {
		$meta = get_post_meta($post->ID,'_gpress_places',TRUE);
		$gpress_latlng = $meta['latlng'];
		if(empty($gpress_latlng)){
			$gpress_lat = "";
			$gpress_lng = "";
		}else{
			$gpress_latlng = explode(",",$gpress_latlng);
			$gpress_lat = trim($gpress_latlng[0]);
			$gpress_lng = trim($gpress_latlng[1]);
		}
		echo '<geo:lat>' . $gpress_lat . '</geo:lat>';
		echo '<geo:long>' . $gpress_lng . '</geo:long>';
		echo '<georss:point>' . $gpress_lat . ' ' . $gpress_lng . '</georss:point>';
	}elseif($post->post_type == 'post') {
		$geo_public = get_post_meta($post->ID,'geo_public',TRUE);
		$geo_latitude = get_post_meta($post->ID,'geo_latitude',TRUE);
		$geo_longitude = get_post_meta($post->ID,'geo_longitude',TRUE);
		$geo_latlng = ''.$geo_latitude.' '.$geo_longitude.'';
		if($geo_public == 1) {
			echo '<geo:lat>' . $geo_latitude . '</geo:lat>';
			echo '<geo:long>' . $geo_longitude . '</geo:long>';
			echo '<georss:point>' . $geo_latlng . '</georss:point>';
		}
	}
}

?>