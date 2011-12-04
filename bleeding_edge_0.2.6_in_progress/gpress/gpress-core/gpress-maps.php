<?php

function gpress_map($options) {
    global $blog_id;
    /* TAXONOMY TERMINOLOGY */
    $places_taxonomy = __( 'place', 'gpress' );
    $places_taxonomy_plural = __( 'places', 'gpress' );
    $places_tag_plural = __( 'placed', 'gpress' );
    $places_types = __( 'Type(s) of Place: ', 'gpress' );
    $places_tags = __( 'Tag(s): ', 'gpress' );
    /* OPTION EXTRACTION */
    $default_options = array(
        'map_id'            => 'id'.mt_rand(0,2147483647),
        'map_height'        => get_site_option('gp_map_height','450'),
        'map_type'          => get_site_option('gp_map_type','ROADMAP'),
        'map_zoom'          => get_site_option('gp_map_zoom','13'),
        'map_lat'           => false,
        'map_lng'           => false,
        'markers'           => false,
        'is_geoform'        => false,
        'use_infowindows'   => ''
    );
    if(is_array($options)){
        $settings = array_merge($default_options,$options);
    }else{
        $settings = $default_options;
    } extract($settings);
    /* OPTIONS EXTRACTED */
    if($markers!=false){
        if(!is_array($markers)){ $errors = __('Expecting Marker Array for Displaying Map','gpress'); }
    }if(empty($map_id)){ $errors = __('ID Required in Order to Construct Map','gpress'); }
    if($errors){ return $errors; }else{
        /* THE NEW GPRESS MAP FUNCTION */
        if($is_geoform){
            echo '<div class="gpress_geoform"><table class="gpress_table" width="100%" border="0" cellspacing="0" cellpadding="0"><tr><td width="100%">';
            echo '<div class="gpress_input_wrapper"><input id="search_address_'.$map_id.'" name="search_address_'.$map_id.'" type="textbox" value="" class="blank"></div>';
            echo '</td><td width="110px">';
            echo '<input type="button" value="'.__('Search', 'gpress').'" onclick="gpress_codeAddress(\''.$map_id.'\')" class="gpress_geoform_button">';
            echo '</td></tr></table>';
            echo '<input type="hidden" id="lat_'.$map_id.'" name="'.$map_id.'[lat]" value="'.$map_lat.'" />';
            echo '<input type="hidden" id="lng_'.$map_id.'" name="'.$map_id.'[lng]" value="'.$map_lng.'" />';
            echo '<div class="gpress_map_frame"><div class="gpress_mapcanvas" id="gpress_canvas_'.$map_id.'"></div></div>';
            echo '<table width="100%" border="0" cellspacing="0" cellpadding="0" class="gpress_table"><tr><td class="half">';
            echo '<span class="gpress-label"><b>'.__('Closest Address:', 'gpress').'</b></span>';
            echo '<textarea id="address_'.$map_id.'" class="gpress_address" name="'.$map_id.'[address]" value="'.$meta['address'].'" class="closest_address"></textarea>';
            echo '</td><td class="half">';
            echo '<span class="gpress-label"><b>'.__('Marker Status:', 'gpress').'</b></span>';
            echo '<div id="markerStatus_'.$map_id.'" class="gpress_marker_status">'.__('Click and Drag the Marker', 'gpress').'</div>';
            echo '</td></tr></table><br style="clear:both;display:block;"></div>';
        }else{
            echo '<div class="gpress_map_frame"><div class="gpress_mapcanvas" id="gpress_canvas_'.$map_id.'"></div></div>';
        }
        $marker_count = count($markers);
        $default_lat = $markers[0]['lat'];
        $default_lng = $markers[0]['lng'];
        if($is_geoform){
            ?>
            <script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
            <script type="text/javascript" src="http://code.google.com/apis/gears/gears_init.js"></script>
            <script type="text/javascript" src="<?php echo GPRESS_URL; ?>/gpress-core/js/geo.js"></script>
            <script type="text/javascript" src="<?php echo GPRESS_URL; ?>/gpress-core/js/gpress-maps.js"></script>
            <script type="text/javascript" src="http://www.google.com/jsapi"></script>
        <?php } ?>
        <style>
        #gpress_canvas_<?php echo $map_id; ?> {
            height:<?php echo $map_height; ?>px;
        }
        </style>
        <script type="text/javascript">
        var is_geoform = <?php if($is_geoform){ echo 'true'; }else{ echo 'false'; } ?>;
        if(is_geoform===true){
            var current_lat; var current_lng;
            var map = new Array(); var marker = new Array(); var geocoder = new Array();
            var marker_cluster = new Array(); var clustered_markers = new Array();
            var gpress_info_box = new Array(); var canvas_width = new Array();
            var canvas_height = new Array(); var inner_width = new Array();
            var gpress_loading_interval = new Array();
            var new_info_box = new Array();
            var gpress_index = new Array();
            var this_marker = new Array();
        }
        <?php if($map_lat){ echo 'var map_lat = '.$map_lat; }else{ echo 'var map_lat = 0'; } ?>;
        <?php if($map_lng){ echo 'var map_lng = '.$map_lng; }else{ echo 'var map_lng = 0'; } ?>;
        function gpress_load_map_<?php echo $map_id; ?>(lat,lng){
            gpress_check_if_gmaps_loaded(
                '<?php echo $map_id; ?>',
                '<?php if($map_type){ echo $map_type; }else{ echo 'ROADMAP'; } ?>',
                <?php if($map_zoom){ echo $map_zoom; }else{ echo 13; } ?>,
                lat, lng, is_geoform,
                <?php if(is_array($markers)){ echo json_encode($markers); }else{ echo 'false'; } ?>,
                <?php if($use_infowindows){ echo 'true'; }else{ echo 'false'; } ?>
            );
        }
        <?php
        /* ONLY USE GEO-CODING IF REQUIRED */
        if(($marker_count!=1)&&(!empty($default_lat))&&(!empty($default_lng))){
        ?>
            if(geo_position_js.init()){
                geo_position_js.getCurrentPosition(function(p){
                    current_lat = p.coords.latitude; current_lng = p.coords.longitude;
                    gpress_loading_interval.<?php echo $map_id; ?> = setInterval("gpress_load_map_<?php echo $map_id; ?>(current_lat,current_lng)",1000);
                },function(p){
                    gpress_loading_interval.<?php echo $map_id; ?> = setInterval("gpress_load_map_<?php echo $map_id; ?>(map_lat,map_lng)",1000);
                },{enableHighAccuracy:true});
            }
        <?php }else{ ?>
            <?php if((empty($default_lat))||(empty($default_lng))){ ?>
                if(geo_position_js.init()){
                    geo_position_js.getCurrentPosition(function(p){
                        current_lat = p.coords.latitude; current_lng = p.coords.longitude;
                        gpress_loading_interval.<?php echo $map_id; ?> = setInterval("gpress_load_map_<?php echo $map_id; ?>(current_lat,current_lng)",1000);
                    },function(p){
                        gpress_loading_interval.<?php echo $map_id; ?> = setInterval("gpress_load_map_<?php echo $map_id; ?>(map_lat,map_lng)",1000);
                    },{enableHighAccuracy:true});
                }
                //gpress_loading_interval.<?php echo $map_id; ?> = setInterval("gpress_load_map_<?php echo $map_id; ?>(current_lat,current_lng)",1000);
            <?php }else{ ?>
                gpress_loading_interval.<?php echo $map_id; ?> = setInterval("gpress_load_map_<?php echo $map_id; ?>(<?php echo $default_lat; ?>,<?php echo $default_lng; ?>)",1000);
            <?php } ?>
        <?php } ?>
        if(is_geoform===true){
            jQuery(document).ready(function(){
                jQuery('#search_address_<?php echo $map_id; ?>').keypress(function(event){
                    if(event.keyCode == 13){
                        gpress_codeAddress('<?php echo $map_id; ?>');
                        return false;
                    }
                 });
            });
        }
        </script>
    <?php }
}