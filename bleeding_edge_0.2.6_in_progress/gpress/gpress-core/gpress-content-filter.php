<?php

function gpress_content_filter($content) {

	global $post;
	$add_map = false;
	$places_taxonomy = __( 'place', 'gpress' );
	$original_content = $content;
	
	// Existing Post Info (if editing post)
	$gpress_post_type = $post->post_type;
	$gpress_post_id = $post->ID;
	$gpress_post_title = $post->post_title;

	// gPress Options
        $credits_for_posts = get_site_option('gp_homepage_loop','enabled');
        $credits_for_places = get_site_option('gp_homepage_loop_method','enabled');
        $default_map_height = get_site_option('gp_map_height');
        $default_map_type = get_site_option('gp_map_type');
        $default_map_zoom = get_site_option('gp_map_zoom');
        $remove_from_content = get_site_option('gp_remove_maps_content','no');
        $page_id_submit = get_site_option('gp_page_id_submit');

        if($gpress_post_id==$page_id_submit){
            /* INSERT FRONT-END PLACE SUBMISSION */
            /* WILL ADD CUSTOM FIELD OPTIONS TO CONTROL VARS LATER */
            $gpress_post_type = 'place';
            $tax_or_cat = 'tax';
            $gpress_tax_type = 'places';
            $gpress_tag_type = 'placed';
            $gpress_post_status = 'publish';
            $gpress_must_be_logged_in = true;
            $gpress_no_map_message = true;
            $required_user_level = 0;
            ob_start();
            gpress_place_submit($gpress_post_type, $tax_or_cat, $gpress_tax_type, $gpress_tag_type, $gpress_post_status, $gpress_must_be_logged_in, $gpress_no_map_message, $required_user_level);
            $content = ob_get_clean();
            return $content;
        }else{
            /* RUN STANDARD GPRESS CONTENT FILTERS */
            // Default Settings
            $gpress_default_map_height = '450';
            if(!empty($default_map_height)) {
                    $gpress_default_map_height = $default_map_height;
            }
            $gpress_default_map_type = 'ROADMAP';
            if(!empty($default_map_type)) {
                    $gpress_default_map_type = $default_map_type;
            }
            $gpress_default_map_zoom = '13';
            if(!empty($default_map_zoom)) {
                    $gpress_default_map_zoom = $default_map_zoom;
            }

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
                    if(is_single()) {
                            $gpress_the_content = $content;
                    }else{
                            $original_excerpt = apply_filters('gpress_infowindow_excerpt',gpress_trim_me($post->post_content, '155', ' [...] - <a href="'.$post->guid.'">'.__('Continue Reading', 'gpress').'</a>'),$post->ID);
                            if (post_password_required($post)) {
                                    $gpress_the_content = __('There is no excerpt because this is a protected post.', 'gpress');
                            }else{
                                    if(empty($original_excerpt)) {
                                            $gpress_the_content = gpress_trim_me($post->post_content, '155', ' [...] - <a href="'.$post->guid.'">'.__('Continue Reading', 'gpress').'</a>');
                                    }else{
                                            $gpress_the_content = $original_excerpt;
                                    }
                            }
                    }

                    // Filter the content / excerpt
                    $gpress_the_content = apply_filters('gpress_infowindow_content', $gpress_the_content, $post->ID);

                    // Map Settings for Places
                    $map_settings = array(
                            'map_id' 				=> $gpress_map_id,
                            'map_height' 			=> $gpress_map_height,
                            'map_type' 				=> $gpress_map_type,
                            'map_zoom' 				=> $gpress_map_zoom,
                            'map_position' 			=> $gpress_map_position,
                            'post_type' 			=> $gpress_post_type,
                            'post_id' 				=> $gpress_post_id,
                            'widget_id' 			=> false,
                            'place_id' 				=> $gpress_post_id,
                            'marker_icon' 			=> $gpress_icon_url,
                            'marker_shadow' 		=> $gpress_shadow_url,
                            'marker_title' 			=> $gpress_post_title,
                            'marker_url' 			=> false,
                            'description'			=> $gpress_the_content
                    );

            }

            $show_map = true;

            if((!is_single()) && (!is_page())) {
                    if($remove_from_content == 'yes') {
                            $show_map = false;
                    }
            }

            if(is_feed()) {
                    $show_map = false;
            };

            if($show_map) {
                    // DISPLAY MAP AND CREDITS
                    if($gpress_post_type == $places_taxonomy) {
                            ob_start();
                            //gpress_add_map($map_settings);
                            $marker_get_args = array(
                                'meta_type'     => 'post',
                                'meta_key'      => $post->post_type,
                                'meta_id'       => $post->ID,
                                'add_content'   => true
                            ); $marker = gpress_get_markers($marker_get_args);
                            $map_args1 = array(
                                'map_id'            => $post->post_type.'_'.$post->ID,
                                'map_zoom'          => $marker[0]['map_zoom'],
                                'map_height'        => $marker[0]['map_height'],
                                'map_type'          => $marker[0]['map_type'],
                                'markers'           => $marker,
                                'use_infowindows'   => true
                            );
                            $map_args = array(
                                'map_id'            => $post->post_type.'_'.$post->ID,
                                'map_zoom'          => 13,
                                'map_height'        => 450,
                                'map_type'          => 'ROADMAP',
                                'markers'           => $marker,
                                'use_infowindows'   => true
                            ); gpress_map($map_args);
                            $content = ob_get_clean();
                            $content = apply_filters('gpress_content',$content,$post->ID);
                            return $content;
                    }elseif($gpress_post_type == 'post') {
                            if($geo_public == 1) {
                                    ob_start();
                                    echo $content;
                                    gpress_add_map($map_settings);
                                    $content = ob_get_clean();
                                    $content = apply_filters('gpress_content',$content,$post->ID);
                                    return $content;
                            }else{
                                    $content = apply_filters('gpress_content',$content,$post->ID);
                                    return $content;
                            }
                    }else{
                            $content = apply_filters('gpress_content',$content,$post->ID);
                            return $content;
                    }

            } else {
                    $content = apply_filters('gpress_content',$content,$post->ID);
                    return $content;
            }
        }
}

?>