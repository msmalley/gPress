<?php

global $post;
$post_id = $post->ID;
$post_type = $post->post_type;
if(empty($post_type)){ $post_type = 'post'; }
echo '<div id="gpress_meta_'.$post_type.'_'.$post_id.'" class="gpress_meta">';

/* CHECK FOR WP LAT / LNG FROM MOBILE DEVICE */
$gpress_post_geo_enabled = get_post_meta($post_id,'geo_enabled',TRUE);
$gpress_post_lat = get_post_meta($post_id,'geo_latitude',TRUE);
$gpress_post_lng = get_post_meta($post_id,'geo_longitude',TRUE);
$empty_latlng = true;
if($gpress_post_geo_enabled==1){
    $empty_latlng = false;
    if(empty($gpress_post_lat)) {
        $empty_latlng = true;
    }else{
        if(empty($gpress_post_lng)) {
            $empty_latlng = true;
        }
    }
}if($empty_latlng){
    /* NOW CHECK FOR GPRESS */
    $marker_get_args = array(
        'meta_type'     => 'post',
        'meta_key'      => $post_type,
        'meta_id'       => $post_id,
        'polygon'       => false,
        'lat'           => false,
        'lng'           => false,
        'distance'      => false,
        'order_by'      => false,
        'add_content'   => false
    );
    $markers = gpress_get_markers($marker_get_args);
    if(is_array($markers)){
        $this_map_height = $markers[0]['map_height'];
        $this_map_type = $markers[0]['map_type'];
        $this_map_zoom = $markers[0]['map_zoom'];
        $gpress_post_lat = $markers[0]['lat'];
        $gpress_post_lng = $markers[0]['lng'];
    }else{
        $markers = array();
        $markers[0]['type'] = 'post';
        $markers[0]['key'] = $post_type;
        $markers[0]['id'] = 'drag_me';
    }
}else{
    /* USE WP METHOD INSTEAD */
    $markers = array();
    $markers[0]['type'] = 'post';
    $markers[0]['key'] = 'post';
    $markers[0]['id'] = $post_id;
    $markers[0]['lat'] = $gpress_post_lat;
    $markers[0]['lng'] = $gpress_post_lng;
    $markers[0]['title'] = __('Drag Me');
    $this_map_position = ''.$gpress_post_lat.', '.$gpress_post_lng.'';
} if(empty($this_map_height)) {
    $this_map_height = get_site_option('gp_map_height','450');
} if(empty($this_map_type)) {
    $this_map_type = get_site_option('gp_map_type','ROADMAP');
} if(empty($this_map_zoom)) {
    $this_map_zoom = get_site_option('gp_map_zoom','13');
}
$gpress_map_args = array(
    'map_id'        => '_gpress_meta',
    'map_height'    => $this_map_height,
    'map_type'      => $this_map_type,
    'map_zoom'      => $this_map_zoom,
    'map_lat'       => $gpress_post_lat,
    'map_lng'       => $gpress_post_lng,
    'is_geoform'    => true,
    'markers'       => $markers[0]
);
gpress_map($gpress_map_args);
echo '</div>';

?>