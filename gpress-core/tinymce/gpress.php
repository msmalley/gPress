<?php

// FINDING WP
include('../../../../../wp-load.php');

// ONCE WP LOADED
require_once(ABSPATH.'/wp-admin/admin.php');
if(!current_user_can('edit_posts')) die;
do_action('admin_init');

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

	<title><?php echo __('gPress TinyMCE Editor', 'gpress'); ?></title>
    
	<?php 
	include( GPRESS_DIR. '/gpress-core/tinymce/scripts.php' ); 
	?>
    
	<script language="javascript" type="text/javascript">
	
	var _self = tinyMCEPopup;
	
	function insertTag () {
		
		var tag = '';

		var mapposition = jQuery('#map-position').val();
		var markertitle = jQuery('#marker-title').val();
		var maptype = jQuery("input[name='map-type']:checked").val();
		var mapzoom = jQuery("input[name='map-zoom']:checked").val();
		
		if(markertitle == '') {
			markertitle = 'My Map';
		}
		
		if(maptype) {
			var map_type = ' map_type="'+maptype+'"';
		}
		if(mapzoom) {
			var map_zoom = ' map_zoom="'+mapzoom+'"';
		}
		
		if(mapposition) {
			tag = '[gpress map_position="'+mapposition+'" marker_title="'+markertitle+'"'+map_type+''+map_zoom+']';
		}
		
		if(window.tinyMCE) {
			window.tinyMCE.execInstanceCommand('<?php $gpress_tinymce_textarea = 'content'; $gpress_tinymce_textarea = apply_filters('gpress_tinymce_textarea', $gpress_tinymce_textarea); echo $gpress_tinymce_textarea; ?>', 'mceInsertContent', false, tag);
			tinyMCEPopup.editor.execCommand('mceRepaint');
			tinyMCEPopup.close();
		}
				
	}
	
	function closePopup () {
		tinyMCEPopup.close();
	}
		
	</script>
	
</head>
<body>

<div id="wpwrap">
    <form onsubmit="insertTag();return false;" action="#" id="gpress_tinymce">
    
		<?php include( GPRESS_DIR. '/gpress-core/tinymce/map.php' ); ?>
        
        <?php include( GPRESS_DIR. '/gpress-core/tinymce/advanced.php' ); ?>
            
        <div class="mceActionPanel">
            <div style="float: left">
                <input type="button" id="cancel" name="cancel" value="{#cancel}" onclick="closePopup()"/>
            </div>
    
            <div style="float: right">
                <input type="button" id="insert" name="insert" value="{#insert}" onclick="insertTag()" />
            </div>
        </div>
        
    </form>
</div>

</body>
</html>