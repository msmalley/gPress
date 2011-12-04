<?php
 
function gpress_add_marker($options){
    global $wpdb;
    $default_options = array(
        'meta_type'     => false,
        'meta_key'      => false,
        'meta_id'       => false,
        'lat'           => false,
        'lng'           => false,
        'icon'          => false,
        'shadow'        => false,
        'title'         => false,
        'label'         => false,
        'url'           => false,
        'added'         => date('Y-m-d G:i:s'),
        'validate'      => true
    );
    if(is_array($options)){
        $settings = array_merge($default_options,$options);
    }else{
        $settings = $default_options;
    }
    extract($settings);
    /* RUN CHECKS */
    if(!is_numeric($max)) { $max = -1; }
    if(!is_numeric($offset)) { $offset = 0; }
    if(!is_numeric($lat)) { $lat = NULL; }
    if(!is_numeric($lng)) { $lng = NULL; }
    if($max<0) { $max = -1; }
    if($offset<0) { $offset = 0; }
    if(empty($meta_type)){ $errors = __('Need Meta Type to Add Marker','gpress'); }
    if(empty($meta_key)){ $errors = __('Need Meta Key to Add Marker','gpress'); }
    if(empty($meta_id)){ $errors = __('Need Meta ID to Add Marker','gpress'); }
    if(empty($lat)){ $errors = __('Need Latitude to Add Marker','gpress'); }
    if(empty($lng)){ $errors = __('Need Longitude to Add Marker','gpress'); }
    if(empty($title)){ $errors = __('Need Title to Add Marker','gpress'); }
    if($errors){ return $errors; }else{
        /* CHECKS PASSED */
        $where_clause = "meta_type = '$meta_type' AND meta_key = '$meta_key' AND meta_id = '$meta_id'";
        $this_marker = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM ".GPRESS_DB_TABLE." WHERE ($where_clause)"));
        if($this_marker<1){
            //gpress_printr('this far?');
            $progress = __('Marker Added','gpress');
            $gpress_data = array('meta_type'=>$meta_type,'meta_key'=>$meta_key,'meta_id'=>$meta_id,'lat'=>$lat,'lng'=>$lng,'icon'=>$icon,'shadow'=>$shadow,'title'=>$title,'url'=>$url,'added'=>$added,'map_height'=>$map_height,'map_type'=>$map_type,'map_zoom'=>$map_zoom,'point'=>'GeomFromText(\'POINT($lat $lng)\')');
            $gpress_formats = array('%s','%s','%d','%s','%s','%s','%s','%s','%s','%s','%d','%s','%d','%s');
            $wpdb->insert(GPRESS_DB_TABLE,$gpress_data,$gpress_formats);
        }else{
            $progress = __('Marker Already Added','gpress');
        } if($validate){
            return $progress;
        }
    }
}

function gpress_update_marker($options){
    global $wpdb;
    $default_options = array(
        'meta_type'     => false,
        'meta_key'      => false,
        'meta_id'       => false,
        'lat'           => false,
        'lng'           => false,
        'icon'          => false,
        'shadow'        => false,
        'title'         => false,
        'label'         => false,
        'url'           => false
    );
    if(is_array($options)){
        $settings = array_merge($default_options,$options);
    }else{
        $settings = $default_options;
    }
    extract($settings);
    /* RUN CHECKS */
    if(!is_numeric($lat)) { $lat = NULL; }
    if(!is_numeric($lng)) { $lng = NULL; }
    if(empty($meta_type)){ $errors = __('Need Meta Type to Add Marker','gpress'); }
    if(empty($meta_key)){ $errors = __('Need Meta Key to Add Marker','gpress'); }
    if(empty($meta_id)){ $errors = __('Need Meta ID to Add Marker','gpress'); }
    if(empty($lat)){ $errors = __('Need Latitude to Add Marker','gpress'); }
    if(empty($lng)){ $errors = __('Need Longitude to Add Marker','gpress'); }
    if(empty($title)){ $errors = __('Need Title to Add Marker','gpress'); }
    if($errors){ return $errors; }else{
        /* CHECKS PASSED */
        $where_clause = "meta_type = '$meta_type' AND meta_key = '$meta_key' AND meta_id = '$meta_id'";
        $progress = __('Marker Added','gpress');
        $gpress_data = array('lat'=>$lat,'lng'=>$lng,'icon'=>$icon,'shadow'=>$shadow,'title'=>$title,'url'=>$url,'map_height'=>$map_height,'map_type'=>$map_type,'map_zoom'=>$map_zoom,'point'=>'GeomFromText(\'POINT($lat $lng)\')');
        $gpress_formats = array('%s','%s','%s','%s','%s','%s','%d','%s','%d','%s');
        $wpdb->update(GPRESS_DB_TABLE,$gpress_data,array('meta_type'=>$meta_type,'meta_key'=>$meta_key,'meta_id'=>$meta_id),$gpress_formats,array('%s','%s','%d'));
    }
}

function gpress_prepare_geo_spatial_sql_distance($lat,$lng,$distance=10,$meta_type,$meta_key,$meta_id,$limit=-1){
    if((empty($lat))||(empty($lng))||(!is_numeric($lat))||(!is_numeric($lng))){
        $errors = __('Need Valid Lat / Lng to Check Polygon','gpress');
    } if(!is_numeric($limit)){
        $errors = __('Numeric Limit Required to Check Polygon','gpress');
    } if($errors){ return $errors; }else{
        if($limit>0){ $limit = 'LIMIT 0, '.$limit; }else{ $limit=NULL; }
        $lng1 = $lng - $distance / abs(cos(deg2rad( $lat ) ) *69 );
        $lng2 = $lng + $distance / abs(cos(deg2rad( $lat ) ) *69 );
        $lat1 = $lat - ( $distance /69 );
        $lat2 = $lat + ( $distance /69 );
        if(!empty($meta_id)){
            $extra_where = "AND meta_id = '".$meta_id."'";
        }else{
            if(!empty($meta_type)){
                if(!empty($meta_key)){
                    $extra_where = "AND meta_key = '".$meta_key."'";
                }else{
                    $extra_where = "AND meta_type = '".$meta_type."'";
                }
            }else{
                if(!empty($meta_key)){
                    $extra_where = "AND meta_key = '".$meta_key."'";
                }
            }
        }
        $points_within_distance_sql = "SELECT *,
            ( 6371 * acos( cos( radians( $lat ) ) * cos( radians( lat ) ) * cos( radians( lng ) - radians( $lng ) ) + sin( radians( $lat ) ) * sin( radians( lat ) ) ) ) AS distance
            FROM ".GPRESS_DB_TABLE."
            WHERE lng between $lng1 and $lng2
            AND lat between $lat1 and $lat2
            $extra_where
            HAVING distance < $distance
            ORDER BY distance
            $limit";
        return $points_within_distance_sql;
    }
}

function gpress_prepare_geo_spatial_sql_polygon($lat,$lng,$distance=10,$meta_type,$meta_key,$meta_id,$limit=-1,$polygon=false){
    if((empty($lat))||(empty($lng))||(!is_numeric($lat))||(!is_numeric($lng))){
        $errors = __('Need Valid Lat / Lng to Check Polygon','gpress');
    } if(!is_numeric($limit)){
        $errors = __('Numeric Limit Required to Check Polygon','gpress');
    } if(!is_numeric($distance)){
        $errors = __('Numeric Distance (KM) Required to Check Polygon','gpress');
    } if($errors){ return $errors; }else{
        if($polygon){
            $virtual_polygon = $polygon;
        }else{
            if($limit>0){ $limit = 'LIMIT 0, '.$limit; }else{ $limit=NULL; }
            if(!empty($meta_id)){
                $extra_where = "AND meta_id = '".$meta_id."'";
            }else{
                if(!empty($meta_type)){
                    if(!empty($meta_key)){
                        $extra_where = "AND meta_key = '".$meta_key."'";
                    }else{
                        $extra_where = "AND meta_type = '".$meta_type."'";
                    }
                }else{
                    if(!empty($meta_key)){
                        $extra_where = "AND meta_key = '".$meta_key."'";
                    }
                }
            }
            $lng1 = $lng - $distance / abs(cos(deg2rad( $lat ) ) *69 );
            $lng2 = $lng + $distance / abs(cos(deg2rad( $lat ) ) *69 );
            $lat1 = $lat - ( $distance /69 );
            $lat2 = $lat + ( $distance /69 );
            $central_lat = $lat;
            $central_lng = $lng;
            $central_lat_positive = $lat2;
            $central_lat_negative = $lat1;
            $central_lng_positive = $lng2;
            $central_lng_negative = $lng1;
            /* CREATE VIRTUAL POLYGON */
            $virtual_polygon = $central_lat_positive.' '.$central_lng.',';
            $virtual_polygon.= $central_lat_positive.' '.$central_lng_positive.',';
            $virtual_polygon.= $central_lat.' '.$central_lng_positive.',';
            $virtual_polygon.= $central_lat_negative.' '.$central_lng_positive.',';
            $virtual_polygon.= $central_lat_negative.' '.$central_lng.',';
            $virtual_polygon.= $central_lat_negative.' '.$central_lng_negative.',';
            $virtual_polygon.= $central_lat.' '.$central_lng_negative.',';
            $virtual_polygon.= $central_lat_positive.' '.$central_lng_negative.',';
            $virtual_polygon.= $central_lat_positive.' '.$central_lng;
        }
        /* COMBINE RESULTS INTO SINGLE QUERY STATEMENT USING GEO-SPATIAL FORMATS */
        if($extra_where){
            $points_within_polygon_sql = "SELECT * FROM ".GPRESS_DB_TABLE." WHERE (MBRContains(
                GeomFromText( 'POLYGON(($virtual_polygon))' ),
                point
            ) = 1 $extra_where) $limit";            
        }else{
            $points_within_polygon_sql = "SELECT * FROM ".GPRESS_DB_TABLE." WHERE MBRContains(
                GeomFromText( 'POLYGON(($virtual_polygon))' ),
                point
            ) = 1 $limit";
        }
        return $points_within_polygon_sql;
    }
}

function gpress_get_markers($options){
    global $wpdb;
    $places_taxonomy = __( 'place', 'gpress' );
    $default_options = array(
        'meta_type'     => false,
        'meta_key'      => false,
        'meta_id'       => false,
        'lat'           => false,
        'lng'           => false,
        'distance'      => false,
        'add_content'   => false,
        'order_by'      => 'updated',
        'polygon'       => '',
        'max'           => -1,
        'offset'        => 0
    );
    if(is_array($options)){
        $settings = array_merge($default_options,$options);
    }else{
        $settings = $default_options;
    }
    extract($settings);
    if(!is_numeric($max)) { $max = -1; }
    if(!is_numeric($offset)) { $offset = 0; }
    if($max<0) { $max = -1; }
    if($offset<0) { $offset = 0; }
    if(($polygon!=true)&&($polygon!=false)){
        if(empty($meta_type)){ $errors = __('Need Meta Type to Get Markers','gpress'); }
        if(empty($meta_key)){ $errors = __('Need Meta Key to Get Markers','gpress'); }
    } if(empty($order_by)){ $order_by = 'updated'; }
    if($errors){ return $errors; }else{
        if(!empty($polygon)){
            /* GET BASED ON POLYGON */
            $points = $wpdb->get_results(gpress_prepare_geo_spatial_sql_polygon($lat,$lng,$distance,$meta_type,$meta_key,$meta_id,$max,$polygon));
        }elseif((!empty($distance))&&(!empty($lat))&&(!empty($lng))){
            /* GET BASED ON DISTANCE */
            $points = $wpdb->get_results(gpress_prepare_geo_spatial_sql_distance($lat,$lng,$distance,$meta_type,$meta_key,$meta_id,$max));
        }elseif(!empty($meta_id)){
            /* GET BASED ON SPECIFIC ID */
            if($limit>0){ $limit = 'LIMIT 0, '.$limit; }else{ $limit=NULL; }
            $points = $wpdb->get_results("SELECT * FROM ".GPRESS_DB_TABLE." WHERE meta_id = '$meta_id' and meta_key = '$meta_key' and meta_type = '$meta_type' $limit");
        }else{
            /* GET ALL FOR THIS TYPE AND KEY */
            if($limit>0){ $limit = 'LIMIT 0, '.$limit; }else{ $limit=NULL; }
            $points = $wpdb->get_results("SELECT * FROM ".GPRESS_DB_TABLE." WHERE meta_key = '$meta_key' and meta_type = '$meta_type' $limit");
        }
        if(!empty($points)){
            if(is_array($points)){
                $markers = array();
                $meta_array = array();
                foreach($points as $point){
                    /* BUILD MARKER ARRAY */
                    $markers[$point->gp_id]['type']         = $point->meta_type;
                    $markers[$point->gp_id]['key']          = $point->meta_key;
                    $markers[$point->gp_id]['id']           = $point->meta_id;
                    $markers[$point->gp_id]['lat']          = $point->lat;
                    $markers[$point->gp_id]['lng']          = $point->lng;
                    $markers[$point->gp_id]['icon']         = $point->icon;
                    $markers[$point->gp_id]['shadow']       = $point->shadow;
                    $markers[$point->gp_id]['title']        = $point->title;
                    $markers[$point->gp_id]['address']      = $point->address;
                    $markers[$point->gp_id]['url']          = $point->url;
                    $markers[$point->gp_id]['map_height']   = $point->map_height;
                    $markers[$point->gp_id]['map_type']     = $point->map_type;
                    $markers[$point->gp_id]['map_zoom']     = $point->map_zoom;
                    $markers[$point->gp_id]['added']        = $point->added;
                    $markers[$point->gp_id]['updated']      = $point->updated;
                    if($point->distance){
                        $markers[$point->gp_id]['distance'] = round($point->distance,0);
                    }else{
                        if((!empty($lat))&&(!empty($lng))){
                            $markers[$point->gp_id]['distance'] = round(gpress_get_distance($point->lat, $point->lng, $lat, $lng),0);
                        }
                    }if($add_content){
                        if($meta_type=='post'){
                            $post = get_post($point->meta_id);
                            $markers[$point->gp_id]['author']           = $post->post_author;
                            $markers[$point->gp_id]['added']            = $post->post_date;
                            $markers[$point->gp_id]['content']          = $post->post_content;
                            $markers[$point->gp_id]['title']            = $post->post_title;
                            $markers[$point->gp_id]['excerpt']          = $post->post_excerpt;
                            $markers[$point->gp_id]['post_status']      = $post->post_status;
                            $markers[$point->gp_id]['comment_status']   = $post->comment_status;
                            $markers[$point->gp_id]['updated']          = $post->post_modified;
                            $markers[$point->gp_id]['url']              = $post->guid;
                            if((empty($markers[$point->gp_id]['icon']))||($markers[$point->gp_id]['icon']=='N')){
                                if($post->post_type==$places_taxonomy){ $marker_name='place'; }else{ $marker_name='post'; }
                                $markers[$point->gp_id]['icon'] = get_site_option('gp_marker_url_posts', GPRESS_URL.'/gpress-core/images/markers/'.$marker_name.'.png');
                            }
                            if((empty($markers[$point->gp_id]['shadow']))||($markers[$point->gp_id]['shadow']=='N')){
                                $markers[$point->gp_id]['shadow'] = get_site_option('gp_shadow_url_posts', GPRESS_URL.'/gpress-core/images/markers/bg.png');
                            }
                            /* FILTER FOR ALLOWING CHANGES TO INDIVIDUAL MARKERS / MARKER TYPES, ETC */
                            $markers[$point->gp_id] = apply_filters('gpress_marker',$markers[$point->gp_id]);
                        }else{
                            $markers = __('Invalid Meta-Type for Including Added Content','gpress');
                        }
                    }
                }
            }else{
                $markers = __('Expecting an Array of Points to Build Marker Array','gpress');
            }
        }else{
            $markers = __('No Points Found','gpress');
        } if(is_array($markers)){
            if(($order_by!='updated')&&($order_by!='added')&&($order_by!='updated')&&($order_by!='distance')){
                $markers = __('Invalid Order By Format for Getting Markers','gpress');
            }else{
                /* SORT MARKERS */
                if($order_by=='distance'){
                    if((empty($lat))||(empty($lng))){
                        $markers = __('Origin Point (Lat / Lng) Required to Order By Distance','gpress');
                    }else{
                        usort($markers,'gpress_marker_sort_by_distance');
                    }
                }else{
                    if($order_by=='added'){
                        usort($markers,'gpress_marker_sort_by_added');
                    }else{
                        usort($markers,'gpress_marker_sort_by_updated');
                    }
                }
            }
        }
        /* THIS FILTER ALLOWS MODIFICATION TO ARRAY AFTER SORTING */
        $markers = apply_filters('gpress_marker_array',$markers);
        return $markers;
    }
}

?>