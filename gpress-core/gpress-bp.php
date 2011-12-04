<?php

function gpress_bp_profile() {
	
	global $bp, $blog_id;
	
	$original_blog_id = $blog_id;
	if(is_multisite()) {
		switch_to_blog(1);
			$gpress_upload_url = get_bloginfo('url').'/wp-content/gpress/'.$original_blog_id;
		restore_current_blog();
	}else{
		$gpress_upload_url = get_bloginfo('url').'/wp-content/gpress/'.$original_blog_id;
	}
	
	$user_id = $bp->displayed_user->id;
	$user_id_logged_in = $bp->loggedin_user->id;
	$nicename = $bp->displayed_user->userdata->user_nicename;
	$users_post_array = get_users_blog_posts($user_id, 100);

        $use_bp_profile = get_site_option('gp_map_type','enabled');
        
        $default_map_height = get_site_option('gp_profile_height','450');
        $default_map_type = get_site_option('gp_profile_type','ROADMAP');
        $default_map_zoom = get_site_option('gp_profile_zoom','13');

        /* CONFIRM MAP HEIGHT, TYPE AND ZOOM */
        $map_height = $default_map_height;
        $map_type = $default_map_type;
        $map_zoom = $default_map_zoom;
	
	$my_user_info = get_userdata($user_id);
	$my_name = $my_user_info->display_name;
	$my_firstname = $my_user_info->first_name;
	
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

	$nicename = $my_name;
	
	$empty_array = true;
		
	$map_id = '_bp_user_location';

        $user_position = get_user_meta( $user_id, 'gp_user_location', true);
        $user_address = get_user_meta( $user_id, 'gp_user_address', true);
	
	$user_array = array();
	$user_array[$user_id]['user_id'] = $user_id;
	$user_array[$user_id]['latlng'] = $user_position;
	$user_array[$user_id]['address'] = $user_address;
	$user_array[$user_id]['title'] = $nicename;
	$user_array[$user_id]['default_icon'] = $default_marker_icon_user;
	$user_array[$user_id]['default_shadow'] = $default_marker_shadow_user;
	if(!empty($user_position)) {
		$empty_array = false;
	}

	$map_settings = array(
		'map_id'		=> $map_id,
		'post_type'		=> 'post',
		'map_height'            => $map_height,
		'map_type'		=> $map_type,
		'map_zoom'		=> $map_zoom,
		'bp_user_array'         => $user_array,
		'post_id'		=> $post_array
	);

	if(!$empty_array) {
                $user_id = $bp->displayed_user->id;
                $user_rights = get_site_option('gp_bp_user_rights','individual');
                $default_show_location = get_site_option('gp_bp_user_location','ABOVE');
                if($default_show_location=='NONE'){ $default_show_location = 'disabled'; }else{ $default_show_location = 'enabled'; }
                $user_show_location = get_user_meta($user_id, 'gp_user_show_location', true);
                if(empty($user_show_location)) { $user_show_location='enabled'; }
                if((($user_rights=='individual') && ($user_show_location!='enabled')) || (($user_rights!='individual') && ($default_show_location=='NONE'))) {
                        /* DO NOTHING */
                }elseif((($user_rights=='individual') && ($user_show_location!='disabled')) || (($user_rights!='individual') && ($default_show_location!='NONE'))) {
                    echo '<h2><span style="text-transform:capitalize">'.$nicename.'</span>\'s '.__('Present Location:', 'gpress').'</h2>';
                    gpress_add_map($map_settings);
                }
	}
}

function gpress_bp_activity() {
	
	global $bp, $blog_id;
	
	$original_blog_id = $blog_id;
	if(is_multisite()) {
		switch_to_blog(1);
			$gpress_upload_url = get_bloginfo('url').'/wp-content/gpress/'.$original_blog_id;
		restore_current_blog();
	}else{
		$gpress_upload_url = get_bloginfo('url').'/wp-content/gpress/'.$original_blog_id;
	}
	
	$user_id = $bp->displayed_user->id;
	$users_post_array = get_users_blog_posts($user_id, 100);

        $user_rights = get_site_option('gp_map_type','individual');
        $default_use_bp_activity = get_site_option('gp_bp_show_posts','enabled');

        $default_map_height = get_site_option('gp_activity_height','450');
        $default_map_type = get_site_option('gp_activity_type','ROADMAP');
        $default_map_zoom = get_site_option('gp_activity_zoom','13');

        /* CONFIRM MAP HEIGHT, TYPE AND ZOOM */
        $map_height = $default_map_height;
        $map_type = $default_map_type;
        $map_zoom = $default_map_zoom;

        $use_bp_activity = $default_use_bp_activity;

	$my_user_info = get_userdata($user_id);
	$nicename = $my_user_info->display_name;
	
	$post_array = array();
	$empty_array = true;
	if(is_array($users_post_array)) {
		foreach($users_post_array as $key => $post) {
			$geo_public = $post['geo_public'];
			$post_id = $post['post_id'];
			$blog_id = $post['blog_id'];

                        $adhoc_markers = get_post_meta($post_id,'_gpress_posts',TRUE);
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
			if(($geo_public)&&(($post['geo_latlng']!=',')||(!empty($post['geo_latlng'])))) {
				$post_array[$key]['blog_id'] = $blog_id;
				$post_array[$key]['post_id'] = $post_id;
				$post_array[$key]['post_date'] = $post['post_date'];
				$post_array[$key]['post_title'] = $post['post_title'];
				$post_array[$key]['post_url'] = $post['post_url'];
				$post_array[$key]['post_type'] = $post['post_type'];
				$post_array[$key]['geo_public'] = $geo_public;
				$post_array[$key]['geo_latlng'] = $post['geo_latlng'];
				$post_array[$key]['default_icon'] = $default_marker_icon_post;
				$post_array[$key]['default_shadow'] = $default_marker_shadow_post;
				$post_array[$key]['icon_url'] = $adhoc_markers['icon_url'];
				$post_array[$key]['icon_file'] = $adhoc_markers['icon_file'];
				$post_array[$key]['shadow_url'] = $adhoc_markers['shadow_url'];
				$post_array[$key]['shadow_file'] = $adhoc_markers['shadow_file'];
				$empty_array = false;
			}
		}
	}
	$map_settings = array(
		'map_id'	=> '_bp_profile',
		'map_height'	=> $map_height,
		'map_type'	=> $map_type,
		'map_zoom'	=> $map_zoom,
		'post_type'	=> 'post',
		'post_id'	=> $post_array
	);

        if($empty_array == false) {
            $user_id = $bp->displayed_user->id;
            $user_rights = get_site_option('gp_bp_user_rights','individual');
            $default_show_posts = get_site_option('gp_bp_show_posts','enabled');
            $user_show_posts = get_user_meta($user_id, 'gp_user_show_posts', true);
            if(empty($user_show_posts)) { $user_show_posts='enabled'; }
            if((($user_rights=='individual') && ($user_show_posts=='disabled')) || (($user_rights=='override') && ($default_show_posts=='disabled'))) {
                /* DO NOTHING */
            }elseif((($user_rights=='individual') && ($user_show_posts=='enabled')) || (($user_rights=='override') && ($default_show_posts=='enabled'))) {
                echo '<h2><span style="text-transform:capitalize">'.$nicename.'</span>\'s '.__('Sitewide Geo-Tagged Posts:', 'gpress').'</h2>';
                gpress_add_map($map_settings);
            }
        }
	
}

function gpress_bp_geo_settings() {
	global $current_user, $bp_settings_updated, $pass_error, $bproot;

	add_action( 'bp_template_title', 'gpress_geo_settings_title' );
	add_action( 'bp_template_content', 'gpress_geo_settings_content' );
	if(empty($bproot)) {
		bp_core_load_template( apply_filters( 'bp_core_template_plugin', 'members/single/plugins' ) );
	}else{
		bp_core_load_template( apply_filters( 'bp_core_template_plugin', ''.$bproot.'/core/templates/members/single/plugins' ) );
	}
}

function gpress_bp_location_settings() {
	global $current_user, $bp_settings_updated, $pass_error, $bproot;

	add_action( 'bp_template_title', 'gpress_location_settings_title' );
	add_action( 'bp_template_content', 'gpress_location_settings_content' );
	if(empty($bproot)) {
		bp_core_load_template( apply_filters( 'bp_core_template_plugin', 'members/single/plugins' ) );
	}else{
		bp_core_load_template( apply_filters( 'bp_core_template_plugin', ''.$bproot.'/core/templates/members/single/plugins' ) );
	}
}

function gpress_geo_settings_title() {
	_e( '<h4>BuddyPress Geo Settings</h4>', 'gpress' );
}

function gpress_location_settings_title() {
	_e( '<h4>Edit My Location</h4>', 'gpress' );
}

function gpress_geo_settings_content() {
	global $bp, $current_user, $bp_settings_updated, $pass_error;
	?>

        <link rel="stylesheet" type="text/css" media="all" href="<?php echo GPRESS_URL; ?>/gpress-core/css/geo-settings.css" />
        
        <form action="<?php echo $bp->loggedin_user->domain . $bp->settings->slug . '/geo' ?>" method="post" class="standard-form" id="geo-form">

            <?php
            $user_id = $bp->displayed_user->id;
            if (isset($_POST['my-geo-settings-submit'])) {
                update_user_meta($user_id, 'gp_user_show_address', $_POST['gp_bp_user_show_address']);
                update_user_meta($user_id, 'gp_user_show_location', $_POST['gp_bp_user_show_location']);
                update_user_meta($user_id, 'gp_user_show_posts', $_POST['gp_bp_user_show_posts']);
            }

            $user_address = get_user_meta( $user_id, 'gp_user_show_address', true);
            $user_location = get_user_meta( $user_id, 'gp_user_show_location', true);
            $user_posts = get_user_meta( $user_id, 'gp_user_show_posts', true);

            if($user_location=='NONE'){
                $user_location='disabled';
            }else{
                $user_location='enabled';
            }

            $default_bp_user_show_address = get_option('gp_bp_show_address','enabled');
            $default_bp_user_show_location = get_option('gp_bp_user_location','enabled');
            $default_bp_user_show_posts = get_option('gp_bp_show_posts','enabled');

            if(empty($user_address)) {
                $bp_user_show_address = $default_bp_user_show_address;
            }else{
                $bp_user_show_address = $user_address;
            }
            if(empty($user_location)) {
                $bp_user_show_location = $default_bp_user_show_location;
            }else{
                $bp_user_show_location = $user_location;
            }
            if(empty($user_posts)) {
                $bp_user_show_posts = $default_bp_user_show_posts;
            }else{
                $bp_user_show_posts = $user_posts;
            }

            ?>

            <label class="gp-clear"><?php _e('Show my closest address (in profile headers)...?', 'gpress'); ?></label>
            <input id="bp-user-show-address-enabled" type="radio" name="gp_bp_user_show_address" autocomplete="off" value="enabled"<?php if($bp_user_show_address=='enabled') { echo ' checked="checked"'; }?> />
            <label class="borderless" for="bp-user-show-address-enabled" class="label-radio"><?php _e('ENABLED', 'gpress'); ?></label>
            <input id="bp-user-show-address-disabled" type="radio" name="gp_bp_user_show_address" autocomplete="off" value="disabled"<?php if($bp_user_show_address=='disabled') { echo ' checked="checked"'; }?> />
            <label class="borderless" for="bp-user-show-address-disabled" class="label-radio"><?php _e('DISABLED', 'gpress'); ?></label>

            <label class="gp-clear"><?php _e('Show my location on maps (throughout the site)...?', 'gpress'); ?></label>
            <input id="bp-user-show-location-enabled" type="radio" name="gp_bp_user_show_location" autocomplete="off" value="enabled"<?php if($bp_user_show_location=='enabled') { echo ' checked="checked"'; }?> />
            <label class="borderless" for="bp-user-show-location-enabled" class="label-radio"><?php _e('ENABLED', 'gpress'); ?></label>
            <input id="bp-user-show-location-disabled" type="radio" name="gp_bp_user_show_location" autocomplete="off" value="disabled"<?php if($bp_user_show_location=='disabled') { echo ' checked="checked"'; }?> />
            <label class="borderless" for="bp-user-show-location-disabled" class="label-radio"><?php _e('DISABLED', 'gpress'); ?></label>

            <label class="gp-clear"><?php _e('Show my geo-tagged posts (in activity)...?', 'gpress'); ?></label>
            <input id="bp-user-show-posts-enabled" type="radio" name="gp_bp_user_show_posts" autocomplete="off" value="enabled"<?php if($bp_user_show_posts=='enabled') { echo ' checked="checked"'; }?> />
            <label class="borderless" for="bp-user-show-posts-enabled" class="label-radio"><?php _e('ENABLED', 'gpress'); ?></label>
            <input id="bp-user-show-posts-disabled" type="radio" name="gp_bp_user_show_posts" autocomplete="off" value="disabled"<?php if($bp_user_show_posts=='disabled') { echo ' checked="checked"'; }?> />
            <label class="borderless" for="bp-user-show-posts-disabled" class="label-radio"><?php _e('DISABLED', 'gpress'); ?></label>

            <div style="clear:both; width:100%; display:block; padding-top: 15px; padding-bottom: 25px">
                <p>You may need to refresh this or any other page in order to see changes made here...</p>
            </div>
            
            <input type="submit" value="Save Changes " id="my-geo-settings-submit" name="my-geo-settings-submit">

            <div style="clear:both; width:100%; display:block"></div>
    
	</form>
<?php
}

function gpress_location_settings_content() {
	global $bp, $current_user, $bp_settings_updated, $pass_error;
	?>

        <link rel="stylesheet" type="text/css" media="all" href="<?php echo GPRESS_URL; ?>/gpress-core/css/geo-settings.css" />

        <form action="<?php echo $bp->loggedin_user->domain . $bp->profile->slug . '/location' ?>" method="post" class="standard-form" id="geo-form">

        <?php

            $user_id = $bp->displayed_user->id;

            // Submit (if required)
            if (isset($_POST['my-location-edit-submit'])) {
                update_user_meta($user_id, 'gp_user_location', $_POST['geo_settings_latlng']);
                update_user_meta($user_id, 'gp_user_address', $_POST['geo_settings_closest_address']);
            }

            // Gather Geo-Meta
            $latlng = get_user_meta( $user_id, 'gp_user_location', true);
            if(empty($latlng)) {
                $user_position = '7.27529233637217, -1.9775390625';
            }else{
                $user_position = $latlng;
            }
 
            // Default Map Settings
            $map_id = '_bp_user_location';
            $map_height = get_site_option('gp_map_type','450');
            $map_type = get_site_option('gp_map_type','ROADMAP');
            $map_zoom = get_site_option('gp_map_zoom','13');

            gpress_geoform($map_id, $map_type, $map_zoom, $user_position, false, true, true);

	?>

        <input type="submit" value="Save Changes " id="my-location-edit-submit" name="my-location-edit-submit">

	</form>
<?php
}

function gpress_bp_profile_address() {
    global $bp;
    $user_id = $bp->displayed_user->id;
    $user_rights = get_site_option('gp_bp_user_rights','individual');
    $default_show_location = get_site_option('gp_bp_user_location','enabled');
    $user_show_location = get_user_meta($user_id, 'gp_user_show_address', true);
    if(empty($user_show_location)) { $user_show_location='enabled'; }
    if($user_rights=='individual'){
        $show_location = $user_show_location;
    }else{
        $show_location = $default_show_location;
    }
    if($show_location=='enabled') {
        $displayname = $bp->displayed_user->userdata->display_name;
        $closest_address = get_user_meta($user_id, 'gp_user_address', true);
        if(!empty($closest_address)) {
                $this_address = sprintf(__('%s is presently located near %s', 'gpress'), $displayname, $closest_address);
                echo '<p class="bp_profile_header_address">'.$this_address.'.</p>';
        }
    }
}

function gpress_add_new_settings_nav() {
    global $bp;
    $user_rights = get_site_option('gp_bp_user_rights','individual');
    if($user_rights=='individual') {
        $settings_link = $bp->loggedin_user->domain . BP_SETTINGS_SLUG . '/';
        bp_core_new_subnav_item( array( 'name' => __( 'Geo-Settings', 'gpress' ), 'slug' => 'geo', 'parent_url' => $settings_link, 'parent_slug' => $bp->settings->slug, 'screen_function' => 'gpress_bp_geo_settings', 'position' => 1, 'user_has_access' => bp_is_home() ) );
    }
    $profile_link = $bp->loggedin_user->domain . BP_XPROFILE_SLUG . '/';
    bp_core_new_subnav_item( array( 'name' => __( 'Edit My Location', 'gpress' ), 'slug' => 'location', 'parent_url' => $profile_link, 'parent_slug' => $bp->profile->slug, 'screen_function' => 'gpress_bp_location_settings', 'position' => 69, 'user_has_access' => bp_is_home() ) );
}

function gpress_user_signup() {
	global $bp, $current_user, $bp_settings_updated, $pass_error;
	?>
	
        <link rel="stylesheet" type="text/css" media="all" href="<?php echo GPRESS_URL; ?>/gpress-core/css/geo-settings.css" />
        <link rel="stylesheet" type="text/css" media="all" href="<?php echo GPRESS_URL; ?>/gpress-bp/css/tppobp.css" />
    
        <div id="gmaps-location-section">
    
	    <h4><?php echo __('Your Location:', 'gpress'); ?></h4>
        
		<?php
    
        $default_primary_bg = '#EEE';
        $default_primary_border = '#DDD';
        $default_primary_color = '#999';
        $default_primary_bg_hover = '#EEE';
        $default_primary_border_hover = '#CCC';
        $default_primary_color_hover = '#666';
        $default_secondary_bg = '#FFF';
        $default_secondary_border = '#DDD';
        $default_secondary_color = '#666';
        $default_secondary_bg_hover = '#EEE';
        $default_secondary_border_hover = '#DDD';
        $default_secondary_color_hover = '#999';
        
        $primary_bg = $geo_setting_styles['primary_bg']['hex'];
        $primary_border = $geo_setting_styles['primary_border']['hex'];
        $primary_color = $geo_setting_styles['primary_color']['hex'];
        $primary_bg_hover = $geo_setting_styles['primary_bg_hover']['hex'];
        $primary_border_hover = $geo_setting_styles['primary_border_hover']['hex'];
        $primary_color_hover = $geo_setting_styles['primary_color_hover']['hex'];
        $secondary_bg = $geo_setting_styles['secondary_bg']['hex'];
        $secondary_border = $geo_setting_styles['secondary_border']['hex'];
        $secondary_color = $geo_setting_styles['secondary_color']['hex'];
        $secondary_bg_hover = $geo_setting_styles['secondary_bg_hover']['hex'];
        $secondary_border_hover = $geo_setting_styles['secondary_border_hover']['hex'];
        $secondary_color_hover = $geo_setting_styles['secondary_color_hover']['hex'];
        
        if(empty($primary_bg)) {
            $primary_bg = $default_primary_bg;
        }
        if(empty($primary_border)) {
            $primary_border = $default_primary_border;
        }
        if(empty($primary_color)) {
            $primary_color = $default_primary_color;
        }
        if(empty($primary_bg_hover)) {
            $primary_bg_hover = $default_primary_bg_hover;
        }
        if(empty($primary_border_hover)) {
            $primary_border_hover = $default_primary_border_hover;
        }
        if(empty($primary_color_hover)) {
            $primary_color_hover = $default_primary_color_hover;
        }
        if(empty($secondary_bg)) {
            $secondary_bg = $default_secondary_bg;
        }
        if(empty($secondary_border)) {
            $secondary_border = $default_secondary_border;
        }
        if(empty($secondary_color)) {
            $secondary_color = $default_secondary_color;
        }
        if(empty($secondary_bg_hover)) {
            $secondary_bg_hover = $default_secondary_bg_hover;
        }
        if(empty($secondary_border_hover)) {
            $secondary_border_hover = $default_secondary_border_hover;
        }
        if(empty($secondary_color_hover)) {
            $secondary_color_hover = $default_secondary_color_hover;
        }
        
        ?>
        
        <style>
        
        /* SECONDARY BG = <?php echo $secondary_bg; ?> */
        form.standard-form .tpp_form_container .ui-state-default, 
        form.standard-form .tpp_form_container .ui-widget-content .ui-state-default {
            background:<?php echo $secondary_bg; ?> !important;
        }
        
        /* SECONDARY BORDER = <?php echo $secondary_border; ?> */
        form.standard-form .tpp_form_container #mapCanvas_user_bp_location, 
        form.standard-form .tpp_form_container #search_address_user_bp_location, 
        form.standard-form .tpp_form_container #search_address_user_bp_location:hover, 
        form.standard-form .tpp_form_container .ui-state-default, 
        form.standard-form .tpp_form_container .ui-widget-content .ui-state-default {
            border-color:<?php echo $secondary_border; ?> !important;
        }
        
        /* SECONDARY COLOR = <?php echo $secondary_color; ?> */
        form.standard-form .tpp_form_container .ui-state-default a, 
        form.standard-form .tpp_form_container .ui-widget-content .ui-state-default a {
            color:<?php echo $secondary_color; ?> !important;
        }
        
        /* SECONDARY BG HOVER = <?php echo $secondary_bg_hover; ?> */
        form.standard-form .tpp_form_container .ui-state-default:hover, 
        form.standard-form .tpp_form_container .ui-widget-content .ui-state-default:hover {
            background:<?php echo $secondary_bg_hover; ?> !important;
        }
        
        /* SECONDARY BORDER HOVER = <?php echo $secondary_border_hover; ?> */
        form.standard-form .tpp_form_container .ui-state-default:hover, 
        form.standard-form .tpp_form_container .ui-widget-content .ui-state-default:hover {
            border-color:<?php echo $secondary_border_hover; ?> !important;
        }
        
        /* SECONDARY COLOR HOVER = <?php echo $secondary_color_hover; ?> */
        form.standard-form .tpp_form_container .ui-state-default a:hover, 
        form.standard-form .tpp_form_container .ui-widget-content .ui-state-default a:hover,
        form.standard-form .tpp_form_container .ui-state-default:hover a, 
        form.standard-form .tpp_form_container .ui-widget-content .ui-state-default:hover a {
            color:<?php echo $secondary_color_hover; ?> !important;
        }
        
        /* PRIMARY BG = <?php echo $primary_bg; ?> */
        form.standard-form .tpp_form_container .ui-state-active, 
        form.standard-form .tpp_form_container .ui-widget-content .ui-state-active, 
        form.standard-form .tpp_form_container .right-container .form-container .button input.submit, 
        form.standard-form .tpp_form_container .right-container .form-container input[type="button"] {
            background:<?php echo $primary_bg; ?> !important;
        }
        
        /* PRIMARY BORDER = <?php echo $primary_border; ?> */
        form.standard-form .tpp_form_container .ui-tabs .ui-tabs-nav, 
        form.standard-form .tpp_form_container .ui-state-active, 
        form.standard-form .tpp_form_container .ui-widget-content .ui-state-active, 
        form.standard-form .tpp_form_container .right-container .form-container .button input.submit, 
        form.standard-form .tpp_form_container .right-container .form-container input[type="button"] {
            border-color:<?php echo $primary_border; ?> !important;
        }
        
        /* PRIMARY COLOR = <?php echo $primary_color; ?> */
        form.standard-form .tpp_form_container .ui-state-active a, 
        form.standard-form .tpp_form_container .ui-widget-content .ui-state-active a, 
        form.standard-form .tpp_form_container .right-container .form-container .button input.submit, 
        form.standard-form .tpp_form_container .right-container .form-container input[type="button"] {
            color:<?php echo $primary_color; ?> !important;
        }
        
        /* PRIMARY BG HOVER = <?php echo $primary_bg_hover; ?> */
        form.standard-form .tpp_form_container .ui-state-active:hover, 
        form.standard-form .tpp_form_container .ui-widget-content .ui-state-active:hover, 
        form.standard-form .tpp_form_container .right-container .form-container .button input.submit:hover, 
        form.standard-form .tpp_form_container .right-container .form-container input[type="button"]:hover {
            background:<?php echo $primary_bg_hover; ?> !important;
        }
        
        /* PRIMARY BORDER HOVER = <?php echo $primary_border_hover; ?> */
        form.standard-form .tpp_form_container .ui-state-active:hover, 
        form.standard-form .tpp_form_container .ui-widget-content .ui-state-active:hover, 
        form.standard-form .tpp_form_container .right-container .form-container .button input.submit:hover, 
        form.standard-form .tpp_form_container .right-container .form-container input[type="button"]:hover {
            border-color:<?php echo $primary_border_hover; ?> !important;
        }
        
        /* PRIMARY COLOR HOVER = <?php echo $primary_color_hover; ?> */
        form.standard-form .tpp_form_container .ui-state-active a:hover, 
        form.standard-form .tpp_form_container .ui-widget-content .ui-state-active a:hover, 
        form.standard-form .tpp_form_container .ui-state-active:hover a, 
        form.standard-form .tpp_form_container .ui-widget-content .ui-state-active:hover a, 
        form.standard-form .tpp_form_container .right-container .form-container .button input.submit:hover, 
        form.standard-form .tpp_form_container .right-container .form-container input[type="button"]:hover {
            color:<?php echo $primary_color_hover; ?> !important;
        }
        
        form.standard-form .tpp_form_container ul.ui-tabs-nav, 
		form.standard-form .tpp_form_container div.button, 
		form.standard-form .tpp_form_container .sub_tab_intro, 
		form.standard-form .tpp_form_container .right-container .form-container ul.form-list li label.general-label {
            display:none !important;
        }
		form.standard-form .tpp_form_container div.second_half, 
		.tpp_form_container .right-container .form-container ul.form-list li span.help-text, 
		.tpp_form_container .right-container .form-container ul.form-list, 
		form.standard-form .tpp_form_container {
			margin:0 !important;
			margin-bottom:0 !important;
		}
		#gmaps-location-section {
			clear:both;
			width:100%;
			padding:15px 0 0;
		}
                .gpress_leftcolumn textarea {
                    border:none !important;
                    background:transparent !important;
                    height:auto !important;
                    padding:0 !important;
                    margin:0 !important;
                }
        
        </style>
        
        <?php

            $map_id = 'gp_signup';
            $map_type = get_site_option('gp_map_type','ROADMAP');
            $map_zoom = get_site_option('gp_map_zoom','13');
            gpress_geoform($map_id, $map_type, $map_zoom, false, false, true, true);
            
        ?>
        
    </div>
    
	<?php
}

function gpress_signup_usermeta($usermeta) {
    $geodata['gp_user_location'] = $_POST['geo_settings_latlng'];
    $geodata['gp_user_address'] = $_POST['geo_settings_closest_address'];
    $result = array_merge($geodata,$usermeta);
    return $result;
}

function gpress_activate_user($user_id, $user_login, $user_password, $user_email, $usermeta){
    update_user_meta($user_id, 'gp_user_location', $usermeta['gp_user_location']);
    update_user_meta($user_id, 'gp_user_address', $usermeta['gp_user_address']);
}

?>