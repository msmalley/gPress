<?php

add_action( 'widgets_init', 'gpress_all_posts_load_widgets' );

function gpress_all_posts_load_widgets() {
	register_widget( 'GPRESS_ALL_POSTS_WIDGET' );
}

class GPRESS_ALL_POSTS_WIDGET extends WP_Widget {

	function GPRESS_ALL_POSTS_WIDGET() {
		$widget_ops = array( 'classname' => 'gpress-all-posts', 'description' => __('Show all geo-tagged posts', 'gpress') );
		$this->WP_Widget( 'gpress-all-posts-widget', __('All Geo-Tagged Posts', 'gpress'), $widget_ops, $control_ops );
	}

	function widget( $args, $instance ) {
		extract( $args );

		$name = $instance['name'];
		$type = $instance['type'];
		$zoom = $instance['zoom'];
		$height = $instance['height'];

                if(empty($height)){
                    $height=350;
                }
		
		$gpress_this_widget_title = __('All Geo-Tagged Posts', 'gpress');

		echo $before_widget;

			if($title!='HIDE') {
				if ( $name ) {
					echo '<h3 class="widgettitle widget-title">'.$instance['name'].'</h3>';
				}else{
					echo '<h3 class="widgettitle widget-title">'.$gpress_this_widget_title.'</h3>';
				}
			}
			echo do_shortcode('[gpress place_id="all" post_type="posts" map_type="'.$type.'" map_zoom="'.$zoom.'" map_height="'.$height.'"]');

		echo $after_widget;
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		/* Strip tags for title and name to remove HTML (important for text inputs). */		
		$instance['name'] = $new_instance['name'];
		$instance['type'] = $new_instance['type'];
		$instance['zoom'] = $new_instance['zoom'];
		$instance['height'] = $new_instance['height'];

		return $instance;
	}

	function form( $instance ) {
		
		$instance = wp_parse_args( (array) $instance );
                $default_map_height = get_site_option('gp_map_height','350');
		
		?>

		<p>
			<label for="<?php echo $this->get_field_id( 'name' ); ?>"><?php echo __('Widget Title:', 'gpress'); ?></label>
			<input id="<?php echo $this->get_field_id( 'name' ); ?>" name="<?php echo $this->get_field_name( 'name' ); ?>" value="<?php echo $instance['name']; ?>" style="width:95%;" />
		</p>
        
        <table width="100%" border="0" cellspacing="0" cellpadding="0" class="gpress_meta_sidebar_table">
          <tr>
            <td width="50%">
                <input type="radio" name="<?php echo $this->get_field_name( 'type' ); ?>" value="ROADMAP" <?php if($instance['type'] == 'ROADMAP') { ?> checked="checked" autocomplete="off" <?php } ?> /><span class="input_label">Roadmap</span><br />
                <input type="radio" name="<?php echo $this->get_field_name( 'type' ); ?>" value="SATELLITE" <?php if(($instance['type'] == 'SATELLITE') || (empty($instance['title']))) { ?> checked="checked" autocomplete="off" <?php } ?> /><span class="input_label">Satellite</span><br />
                <input type="radio" name="<?php echo $this->get_field_name( 'type' ); ?>" value="HYBRID" <?php if($instance['type'] == 'HYBRID') { ?> checked="checked" autocomplete="off" <?php } ?> /><span class="input_label">Hybrid</span><br />
                <input type="radio" name="<?php echo $this->get_field_name( 'type' ); ?>" value="TERRAIN" <?php if($instance['type'] == 'TERRAIN') { ?> checked="checked" autocomplete="off" <?php } ?> /><span class="input_label">Terrain</span><br />
            </td>
            <td width="10px" class="divider">&nbsp;</td>
            <td width="50%">
                <input type="radio" name="<?php echo $this->get_field_name( 'zoom' ); ?>" value="18" <?php if($instance['zoom'] == '18') { ?> checked="checked" autocomplete="off" <?php } ?> /><span class="input_label">Close-Up</span><br />
                <input type="radio" name="<?php echo $this->get_field_name( 'zoom' ); ?>" value="13" <?php if(($instance['zoom'] == '13') || (empty($instance['title']))) { ?> checked="checked" autocomplete="off" <?php } ?> /><span class="input_label">Nearby</span><br />
                <input type="radio" name="<?php echo $this->get_field_name( 'zoom' ); ?>" value="10" <?php if($instance['zoom'] == '10') { ?> checked="checked" autocomplete="off" <?php } ?> /><span class="input_label">Cities</span><br />
                <input type="radio" name="<?php echo $this->get_field_name( 'zoom' ); ?>" value="5" <?php if($instance['zoom'] == '5') { ?> checked="checked" autocomplete="off" <?php } ?> /><span class="input_label">Countries</span><br />
            </td>
          </tr>
        </table>
        
        <div class="advanced_holder">
            <p><a href="#" id="advanced_settings_<?php echo $this->get_field_id( 'placeid' ); ?>"><?php echo __('Advanced Settings', 'gpress'); ?></a></p>
            <div id="gpress_advanced_hidden_<?php echo $this->get_field_id( 'placeid' ); ?>" style="display:none;">
                <span class="advanced_divider"></span>
                <label><?php echo __('Overwrite default height for this map:', 'gpress'); ?></label>
                <input type="text" name="<?php echo $this->get_field_name( 'height' ); ?>" value="<?php echo $instance['height']; ?>" style="width:100%;" />
                <p class="advanced_description"><?php echo __('Numbers only - Defaults to 350', 'gpress'); ?></p>
            </div>
        </div>
        
        <script type="text/javascript">
        /* NEED TO MAKE THIS GLOBAL FUNCTION FOR WIDGETS AND FORMS */
        jQuery(document).ready(function(){
            var widget_all_posts_state; if(!widget_all_posts_state){
                widget_all_posts_state = 'closed';
                jQuery("#advanced_settings_<?php echo $this->get_field_id( 'placeid' ); ?>").addClass('closed');
            }
            jQuery("#advanced_settings_<?php echo $this->get_field_id( 'placeid' ); ?>.closed").live('click',function(e) {
                e.preventDefault();
                jQuery(this).parent().next().css({'display':'block'});
                jQuery(this).removeClass('closed');
                jQuery(this).addClass('opened');
            });
            jQuery("#advanced_settings_<?php echo $this->get_field_id( 'placeid' ); ?>.opened").live('click',function(e) {
                e.preventDefault();
                jQuery(this).parent().next().css({'display':'none'});
                jQuery(this).removeClass('opened');
                jQuery(this).addClass('closed');
            });
        });
        </script>

	<?php
	}
}

?>