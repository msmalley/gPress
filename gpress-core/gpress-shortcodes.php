<?php

function gpress_shortcode($map_settings, $content = null) {
    global $blog_id;
    $places_taxonomy = __( 'place', 'gpress' );
    $original_blog_id = $blog_id;
    if(is_multisite()) {
        switch_to_blog(1);
            $gpress_upload_url = get_bloginfo('url').'/wp-content/gpress/'.$original_blog_id;
        restore_current_blog();
    }else{
        $gpress_upload_url = get_bloginfo('url').'/wp-content/gpress/'.$original_blog_id;
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
    // gPress Post Markers
    $marker_posts_icon_url = get_site_option('gp_marker_url_posts');
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
    if(is_array($map_settings)) {
        // Default Shortcode Map Settings
        $map_settings_default = array(
            'map_id'                => '_shortcode',
            'marker_description'    => $content,
            'post_type'             => 'shortcode'
        );
        $map_settings = array_merge($map_settings_default,$map_settings);
        extract($map_settings);
        // gPress User Markers
        $marker_users_icon_url = get_site_option('gp_marker_url_users');
        $marker_users_shadow_url = get_site_option('gp_shadow_url_users');
	if(!empty($marker_users_icon_url)) {
		$default_marker_icon_user = $marker_users_icon_url;
	}else{
		if(!empty($marker_users_icon_file)) {
			$default_marker_icon_user = $gpress_upload_url.'/'.$marker_users_icon_file;
		} else {
			$default_marker_icon_user = GPRESS_URL.'/gpress-core/images/markers/user.png';
		}
	}
	if(!empty($marker_users_shadow_url)) {
		$default_marker_shadow_user = $marker_users_shadow_url;
	}else{
		if(!empty($marker_users_shadow_file)) {
			$default_marker_shadow_user = $gpress_upload_url.'/'.$marker_users_shadow_file;
		} else {
			$default_marker_shadow_user = GPRESS_URL.'/gpress-core/images/markers/bg.png';
		}
	}
        if($place_id == 'all') {
            if($post_type=='users'){
                /* QUERY ALL USERS */
                $map_id = '_bp_user_location';
                $users = get_users();
                if(is_array($users)){
                    $user_array = array();
                    foreach($users as $user){
                        $user_position = get_user_meta( $user->ID, 'gp_user_location', true);
                        $user_address = get_user_meta( $user->ID, 'gp_user_address', true);
                        if($user_position){
                            $my_user_info = get_userdata($user->ID);
                            $my_name = $my_user_info->display_name;
                            $my_url = $my_user_info->user_url;
                            $user_array[$user->ID]['user_id'] = $user_id;
                            $user_array[$user->ID]['latlng'] = $user_position;
                            $user_array[$user->ID]['address'] = $user_address;
                            $user_array[$user->ID]['title'] = $my_name;
                            $user_array[$user->ID]['default_icon'] = $default_marker_icon_user;
                            $user_array[$user->ID]['default_shadow'] = $default_marker_shadow_user;
                            $user_array[$user->ID]['marker_url'] = $my_url;
                        }
                    }
                    $map_settings = array(
                            'map_id'		=> $map_id,
                            'post_type'		=> 'post',
                            'map_height'        => $map_height,
                            'map_type'          => $map_type,
                            'map_zoom'          => $map_zoom,
                            'bp_user_array'     => $user_array
                    );
                }
            }elseif($post_type=='posts'){
                /* QUERY ALL GEO-TAGGED POSTS */
                $place_array = array();
                if(empty($max_places)) {
                    query_posts('posts_per_page=-1');
                }else{
                    query_posts('posts_per_page='.$max_places.'');
                }
                if ( have_posts() ) : while ( have_posts() ) : the_post();
                    global $post;
                    $meta = get_post_meta($post->ID,'_gpress_posts',TRUE);
                    if(($meta['latlng'])&&($meta['latlng']!=',')){
                        $place_array[$post->ID]['latlng'] = $meta['latlng'];
                        $place_array[$post->ID]['id'] = $post->ID;
                        $place_array[$post->ID]['title'] = get_the_title();
                        $place_array[$post->ID]['url'] = get_permalink();
                        $place_array[$post->ID]['address'] = $meta['address'];
                        $place_array[$post->ID]['description'] = prepare_content_for_marker(apply_filters('gpress_infowindow_excerpt', get_the_content(), $post->ID));
                        $place_array[$post->ID]['icon_url'] = $meta['icon_url'];
                        $place_array[$post->ID]['icon_file'] = $meta['icon_file'];
                        $place_array[$post->ID]['shadow_url'] = $meta['shadow_url'];
                        $place_array[$post->ID]['shadow_file'] = $meta['shadow_file'];
                        $place_array[$post->ID]['default_icon'] = $default_marker_icon_post;
                        $place_array[$post->ID]['default_shadow'] = $default_marker_shadow_post;
                        if(empty($map_position)) {
                            $map_position = $meta['latlng'];
                        }
                    }
                endwhile; else:
                endif;
                wp_reset_query();
                $map_settings = array(
                    'map_id'            => '_gpress_places',
                    'map_height'        => $map_height,
                    'map_type'          => $map_type,
                    'map_zoom'          => $map_zoom,
                    'map_position'      => $map_position,
                    'marker_icon'       => $marker_icon,
                    'marker_shadow'     => $marker_shadow,
                    'post_type'         => $places_taxonomy,
                    'place_id'          => $place_array
                );
                extract($map_settings);
            }else{
                /* ELSE - GET PLACES */
                $place_array = array();
                if(empty($max_places)) {
                    query_posts('post_type='.$places_taxonomy.'&posts_per_page=-1');
                }else{
                    query_posts('post_type='.$places_taxonomy.'&posts_per_page='.$max_places.'');
                }
                if ( have_posts() ) : while ( have_posts() ) : the_post();
                    global $post;
                    $meta = get_post_meta($post->ID,'_gpress_places',TRUE);
                    $place_array[$post->ID]['latlng'] = $meta['latlng'];
                    $place_array[$post->ID]['id'] = $post->ID;
                    $place_array[$post->ID]['title'] = get_the_title();
                    $place_array[$post->ID]['url'] = get_permalink();
                    $place_array[$post->ID]['address'] = $meta['address'];
                    $place_array[$post->ID]['description'] = prepare_content_for_marker(apply_filters('gpress_infowindow_excerpt', get_the_content(), $post->ID));
                    $place_array[$post->ID]['icon_url'] = $meta['icon_url'];
                    $place_array[$post->ID]['icon_file'] = $meta['icon_file'];
                    $place_array[$post->ID]['shadow_url'] = $meta['shadow_url'];
                    $place_array[$post->ID]['shadow_file'] = $meta['shadow_file'];
                    $place_array[$post->ID]['default_icon'] = $default_marker_icon_place;
                    $place_array[$post->ID]['default_shadow'] = $default_marker_shadow_place ;
                    if(empty($map_position)) {
                        $map_position = $meta['latlng'];
                    }
                endwhile; else:
                endif;
                wp_reset_query();
                $map_settings = array(
                    'map_id'            => '_gpress_places',
                    'map_height'        => $map_height,
                    'map_type'          => $map_type,
                    'map_zoom'          => $map_zoom,
                    'map_position'      => $map_position,
                    'marker_icon'       => $marker_icon,
                    'marker_shadow'     => $marker_shadow,
                    'post_type'         => $places_taxonomy,
                    'place_id'          => $place_array
                );
                extract($map_settings);
            }
        }
        ob_start();
        gpress_add_map($map_settings);
        $content = ob_get_clean();
        return $content;
    }
}

function gpress_shortcode_display($map_settings_display, $content = null) {
    /*
     *
     *  THIS FUNCTION IS USED MERELT FOR DEBIGGING - OR MORE SPECIFICALLY
     *  RO GENERATE EXAMPLE SHORTCODE FOR USING THE GPRESS SHORTCODES
     *  WITHOUT ACTUALLY RUNNING THE SHORTCODES THEMSELVES
     *
     */
    if(is_array($map_settings_display)) {
        extract($map_settings_display);
    }
    $display_content = '<span class="gpress_shortcode_display">[gpress';
    if(!empty($map_id)) {
        $display_content .= ' map_id="'.$map_id.'"';
    }
    if(!empty($map_position)) {
        $display_content .= ' map_position="'.$map_position.'"';
    }
    if(!empty($marker_title)) {
        $display_content .= ' marker_title="'.$marker_title.'"';
    }
    if(!empty($map_height)) {
        $display_content .= ' map_height="'.$map_height.'"';
    }
    if(!empty($map_type)) {
        $display_content .= ' map_type="'.$map_type.'"';
    }
    if(!empty($map_zoom)) {
        $display_content .= ' map_zoom="'.$map_zoom.'"';
    }
    if(!empty($marker_icon)) {
        $display_content .= ' marker_icon="'.$marker_icon.'"';
    }
    if(!empty($marker_shadow)) {
        $display_content .= ' marker_shadow="'.$marker_shadow.'"';
    }
    if(!empty($place_id)) {
        $display_content .= ' place_id="'.$place_id.'"';
    }
    if(!empty($post_type)) {
        $display_content .= ' post_type="'.$post_type.'"';
    }
    $display_content .= ']';
    if(!empty($content)) {
        $display_content .= ''.$content.'[/gpress]';
    }
    $display_content .= '</span>';

    return $display_content;
}

?>