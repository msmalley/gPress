<?php
 
function gpress_meta_normal_init() {
    wp_enqueue_style('gpress_meta_box_places_css', GPRESS_URL . '/gpress-core/css/meta.css');
    $places_taxonomy = __( 'place', 'gpress' );
    $taxonomy_array = array($places_taxonomy);
    $taxonomy_array = apply_filters('gpress_post_array',$taxonomy_array);
    foreach ($taxonomy_array as $type) {
            $id 	= 'gpress_normal';
            $title 	= apply_filters('gpress_post_array_title',__('Location', 'gpress'),$type);
            $callback	= 'gpress_meta_normal_content';
            $page 	= $type;
            $context 	= 'normal';
            $priority 	= 'high';
            add_meta_box( $id, $title, $callback, $page, $context, $priority );
    }
    add_action('save_post','gpress_meta_save');
    //$use_geopost = get_site_option('gp_module_posts','enabled');
}

function gpress_meta_sidebar_init() {
    $places_taxonomy = __( 'place', 'gpress' );
    $taxonomy_array = array($places_taxonomy);
    $taxonomy_array = apply_filters('gpress_post_array',$taxonomy_array);
    foreach ($taxonomy_array as $type) {
            $id 	= 'gpress_sidebar';
            $title 	= __('Map Options', 'gpress');
            $callback	= 'gpress_meta_sidebar_content';
            $page 	= $type;
            $context 	= 'side';
            $priority 	= 'high';
            add_meta_box( $id, $title, $callback, $page, $context, $priority );
    }
    add_action('save_post','gpress_meta_save');
    //$use_geopost = get_site_option('gp_module_posts','enabled');
}
 
function gpress_meta_normal_content() {
    include(GPRESS_DIR . '/gpress-core/meta/meta-normal.php');
    echo '<input type="hidden" name="gpress_meta_noncename_normal" value="' . wp_create_nonce(__FILE__) . '" />';
}

function gpress_meta_sidebar_content() {
    include(GPRESS_DIR . '/gpress-core/meta/meta-sidebar.php');
    echo '<input type="hidden" name="gpress_meta_noncename_sidebar" value="' . wp_create_nonce(__FILE__) . '" />';
}
 
function gpress_meta_save($post_id) {
    global $post;
    if(is_array($_POST['_gpress_meta'])){
        $post_id = $post->ID;
        $post_type = $post->post_type;
        $post_url = $post->guid;
        $post_title = $post->post_title;
        if(empty($post_type)){ $post_type = 'post'; }
        if( (!wp_verify_nonce($_POST['gpress_meta_noncename_normal'],__FILE__)) && (!wp_verify_nonce($_POST['gpress_meta_noncename_sidebar'],__FILE__)) ) return $post_id;
        if ($_POST['post_type'] == 'page') {
            if (!current_user_can('edit_page', $post_id)) return $post_id;
        }else{
            if (!current_user_can('edit_post', $post_id)) return $post_id;
        }
        // AUTHENTICATED - TIME TO SAVE DATA
        $new_data = $_POST['_gpress_meta'];
        $marker_get_args = array(
            'meta_type'     => 'post',
            'meta_key'      => $post_type,
            'meta_id'       => $post_id
        );
        $current_data = gpress_get_markers($marker_get_args);
        if(is_array($current_data)){
            $current_lat = $current_data[0]['lat'];
            $current_lng = $current_data[0]['lng'];
        }
        if((empty($current_lat))||(empty($current_lng))){
            if(!empty($new_data['lat'])){
                /* ADD META */
                $gpress_add_marker_args = array(
                    'meta_type'     => 'post',
                    'meta_key'      => $post_type,
                    'meta_id'       => $post_id,
                    'lat'           => $new_data['lat'],
                    'lng'           => $new_data['lng'],
                    'icon'          => $new_data['icon'],
                    'shadow'        => $new_data['shadow'],
                    'title'         => $post_title,
                    'url'           => $post_url,
                    'added'         => date('Y-m-d G:i:s'),
                    'validate'      => false
                );
                gpress_add_marker($gpress_add_marker_args);
            }
        }else{
            if((!empty($new_data['lat']))&&(!empty($new_data['lng']))){
                /* UPDATE META */
                $gpress_update_marker_args = array(
                    'meta_type'     => 'post',
                    'meta_key'      => $post_type,
                    'meta_id'       => $post_id,
                    'lat'           => $new_data['lat'],
                    'lng'           => $new_data['lng'],
                    'icon'          => $new_data['icon'],
                    'shadow'        => $new_data['shadow'],
                    'title'         => $post_title,
                    'url'           => $post_url
                );
                gpress_update_marker($gpress_update_marker_args);
            }
        }
        if($post_type=='post'){
            $gpress_post_lat = get_post_meta($post_id,'geo_latitude',TRUE);
            $gpress_post_lng = get_post_meta($post_id,'geo_longitude',TRUE);
            if((!empty($gpress_post_lat))||(!empty($gpress_post_lng))){
                update_post_meta($post_id,'geo_latitude',$new_data['lat']);
                update_post_meta($post_id,'geo_longitude',$new_data['lng']);
            }
        }
        return $post_id;
    }
}

?>