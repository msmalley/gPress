<?php

/* THESE ARE MISCELLANEOUS FUNCTIONS USED THROUGHOUT THE PLUGIN */
/* NEED TO ADD GPRESS_ TO FRONT OF ALL MY GENERIC FUNCTIONS */

function gpress_printr($arr,$exit=true){
    echo '<pre>';
    if(is_array($arr)){
        print_r($arr);
    }elseif(is_object($arr)){
        echo 'This is an object:</br>';
        print_r($arr);
        echo '<br/>';
    }else{
        if(!empty($arr)){
            echo 'This $arr is a string = '.$arr.'<br />';
        }
    }
    echo '</pre>';
    if($exit){
        exit;
    }
}

function gpress_get_distance($latitude1, $longitude1, $latitude2, $longitude2){
    $earth_radius = 6371;
    $dLat = deg2rad($latitude2 - $latitude1);
    $dLon = deg2rad($longitude2 - $longitude1);
    $a = sin($dLat/2) * sin($dLat/2) + cos(deg2rad($latitude1)) * cos(deg2rad($latitude2)) * sin($dLon/2) * sin($dLon/2);
    $c = 2 * asin(sqrt($a));
    $d = $earth_radius * $c;
    return $d;
}

function gpress_marker_sort_by_updated($a, $b){
    if($a==$b){
        return 0;
    } return ($a>$b) ? -1 : 1;
}
function gpress_marker_sort_by_added($a, $b){
    if($a==$b){
        return 0;
    } return ($a<$b) ? -1 : 1;
}
function gpress_marker_sort_by_distance($a, $b){
    if($a==$b){
        return 0;
    } return ($a>$b) ? -1 : 1;
}

function gpress_trim_me($src, $limit, $added_text) {
	$clean_text = strip_tags($src);
	$src = str_replace("'", "", "$clean_text");
	$src_length = strlen($src);
	if($src_length > $limit) {
		$excerpt = substr($src,0,$limit);
		$pretty_excerpt = ''.$excerpt.' '.$added_text.'';
		if(empty($added_text)) {
			return $excerpt;
		}else{
			return $pretty_excerpt;
		}
	}else{
		return $src;	
	}
}

function gpress_prepare_content_for_marker($src) {
	$content = addslashes($src);
	$content = nl2br($content);
	$content = str_replace(chr(10), " ", $content);
	$content = str_replace(chr(13), " ", $content);
	return $content;
}

function gpress_get_users_blog_posts( $user_id = 1, $num_per_blog = 1, $orderby = 'date', $sort = 'post_date_gmt', $gpress = true ) {
	$posts = array();
	$i = 0;
	$blogs = get_blogs_of_user($user_id);
	if(is_array($blogs)) {
		// MS SPECIFIC
		foreach ( $blogs as $key => $blog ):
			$blog_id = $blog->userblog_id;
			if(is_multisite()) {
				switch_to_blog($blog_id);
					$get_posts = get_posts('orderby='.$orderby.'&numberposts='.$num_per_blog);
					foreach($get_posts as $key => $the_post) {
						$author_id = $the_post->post_author;
						if($author_id = $user_id) {
							$post_id = $the_post->ID;
							$posts[$i]['blog_id'] = $blog_id;
							$posts[$i]['post_id'] = $post_id;
							$posts[$i]['post_date'] = $the_post->post_date;
							$posts[$i]['post_title'] = $the_post->post_title;
							$posts[$i]['post_url'] = $the_post->guid;
							$posts[$i]['post_type'] = $the_post->post_type;
							$geo_latlng = ''.get_post_meta($post_id,'geo_latitude',TRUE).', '.get_post_meta($post_id,'geo_longitude',TRUE).'';
							$posts[$i]['geo_public'] = get_post_meta($post_id,'geo_public',TRUE);
							$posts[$i]['geo_latlng'] = $geo_latlng;
							$i++;
						}
					}
				restore_current_blog();
			}else{
				$get_posts = get_posts('orderby='.$orderby.'&numberposts='.$num_per_blog);
				foreach($get_posts as $key => $the_post) {
                                    $author_id = $the_post->post_author;
                                    if($author_id == $user_id) {
                                            $post_id = $the_post->ID;
                                            $posts[$i]['blog_id'] = $blog_id;
                                            $posts[$i]['post_id'] = $post_id;
                                            $posts[$i]['post_date'] = $the_post->post_date;
                                            $posts[$i]['post_title'] = $the_post->post_title;
                                            $posts[$i]['post_url'] = $the_post->guid;
                                            $posts[$i]['post_type'] = $the_post->post_type;
                                            $geo_latlng = ''.get_post_meta($post_id,'geo_latitude',TRUE).', '.get_post_meta($post_id,'geo_longitude',TRUE).'';
                                            $posts[$i]['geo_public'] = get_post_meta($post_id,'geo_public',TRUE);
                                            $posts[$i]['geo_latlng'] = $geo_latlng;
                                            $i++;
                                    }
				}
			}
		endforeach;
		return $posts;
	}else{
		// STANDARD WP
		$get_posts = get_posts('orderby='.$orderby.'&numberposts='.$num_per_blog);
		foreach($get_posts as $key => $the_post) {
			$post_id = $the_post->ID;
			$posts[$i]['blog_id'] = $blog_id;
			$posts[$i]['post_id'] = $post_id;
			$posts[$i]['post_date'] = $the_post->post_date;
			$posts[$i]['post_title'] = $the_post->post_title;
			$posts[$i]['post_url'] = $the_post->guid;
			$posts[$i]['post_type'] = $the_post->post_type;
			$geo_latlng = ''.get_post_meta($post_id,'geo_latitude',TRUE).', '.get_post_meta($post_id,'geo_longitude',TRUE).'';
			$posts[$i]['geo_public'] = get_post_meta($post_id,'geo_public',TRUE);
			$posts[$i]['geo_latlng'] = $geo_latlng;
			$i++;
		}
		return $posts;
	}
}

function gpress_get_posts( $query ) {
	
	$places_taxonomy = __( 'place', 'gpress' );
        $home_loop = get_site_option('gp_homepage_loop','BOTH');
        $home_loop_method = get_site_option('gp_homepage_loop_method','query');
	
	if($home_loop_method == 'query') {
			
		if($home_loop == 'BOTH') {
			if(!is_single()) {
				if ( $query->is_home ) {
					if(empty($query->query)) {
						$query->set( 'post_type', array('post', $places_taxonomy) );
					}
				}		
			}
		}elseif($home_loop == 'PLACES') {
			if(!is_single()) {
				if ( $query->is_home ) {
					if(empty($query->query)) {
						$query->set( 'post_type', $places_taxonomy );
					}
				}
			}
		}else{
			// DO NOTHING
		}
	
	}
	
	return $query;

}

function gpress_place_submit($gpress_post_type = 'place', $tax_or_cat = 'tax', $gpress_tax_type = 'places', $gpress_tag_type = 'placed', $gpress_post_status = 'publish', $gpress_must_be_logged_in = true, $gpress_no_map_message = true, $required_user_level = 10) {
	global $current_user;
	get_currentuserinfo();
        $current_user_level = $current_user->user_level;
	if($gpress_no_map_message == 1) {
		$gpress_no_map_message = __('In order to add new places, you must first log-in.', 'gpress');
	}
	if($gpress_post_status == 'draft') {
		$post_status = 'draft';
	}elseif($gpress_post_status == 'pending') {
		$post_status = 'pending';
	}else{
		$post_status = 'publish';
	}
	$continue = 'no';
	if($gpress_must_be_logged_in) {
		if(is_user_logged_in()) {
			$continue = 'yes';
		}else{
			$continue = 'no';
		}
	}else{
		$continue = 'yes';
	}
	if($current_user_level < $required_user_level) {
		$continue = 'no';
	}
	if($continue == 'yes') {
		if( 'POST' == $_SERVER['REQUEST_METHOD'] && !empty( $_POST['gpress_title'] )) {
			// Need some validation here...
                        $gpress_places_tax_type = __('places','gpress');
                        $gpress_places_tag_type = __('placed','gpress');
			$title =  $_POST['gpress_title']; 
			$description = $_POST['gpress_description']; 
			$latlng = $_POST['_gpress_new_place']['latlng'];
			$address = $_POST['_gpress_new_place']['address'];
			$category = $_POST['cat'];
			$tags = $_POST['gpress_post_tags'];
                        $taged = explode(',', $tags);
			// Add the content of the form to $post as an array
                        if($tax_or_cat == 'tax') {
                            $tax_input = array(
                                $gpress_places_tax_type => array($category),
                                $gpress_places_tag_type => $taged
                            );
                            $post = array(
                                    'post_title'	=> $title,
                                    'post_content'	=> $description,
                                    'post_status'	=> $post_status,
                                    'post_type'        	=> $gpress_post_type
                            );
                        }else{
                            $post = array(
                                    'post_title'	=> $title,
                                    'post_content'	=> $description,
                                    'post_status'	=> $post_status,
                                    'post_category'     => array($category),
                                    'post_type'         => $gpress_post_type
                            );
                        }
                        $post = apply_filters('gpress_post_from_front_end', $post, $_FILES);
                        // Insert post into DB
			$post_id = wp_insert_post($post);
                        /* NEED TO MANUALLY INSERT TAGS AND CATS DUE TO CURRENT_USER LEVEL CONFLICTS IMPOSSED BY WORDPRESS CORE */
                        if(($post_id)&&(is_array($tax_input))){
                            foreach($tax_input as $taxonomy => $tags) {
                                $taxonomy_obj = get_taxonomy($taxonomy);
                                if (is_array($tags)){
                                    $tags = array_filter($tags);
                                }
                                wp_set_post_terms($post_id,$tags,$taxonomy);
                            }
                        }
			if($post_id == 0) {
				echo '<span class="gpress_message">'.__('Error - Place Not Published', 'gpress').'</span><br /><br />';			
			}else{
				// Add custom fields
				$meta_data = array();
				$meta_data['latlng'] = $latlng;
				$meta_data['address'] = $address;
				add_post_meta($post_id, '_gpress_places', $meta_data);
				echo '<span class="gpress_message"><a href="'.get_permalink($post_id).'">'.__('Place Published - Click to View Place', 'gpress').'</a></span><br /><br />';
                                $file = $_FILES;
                                do_action('after_front_end_post_submission', $post_id, $file);
			}
		} 
		?>
		<style>
		/* NEED TO ADD THIS TO STYLESHEET */
                form#new_post {
                    width:99%;
                }
                form#new_post table {
                    background: transparent;
                    border: none;
                }
                form#new_post input, form#new_post label, form#new_post select, form#new_post textarea {
			clear:both;
			width:99%;
                        margin:5px 0 15px;
		}
                form#new_post input#submit {
                    width: auto;
                }
		form#new_post select,form #new_post input[type="submit"] {
			width:auto;
		}
		span.gpress_message {
			display:block;
			padding:15px 0;
			text-align:center;
			background:#EEE;
			border:1px solid #CCC;
			color:#666;
			font-size:14px;
			font-weight:bold;
			text-transform:uppercase;
		}
                form#new_post .gpress_map_frame {
                    margin: 15px 0 0 0;
                }
                form#new_post .gpress_map_frame, form#new_post .gpress_mapcanvas {
                    clear: both;
                    display: block;
                }
		form#new_post mapFrame_gpress_new_place table,
		form#new_post #mapFrame_gpress_new_place tr td {
			border:none;
			margin:0;
			padding:0;
		}
		form#new_post #mapFrame_gpress_new_place table input[type="button"] {
			width:110px;
		}
		form#new_post #mapFrame_gpress_new_place table {
			margin:15px 0 -20px;
		}
		.other_stuff_gpress_new_place {
			display:none;
		}
		form#new_post #mapFrame_gpress_new_place .other_stuff_gpress_new_place {
			display:block;
		}
		</style>
		<div id="postbox">
			<form id="new_post" class="standard-form" name="new_post" method="post" action="" enctype="multipart/form-data">
                            <?php
                            do_action('before_front_end_post_form');
                            if($gpress_post_type == 'place') { ?>
				<label for="title"><?php _e('Name of Place', 'gpress'); ?></label>
				<input type="text" id="title" value="" tabindex="1" size="20" name="gpress_title" />
				<label for="description"><?php _e('Description of Place', 'gpress'); ?></label>
				<textarea id="description" tabindex="3" name="gpress_description" cols="50" rows="6"></textarea>
				<?php
                                do_action('before_front_end_post_map');
                                echo '<br /><label>'.__('Location of Place', 'gpress').'</label>';
                                gpress_geoform('_gpress_new_place', 'ROADMAP', '13', false, true);
                                echo '<br /><label for="cst">'.__('Type of Place', 'gpress').'</label>';
                                do_action('before_front_end_post_cat');
                                if($tax_or_cat == 'tax') {
                                    wp_dropdown_categories( 'tab_index=4&taxonomy='.$gpress_tax_type.'&hide_empty=' );
                                }else{
                                    wp_dropdown_categories( 'tab_index=4&hide_empty=' );
                                }?>
				<br /><label for="post_tags"><?php _e('Place Tags', 'gpress'); ?></label>
				<input type="text" value="" tabindex="5" size="16" name="gpress_post_tags" id="post_tags" />
                                <?php do_action('after_front_end_post_tags'); ?>
				<input type="submit" value="Publish" tabindex="6" id="submit" name="gpress_submit" />
                            <?php } else { ?>
				<label for="title"><?php _e('Title', 'gpress'); ?></label>
				<input type="text" id="title" value="" tabindex="1" size="20" name="gpress_title" />
				<label for="description"><?php _e('Description', 'gpress'); ?></label>
				<textarea id="description" tabindex="3" name="gpress_description" cols="50" rows="6"></textarea>
                                <br /><label for="cst"><?php _e('Category', 'gpress'); ?></label>
				<?php
                                do_action('before_front_end_post_cat');
                                if($tax_or_cat == 'tax') {
                                    wp_dropdown_categories( 'tab_index=4&taxonomy='.$gpress_tax_type.'&hide_empty=' );
                                }else{
                                    wp_dropdown_categories( 'tab_index=4&hide_empty=' );
                                }
                                ?>
				<br /><label for="post_tags"><?php _e('Tags', 'gpress'); ?></label>
				<input type="text" value="" tabindex="5" size="16" name="gpress_post_tags" id="post_tags" />
                                <?php do_action('after_front_end_post_tags'); ?>
				<input type="submit" value="Publish" tabindex="6" id="submit" name="gpress_submit" />
                            <?php }
                            do_action('after_front_end_post_form');
                            ?>
			</form>
		</div>
	<?php
    }else{
		echo $gpress_no_map_message;
	}
}
?>