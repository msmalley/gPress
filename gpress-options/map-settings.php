<div id="normal-sortables" class="meta-box-sortables ui-sortable">
    <div id="map-settings" class="postbox">
        <h3 class="not-sortable"><span><?php _e('Map Settings','gpress'); ?></span></h3>
        <div class="inside">
            <span class="option-intro"><?php _e('<p>These options allow you to control the default settings for maps.</p>','gpress'); ?></span>
            <ul class="gpress-option-list">
                <li class="left-half">
                    <label for="map-height"><?php _e('Default Map Height:', 'gpress'); ?></label>
                </li>
                <li class="right-half">
                    <span class="input-wrapper">
                        <?php $map_height = get_option('gp_map_height','450'); ?>
                        <input id="map-height" type="text" name="gp_map_height" value="<?php echo $map_height; ?>" />
                    </span>
                    <span class="help-text"><?php _e('This is a numbers only field and defaults to 450.','gpress'); ?></span>
                </li>
            </ul>
            <ul class="gpress-option-list">
                <li class="left-half">
                    <label for="map-location"><?php _e('Default Map Location:', 'gpress'); ?></label>
                </li>
                <li class="right-half">
                    <span class="input-wrapper">
                        <?php $map_location = get_option('gp_map_location','San Fransisco'); ?>
                        <input id="map-location" type="text" name="gp_map_location" value="<?php echo $map_location; ?>" />
                    </span>
                    <span class="help-text"><?php _e('This address is used as the default location for new maps when the geo-coding services are unable to obtain an address for the user.<br/><br/>It can be a country, city, street address or even direct coordinates...','gpress'); ?></span>
                </li>
            </ul>
            <ul class="gpress-option-list">
                <li class="left-half">
                    <label><?php _e('Default Map Type:', 'gpress'); ?></label>
                </li>
                <li class="right-half">
                    <?php $map_type = get_option('gp_map_type','ROADMAP'); ?>
                    <input id="map-type-roadmap" type="radio" name="gp_map_type" autocomplete="off" value="ROADMAP"<?php if($map_type=='ROADMAP') { echo ' checked="checked"'; }?> />
                    <label for="map-type-roadmap" class="label-radio"><?php _e('ROADPMAP VIEW', 'gpress'); ?></label>
                    <input id="map-type-satellite" type="radio" name="gp_map_type" autocomplete="off" value="SATELLITE"<?php if($map_type=='SATELLITE') { echo ' checked="checked"'; }?> />
                    <label for="map-type-satellite" class="label-radio"><?php _e('SATELLITE VIEW', 'gpress'); ?></label>
                    <input id="map-type-hybrid" type="radio" name="gp_map_type" autocomplete="off" value="HYBRID"<?php if($map_type=='HYBRID') { echo ' checked="checked"'; }?> />
                    <label for="map-type-hybrid" class="label-radio"><?php _e('HYBRID VIEW', 'gpress'); ?></label>
                    <input id="map-type-terrain" type="radio" name="gp_map_type" autocomplete="off" value="TERRAIN"<?php if($map_type=='TERRAIN') { echo ' checked="checked"'; }?> />
                    <label for="map-type-terrain" class="label-radio"><?php _e('TERRAIN VIEW', 'gpress'); ?></label>
                </li>
            </ul>
            <ul class="gpress-option-list">
                <li class="left-half">
                    <label><?php _e('Default Map Zoom:', 'gpress'); ?></label>
                </li>
                <li class="right-half">
                    <?php $map_zoom = get_option('gp_map_zoom','13'); ?>
                    <input id="map-zoom-close" type="radio" name="gp_map_zoom" autocomplete="off" value="18"<?php if($map_zoom==18) { echo ' checked="checked"'; }?> />
                    <label for="map-zoom-close" class="label-radio"><?php _e('CLOSE-UP', 'gpress'); ?></label>
                    <input id="map-zoom-nearby" type="radio" name="gp_map_zoom" autocomplete="off" value="13"<?php if($map_zoom==13) { echo ' checked="checked"'; }?> />
                    <label for="map-zoom-nearby" class="label-radio"><?php _e('NEARBY', 'gpress'); ?></label>
                    <input id="map-zoom-city" type="radio" name="gp_map_zoom" autocomplete="off" value="10"<?php if($map_zoom==10) { echo ' checked="checked"'; }?> />
                    <label for="map-zoom-city" class="label-radio"><?php _e('CITY VIEW', 'gpress'); ?></label>
                    <input id="map-zoom-country" type="radio" name="gp_map_zoom" autocomplete="off" value="5"<?php if($map_zoom==5) { echo ' checked="checked"'; }?> />
                    <label for="map-zoom-country" class="label-radio"><?php _e('COUNTRY VIEW', 'gpress'); ?></label>
                </li>
            </ul>
            <?php include(GPRESS_DIR.'/gpress-options/submit.php'); ?>
        </div>
    </div>
</div>