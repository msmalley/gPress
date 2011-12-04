<?php

global $post;
$post_id = $post->ID;
$post_type = $post->post_type;
if(empty($post_type)){ $post_type = 'post'; }
echo '<div id="gpress_meta_'.$post_type.'_'.$post_id.'_sidebar" class="gpress_meta sidebar">';
/* GET META */
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
$meta = gpress_get_markers($marker_get_args);
$this_map_height = $meta['map_height'];
$this_map_type = $meta['map_type'];
$this_map_zoom = $meta['map_zoom'];
if(empty($this_map_height)) {
    $this_map_height = get_site_option('gp_map_height','450');
} if(empty($this_map_type)) {
    $this_map_type = get_site_option('gp_map_type','ROADMAP');
} if(empty($this_map_zoom)) {
    $this_map_zoom = get_site_option('gp_map_zoom','13');
}
$gpress_map_id = '_gpress_meta';
include( GPRESS_DIR . '/gpress-core/meta/meta-sidebar-content.php');
echo '</div>';

?>