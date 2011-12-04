<?php
	
function gpress_add_map($gpress_map_settings) {
	
	global $blog_id;
	$places_taxonomy = __( 'place', 'gpress' );
	$places_taxonomy_plural = __( 'places', 'gpress' );
	$places_tag_plural = __( 'placed', 'gpress' );
	$places_types = __( 'Type(s) of Place: ', 'gpress' );
	$places_tags = __( 'Tag(s): ', 'gpress' );
	
	$original_blog_id = $blog_id;
	if(is_multisite()) {
		switch_to_blog(1);
			$gpress_upload_url = get_bloginfo('url').'/wp-content/gpress/'.$original_blog_id;
		restore_current_blog();
	}else{
		$gpress_upload_url = get_bloginfo('url').'/wp-content/gpress/'.$original_blog_id;
	}
	
	// Default Map Settings
	$map_settings_default = array(
		'map_id' 		=> false,
		'map_height' 		=> false,
		'map_type' 		=> false,
		'map_zoom' 		=> false,
		'map_position' 		=> false,
		'post_type' 		=> false,
		'post_id' 		=> false,
		'place_id' 		=> false,
		'widget_id' 		=> false,
		'marker_icon' 		=> false,
		'marker_shadow'		=> false,
		'marker_title' 		=> false,
		'description'           => false,
		'marker_url' 		=> false,
		'bp_user_array'		=> false,
		'four_you'		=> false,
		'four_friends'		=> false
	);
	$map_settings = array_merge($map_settings_default,$gpress_map_settings);
	extract($map_settings);
	
	// gPress Map Options
        $default_map_height = get_site_option('gp_map_height','450');
        $default_map_type = get_site_option('gp_map_type','ROADMAP');
        $default_map_zoom = get_site_option('gp_map_zoom','13');
	
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
	
	// gPress Place Markers
        $marker_places_icon_url = get_site_option('gp_marker_url_places');
        $marker_places_shadow_url = get_site_option('gp_shadow_url_places');

	if(!empty($marker_places_icon_url)) {
		$default_marker_icon_place = $marker_places_icon_url;
	}else{
		if(!empty($marker_places_icon_file)) {
			$default_marker_icon_place = $gpress_upload_url.'/'.$marker_places_icon_file;
		} else {
			$default_marker_icon_place = GPRESS_URL.'/gpress-core/images/markers/place.png';
		}
	}
	if(!empty($marker_places_shadow_url)) {
		$default_marker_shadow_place = $marker_places_shadow_url;
	}else{
		if(!empty($marker_places_shadow_file)) {
			$default_marker_shadow_place = $gpress_upload_url.'/'.$marker_places_shadow_file;
		} else {
			$default_marker_shadow_place = GPRESS_URL.'/gpress-core/images/markers/bg.png';
		}
	}
	
	// gPress Favorite Place Widget Markers
        $marker_favwidget_icon_url = get_site_option('gp_marker_url_favplaces');
        $marker_favwidget_shadow_url = get_site_option('gp_shadow_url_favplaces');
        
	if(!empty($marker_favwidget_icon_url)) {
		$default_marker_icon_favwidget = $marker_favwidget_icon_url;
	}else{
		if(!empty($marker_favwidget_icon_file)) {
			$default_marker_icon_favwidget = $gpress_upload_url.'/'.$marker_favwidget_icon_file;
		} else {
			$default_marker_icon_favwidget = GPRESS_URL.'/gpress-core/images/markers/widget.png';
		}
	}
	if(!empty($marker_favwidget_shadow_url)) {
		$default_marker_shadow_favwidget = $marker_favwidget_shadow_url;
	}else{
		if(!empty($marker_favwidget_shadow_file)) {
			$default_marker_shadow_favwidget = $gpress_upload_url.'/'.$marker_favwidget_shadow_file;
		} else {
			$default_marker_shadow_favwidget = GPRESS_URL.'/gpress-core/images/markers/bg.png';
		}
	}
	
	// POSTS
	if($post_type == 'post') {
		
		if(is_array($post_id)) {
			$gpid = ''.$map_id.'_'.mt_rand(0,2147483647).'';
			$place_id = $post_id;
			$marker_array = $post_id;
			if($map_id == '_bp_profile') {
				foreach($marker_array as $temp_id => $marker) {
					$map_position = $marker['geo_latlng'];
				}
				$this_map_height = $map_height;
			}
		}else{
			$gpid = ''.$map_id.'_'.$post_id.'';
			$meta = get_post_meta($post_id,'_gpress_posts',TRUE);
			$this_map_height = $meta['height'];
			
			if($map_id == '_bp_user_location') {
				$map_type = $map_type;
				$map_zoom = $map_zoom;
			}else{
				$map_type = $meta['type'];
				$map_zoom = $meta['zoom'];
			}
			
			$marker_url = get_permalink();
			
			$metalatlng = $meta['latlng'];
			$metaaddress = $meta['address'];
			$geopublic = get_post_meta($post_id,'geo_public',TRUE);
			$geolatitude = get_post_meta($post_id,'geo_latitude',TRUE);
			$geolongitude = get_post_meta($post_id,'geo_longitude',TRUE);
			$geolatlng = ''.$geolatitude.', '.$geolongitude.'';
			if($geopublic == 1) {
				if(empty($metalatlng)) {
					$metalatlng = $geolatlng;
				}
			}
			if($geopublic == 1) {
				if(empty($metaaddress)) {
					$metaaddress = $metalatlng;
				}
			}
			
			$marker_array = array();
			
			// Add Markers		
			$marker_array[$post_id]['icon_url'] = $meta['icon_url'];
			$marker_array[$post_id]['icon_file'] = $meta['icon_file'];
			$marker_array[$post_id]['shadow_url'] = $meta['shadow_url']; 
			$marker_array[$post_id]['shadow_file'] = $meta['shadow_file']; 
			$marker_array[$post_id]['default_icon'] = $default_marker_icon_post;
			$marker_array[$post_id]['default_shadow'] = $default_marker_shadow_post;
			// End of Markers
			
			$marker_array[$post_id]['latlng'] = $metalatlng;
			$marker_array[$post_id]['id'] = $post_id;
			$marker_array[$post_id]['title'] = $marker_title;
			$marker_array[$post_id]['url'] = $marker_url;
			$marker_array[$post_id]['address'] = $metaaddress;
			
		}
		
	}
	
	// PLACES
	if($post_type == $places_taxonomy) {

		if(is_array($place_id)) {
			$gpid = ''.$map_id.'_'.mt_rand(0,2147483647).'';		
			$marker_array = $place_id;
		}else{
			$gpid = ''.$map_id.'_'.$place_id.'';			
			$meta = get_post_meta($place_id,'_gpress_places',TRUE);
			$this_map_height = $meta['height'];
			
			$marker_url = get_bloginfo('url').'/?post_type='.$places_taxonomy.'&p='.$place_id.'';
			
			$marker_array = array();
			
			// Add Markers		
			$marker_array[$place_id]['icon_url'] = $meta['icon_url'];
			$marker_array[$place_id]['icon_file'] = $meta['icon_file'];
			$marker_array[$place_id]['shadow_url'] = $meta['shadow_url']; 
			$marker_array[$place_id]['shadow_file'] = $meta['shadow_file']; 
			$marker_array[$place_id]['default_icon'] = $default_marker_icon_place;
			$marker_array[$place_id]['default_shadow'] = $default_marker_shadow_place;
			// End of Markers
			
			$marker_array[$place_id]['latlng'] = $meta['latlng'];
			$marker_array[$place_id]['id'] = $place_id;
			$marker_array[$place_id]['title'] = $marker_title;
			$marker_array[$place_id]['url'] = $marker_url;
			$marker_array[$place_id]['address'] = $meta['address'];
			$marker_array[$place_id]['description'] = prepare_content_for_marker($description);
		}

	}
	
	// WIDGETS
	if($post_type == 'widget') {

		if(is_array($widget_id)) {
			$gpid = ''.$map_id.'_'.mt_rand(0,2147483647).'';
			$marker_array = $widget_id;
		}else{
			$gpid = ''.$map_id.'_'.$widget_id.'';
			
			$marker_url = get_bloginfo('url').'/?post_type='.$places_taxonomy.'&p='.$place_id.'';
			$meta = get_post_meta($place_id,'_gpress_places',TRUE);
			$map_position = $meta['latlng'];
			$using_gpress_widget = true;		
			
			query_posts('post_type='.$places_taxonomy.'&p='.$place_id.'');
			if ( have_posts() ) : while ( have_posts() ) : the_post();
			$gpress_this_place_title = single_post_title('', FALSE);
			endwhile; else:
			endif;
			wp_reset_query();
			
			$marker_title = $gpress_this_place_title;
			$this_map_height = $map_height;
			$default_map_height = '250';
		
			$marker_array = array();
			$marker_array[$place_id]['latlng'] = $meta['latlng'];
			$marker_array[$place_id]['id'] = $place_id;
			$marker_array[$place_id]['title'] = $gpress_this_place_title;
			$marker_array[$place_id]['url'] = $marker_url;
			$marker_array[$place_id]['address'] = $meta['address'];
			$marker_array[$place_id]['default_icon'] = $default_marker_icon_favwidget;
			$marker_array[$place_id]['default_shadow'] = $default_marker_shadow_favwidget;
		}

	}
	
	// SHORTCODES
	if($post_type == 'shortcode') {

		$gpid = ''.$map_id.'_'.mt_rand(0,2147483647).'';
		$this_map_height = $map_height;
		$this_map_icon = $marker_icon;
		$this_map_shadow = $marker_shadow;
		
		$marker_array = array();
		$marker_array[$place_id]['latlng'] = $map_position;
		$marker_array[$place_id]['description'] = $marker_description;
		$marker_array[$place_id]['title'] = $marker_title;
		$marker_array[$place_id]['url'] = $marker_url;
		
		// Add Markers		
		$marker_array[$place_id]['icon_url'] = $marker_icon;
		$marker_array[$place_id]['shadow_url'] = $marker_shadow; 
		$marker_array[$place_id]['default_icon'] = $default_marker_icon_place;
		$marker_array[$place_id]['default_shadow'] = $default_marker_shadow_place;
		// End of Markers

	}
	
	// BP USER LOCATION
	if($map_id == '_bp_user_location') {
		if(is_array($bp_user_array)) {
			$gpid = ''.$map_id.'_'.mt_rand(0,2147483647).'';
			foreach($bp_user_array as $user => $marker) {
				$map_position = $marker['latlng'];
			}
			$marker_array = $bp_user_array;
			$this_map_height = $map_height;
		}
	}

	// FINAL CHECK FOR EMPTY VARIABLES
	if(!empty($this_map_height)) {
		$this_height = $this_map_height;
	}else{
            if(empty($map_height)){
		$this_height = $default_map_height;
            }else{
                $this_height = $map_height;
            }
	}
	if(empty($map_type)) {
		$map_type = $default_map_type;
	}
	if(empty($map_zoom)) {
		$map_zoom = $default_map_zoom;
	}
	
	// HARDCODED DEFAULTS
	$default_marker_icon = ''.GPRESS_URL.'/gpress-core/images/markers/place.png';
	$default_marker_shadow = ''.GPRESS_URL.'/gpress-core/images/markers/bg.png';

	?>
    
    <style>
	/* THESE OPTIONS OVERWRITE THE DEFAULTS */
	.gpress_mapcanvas {
		height:<?php echo $default_map_height; ?>px;
	}
	#mapCanvas<?php echo $gpid; ?> {
		height:<?php echo $this_height; ?>px !important;
	}
	</style>
	
	<script type="text/javascript">
	
	var canvas_width<?php echo $gpid; ?> = '';
	var canvas_height<?php echo $gpid; ?> = '';
	var inner_width<?php echo $gpid; ?> = '';
	
	jQuery(document).ready(function() {
									
		canvas_width<?php echo $gpid; ?> = jQuery('#mapCanvas<?php echo $gpid; ?>').width();
		canvas_height<?php echo $gpid; ?> = jQuery('#mapCanvas<?php echo $gpid; ?>').height();		
		inner_width<?php echo $gpid; ?> = (canvas_width<?php echo $gpid; ?> - 200);
		
		<?php if($using_gpress_widget) { ?>
		
			jQuery('#mapCanvas<?php echo $gpid; ?>').css({ 'height': (canvas_width<?php echo $gpid; ?> * 1.5) });
			inner_width<?php echo $gpid; ?> = (canvas_width<?php echo $gpid; ?> - 10);
			//alert(inner_width<?php echo $gpid; ?>);
		
		<?php } ?>
	
	});
			
	</script>
		
		<script type="text/javascript">
			var GPRESS_DIR = '<?php echo GPRESS_DIR; ?>';
			var GPRESS_URL = '<?php echo GPRESS_URL; ?>';
		</script>
        
        <script type="text/javascript">
			
			var map<?php echo $gpid; ?> = '';
			var marker<?php echo $gpid; ?> = '';
			
			var markerClusterer<?php echo $gpid; ?> = null;
			var clustered_markers<?php echo $gpid; ?> = [];
			clustered_markers<?php echo $gpid; ?> = new Array();
			
	  		var styles = [[{
			  url: '<?php echo $default_marker_shadow_place; ?>',
			  height: 40,
			  width: 40,
			  opt_anchor: [16, 0],
			  opt_textColor: '#FFFFFF',
			  opt_textSize: 18
			}, {
			  url: '<?php echo $default_marker_shadow_place; ?>',
			  height: 40,
			  width: 40,
			  opt_anchor: [16, 0],
			  opt_textColor: '#FFFFFF',
			  opt_textSize: 18
			}, {
			  url: '<?php echo $default_marker_shadow_place; ?>',
			  height: 40,
			  width: 40,
			  opt_anchor: [16, 0],
			  opt_textColor: '#FFFFFF',
			  opt_textSize: 18
			}]];
			
			function clusters<?php echo $gpid; ?>() {
				
				if (markerClusterer<?php echo $gpid; ?>) {
          			markerClusterer<?php echo $gpid; ?>.clearMarkers();
        		}

				var zoom = 18;
				var size = 50;
				var style = 0;
				markerClusterer<?php echo $gpid; ?> = new MarkerClusterer(map<?php echo $gpid; ?>, clustered_markers<?php echo $gpid; ?>, {
         			maxZoom: zoom,
          			gridSize: size,
          			styles: styles[style]
        		});
				
			}
			
            function initialize<?php echo $gpid; ?>() {
				
				var latLng = new google.maps.LatLng(<?php echo $map_position; ?>);
				
				map<?php echo $gpid; ?> = new google.maps.Map(document.getElementById('mapCanvas<?php echo $gpid; ?>'), {
                                    zoom: <?php echo $map_zoom; ?>,
                                    center: latLng,
                                    mapTypeId: google.maps.MapTypeId.<?php echo $map_type; ?>
                                });
				
				<?php
                                foreach($marker_array as $id => $marker) {
				
					if($map_id == '_bp_profile') {
						$blog_id = $marker['blog_id'];
						$post_id = $marker['post_id'];
						$id = $blog_id.'_'.$post_id;
						$marker_title = $marker['post_title'];
						$marker_title = trim_me($marker_title, 140, '[...]');
						$place_address = $marker['geo_latlng'];	
						$place_address = trim_me($place_address, 140, '[...]');
						$pos = $marker['geo_latlng'];
						$thumb_array = wp_get_attachment_image_src(get_post_thumbnail_id( $id ), 'post-thumbnail' );
						$thumb_src = $thumb_array[0];
						$left_width = '49%';
						$right_width = '51%';
						$marker_url = $marker['post_url'];
						$photo_url = $marker_url;
						$place_hidden = 'no';		
					}elseif($map_id == '_bp_user_location') {
						$id = $marker['user_id'];
						$pos = $marker['latlng'];
						$map_position = $marker['latlng'];
						$place_address = $marker['address'];
						$marker_title = $marker['title'];
                                                $marker_url = $marker['marker_url'];
						$place_hidden = 'no';
					}else{
						/* PLACES AND POSTS */
						$id = $marker['id'];
						$marker_title = $marker['title'];
						$marker_title = trim_me($marker_title, 140, '[...]');
						$place_address = $marker['address'];
						$place_address = trim_me($place_address, 140, '[...]');
						$person_name = $marker['name'];
						$person_name = trim_me($person_name, 140, '[...]');
						$pos = $marker['latlng'];
						$thumb_array = wp_get_attachment_image_src(get_post_thumbnail_id( $id ), 'post-thumbnail' );
						$thumb_src = $thumb_array[0];
						$meta = get_post_meta($id,'_gpress_places',TRUE);
						//$this_description = prepare_content_for_marker($marker['description']);
						$this_description = $marker['description'];
                                                $this_description = $marker['description'];
						$left_width = '49%';
						$right_width = '51%';
						$marker_url = $marker['url'];
						$photo_url = $marker_url;
						$place_hidden = 'no';
					}
					
					// SORT MARKERS
					$adhoc_marker_icon_url = $marker['icon_url'];
					$adhoc_marker_icon_file = $marker['icon_file'];
					$adhoc_marker_shadow_url = $marker['shadow_url'];
					$adhoc_marker_shadow_file = $marker['shadow_file'];
					$default_marker_icon = $marker['default_icon'];
					$default_marker_shadow = $marker['default_shadow'];
					if(!empty($adhoc_marker_icon_url)) {
						$adhoc_marker_icon = $adhoc_marker_icon_url;
					}else{
						if(!empty($adhoc_marker_icon_file)) {
							$adhoc_marker_icon = $gpress_upload_url.'/'.$adhoc_marker_icon_file;
						}else{
							$adhoc_marker_icon = $default_marker_icon;
						}
					}
					if(!empty($adhoc_marker_shadow_url)) {
						$adhoc_marker_shadow = $adhoc_marker_shadow_url;
					}else{
						if(!empty($adhoc_marker_shadow_file)) {
							$adhoc_marker_shadow = $gpress_upload_url.'/'.$adhoc_marker_shadow_file;
						}else{
							$adhoc_marker_shadow = $default_marker_shadow;
						}
					}
					if(!empty($adhoc_marker_icon)) {
						$this_marker_icon = $adhoc_marker_icon;
					}else{
						$this_marker_icon = $default_marker_icon;
					}
					if(!empty($adhoc_marker_shadow)) {
						$this_marker_shadow = $adhoc_marker_shadow;
					}else{
						$this_marker_shadow = $default_marker_shadow;
					}
					if(($map_id == '_foursquare') || ($map_id == '_foursquare_widget')) {
						$this_marker_icon = $marker['icon'];
						$this_marker_shadow = $marker['shadow'];
					}
					// END OF MARKER SORT
					
					if($map_id !== '_foursquare') {
						$places_list = get_the_term_list( $id, $places_taxonomy_plural, $places_types, ', ', '' );
						$placed_list = get_the_term_list( $id, $places_tag_plural, $places_tags, ', ', '' ); 
					}
					
					if($four_type == 'person') {
						if($place_address == __('was not given an address', 'gpress')) {
							$place_address = $marker_title;
						}
						$title = sprintf(__('%s was last spotted near %s', 'gpress'), $person_name, $place_address);
					} else {
						if($map_id == '_bp_profile') {
							$title = sprintf(__('%s was geo-tagged at %s', 'gpress'), $marker_title, $place_address);									
						}else{
							if($post_type == 'shortcode') {
								$title = sprintf(__('%s was geo-tagged at %s', 'gpress'), $marker_title, $map_position);								
							}else{
								$title = sprintf(__('%s is located near %s', 'gpress'), $marker_title, $place_address);
							}
						}
					}
					
					if($place_hidden != 'yes') {
					
						$inner_content = '';
						if(empty($thumb_src)) {
							if(empty($this_description)) {
								$no_image_description = __('This place does not have any content yet...', 'gpress');
								$inner_content .= '<div class="gpress_infobox" id="infobox'.$gpid.'">';
									$inner_content .= '<p class="infobox_description">'.$no_image_description.'</p>';
									if ( '' != $places_list ) {  
										$inner_content .= '<p class="place_meta">'.$places_list.' ';
											if ( '' != $placed_list ) {  
												$inner_content .= 'and '.$placed_list.'';
											}
										$inner_content .= '</p>';
									}else{
										if ( '' != $placed_list ) {  
											$inner_content .= '<p class="place_meta">'.$placed_list.'</p>';
										}
									}
								$inner_content .= '</div>';
							}else{
								$inner_content .= '<div class="gpress_infobox" id="infobox'.$gpid.'">';
									$inner_content .= '<div id="infobox'.$gpid.'"><p class="infobox_description">'.$this_description.'</p></div>';
									if ( '' != $places_list ) {  
										$inner_content .= '<p class="place_meta">'.$places_list.' ';
											if ( '' != $placed_list ) {  
												$inner_content .= 'and '.$placed_list.'';
											}
										$inner_content .= '</p>';
									}else{
										if ( '' != $placed_list ) {  
											$inner_content .= '<p class="place_meta">'.$placed_list.'</p>';
										}
									}
								$inner_content .= '</div>';
							}
						}else{	
							if(empty($this_description)) {
								$inner_content .= '<div class="gpress_infobox" id="infobox'.$gpid.'">';
									if(is_single()) {
										$inner_content .= '<img class="gpress_infobox_img" id="infobox_img'.$gpid.'" src="'.$thumb_src.'" style="width:100%; margin-left:-2px;" />';
									} else {
										$inner_content .= '<a href="'.$photo_url.'"><img class="gpress_infobox_img" id="infobox_img'.$gpid.'" src="'.$thumb_src.'" style="width:100%; margin-left:-2px;" /></a>';
									}
									if ( '' != $places_list ) {  
										$inner_content .= '<p class="place_meta">'.$places_list.' ';
											if ( '' != $placed_list ) {  
												$inner_content .= 'and '.$placed_list.'';
											}
										$inner_content .= '</p>';
									}else{
										if ( '' != $placed_list ) {  
											$inner_content .= '<p class="place_meta">'.$placed_list.'</p>';
										}
									}
								$inner_content .= '</div>';
							}else{
								$inner_content .= '<div class="gpress_infobox" id="infobox'.$gpid.'">';
									$inner_content .= '<table width="100%" border="0" cellspacing="0" cellpadding="0">';
										$inner_content .= '<tr>';
											$inner_content .= '<td width="'.$left_width.'">';
												if(is_single()) {
													$inner_content .= '<img class="gpress_infobox_img" id="infobox_img'.$gpid.'" src="'.$thumb_src.'" style="width:100%;" />';
												} else {
													$inner_content .= '<a href="'.$photo_url.'"><img class="gpress_infobox_img" id="infobox_img'.$gpid.'" src="'.$thumb_src.'" style="width:100%;" /></a>';													
												}
											$inner_content .= '</td>';
											$inner_content .= '<td width="15px"><div class="infobox_table_spacer"></div></td>';
											$inner_content .= '<td width="'.$right_width.'">';
												$inner_content .= '<p class="infobox_description">'.$this_description.'</p>';
											$inner_content .= '</td>';
										$inner_content .= '</tr>';
									$inner_content .= '</table>';
									$inner_content .= '<table class="table_places_meta" width="100%" border="0" cellspacing="0" cellpadding="0">';
										$inner_content .= '<tr><td>';
											if ( '' != $places_list ) {  
												$inner_content .= '<p class="place_meta">'.$places_list.' ';
													if ( '' != $placed_list ) {  
														$inner_content .= 'and '.$placed_list.'';
													}
												$inner_content .= '</p>';
											}else{
												if ( '' != $placed_list ) {  
													$inner_content .= '<p class="place_meta">'.$placed_list.'</p>';
												}
											}
										$inner_content .= '</td></tr>';
									$inner_content .= '</table>';
								$inner_content .= '</div>';
							}
						}
						
						if($post_type == 'shortcode') {
							$description = $marker['description'];
							if(empty($description)) {
								$description = __('No description was provided...', 'gpress');
							}
							$inner_content = '<p class="infobox_description">'.$description.'</p>';
						}

                                                $inner_content = prepare_content_for_marker(apply_filters('gpress_infowindow_content', $inner_content, $id));
						
						?>
						
						var marker<?php echo $id; ?> = '';
				
						function InfoBox<?php echo $id; ?>(opts) {
						  google.maps.OverlayView.call(this);
						  this.latlng_ = opts.latlng;
						  this.map_ = opts.map;
						  this.offsetVertical_ = -29;
						  this.offsetHorizontal_ = -28;
						  this.maxHeight_ = (canvas_height<?php echo $gpid; ?> - 90);
						  this.width_ = (canvas_width<?php echo $gpid; ?> - 200);
						
						  var me = this;
						  this.boundsChangedListener_ =
							google.maps.event.addListener(this.map_, 'bounds_changed', function() {
							  return me.panMap.apply(me);
							});
	
						  this.setMap(this.map_);
						}
	
						InfoBox<?php echo $id; ?>.prototype = new google.maps.OverlayView();
	
						InfoBox<?php echo $id; ?>.prototype.remove = function() {
						  if (this.div_) {
							this.div_.parentNode.removeChild(this.div_);
							this.div_ = null;
						  }
						};
	
						InfoBox<?php echo $id; ?>.prototype.draw = function() {
						  this.createElement();
						  if (!this.div_) return;
						  var pixPosition = this.getProjection().fromLatLngToDivPixel(this.latlng_);
						  if (!pixPosition) return;
						  this.div_.style.width = this.width_ + 'px';
						  this.div_.style.left = (pixPosition.x + this.offsetHorizontal_) + 'px';
						  this.div_.style.maxHeight = this.maxHeight_ + 'px';
						  this.div_.style.height = 'auto';
						  this.div_.style.top = (pixPosition.y + this.offsetVertical_) + 'px';
						  this.div_.style.display = 'block';
						};
	
						InfoBox<?php echo $id; ?>.prototype.createElement = function() {
						  var panes = this.getPanes();
						  var div = this.div_;
						  if (!div) {
	
							div = this.div_ = document.createElement('div');
							div.style.border = '2px solid #CCC';
							div.style.position = 'absolute';
							div.style.background = '#FFF';
							div.style.width = this.width_ + 'px';
							div.style.height = 'auto';
							div.style.maxHeight = this.maxHeight_ + 'px';
							
							jQuery('p.infobox_description').css({ 'width' : inner_width<?php echo $gpid; ?> });
							
							var contentDiv = document.createElement('div');
							contentDiv.style.maxHeight = (( this.maxHeight_ ) - 72 )+ 'px';
							contentDiv.style.padding = '15px';
							contentDiv.style.cursor = 'auto';
							contentDiv.style.overflowY = 'auto';
							contentDiv.style.overflowX = 'hidden';
							contentDiv.innerHTML = '<?php echo $inner_content; ?>';
						
							var topDiv = document.createElement('div');
							topDiv.style.textAlign = 'right';
							
							topDiv.style.background = '#C22B99 url("<?php echo $this_marker_icon; ?>") no-repeat 5px 5px';
                                                        jQuery(topDiv).addClass('gpress-window-titlebar');
							
							topDiv.style.padding = '5px';
							
							var closeImg = document.createElement('img');
							closeImg.style.width = '84px';
							closeImg.style.height = '33px';
							closeImg.style.cursor = 'pointer';
							closeImg.style.position = 'absolute';
							closeImg.style.top = '5px';
							closeImg.style.right = '5px';
                                                        jQuery(closeImg).addClass('gpress-window-close');
	
							<?php if($four_type == 'person') { ?>
								topDiv.innerHTML = '<div class="infobox_title"><a href="<?php echo $marker['user_url']; ?>"><?php echo $marker['name']; ?></a> @ <a href="<?php echo $marker['venue_url']; ?>"><?php echo $marker_title; ?></a></div>';
							<?php } else { ?>
								<?php if(is_single()) { ?>
								topDiv.innerHTML = '<div class="infobox_title"><?php echo $marker_title; ?></div>';
								<?php } else { ?>
								topDiv.innerHTML = '<div class="infobox_title"><a href="<?php echo $marker_url; ?>"><?php echo $marker_title; ?></a></div>';	
								<?php } ?>
							<?php } ?>
	
							topDiv.appendChild(closeImg);
						
							function removeInfoBox(ib) {
							  return function() {
								ib.setMap(null);
							  };
							}
							
							function stealAction_(e) {
								if(navigator.userAgent.toLowerCase().indexOf('msie') != -1 && document.all) {
									window.event.cancelBubble = true;
									window.event.returnValue = false;
								} else {
									e.stopPropagation();
								}
							}
							
							google.maps.event.addDomListener(closeImg, 'click', removeInfoBox(this));
							google.maps.event.addDomListener(contentDiv, 'dblclick', stealAction_);
							google.maps.event.addDomListener(contentDiv, 'mousedown', stealAction_);
							google.maps.event.addDomListener(contentDiv, 'mousewheel', stealAction_);
							google.maps.event.addDomListener(contentDiv, 'DOMMouseScroll', stealAction_);
							google.maps.event.addDomListener(contentDiv, 'mousemove', stealAction_);	
						
							div.appendChild(topDiv);
							div.appendChild(contentDiv);
							div.style.display = 'none';
							panes.floatPane.appendChild(div);
							this.panMap();
						  } else if (div.parentNode != panes.floatPane) {
							div.parentNode.removeChild(div);
							panes.floatPane.appendChild(div);
						  } else {
							// The panes have not changed, so no need to create or move the div.
						  }
						}
						
						InfoBox<?php echo $id; ?>.prototype.panMap = function() {
							
							var map = this.map_;
							var bounds = map.getBounds();
							if (!bounds) return;
							var position = this.latlng_;
							// jQuery dependant now ...
							var iwWidth = jQuery(this.div_).width();
							var iwHeight = jQuery(this.div_).height();
							var mapDiv = map.getDiv();
							var mapWidth = mapDiv.offsetWidth;
							var mapHeight = mapDiv.offsetHeight;
							var boundsSpan = bounds.toSpan();
							var longSpan = boundsSpan.lng();
							var latSpan = boundsSpan.lat();
							var degPixelX = longSpan / mapWidth;
							var degPixelY = latSpan / mapHeight;
							var centerX = position.lng() + ( iwWidth / 2 + this.offsetHorizontal_) * degPixelX;
							var centerY = position.lat() - ( iwHeight / 2 + this.offsetVertical_) * degPixelY;				  
							map.panTo(new google.maps.LatLng(centerY, centerX));
							google.maps.event.removeListener(this.boundsChangedListener_);
							this.boundsChangedListener_ = null;
						};

						var image<?php echo $id; ?> = new google.maps.MarkerImage('<?php echo $this_marker_icon; ?>',
							new google.maps.Size(30, 30),
							new google.maps.Point(0, 0),
							new google.maps.Point(21, 22));

						var shadow<?php echo $id; ?> = new google.maps.MarkerImage('<?php echo $this_marker_shadow; ?>',
							new google.maps.Size(40, 40),
							new google.maps.Point(0, 0),
							new google.maps.Point(26, 27)); 
						
						var latLng<?php echo $id; ?> = new google.maps.LatLng(<?php echo $pos; ?>);
				
						marker<?php echo $id; ?> = new google.maps.Marker({
							position: latLng<?php echo $id; ?>,
							title: '<?php echo $title; ?>',
							map: map<?php echo $gpid; ?>,
							icon: image<?php echo $id; ?>,
							shadow: shadow<?php echo $id; ?>,
							draggable: false
						});
						
                                                var thisMapWidth<?php echo $id; ?> = jQuery('#mapCanvas<?php echo $gpid; ?>').width();
                                                
						clustered_markers<?php echo $gpid; ?>.push(marker<?php echo $id; ?>);
						
						<?php if($map_id !== '_bp_user_location') { ?>
							<?php if($map_id == '_foursquare_widget') { ?>
								google.maps.event.addListener(marker<?php echo $id; ?>, "click", function(e) {
									parent.location.href = '<?php echo $marker_url; ?>';
								});									
							<?php }elseif($map_id == '_bp_profile') { ?>
								google.maps.event.addListener(marker<?php echo $id; ?>, "click", function(e) {
									parent.location.href = '<?php echo $marker['post_url']; ?>';
								});							
							<?php } else { ?>
								<?php if($post_type == 'widget') { ?>
									google.maps.event.addListener(marker<?php echo $id; ?>, "click", function(e) {
										parent.location.href = '<?php echo $marker_url; ?>';
									});								
								<?php } else { ?>
									<?php if(($post_type == 'post') && (!is_single())) { ?>
										google.maps.event.addListener(marker<?php echo $id; ?>, "click", function(e) {
											parent.location.href = '<?php echo $marker_url; ?>';
										});			
									<?php }elseif(($post_type !== 'post') && (!is_single())) { ?>
                                                                                if(thisMapWidth<?php echo $id; ?><350){
                                                                                    google.maps.event.addListener(marker<?php echo $id; ?>, "click", function(e) {
                                                                                        parent.location.href = '<?php echo $marker_url; ?>';
                                                                                    });
                                                                                }else{
                                                                                    google.maps.event.addListener(marker<?php echo $id; ?>, "click", function(e) {
                                                                                            var infoBox = new InfoBox<?php echo $id; ?>({latlng: marker<?php echo $id; ?>.getPosition(), map: map<?php echo $gpid; ?>});
                                                                                    });
                                                                                }
									<?php } else { ?>
										<?php if($post_type !== 'post') { ?>
                                                                                        if(thisMapWidth<?php echo $id; ?><350){
                                                                                            google.maps.event.addListener(marker<?php echo $id; ?>, "click", function(e) {
                                                                                                parent.location.href = '<?php echo $marker_url; ?>';
                                                                                            });
                                                                                        }else{
                                                                                            google.maps.event.addListener(marker<?php echo $id; ?>, "click", function(e) {
                                                                                                    var infoBox = new InfoBox<?php echo $id; ?>({latlng: marker<?php echo $id; ?>.getPosition(), map: map<?php echo $gpid; ?>});
                                                                                            });
                                                                                        }
										<?php } ?>
									<?php } ?>
								<?php } ?>
							<?php } ?>
						<?php } else{ ?>
                                                    <?php if(!empty($marker_url)) { ?>
                                                        google.maps.event.addListener(marker<?php echo $id; ?>, "click", function(e) {
                                                            parent.location.href = '<?php echo $marker_url; ?>';
                                                        });
                                                    <?php } ?>
                                                <?php } ?>
						
						<?php
						
					} // END OF HIDE IF HIDDEN
					
				} ?>
				
				clusters<?php echo $gpid; ?>();
				
			}
			
         	google.maps.event.addDomListener(window, 'load', initialize<?php echo $gpid; ?>);
            
        </script>
             
		<div class="gpress_mapcanvas" id="mapCanvas<?php echo $gpid; ?>"></div>            
    
    <?php

}

?>