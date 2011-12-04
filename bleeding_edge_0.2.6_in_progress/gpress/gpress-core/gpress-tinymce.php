<?php

// TINYMCE
function gpress_tinymce() {
	$limited_wysiwyg = true;
	$limited_wysiwyg = apply_filters( 'gmaps_limit_wysiwyg', $limited_wysiwyg );
	if($limited_wysiwyg) {
		if(!current_user_can('edit_posts') && !current_user_can('edit_pages')) {
			return;
		}
	}
   	if ( get_user_option('rich_editing') == 'true') {
    	add_filter("mce_external_plugins", "gpress_tinymce_plugin");
     	add_filter('mce_buttons', 'gpress_tinymce_button');
   	}
}
function gpress_tinymce_button($buttons) {
   array_push($buttons, "separator", "gPress");
   return $buttons;
}
function gpress_tinymce_plugin($plugin_array) {
   $plugin_array['gPress'] = GPRESS_URL .'/gpress-core/tinymce/editor_plugin.js';
   return $plugin_array;
}

?>