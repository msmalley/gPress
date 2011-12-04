<?php

function gpress_options() {
    add_menu_page('gPress Options', 'gPress Options', 'manage_options', 'gp-general-options', 'gpress_options_page', '', null);
    add_submenu_page( 'gp-general-options', 'gPress Components', 'gPress Components', 'manage_options', 'gpress-components', 'gpress_components_page');
    add_submenu_page( 'gp-general-options', 'Advanced Settings', 'Advanced Settings', 'manage_options', 'advanced-settings', 'advanced_settings_page');
    if (defined('BP_VERSION') || did_action('bp_init')) {
        add_submenu_page( 'gp-general-options', 'BuddyPress Settings', 'BuddyPress Settings', 'manage_options', 'buddypress-settings', 'buddypress_settings_page');
    }
    add_action( 'admin_init', 'gpress_register_settings' );
}

function gpress_register_settings() {
    register_setting( 'gpress-general-settings-group',          'gp_map_height' );
    register_setting( 'gpress-general-settings-group',          'gp_map_location' );
    register_setting( 'gpress-general-settings-group',          'gp_map_type' );
    register_setting( 'gpress-general-settings-group',          'gp_map_zoom' );
    register_setting( 'gpress-general-settings-group',          'gp_marker_url_posts' );
    register_setting( 'gpress-general-settings-group',          'gp_shadow_url_posts' );
    register_setting( 'gpress-general-settings-group',          'gp_marker_url_places' );
    register_setting( 'gpress-general-settings-group',          'gp_shadow_url_places' );
    register_setting( 'gpress-general-settings-group',          'gp_marker_url_favplaces' );
    register_setting( 'gpress-general-settings-group',          'gp_shadow_url_favplaces' );
    register_setting( 'gpress-general-settings-group',          'gp_marker_url_users' );
    register_setting( 'gpress-general-settings-group',          'gp_shadow_url_users' );
    register_setting( 'gpress-components-group',                'gp_module_posts' );
    register_setting( 'gpress-components-group',                'gp_module_rss' );
    register_setting( 'gpress-components-group',                'gp_module_places' );
    register_setting( 'gpress-components-group',                'gp_force_geo' );
    register_setting( 'gpress-advanced-settings-group',         'gp_homepage_loop' );
    register_setting( 'gpress-advanced-settings-group',         'gp_remove_maps_content' );
    register_setting( 'gpress-advanced-settings-group',         'gp_remove_maps_excerpt' );
    register_setting( 'gpress-advanced-settings-group',         'gp_show_credits' );
    register_setting( 'gpress-advanced-settings-group',         'gp_misc_jquery' );
    register_setting( 'gpress-advanced-settings-group',         'gp_page_id_submit' );
    register_setting( 'gpress-advanced-settings-group',         'gp_custom_mo' );
    if (defined('BP_VERSION') || did_action('bp_init')) {
        register_setting( 'gpress-buddypress-settings-group',   'gp_bp_show_signup' );
        register_setting( 'gpress-buddypress-settings-group',   'gp_bp_user_rights' );
        register_setting( 'gpress-buddypress-settings-group',   'gp_bp_user_location' );
        register_setting( 'gpress-buddypress-settings-group',   'gp_bp_show_address' );
        register_setting( 'gpress-buddypress-settings-group',   'gp_bp_show_posts' );
        register_setting( 'gpress-buddypress-settings-group',   'gp_profile_height' );
        register_setting( 'gpress-buddypress-settings-group',   'gp_profile_type' );
        register_setting( 'gpress-buddypress-settings-group',   'gp_profile_zoom' );
        register_setting( 'gpress-buddypress-settings-group',   'gp_activity_height' );
        register_setting( 'gpress-buddypress-settings-group',   'gp_activity_type' );
        register_setting( 'gpress-buddypress-settings-group',   'gp_activity_zoom' );
    }
}

function gpress_options_page() {
  $gp_update_notice = get_site_option('gp_update_notice');
  $notification_content = get_site_option('gp_update_notice_content');
  $show_credits = get_site_option('gp_show_credits','enabled');
  /* GENERAL SETTINGS */
  if (!current_user_can('manage_options'))  {
    wp_die( __('You do not have sufficient permissions to access this page.') );
  }
  echo '<div id="gpress-options-page" class="wrap">';
      echo '<div class="icon32" id="icon-index"><br></div><h2>gPress Options > General Settings</h2>';
      echo '<div id="dashboard-widgets-wrap">';
	  	  if($gp_update_notice=='yes'){
			  if(!empty($notification_content)){
				echo '<div class="gpress-options-notification">'.$notification_content.'</div>';
				update_site_option('gp_update_notice','');
  				update_site_option('gp_update_notice_content','');
			  }
		  }
          echo '<form method="post" action="options.php">';
              settings_fields( 'gpress-general-settings-group' );
              echo '<div class="metabox-holder" id="dashboard-widgets">';
                  echo '<div class="postbox-container" style="width:36%">';
                    /* LEFT COLUMN */
                    include(GPRESS_DIR.'/gpress-options/map-settings.php');
                  echo '</div>';
                  echo '<div class="postbox-container" style="width:36%">';
                    /* MIDDLE COLUMN */
                    include(GPRESS_DIR.'/gpress-options/marker-settings.php');
                  echo '</div>';
                  echo '<div class="postbox-container credits" style="width:25%">';
                    /* RIGHT COLUMN */
                    if($show_credits=='enabled'){
                        include(GPRESS_DIR.'/gpress-options/gpress-credits.php');
                    }
                  echo '</div>';
              echo '</div>';
        echo '</form>';
    echo '</div>';
  echo '</div>';
}

function gpress_components_page() {
  $show_credits = get_site_option('gp_show_credits','enabled');
  /* GPRESS COMPONENTS */
  if (!current_user_can('manage_options'))  {
    wp_die( __('You do not have sufficient permissions to access this page.') );
  }
  echo '<div id="gpress-options-page" class="wrap">';
      echo '<div class="icon32" id="icon-index"><br></div><h2>gPress Options > Components</h2>';
      echo '<div id="dashboard-widgets-wrap">';
          echo '<form method="post" action="options.php">';
              settings_fields( 'gpress-components-group' );
              echo '<div class="metabox-holder" id="dashboard-widgets">';
                  echo '<div class="postbox-container" style="width:36%">';
                    /* LEFT COLUMN */
                    include(GPRESS_DIR.'/gpress-options/module-control.php');
                  echo '</div>';
                  echo '<div class="postbox-container" style="width:36%">';
                    /* MIDDLE COLUMN */
                    include(GPRESS_DIR.'/gpress-options/geotagged-posts.php');
                  echo '</div>';
                  echo '<div class="postbox-container credits" style="width:25%">';
                    /* RIGHT COLUMN */
                    if($show_credits=='enabled'){
                        include(GPRESS_DIR.'/gpress-options/gpress-credits.php');
                    }
                  echo '</div>';
              echo '</div>';
          echo '</form>';
      echo '</div>';
  echo '</div>';
}


function advanced_settings_page() {
  $show_credits = get_site_option('gp_show_credits','enabled');
  /* ADVANCED SETTINGS */
  if (!current_user_can('manage_options'))  {
    wp_die( __('You do not have sufficient permissions to access this page.') );
  }
  echo '<div id="gpress-options-page" class="wrap">';
      echo '<div class="icon32" id="icon-index"><br></div><h2>gPress Options > Advanced Settings</h2>';
      echo '<span class="option-group-intro">'.__('All of the options seen on this Advanced Settings page are only accesible and visible to Super Adminstrators','gpress').'</span>';
      echo '<div id="dashboard-widgets-wrap">';
          echo '<form method="post" action="options.php">';
              settings_fields( 'gpress-advanced-settings-group' );
              echo '<div class="metabox-holder" id="dashboard-widgets">';
                  echo '<div class="postbox-container" style="width:36%">';
                    /* LEFT COLUMN */
                    include(GPRESS_DIR.'/gpress-options/loop-settings.php');
                    include(GPRESS_DIR.'/gpress-options/excerpt-settings.php');
                  echo '</div>';
                  echo '<div class="postbox-container" style="width:36%">';
                    /* MIDDLE COLUMN */
                    include(GPRESS_DIR.'/gpress-options/credit-settings.php');
                    include(GPRESS_DIR.'/gpress-options/misc-settings.php');
                    include(GPRESS_DIR.'/gpress-options/custom-pages.php');
                  echo '</div>';
                  echo '<div class="postbox-container credits" style="width:25%">';
                    /* RIGHT COLUMN */
                    include(GPRESS_DIR.'/gpress-options/language-and-lingo.php');
                    include(GPRESS_DIR.'/gpress-options/gpress-credits.php');
                  echo '</div>';
              echo '</div>';
          echo '</form>';
      echo '</div>';
  echo '</div>';
}

function buddypress_settings_page() {
  $show_credits = get_site_option('gp_show_credits','enabled');
  /* BUDDYPRESS SETTINGS */
  if (!current_user_can('manage_options'))  {
    wp_die( __('You do not have sufficient permissions to access this page.') );
  }
  echo '<div id="gpress-options-page" class="wrap">';
      echo '<div class="icon32" id="icon-index"><br></div><h2>gPress Options > BuddyPress Settings</h2>';
      echo '<div id="dashboard-widgets-wrap">';
          echo '<form method="post" action="options.php">';
              settings_fields( 'gpress-buddypress-settings-group' );
              echo '<div class="metabox-holder" id="dashboard-widgets">';
                  echo '<div class="postbox-container" style="width:36%">';
                    /* LEFT COLUMN */
                    include(GPRESS_DIR.'/gpress-options/buddypress-components.php');
                  echo '</div>';
                  echo '<div class="postbox-container" style="width:36%">';
                    /* MIDDLE COLUMN */
                    include(GPRESS_DIR.'/gpress-options/buddypress-maps.php');
                  echo '</div>';
                  echo '<div class="postbox-container credits" style="width:25%">';
                    /* RIGHT COLUMN */
                    if($show_credits=='enabled'){
                        include(GPRESS_DIR.'/gpress-options/gpress-credits.php');
                    }
                  echo '</div>';
              echo '</div>';
          echo '</form>';
      echo '</div>';
  echo '</div>';
}

?>
