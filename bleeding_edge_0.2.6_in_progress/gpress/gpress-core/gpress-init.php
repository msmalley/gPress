<?php

// HARDCODED FALSE FOR NOW - HERE FOR EMERGENCIES ONLY
$use_jquery_142 = false;

function load_essentials() {
	add_theme_support( 'post-thumbnails' );
}
add_action('after_setup_theme', 'load_essentials');

/* Replace and Register Scripts and Styles */
if($use_jquery_142) {
	wp_deregister_script( 'jquery' );
	wp_register_script( 'jquery', GPRESS_URL.'/gpress-core/js/jquery-1.4.2.min.js');
}
wp_register_script( 'textchange', GPRESS_URL.'/gpress-core/js/jquery.textchange.min.js');
wp_register_script( 'gpress_sidebar_advanced', GPRESS_URL.'/gpress-core/js/gpress_sidebar_advanced.js');
wp_register_style( 'cssmaps', GPRESS_URL.'/gpress-core/css/maps.css');
wp_register_style( 'cssforms', GPRESS_URL.'/gpress-core/css/forms.css');

// CHECK FOR CUSTOM CSS AND CUSTOM JS
if(file_exists(GPRESS_CS_CSS_DIR)) {
	wp_register_style( 'csscustom', GPRESS_CS_CSS_URL );
}
if(file_exists(GPRESS_CS_JS_DIR)) {
	wp_register_script( 'jscustom', GPRESS_CS_JS_URL );
}

/* Use These Scripts and Styles on ADMIN Only Pages */
function scripts_for_admin_pages() {
	if($use_jquery_142) {
		wp_enqueue_script('jquery');
		wp_deregister_script( 'editor' );
		wp_register_script( 'editor' , null, 'jquery');
	}
	global $pagenow;
	if($pagenow == 'admin.php') {
		if($_GET['page'] == "tpp_options_form") {
			wp_deregister_script( 'jquery-ui-tabs' );
			wp_register_script( 'jquery-ui', GPRESS_URL.'/gpress-admin/js/ui-customised.js');
			wp_enqueue_script('jquery-ui');
		}
	}
	switch($pagenow){
		case "post-new.php":
		case "widgets.php":
		case "post.php":
			wp_enqueue_script('textchange');
			wp_enqueue_script('gpress_sidebar_advanced');
			wp_enqueue_style('cssforms');
			break;
	}
}
add_action('admin_init', 'scripts_for_admin_pages');

/* Use These Scripts and Styles on THEME Only Pages */
function styles_for_theme_pages() {
	wp_enqueue_style('cssmaps');
	if(file_exists(GPRESS_CS_CSS_DIR)) {
		wp_enqueue_style('csscustom');
	}
}

function scripts_for_theme_pages() {
	$places_taxonomy = __( 'place', 'gpress' );
	$home_loop = get_site_option('gp_homepage_loop','BOTH');
        $home_loop_method = get_site_option('gp_homepage_loop_method','query');
        if($home_loop == 'BOTH') {
                if(is_home()) {
                        global $wpdb, $wp_query;
                        query_posts(
                                array_merge(
                                        array('post_type' => array('post', $places_taxonomy)),
                                        $wp_query->query
                                )
                        );

                }
        }elseif($home_loop == 'PLACES') {
                if(is_home()) {
                        global $wp_query;
                        query_posts(
                                array_merge(
                                        array('post_type' => $places_taxonomy),
                                        $wp_query->query
                                )
                        );
                }
        }else{
                // DO NOTHING
        }
	/* NEED TO BETTER ENQUEUE THIS LOT */
        $default_cluster_bg = GPRESS_URL.'/gpress-core/images/markers/bg.png';
        $default_cluster_size = 40; $default_cluster_text_size = 18;
        $default_cluster_text_colour = '#FFFFFF';
        $cluster_bg = apply_filters('gpress_cluster_bg',$default_cluster_bg);
        $cluster_size = apply_filters('gpress_cluster_size',$default_cluster_size);
        $cluster_text_size = apply_filters('gpress_cluster_text_size',$default_cluster_text_size);
        $cluster_text_colour = apply_filters('gpress_cluster_text_colour',$default_cluster_text_colour);
	?>

	<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
        <script type="text/javascript" src="http://code.google.com/apis/gears/gears_init.js"></script>
        <script type="text/javascript" src="<?php echo GPRESS_URL; ?>/gpress-core/js/geo.js"></script>
        <script type="text/javascript" src="<?php echo GPRESS_URL; ?>/gpress-core/js/gpress-maps.js"></script>
	<script type="text/javascript" src="http://www.google.com/jsapi"></script>
        <script type="text/javascript">
            var current_lat; var current_lng;
            var map = new Array(); var marker = new Array(); var geocoder = new Array();
            var marker_cluster = new Array(); var clustered_markers = new Array();
            var gpress_info_box = new Array(); var canvas_width = new Array();
            var canvas_height = new Array(); var inner_width = new Array();
            var gpress_loading_interval = new Array();
            var new_info_box = new Array();
            var gpress_index = new Array();
            var this_marker = new Array();
            var styles = [[{
                url: '<?php echo $cluster_bg; ?>',
                height: <?php echo $cluster_size; ?>,
                width: <?php echo $cluster_size; ?>,
                opt_anchor: [16, 0],
                opt_textColor: '<?php echo $cluster_text_colour; ?>',
                opt_textSize: <?php echo $cluster_text_size; ?>
            }]];
            /* RUNNING GPRESS_VERSION = <?php echo GPRESS_VERSION; ?> */
	</script>
    <?php
}

/* JS FOR THEME PAGES ONLY */
function js_for_theme_pages() {
    wp_register_script( 'clusters', GPRESS_URL.'/gpress-core/js/markerclusterer.js');
    wp_enqueue_script('clusters');
    $use_js_in_theme = get_site_option('gp_misc_jquery','yes');
    if($use_js_in_theme == 'yes') {
            wp_register_script( 'jquery142', GPRESS_URL.'/gpress-core/js/jquery-1.4.2.min.js');
            wp_enqueue_script('jquery142');
    }
    if(file_exists(GPRESS_CS_JS_DIR)) {
            wp_enqueue_script('jscustom');
    }
}
add_action('wp_print_styles', 'styles_for_theme_pages');
add_action('wp_head', 'scripts_for_theme_pages', 43);
add_action('wp', 'js_for_theme_pages');
	
?>