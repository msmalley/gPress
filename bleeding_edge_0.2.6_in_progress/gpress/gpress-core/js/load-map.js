function gpress_checkGmapsContainer(){
    console.log('checkGmapsContainer');
    jQuery('div.gpress_mapcanvas').each(function(){
        console.log('a');
        console.log(this);
        console.log(gmaps_settings);
        if((jQuery.abovethetop(this, gmaps_settings)) || (jQuery.leftofbegin(this, gmaps_settings))) {
            /* Nothing. */
            console.log('b');
        } else if((!jQuery.belowthefold(this, gmaps_settings)) && (!jQuery.rightoffold(this, gmaps_settings))) {
            console.log('c');
            if(!gpress_array_find(alreadyInit,this)){
                alreadyInit.push(this);
                var id = jQuery(this).attr('id').replace('gpress_canvas_','');
                console.log(id);
                eval('initialize_'+id+'();');
            }
        } else {
            console.log('d');
            return false;
        }
    });
}

jQuery(document).ready(function(){
    setInterval(gpress_checkGmapsContainer,1000);
});