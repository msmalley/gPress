<?php
 
function gpress_meta_normal_init() {
	
	$places_taxonomy = __( 'place', 'gpress' );
 
	wp_enqueue_style('gpress_meta_box_places_css', GPRESS_URL . '/gpress-core/css/meta.css');

	foreach (array($places_taxonomy) as $type) {
		$id 		= 'gpress_places_normal';
		$title 		= __('Location of Place:', 'gpress');
		$callback	= 'gpress_meta_normal_places_content';
		$page 		= $places_taxonomy;
		$context 	= 'normal';
		$priority 	= 'high';
		add_meta_box( $id, $title, $callback, $page, $context, $priority );
	}
	add_action('save_post','gpress_meta_normal_places_save');
	
	$use_geopost = get_site_option('gp_module_posts','enabled');
	
	if($use_geopost == 'enabled') {

		foreach (array('post') as $type) {
			$id 		= 'gpress_posts_normal';
			$title 		= __('Location of Post:', 'gpress');
			$callback	= 'gpress_meta_normal_posts_content';
			$page 		= 'post';
			$context 	= 'normal';
			$priority 	= 'high';
			add_meta_box( $id, $title, $callback, $page, $context, $priority );
		}
		add_action('save_post','gpress_meta_normal_posts_save');
	
	}

}

function gpress_meta_sidebar_init() {
	
	$places_taxonomy = __( 'place', 'gpress' );

	foreach (array($places_taxonomy) as $type) {
		$id 		= 'gpress_places_sidebar';
		$title 		= __('Map Options', 'gpress');
		$callback	= 'gpress_meta_sidebar_places_content';
		$page 		= $places_taxonomy;
		$context 	= 'side';
		$priority 	= 'high';
		add_meta_box( $id, $title, $callback, $page, $context, $priority );
	}
	add_action('save_post','gpress_meta_sidebar_places_save');
	
	$use_geopost = get_site_option('gp_module_posts','enabled');
	
	if($use_geopost == 'enabled') {
	
		foreach (array('post') as $type) {
			$id 		= 'gpress_posts_sidebar';
			$title 		= __('Map Options', 'gpress');
			$callback	= 'gpress_meta_sidebar_posts_content';
			$page 		= 'post';
			$context 	= 'side';
			$priority 	= 'high';
			add_meta_box( $id, $title, $callback, $page, $context, $priority );
		}
		add_action('save_post','gpress_meta_sidebar_posts_save');
	
	}

}
 
function gpress_meta_normal_places_content() {
	
	global $post;

	$meta = get_post_meta($post->ID,'_gpress_places',TRUE);
	
	include(GPRESS_DIR . '/gpress-core/meta/meta-normal-places.php');
 
	// create a custom nonce for submit verification later
	echo '<input type="hidden" name="gpress_places_meta_normal_noncename" value="' . wp_create_nonce(__FILE__) . '" />';
}

function gpress_meta_normal_posts_content() {
	
	global $post;
	
	$geo_latitude = get_post_meta($post->ID,'geo_latitude',TRUE);
	
	if(!empty($geo_latitude)) {
	
		include(GPRESS_DIR . '/gpress-core/meta/meta-normal-posts.php');
	 
		// create a custom nonce for submit verification later
		echo '<input type="hidden" name="gpress_posts_meta_normal_noncename" value="' . wp_create_nonce(__FILE__) . '" />';
	
	}else{
		
            $force_geopost = get_site_option('gp_force_geo','disabled');	
		
		if($force_geopost !== 'enabled') {
		
			// Surprsingly, this does not work
			// remove_meta_box( 'gpress_posts_normal' , 'post' , 'normal' ); 
			
			/* CANNOT FIND CORRECT WAY TO REMOVE SO AM HIDING IT WITH CSS */
			echo '<style> div#gpress_posts_normal { display:none !important; } </style>';
		
		}else{
			
			include(GPRESS_DIR . '/gpress-core/meta/meta-normal-posts.php');
		 
			// create a custom nonce for submit verification later
			echo '<input type="hidden" name="gpress_posts_meta_normal_noncename" value="' . wp_create_nonce(__FILE__) . '" />';
			
		}
		
	}
	
}

function gpress_meta_sidebar_places_content() {
	
	global $post;

	$meta = get_post_meta($post->ID,'_gpress_places',TRUE);
	
	include(GPRESS_DIR . '/gpress-core/meta/meta-sidebar-places.php');
 
	// create a custom nonce for submit verification later
	echo '<input type="hidden" name="gpress_places_meta_sidebar_noncename" value="' . wp_create_nonce(__FILE__) . '" />';
}

function gpress_meta_sidebar_posts_content() {
	
	global $post;
	
	$meta = get_post_meta($post->ID,'_gpress_posts',TRUE);
	$geo_latitude = get_post_meta($post->ID,'geo_latitude',TRUE);
	
	if(!empty($geo_latitude)) {
	
		include(GPRESS_DIR . '/gpress-core/meta/meta-sidebar-posts.php');
		
		// create a custom nonce for submit verification later
		echo '<input type="hidden" name="gpress_posts_meta_sidebar_noncename" value="' . wp_create_nonce(__FILE__) . '" />';
		
	}else{
		
                $force_geopost = get_site_option('gp_force_geo','disabled');
		
		if($force_geopost !== 'enabled') {
		
			// Surprsingly, this does not work
			// remove_meta_box( 'gpress_posts_sidebar' , 'post' , 'normal' ); 
			
			/* CANNOT FIND CORRECT WAY TO REMOVE SO AM HIDING IT WITH CSS */
			echo '<style> div#gpress_posts_sidebar { display:none !important; } </style>';
		
		}else{
			
			include(GPRESS_DIR . '/gpress-core/meta/meta-sidebar-posts.php');
			
			// create a custom nonce for submit verification later
			echo '<input type="hidden" name="gpress_posts_meta_sidebar_noncename" value="' . wp_create_nonce(__FILE__) . '" />';
			
		}
		
	}

}
 
function gpress_meta_normal_places_save($post_id) {
 
	// make sure data came from our meta box
	if (!wp_verify_nonce($_POST['gpress_places_meta_normal_noncename'],__FILE__)) return $post_id;
 
	// check user permissions
	if ($_POST['post_type'] == 'page') {
		if (!current_user_can('edit_page', $post_id)) return $post_id;
	}else{
		if (!current_user_can('edit_post', $post_id)) return $post_id;
	}
 
	// authentication passed, save data 
	$current_data = get_post_meta($post_id, '_gpress_places', TRUE);	
	$new_data = $_POST['_gpress_places'];
	my_meta_clean($new_data);

	if ($current_data) {
		if (is_null($new_data)) delete_post_meta($post_id,'_gpress_places');
		else update_post_meta($post_id,'_gpress_places',$new_data);
	}elseif(!is_null($new_data)) {
		add_post_meta($post_id,'_gpress_places',$new_data,TRUE);
	}
	return $post_id;
}

function gpress_meta_normal_posts_save($post_id) {
	
	// make sure data came from our meta box
	if (!wp_verify_nonce($_POST['gpress_posts_meta_normal_noncename'],__FILE__)) return $post_id;
 
	// check user permissions
	if ($_POST['post_type'] == 'page') {
		if (!current_user_can('edit_page', $post_id)) return $post_id;
	}else{
		if (!current_user_can('edit_post', $post_id)) return $post_id;
	}
 
	// authentication passed, save data 
	$current_data = get_post_meta($post_id, '_gpress_places', TRUE);	
	$new_data = $_POST['_gpress_places'];
	my_meta_clean($new_data);
	
	$geo_array = $_POST['_gpress_posts'];
	$geo_latlng = $geo_array['latlng'];

	if($_POST['post_type'] == 'post') {
		
		if(empty($geo_latlng)){
			$geo_lat = "";
			$geo_lng = "";
		}else{
			$geo_latlng = explode(",",$geo_latlng);
			$geo_lat = trim($geo_latlng[0]);
			$geo_lng = trim($geo_latlng[1]);
		}
	
		update_post_meta($post_id,'geo_latitude', $geo_lat);
		update_post_meta($post_id,'geo_longitude', $geo_lng);
		
		$force_geopost = get_site_option('gp_force_geo','disabled');
		if($force_geopost == 'enabled') {
			update_post_meta($post_id,'geo_anabled', 1);
			update_post_meta($post_id,'geo_public', 1);
		}
	
	}

	if ($current_data) {
		if (is_null($new_data)) {
			delete_post_meta($post_id,'_gpress_places');
		}else{
			update_post_meta($post_id,'_gpress_places',$new_data);
		}
	}elseif(!is_null($new_data)) {
		add_post_meta($post_id,'_gpress_places',$new_data,TRUE);
	}
	return $post_id;
}

function gpress_meta_sidebar_places_save($post_id) {
 
	// make sure data came from our meta box
	if (!wp_verify_nonce($_POST['gpress_places_meta_sidebar_noncename'],__FILE__)) return $post_id;
 
	// check user permissions
	if ($_POST['post_type'] == 'page') {
		if (!current_user_can('edit_page', $post_id)) return $post_id;
	}else{
		if (!current_user_can('edit_post', $post_id)) return $post_id;
	}
 
	// authentication passed, save data 
	$current_data = get_post_meta($post_id, '_gpress_places', TRUE);	
	$new_data = $_POST['_gpress_places'];
	my_meta_clean($new_data);

	if ($current_data) {
		if (is_null($new_data)) delete_post_meta($post_id,'_gpress_places');
		else update_post_meta($post_id,'_gpress_places',$new_data);
	}elseif(!is_null($new_data)) {
		add_post_meta($post_id,'_gpress_places',$new_data,TRUE);
	}
	return $post_id;
}

function gpress_meta_sidebar_posts_save($post_id) {
 
	// make sure data came from our meta box
	if (!wp_verify_nonce($_POST['gpress_posts_meta_sidebar_noncename'],__FILE__)) return $post_id;
 
	// check user permissions
	if ($_POST['post_type'] == 'page') {
		if (!current_user_can('edit_page', $post_id)) return $post_id;
	}else{
		if (!current_user_can('edit_post', $post_id)) return $post_id;
	}
 
	// authentication passed, save data 
	$current_data = get_post_meta($post_id, '_gpress_posts', TRUE);	
	$new_data = $_POST['_gpress_posts'];
	my_meta_clean($new_data);

	if ($current_data) {
		if (is_null($new_data)) delete_post_meta($post_id,'_gpress_posts');
		else update_post_meta($post_id,'_gpress_posts',$new_data);
	}elseif(!is_null($new_data)) {
		add_post_meta($post_id,'_gpress_posts',$new_data,TRUE);
	}
	return $post_id;
}
 
function my_meta_clean(&$arr) {
	if (is_array($arr)) {
		foreach ($arr as $i => $v) {
			if (is_array($arr[$i])) {
				my_meta_clean($arr[$i]);
				if (!count($arr[$i])) {
					unset($arr[$i]);
				}
			}else{
				if (trim($arr[$i]) == '') {
					unset($arr[$i]);
				}
			}
		}
		if (!count($arr)) {
			$arr = NULL;
		}
	}
}

?>