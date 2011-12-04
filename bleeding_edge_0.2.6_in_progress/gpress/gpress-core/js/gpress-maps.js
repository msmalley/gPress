function gpress_array_find(array,item){
    for(var i=0;i<array.length;i++){
        if(item == array[i])
            return true;
    }
    return false;
}

function gpress_clear_interval(id){
    clearInterval(gpress_loading_interval[id]);
}

function initialize_gpress_map(id,map_type,map_zoom,lat,lng,is_geoform,markers,use_infowindows){
    var default_lat = 0; var this_lat;
    var default_lng = 0; var this_lng;
    if(lat!=0){ this_lat=lat; }else{ this_lat=current_lat; }
    if(lng!=0){ this_lng=lng; }else{ this_lng=current_lng; }
    if((!lat)||(!lng)){
        lat = default_lat;
        lng = default_lng;
    }
    gpress_display_map(
        id,
        map_type,
        map_zoom,
        this_lat,
        this_lng,
        is_geoform,
        markers,
        use_infowindows
    );
}

function gpress_check_if_gmaps_loaded(id,map_type,map_zoom,lat,lng,is_geoform,markers,use_infowindows){
    var elem = '#gpress_canvas_'+id;
    var docViewTop = jQuery(window).scrollTop(),
    docViewBottom = docViewTop + jQuery(window).height(),
    elemTop = jQuery(elem).offset().top,
    elemBottom = elemTop + jQuery(elem).height();
    result = ((elemTop + ((elemBottom - elemTop)/2)) >= docViewTop && ((elemTop + ((elemBottom - elemTop)/2)) <= docViewBottom));
    if(result===true){
        initialize_gpress_map(id,map_type,map_zoom,lat,lng,is_geoform,markers,use_infowindows);
        gpress_clear_interval(id);
    }
}

function gpress_geocodePosition(id,pos) {
    geocoder.id = new google.maps.Geocoder();
    geocoder.id.geocode({
        latLng: pos
    }, function(responses) {
        if (responses && responses.length > 0) {
            gpress_updateMarkerAddress(id, responses[0].formatted_address);
        }else{
            gpress_updateMarkerAddress(id, 'Cannot determine address at this location.');
        }
    });
}

function gpress_updateMarkerStatus(id,str) {
    jQuery('#markerStatus_'+id).html(str);
}

function gpress_updateMarkerPosition(id,latLng) {
    jQuery('#lat_'+id).val(latLng.lat());
    jQuery('#lng_'+id).val(latLng.lng());
}

function gpress_updateMarkerAddress(id,str) {
    jQuery('#address_'+id).val(str);
}

function gpress_get_coordinates_from_address(id,address,this_function){
    if(address){
        if(geocoder.id){
            geocoder.id.geocode( { 'address': address}, function(results, status) {
                if (status == google.maps.GeocoderStatus.OK) {
                    newLatLng = ''+results[0].geometry.location.za+','+results[0].geometry.location.Ba+'';
                    this_function(newLatLng);
                }
            });
        }
    }
}

function gpress_getFormattedLocation() {
    if (google.loader.ClientLocation.address.country_code == "US" &&
        google.loader.ClientLocation.address.region) {
            return google.loader.ClientLocation.address.city + ", "
            + google.loader.ClientLocation.address.region.toUpperCase();
    }else{
        return  google.loader.ClientLocation.address.city + ", "
        + google.loader.ClientLocation.address.country_code;
    }
}

function gpress_codeAddress(id,use_specific_value) {
    if(!use_specific_value){
        var address = jQuery('#search_address_'+id).val();
    }else{
        var address = use_specific_value;
    }
    geocoder.id = new google.maps.Geocoder();
    geocoder.id.geocode( { 'address': address}, function(results, status) {
        if (status == google.maps.GeocoderStatus.OK) {
            map.id.setCenter(results[0].geometry.location);
            marker.id.setPosition(results[0].geometry.location);
            gpress_geocodePosition(id,results[0].geometry.location);
            gpress_updateMarkerPosition(id,results[0].geometry.location);
        }else{
            if(status == "ZERO_RESULTS") {
                alert("Sorry, but the address specified cannot be found...");
            }else if (status == "OVER_QUERY_LIMIT") {
                alert("Sorry, but you have exceeded your query limit quota...");
            }else if (status == "REQUEST_DENIED") {
                alert("Sorry, but for some reason, Google denied your request...");
            }else if (status == "INVALID_REQUEST") {
                alert("Sorry, but for some reason, something seems to have gone wrong...");
            }else {
                alert("Geocode was not successful for the following reason: " + status);
            }
        }
    });
}

function gpress_clusters(id){
    if(marker_cluster.id){
        //marker_cluster.id.clearMarkers();
        //-> USING THIS MAKES MARKERS SKIP MAPS WHEN 2 OR MORE MAPS ON ONE PAGE
    }
    var zoom = 18;
    var size = 50;
    var style = 0;
    marker_cluster.id = new MarkerClusterer(map.id, clustered_markers.id, {
        maxZoom: zoom,
        gridSize: size,
        styles: styles[style]
    });
}

function gpress_display_map(id,type,zoom,lat,lng,geoform,markers,use_infowindows){
    canvas_width.id = (jQuery('#gpress_canvas_'+id).width()-75);
    canvas_height.id = (jQuery('#gpress_canvas_'+id).height()-125);
    inner_width.id = (canvas_width.id - 200);
    latLng = new google.maps.LatLng(lat,lng);
    if(type=='SATELLITE'){
        map_type = google.maps.MapTypeId.SATELLITE;
    }else if(type=='TERRAIN'){
        map_type = google.maps.MapTypeId.TERRAIN;
    }else if(type=='HYBRID'){
        map_type = google.maps.MapTypeId.HYBRID;
    }else{
        map_type = google.maps.MapTypeId.ROADMAP;
    }
    map.id = new google.maps.Map(document.getElementById('gpress_canvas_'+id), {
        zoom: zoom,
        center: latLng,
        mapTypeId: map_type
    });
    if(geoform){
        marker_lat_lng = new google.maps.LatLng(lat, lng);
        marker.id = new google.maps.Marker({
            position: marker_lat_lng,
            title: 'Drag Me',
            map: map.id,
            draggable: true
        });
        // Update Current Position
        if((!lat)||(!lng)){
            gpress_updateMarkerPosition(id,marker_lat_lng);
            gpress_geocodePosition(id,marker_lat_lng);
            gpress_codeAddress(id, lat+','+lng);
        }
        // Add Dragging Listeners
        google.maps.event.addListener(marker.id, 'dragstart', function() {
            gpress_updateMarkerAddress(id,'Dragging...');
        });
        google.maps.event.addListener(marker.id, 'drag', function() {
            gpress_updateMarkerStatus(id,'Dragging...');
            gpress_updateMarkerPosition(id,marker.id.getPosition());
        });
        google.maps.event.addListener(marker.id, 'dragend', function() {
            gpress_updateMarkerStatus(id,'Drag ended');
            gpress_geocodePosition(id,marker.id.getPosition());
        });
    }else{
        if(markers){
            gpress_index.id = 0;
            clustered_markers.id = new Array();
            jQuery.each(markers, function(index,value){
                var this_id = id+'_'+gpress_index.id;
                icon = new google.maps.MarkerImage(value.icon,
                    new google.maps.Size(30, 30),
                    new google.maps.Point(0, 0),
                    new google.maps.Point(21, 22)
                );
                shadow = new google.maps.MarkerImage(value.shadow,
                    new google.maps.Size(40, 40),
                    new google.maps.Point(0, 0),
                    new google.maps.Point(26, 27)
                );
                marker_lat_lng = new google.maps.LatLng(value.lat, value.lng);
                marker[this_id] = new google.maps.Marker({
                    position: marker_lat_lng,
                    title: value.title,
                    map: map.id,
                    icon: icon,
                    shadow: shadow,
                    draggable: false
                });
                clustered_markers.id[gpress_index.id] = new Array();
                clustered_markers.id[gpress_index.id] = marker[this_id];
                if(marker[this_id].use_infowindows){
                    use_infowindows = marker[this_id].use_infowindows;
                }
                if(use_infowindows){
                    gpress_info_box[this_id] = function(id,opts){
                        google.maps.OverlayView.call(this);
                        this.latlng_ = opts.latlng;
                        this.map_ = opts.map;
                        this.offsetVertical_ = -29;
                        this.offsetHorizontal_ = -28;
                        this.maxHeight_ = (canvas_height.id - 90);
                        this.width_ = (canvas_width.id - 200);
                        var me = this;
                        this.boundsChangedListener_ = google.maps.event.addListener(map.id, 'bounds_changed', function() {
                            return me.panMap.apply(me);
                        });
                        this.setMap(this.map_);
                    }
                    /* INFOWINDOWS */
                    gpress_info_box[this_id].prototype = new google.maps.OverlayView();
                    gpress_info_box[this_id].prototype.remove = function() {
                        if(this.div_){
                            this.div_.parentNode.removeChild(this.div_);
                            this.div_ = null;
                        }
                    };
                    gpress_info_box[this_id].prototype.draw = function() {
                      this.createElement();
                      if(!this.div_) return;
                      var pixPosition = this.getProjection().fromLatLngToDivPixel(this.latlng_);
                      if (!pixPosition) return;
                      this.div_.style.width = this.width_ + 'px';
                      this.div_.style.left = (pixPosition.x + this.offsetHorizontal_) + 'px';
                      this.div_.style.maxHeight = this.maxHeight_ + 'px';
                      this.div_.style.height = 'auto';
                      this.div_.style.top = (pixPosition.y + this.offsetVertical_) + 'px';
                      this.div_.style.display = 'block';
                    };
                    gpress_info_box[this_id].prototype.createElement = function() {
                        var panes = this.getPanes();
                        var div = this.div_;
                        if(!div){
                            console.log('got no div for create');
                            div = this.div_ = document.createElement('div');
                            div.style.border = '2px solid #CCC';
                            div.style.position = 'absolute';
                            div.style.background = '#FFF';
                            div.style.paddingBottom = '15px';
                            div.style.width = this.width_ + 'px';
                            div.style.height = 'auto';
                            div.style.maxHeight = this.maxHeight_ + 'px';
                            jQuery(div).addClass('gpress-window-wrapper');
                            var contentDiv = document.createElement('div');
                            contentDiv.style.maxHeight = (( this.maxHeight_ ) - 100 )+ 'px';
                            contentDiv.style.padding = '15px 15px 10px';
                            contentDiv.style.margin = '5px';
                            contentDiv.style.cursor = 'auto';
                            contentDiv.style.overflowY = 'auto';
                            contentDiv.style.overflowX = 'hidden';
                            contentDiv.innerHTML = value.content;
                            jQuery(contentDiv).addClass('gpress-window-content');
                            var topDiv = document.createElement('div');
                            topDiv.style.textAlign = 'right';
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
                            topDiv.innerHTML = '<div class="infobox_title" style="background-image:url(\''+value.icon+'\'); background-repeat: no-repeat;"><a href="'+value.url+'">'+value.title+'</a></div>';
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
                                }else{
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
                    gpress_info_box[this_id].prototype.panMap = function() {
                        var map = this.map_;
                        var bounds = map.getBounds();
                        if (!bounds) return;
                        var position = this.latlng_;
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
                    google.maps.event.addListener(marker[this_id], "click", function(e) {
                        new_info_box.id = new gpress_info_box[this_id](id,{latlng: marker[this_id].getPosition(), map: marker[this_id].map});
                    });
                }else{
                    if(value.url){
                        google.maps.event.addListener(this_marker, "click", function(e) {
                            parent.location.href = value.url;
                        });
                    }
                } gpress_index.id++;
            });
        }
    } if(!is_geoform){
        gpress_clusters(id);
    }
}