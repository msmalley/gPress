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
	?>

	<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
	<script type="text/javascript" src="http://www.google.com/jsapi"></script>
        <script type="text/javascript">
	
		/* RUNNING GPRESS_VERSION = <?php echo GPRESS_VERSION; ?> */
		/* LAST UPDATED OPTIONS IN VERSION = <?php echo $gpress_version_number; ?> */
		
		jQuery.belowthefold = function(element, settings) {
			var fold = jQuery(element).parents(settings.container).height() + jQuery(element).parents(settings.container).scrollTop();
			return fold <= jQuery(element).position().top - settings.threshold;
		};
		
		jQuery.rightoffold = function(element, settings) {
			var fold = jQuery(element).parents(settings.container).width() + jQuery(element).parents(settings.container).scrollLeft();
			return fold <= jQuery(element).position().left - settings.threshold;
		};
			
		jQuery.abovethetop = function(element, settings) {
			var fold = jQuery(element).parents(settings.container).scrollTop();
			return fold >= jQuery(element).position().top + settings.threshold  + jQuery(element).height();
		};
		
		jQuery.leftofbegin = function(element, settings) {
			var fold = jQuery(element).parents(settings.container).scrollLeft();
			return fold >= jQuery(element).position().left + settings.threshold + jQuery(element).width();
		};
		
		var alreadyInit = new Array()
		function array_find(array,item){
			for(var i=0;i<array.length;i++){
				if(item == array[i])
					return true;
			}	
			return false;
		}
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