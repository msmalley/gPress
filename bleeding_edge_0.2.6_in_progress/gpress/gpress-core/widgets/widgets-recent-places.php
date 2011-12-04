<?php

add_action( 'widgets_init', 'gpress_recent_places_load_widgets' );

function gpress_recent_places_load_widgets() {
	register_widget( 'GPRESS_RECENT_PLACES_WIDGET' );
}

class GPRESS_RECENT_PLACES_WIDGET extends WP_Widget {

	function GPRESS_RECENT_PLACES_WIDGET() {
		$widget_ops = array( 'classname' => 'gpress-recent-places', 'description' => __('The most recent places on your site', 'gpress') );
		$this->WP_Widget( 'gpress-recent-places-widget', __('Recent Places', 'gpress'), $widget_ops, $control_ops );
	}

	function widget( $args, $instance ) {
		extract( $args );

		$name = $instance['name'];
		$count = $instance['count'];
		
		if(empty($count)) {
			$count = 5;
		}

		echo $before_widget;

			/* Display name from widget settings if one was input. */
			if ( $name ) {
				echo '<h3 class="widgettitle widget-title">'.$instance['name'].'</h3>';
			}else{
				echo '<h3 class="widgettitle widget-title">'.__('Recent Places', 'gpress').'</h3>';
			}	
			
			query_posts('post_type='.__('place', 'gpress').'&showposts='.$count.'');
			echo '<ul>';
				if ( have_posts() ) : while ( have_posts() ) : the_post();
					echo '<li><a href="'.get_permalink().'">'.get_the_title().'</a></li>';
				endwhile; else:
				endif;
			echo '</ul>';
			wp_reset_query();

		echo $after_widget;
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		/* Strip tags for title and name to remove HTML (important for text inputs). */		
		$instance['name'] = $new_instance['name'];
		$instance['count'] = $new_instance['count'];

		return $instance;
	}

	function form( $instance ) {

		$instance = wp_parse_args( (array) $instance ); ?>

		<p>
			<label for="<?php echo $this->get_field_id( 'name' ); ?>"><?php echo __('Widget Title:', 'gpress'); ?></label>
			<input id="<?php echo $this->get_field_id( 'name' ); ?>" name="<?php echo $this->get_field_name( 'name' ); ?>" value="<?php echo $instance['name']; ?>" style="width:95%;" />
		</p>
        
        <p>
			<label for="<?php echo $this->get_field_id( 'count' ); ?>"><?php echo __('Number of Places:', 'gpress'); ?></label>
			<input id="<?php echo $this->get_field_id( 'count' ); ?>" name="<?php echo $this->get_field_name( 'count' ); ?>" value="<?php echo $instance['count']; ?>" style="width:95%;" />
		</p>

	<?php
	}
}

?>