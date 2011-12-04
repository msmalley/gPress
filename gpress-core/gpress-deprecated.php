<?php

function gpress_deprecated_functions($this_version, $previous_version){
    if($previous_version < 0.25){
        // This inline function transfers place descriptions into the relevant post content...
        // In theory, it should only run once due to the logic leading to this destination...
        // If anyone discovers a flaw to this logic, it is essential to report it...
        $places_taxonomy = __( 'place', 'gpress' );
        query_posts('post_type='.$places_taxonomy.'&posts_per_page=-1&nopaging=true');
        if ( have_posts() ) : while ( have_posts() ) : the_post();
                $meta = get_post_meta(get_the_ID(),'_gpress_places',true);
                $place_description = $meta['description'];
                if(!empty($place_description)){
                    $place_array = array();
                    $place_array['ID'] = get_the_ID();
                    $place_array['post_content'] = $place_description;
                    wp_update_post($place_array);
                }
        endwhile; else: endif;
        wp_reset_query();
        wp_redirect(admin_url('admin.php?page=gp-general-options')); exit;
    }
}

?>