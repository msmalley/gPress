<?php

/*
Plugin Name: gPress
Plugin URI: http://gpress.my/
Description: gPress adds new geo-relevant layers to WordPress, allowing you to create your own location-based services...
Version: 0.2.5
Requires at least: WordPress 3.1 / BuddyPress 1.2.8
Tested up to: WordPress 3.1 / BuddyPress 1.2.8
License: GPL 3
Author: PressBuddies
Author URI: http://pressbuddies.com/
*/

define( 'GPRESS_VERSION', 0.25 );
define( 'GPRESS_MIN_VERSION', 3.1 );
define( 'GPRESS_DIR', WP_PLUGIN_DIR . '/gpress' );
define( 'GPRESS_URL', plugins_url( $path = '/gpress' ) );
define( 'GPRESS_CONTENT_DIR', WP_CONTENT_DIR . '/gpress' );
define( 'GPRESS_CONTENT_URL', content_url( $path = '/gpress' ) );

/* THE FOLLOWING DEFINITIONS SCAN FOR CUSTOM FILES IN CUSTOM FOLDERS */
/* IT IS WITHIN THESE CUSTOM FILES THAT YOU SHOULD ADD YOUR OWN CODE */
define( 'GPRESS_CS_PHP_DIR', WP_CONTENT_DIR . '/gpress/custom.php' );
define( 'GPRESS_CS_PHP_URL', content_url( $path = '/gpress/custom.php' ) );
define( 'GPRESS_CS_CSS_DIR', WP_CONTENT_DIR . '/gpress/custom.css' );
define( 'GPRESS_CS_CSS_URL', content_url( $path = '/gpress/custom.css' ) );
define( 'GPRESS_CS_JS_DIR', WP_CONTENT_DIR . '/gpress/custom.js' );
define( 'GPRESS_CS_JS_URL', content_url( $path = '/gpress/custom.js' ) );
/* END OF CUSTOM FILE DEFINITIONS */

/* SET VERSION NUMBER */
global $default_version_number, $previous_version_number, $this_version;
$default_version_number = get_site_option('gp_default_version_number');
$previous_version_number = get_site_option('gp_previous_version_number');
$update_notice = get_site_option('gp_update_notice');
$update_notice_content = get_site_option('gp_update_notice_content');
$this_version = get_site_option('gp_version_number');
if(empty($default_version_number)){
	add_site_option('gp_default_version_number',0.2442);
}if(empty($previous_version_number)){
	add_site_option('gp_previous_version_number',$default_version_number);
}if(empty($this_version)){
	add_site_option('gp_version_number',GPRESS_VERSION);
}if(empty($update_notice)){
	add_site_option('gp_update_notice','');
}if(empty($update_notice_content)){
	add_site_option('gp_update_notice_content','');
}

// THIS GETS LOADED AND USED AT THE BOTTOM OF THIS PAGE
function load_after_buddypress() {

	// Secondly, we need to check WP version to ensure it only works with WP 3.0+
	if(get_bloginfo('version') < GPRESS_MIN_VERSION) {
		add_action('admin_notices', 'gpress_admin_message');
		function gpress_admin_message() {
                    $admin_message = __('Please note that gPress requires WordPress 3.1 or higher', 'gpress');
                    echo '<div id="message_gpress" class="error"><p style="display:block; text-align:center; font-weight:bold;">'.$admin_message.'</p></div>';
		}		
		
	}else{
		
            add_theme_support( 'post-thumbnails' );
		
		/* ADD ACTIONS */
                add_action( 'init', 'gpress_tinymce' );
		add_action( 'admin_menu', 'gpress_meta_normal_init' );
		add_action( 'admin_menu', 'gpress_meta_sidebar_init' );
                add_action( 'admin_menu', 'gpress_options');

		/* ADD FILTERS */
		add_filter('post_updated_messages','gpress_updated_messages');
		add_filter('the_content','gpress_content_filter');
		add_filter('pre_get_posts','gpress_get_posts' );

                /* CORE INCLUDES */
		include( GPRESS_DIR . '/gpress-core/gpress-functions.php' );
                include( GPRESS_DIR . '/gpress-core/gpress-options.php' );
		include( GPRESS_DIR . '/gpress-core/gpress-tinymce.php' );
		include( GPRESS_DIR . '/gpress-core/gpress-maps.php' );
		include( GPRESS_DIR . '/gpress-core/gpress-content-filter.php' );
		include( GPRESS_DIR . '/gpress-core/gpress-excerpt-filter.php' );
		include( GPRESS_DIR . '/gpress-core/gpress-shortcodes.php' );
		include( GPRESS_DIR . '/gpress-core/gpress-geoform.php' );
		include( GPRESS_DIR . '/gpress-core/gpress-meta-boxes.php' );
                include( GPRESS_DIR . '/gpress-core/widgets/widgets-all-users.php' );
                include( GPRESS_DIR . '/gpress-core/widgets/widgets-all-posts.php' );
		include( GPRESS_DIR . '/gpress-core/widgets/widgets-fav-place.php' );
		include( GPRESS_DIR . '/gpress-core/widgets/widgets-recent-places.php' );
		include( GPRESS_DIR . '/gpress-core/widgets/widgets-all-places.php' );
		
		// SHORTCODES
		add_shortcode( 'gpress', 'gpress_shortcode' );
		add_shortcode( 'gpress_display', 'gpress_shortcode_display' );

                $user_rights = get_site_option('gp_bp_user_rights','individual');
                $remove_from_excerpt = get_site_option('gp_remove_maps_excerpt','no');

                if (defined('BP_VERSION') || did_action('bp_init')) {

                    $location_at_signup = get_site_option('gp_bp_show_signup','enabled');
                    if($location_at_signup == 'enabled') {
                        add_filter( 'bp_signup_usermeta' , 'gpress_signup_usermeta' );
                        add_action( 'bp_before_registration_submit_buttons', 'gpress_user_signup', 1 );
                        /* MAY ONLY NEED THIS SINGLE LINE NOW ...? */
                        add_action( 'bp_core_signup_user', 'gpress_activate_user', 10, 5 );
                    }

                    $default_use_bp_profile = get_site_option('gp_bp_user_location','ABOVE');
                    $default_use_bp_profile_address = get_site_option('gp_bp_show_address','enabled');
                    /* NEED TO REPLACE THIS WITH NEW FILTER FOR INDIVIDUAL USER / BLOG OPTIONS */
                    $use_bp_profile = $default_use_bp_profile;
                    $use_bp_profile_address = $default_use_bp_profile_address;
                    if($use_bp_profile == 'ABOVE') {
                        add_action( 'bp_before_profile_loop_content', 'gpress_bp_profile' );
                    }
                    if($use_bp_profile == 'BELOW') {
                        add_action( 'bp_after_profile_loop_content', 'gpress_bp_profile' );
                    }
                    if($use_bp_profile_address == 'enabled') {
                        add_action( 'bp_profile_header_meta', 'gpress_bp_profile_address' );
                    }
                }
                if($remove_from_excerpt == 'no') {
                    add_filter( 'the_excerpt', 'gpress_excerpt_filter' );
                }

                // LAST THING TO LOAD IS CUSTOM SCRIPT
                if(file_exists(GPRESS_CS_PHP_DIR)) {
                    include(GPRESS_CS_PHP_DIR);
                }
		
	}

}

// THIS CHECKS FOR BUDDYPRESS
global $gpress_bp;
if (defined('BP_VERSION') || did_action('bp_init')) {
        $gpress_bp = true;
        define( 'GPRESS_BP', true );
        include( GPRESS_DIR . '/gpress-core/gpress-bp.php' );
        add_action( 'bp_before_member_activity_post_form', 'gpress_bp_activity' );
        add_action( 'bp_init', 'load_after_buddypress' );
} else {
        $gpress_bp = false;
        define( 'GPRESS_BP', false );
        load_after_buddypress();
}


// THIS LOADS CERTAIN OPTIONS AS EARLY AS POSSIBLE
function load_admin_options() {
    if(get_bloginfo('version') >= GPRESS_MIN_VERSION) {
        /* DEAL WITH DEPRECATION */
        global $default_version_number, $previous_version_number, $this_version;
        require_once(GPRESS_DIR.'/gpress-core/gpress-deprecated.php' );
        if($default_version_number<$this_version){
            update_site_option('gp_default_version_number',$this_version);
            update_site_option('gp_update_notice','yes');
            update_site_option('gp_update_notice_content',__('<h3>IMPORTANT UPGRADE NOTIFICATIONS</h3><p>Please note that gPress has just automatically converted all of your place descriptions into the new custom post type content that will be used from this version forward. Now that we are using proper content and excerpt features, there are no limitations to describing or utilising places.</p><p>Please also take note of these new gPress Option Pages (gPress Options, gPress Components and Advanced Settings). This new format utilises standard WordPress options, which are far less buggy than the previous Options Framework we were using as they do not have any jQuery dependencies and have much less database bloat. Unfortunately, it also means that any options you may have already set-up previous will now need to be re-input here as there have been several critical changes to the core functionality...</p><p>Most importantly, please note that all Foursquare functionality has been removed from core. Specifically becuase of the problems it provided by being included within a core-package and reliant upon a third-party provider... Such functionality is best suited for a seperate gPress add-on / plugin, which will be made much easier to develop further now that gPress features a good solid starting point with its hooks and filters, allowing developers to do some pretty radical things.</p><p>We have also removed all the file upload options, and MS (multi-site) options, but will be adding those in again in the next version. Markers can still however be customised globally by adding absolute URLs, as seen below.<p><span>PLEASE NOTE THAT THIS MESSAGE WILL ONLY BE AVAILABLE ONCE - SO PLEASE PAY SPECIAL ATTENTION TO IT NOW</span></p>','gpress'));
            if($this_version>$previous_version_number){
                update_site_option('gp_version_number',$this_version);
                update_site_option('gp_previous_version_number',$this_version);
                gpress_deprecated_functions($this_version, $previous_version_number);
            }
        }
        /* LANGUAGES */
        $wp_language = WPLANG;
        $gpress_custom_lang_file = get_site_option('gp_custom_mo');
        define( 'GPRESS_LANG_FILE', GPRESS_CONTENT_DIR . '/'.$gpress_custom_lang_file.'.mo' );
        if(file_exists(GPRESS_LANG_FILE)) {
            load_textdomain( 'gpress', GPRESS_CONTENT_DIR . '/'.$gpress_custom_lang_file.'.mo' );
        }else{
            if(!empty($wp_language)) {
                load_textdomain( 'gpress', GPRESS_DIR . '/gpress-lang/gpress-'.$wp_language.'.mo');
            }
        }

        // ONLY IF BUDDYPRESS IS ACTIVE
        if (defined('BP_VERSION') || did_action('bp_init')) {
            add_action( 'wp', 'gpress_add_new_settings_nav', 2 );
            add_action( 'admin_menu', 'gpress_add_new_settings_nav', 2 );
        }
        // LOADED REGARDLESS OF BUDDYPRESS AT STAGE OF SETTING CURRENT USER
        require_once( GPRESS_DIR . '/gpress-core/gpress-post-types.php' );
        require_once( GPRESS_DIR . '/gpress-core/gpress-taxonomy.php' );
        require_once( GPRESS_DIR . '/gpress-core/gpress-init.php' );
        require_once( GPRESS_DIR . '/gpress-core/gpress-rss.php' );
        add_action( 'init', 'gpress_init' );
        add_action( 'init', 'gpress_taxonomies', 0 );
    }
}
add_action( 'set_current_user', 'load_admin_options', 1 );

?>