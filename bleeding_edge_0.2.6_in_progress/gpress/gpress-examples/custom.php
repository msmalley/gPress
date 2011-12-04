<?php

/*
 *
 *  SIMPLY UPLOAD A FILE CALLED "custom.php" into "wp-content/gpress/"
 *  If gPress finds that file, it will automatically include it at run-time
 *  This allows you to further customise gPress without modifying core files
 *
 *  The "wp-content/gpress" folder can also contain custom.css and custom.js files
 *
 *  PLEASE FIND SEVERAL USAGE EXAMPLES BELOW WHICH MAY LATER BECOME GUI
 *  CONTROLLABLE COMPONENTS BUT ARE FOR NOW TO SHOWCASE LIVE EXAMPLES
 *
 */

/*
 *  This function shows how simple it is to filter the $post submission...
 */
function gpress_post_from_front_end_filter($post, $files) {
    // If you need to, you can always directly manipulate the $post array prior to using wp_insert_post;
    return $post;
}
//add_filter('gpress_post_from_front_end', 'gpress_post_from_front_end_filter', 1, 2);

/*
 *  This function adds additional fields to the front-end submission form...
 *  In this case, we have added image upload inputs...
 */
function add_file_upload_inputs() {
    ?>

    <div class="upload-wrapper">
        <label><?php _e('Primary Photo of Place:', 'gpress'); ?></label>
        <input type='file' class='multi' name='gpress_image1' accept='png|gif|jpg|jpeg'/>
    </div>

    <div class="upload-wrapper">
        <label><?php _e('2nd Photo of Place:', 'gpress'); ?></label>
        <input type='file' class='multi' name='gpress_image2' accept='png|gif|jpg|jpeg'/>
    </div>

    <div class="upload-wrapper">
        <label><?php _e('3rd Photo of Place:', 'gpress'); ?></label>
        <input type='file' class='multi' name='gpress_image3' accept='png|gif|jpg|jpeg'/>
    </div>

    <div class="upload-wrapper">
        <label><?php _e('4th Photo of Place:', 'gpress'); ?></label>
        <input type='file' class='multi' name='gpress_image4' accept='png|gif|jpg|jpeg'/>
    </div>

    <div class="upload-wrapper">
        <label><?php _e('5th Photo of Place:', 'gpress'); ?></label>
        <input type='file' class='multi' name='gpress_image5' accept='png|gif|jpg|jpeg'/>
    </div>

    <div class="upload-wrapper">
        <label><?php _e('6th Photo of Place:', 'gpress'); ?></label>
        <input type='file' class='multi' name='gpress_image6' accept='png|gif|jpg|jpeg'/>
    </div>

    <?php
}
//add_action('after_front_end_post_tags','add_file_upload_inputs');

/*
 *  This function adds the files as attachments (if the place was sucessfully submitted)...
 */
function add_attachments_to_place($post_id, $files) {
    $limit_size = 1000000;
    $mb_limit = $limit_size/1000000;
    if(!empty($files)) {
        foreach($files as $file) {
            $filename = $file["name"];
            $filesize = $file["size"];
            $uploads = wp_upload_dir();
            if($filesize > 1) {
                if($filesize < $limit_size) {
                    move_uploaded_file($file["tmp_name"], $uploads['path'].'/'.$filename);
                    $wp_filetype = wp_check_filetype(basename($filename), null );
                    $attachment = array(
                        'post_mime_type' => $wp_filetype['type'],
                        'post_title' => preg_replace('/\.[^.]+$/', '', basename($filename)),
                        'post_content' => '',
                        'post_status' => 'inherit'
                    );
                    $attach_id = wp_insert_attachment( $attachment, $uploads['path'].'/'.$filename, $post_id );
                    // you must first include the image.php file
                    // for the function wp_generate_attachment_metadata() to work
                    require_once(ABSPATH . "wp-admin" . '/includes/image.php');
                    $attach_data = wp_generate_attachment_metadata( $attach_id, $uploads['path'].'/'.$filename );
                    $upload_dir = ( $uploads['url'] );
                    wp_update_attachment_metadata( $attach_id,  $attach_data );
                    //echo $upload_dir . '/'.$filename; exit;
                }else{
                    echo '<span class="gpress_message">'.$filename.' was not added due to being over the '.$mb_limit.'MB limit</span><br /><br />';
                }
            }
        }
    }
}
//add_action('after_front_end_post_submission','add_attachments_to_place', 1, 2);

/*
 *  This function adds a gallery to single places...
 */
function gpress_infowindow_content_filter_photo_gallery($content, $id) {
    if(is_single()){
        $gallery = do_shortcode('[gallery option1="value1" columns="2"]');
        $content.= $gallery;
    }
    return $content;
}
// add_filter('gpress_infowindow_content', 'gpress_infowindow_content_filter_photo_gallery', 1, 2);
// UNCOMMENT THIS FILTER - IF YOU WANT TO USE SIMPLE GALLERY EXTENSION
// YOU WILL THEN NEED TO COMMENT OUT THE TWO FILTERS BELOW

/*
 *  This function replaces the content of all windows with custom tabbed content...
 */
function gpress_infowindow_content_filter($original_content, $id) {
    /* GATHER INFO */
    $args = array(
        'post_type' => 'attachment',
        'numberposts' => -1,
        'post_status' => null,
        'post_parent' => $id,
        'orderby' => 'ID',
        'order' => 'DESC'
    );
    $attachments = get_posts($args);
    if ($attachments) {
        foreach ($attachments as $attachment) {
            $thisTempAttachment = wp_get_attachment_image_src($attachment->ID, 'featured-image');
            if(!empty($thisTempAttachment)) {
                $thisAttachment = $thisTempAttachment[0];
            }
        }
    }
    $thisPermalink = get_permalink($id);
    $thisPost = get_post($id,ARRAY_A);
    $thisContent = $thisPost['post_content'];
    $ratingObj = wp_gdsr_rating_article($id);
    $theRating = $ratingObj->rating;
    $theVotes = $ratingObj->votes;
    query_posts('post_type=place&p='.$id);
    if ( have_posts() ) : while ( have_posts() ) : the_post();
        $theStars = wp_gdsr_render_article(0, false, "", "", "", false);
    endwhile; else:
    endif;
    wp_reset_query();
    //$theStars = wp_gdsr_render_article(0, false, "", "", "", false);
    $commentArgs = array(
        'number'    => 3,
        'post_id'   => $id
    );
    $theComments = get_comments($commentArgs);

    /* NEED TO ADD THIS JS FUNCTION TO CUSTOM.JS
    function checkString(string, txt) {
        if(string.indexOf(txt) != -1) {
            return true;
        }else{
            return false;
        }
    }
    function gpress_tab_toggle(key, id){
        var tabLI = $('li#gpress-tabbed-content-tab-'+key+'-'+id);
        var tabLIclass = $(tabLI).attr('class');
        if(checkString(tabLIclass,'selected')){
            // Do Nothing...
        }else{
            $('ul#gpress-tabbed-content-list-'+id).find('li.gpress-tabbed-tab').removeClass('selected');
            $(tabLI).addClass('selected');
        }
    } END OF CONTENT THAT NEEDED TO BE ADDED TO CUSTGOM.JS */

    /* COMPILE CONTENT */
    $content='<style>p.place_meta{display:none;}li.gpress-tabbed-tab{float:left;background:transparent !important;padding:0 !important;height:350px;overflow:hidden;width:100%;}.gpress-photo-wrapper{float:left;padding:36px;background:#F7F7F7;border:1px solid #D7D7D7;}.gpress-tab{float:left;position:relative;width:100%;padding:0;margin-top:-1px;display:none;}li.gpress-tabbed-tab.two,li.gpress-tabbed-tab.three{margin-top:-350px;}a.gpress-tab-link{float:left;z-index:993;position:relative;}.gpress-tabbed-tab.two a.gpress-tab-link{margin-left:100px;z-index:992;}.gpress-tabbed-tab.three a.gpress-tab-link{margin-left:200px;z-index:991;}li.gpress-tabbed-tab.selected .gpress-tab{display:inline;}.gpress-tabbed-tab a.gpress-tab-link{color:#CC0404 !important;font-weight:bold;margin-top:3px;padding:4px 12px;width:73px;text-align:center;background:#FFF;border-style:solid;border-width:1px;border-color:#D7D7D7;text-decoration:none;}.gpress-tabbed-tab.selected a.gpress-tab-link,.gpress-tabbed-tab a.gpress-tab-link:hover{color:#333 !important;background:#F7F7F7;border-style:solid;border-width:1px;border-color:#D7D7D7 #D7D7D7 #F7F7F7;}div.the-gpress-tabbed-content{background:#FFF;width:370px;height:220px;padding:13px;border:1px solid #CCC;overflow:auto;}</style>';
    $content.='<ul id="gpress-tabbed-content-list-'.$id.'" class="gpress-tabbed-content">';
        $content.='<li id="gpress-tabbed-content-tab-one-'.$id.'" class="gpress-tabbed-tab selected one">';
            $content.='<a href="#" id="gpress-tab-link-one-'.$id.'" class="gpress-tab-link" onclick="gpress_tab_toggle(\'one\', '.$id.'); return false;">'.__('PHOTO', 'gpress').'</a>';
            $content.='<div id="gpress-tab-one'.$id.'" class="gpress-tab">';
                $content.='<a href="'.$thisPermalink.'" style="width:400px; height:250px;" class="gpress-photo-wrapper"><img src="'.$thisAttachment.'" width="400" height="250" /></a>';
            $content.='</div>';
        $content.='</li>';
        $content.='<li id="gpress-tabbed-content-tab-two-'.$id.'" class="gpress-tabbed-tab two">';
            $content.='<a href="#" id="gpress-tab-link-two-'.$id.'" class="gpress-tab-link" onclick="gpress_tab_toggle(\'two\', '.$id.'); return false;">'.__('INFO', 'gpress').'</a>';
            $content.='<div id="gpress-tab-two'.$id.'" class="gpress-tab">';
                $content.='<span class="gpress-photo-wrapper" style="width:400px; height:250px; overflow:hidden;">';
                    $content.='<div class="the-gpress-tabbed-content">';
                        $content.='<p>'.$thisContent.'</p>';
                    $content.='</div>';
                $content.='</span>';
            $content.='</div>';
        $content.='</li>';
        $content.='<li id="gpress-tabbed-content-tab-three-'.$id.'" class="gpress-tabbed-tab three">';
            $content.='<a href="#" id="gpress-tab-link-three-'.$id.'" class="gpress-tab-link" onclick="gpress_tab_toggle(\'three\', '.$id.'); return false;">'.__('REVIEWS', 'gpress').'</a>';
            $content.='<div id="gpress-tab-three'.$id.'" class="gpress-tab">';
                $content.='<span class="gpress-photo-wrapper" style="width:400px; height:250px; overflow:hidden;">';
                    $content.='<div class="the-gpress-tabbed-content">';
                        $content.='<span class="star-title">Rating '.$theRating.' ('.$theVotes.' Votes): </span><span class="the-stars">'.$theStars.'</span>';
                        $content.='<div class="the-tabbed-comments"><ul class="gpress-tabbed-comments">';
                            foreach($theComments as $comment) {
                                $content.='<li><span class="author">'.$comment->comment_author.' wrote:</span><span class="comment">'.$comment->comment_content.'</span></li>';
                            };
                        $content.='</ul></div>';
                    $content.='</div>';
                $content.='</span>';
            $content.='</div>';
        $content.='</li>';
    $content.='</ul>';
    /* SEND CONTENT BACK */
    return $content;
}
/* COMMENT OUT THESE FILTERS IF USING THE SIMPLE GALLERY EXTENSION ABOVE */
//add_filter('gpress_infowindow_content', 'gpress_infowindow_content_filter', 1, 2);
//add_filter('gpress_infowindow_excerpt', 'gpress_infowindow_content_filter', 1, 2);

/* THIS EXAMPLE SIMPLY REMOVES THE MAP FROM SINGLE PLACE PAGES */
function gpress_remove_map_from_single_place_content($content, $id){
    if(is_single()){
        return get_the_content($id);
    }else{
        return $content;
    }
}
//add_filter('gpress_content','gpress_remove_map_from_single_place_content', 1, 2);

/* THIS EXAMPLE WOULD SHOW YOU HOW TO REMOVE MAP FROM SINGLE PLACE PAGES */
function gpress_remove_map_from_single_content($original_content,$post_id){
    if(is_single()){
        return get_the_content($post_id);
    }else{
        return $original_content;
    }
}
//add_filter('gpress_content','gpress_remove_map_from_single_content',1,2);
?>