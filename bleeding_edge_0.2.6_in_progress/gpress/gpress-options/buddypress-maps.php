<div id="normal-sortables" class="meta-box-sortables ui-sortable">
    <div id="bp-maps" class="postbox">
        <h3 class="not-sortable"><span><?php _e('BuddyPress Maps','gpress'); ?></span></h3>
        <div class="inside">
            <span class="option-intro"><?php _e('<p>These options allow you to control BuddyPress maps.</p>','gpress'); ?></span>
            <ul class="gpress-option-list">
                <li class="left-half">
                    <label for="profile-height"><?php _e('Profile Map Height:', 'gpress'); ?></label>
                </li>
                <li class="right-half">
                    <span class="input-wrapper">
                        <?php $profile_height = get_option('gp_profile_height','450'); ?>
                        <input id="profile-height" type="text" name="gp_profile_height" value="<?php echo $profile_height; ?>" />
                    </span>
                    <span class="help-text"><?php _e('This is a numbers only field and defaults to 450.','gpress'); ?></span>
                </li>
            </ul>
            <ul class="gpress-option-list">
                <li class="left-half">
                    <label><?php _e('Profile Map Type:', 'gpress'); ?></label>
                </li>
                <li class="right-half">
                    <?php $profile_type = get_option('gp_profile_type','ROADMAP'); ?>
                    <input id="profile-type-roadmap" type="radio" name="gp_profile_type" autocomplete="off" value="ROADMAP"<?php if($profile_type=='ROADMAP') { echo ' checked="checked"'; }?> />
                    <label for="profile-type-roadmap" class="label-radio"><?php _e('ROADPMAP VIEW', 'gpress'); ?></label>
                    <input id="profile-type-satellite" type="radio" name="gp_profile_type" autocomplete="off" value="SATELLITE"<?php if($profile_type=='SATELLITE') { echo ' checked="checked"'; }?> />
                    <label for="profile-type-satellite" class="label-radio"><?php _e('SATELLITE VIEW', 'gpress'); ?></label>
                    <input id="profile-type-hybrid" type="radio" name="gp_profile_type" autocomplete="off" value="HYBRID"<?php if($profile_type=='HYBRID') { echo ' checked="checked"'; }?> />
                    <label for="profile-type-hybrid" class="label-radio"><?php _e('HYBRID VIEW', 'gpress'); ?></label>
                    <input id="profile-type-terrain" type="radio" name="gp_profile_type" autocomplete="off" value="TERRAIN"<?php if($profile_type=='TERRAIN') { echo ' checked="checked"'; }?> />
                    <label for="profile-type-terrain" class="label-radio"><?php _e('TERRAIN VIEW', 'gpress'); ?></label>
                </li>
            </ul>
            <ul class="gpress-option-list">
                <li class="left-half">
                    <label><?php _e('Profile Map Zoom:', 'gpress'); ?></label>
                </li>
                <li class="right-half">
                    <?php $profile_zoom = get_option('gp_profile_zoom','13'); ?>
                    <input id="profile-zoom-close" type="radio" name="gp_profile_zoom" autocomplete="off" value="18"<?php if($profile_zoom==18) { echo ' checked="checked"'; }?> />
                    <label for="profile-zoom-close" class="label-radio"><?php _e('CLOSE-UP', 'gpress'); ?></label>
                    <input id="profile-zoom-nearby" type="radio" name="gp_profile_zoom" autocomplete="off" value="13"<?php if($profile_zoom==13) { echo ' checked="checked"'; }?> />
                    <label for="profile-zoom-nearby" class="label-radio"><?php _e('NEARBY', 'gpress'); ?></label>
                    <input id="profile-zoom-city" type="radio" name="gp_profile_zoom" autocomplete="off" value="10"<?php if($profile_zoom==10) { echo ' checked="checked"'; }?> />
                    <label for="profile-zoom-city" class="label-radio"><?php _e('CITY VIEW', 'gpress'); ?></label>
                    <input id="profile-zoom-country" type="radio" name="gp_profile_zoom" autocomplete="off" value="5"<?php if($profile_zoom==5) { echo ' checked="checked"'; }?> />
                    <label for="profile-zoom-country" class="label-radio"><?php _e('COUNTRY VIEW', 'gpress'); ?></label>
                </li>
            </ul>
            <ul class="gpress-option-list">
                <li class="left-half">
                    <label for="activity-height"><?php _e('Activity Map Height:', 'gpress'); ?></label>
                </li>
                <li class="right-half">
                    <span class="input-wrapper">
                        <?php $activity_height = get_option('gp_activity_height','450'); ?>
                        <input id="activity-height" type="text" name="gp_activity_height" value="<?php echo $activity_height; ?>" />
                    </span>
                    <span class="help-text"><?php _e('This is a numbers only field and defaults to 450.','gpress'); ?></span>
                </li>
            </ul>
            <ul class="gpress-option-list">
                <li class="left-half">
                    <label><?php _e('Activity Map Type:', 'gpress'); ?></label>
                </li>
                <li class="right-half">
                    <?php $activity_type = get_option('gp_activity_type','ROADMAP'); ?>
                    <input id="activity-type-roadmap" type="radio" name="gp_activity_type" autocomplete="off" value="ROADMAP"<?php if($activity_type=='ROADMAP') { echo ' checked="checked"'; }?> />
                    <label for="activity-type-roadmap" class="label-radio"><?php _e('ROADPMAP VIEW', 'gpress'); ?></label>
                    <input id="activity-type-satellite" type="radio" name="gp_activity_type" autocomplete="off" value="SATELLITE"<?php if($activity_type=='SATELLITE') { echo ' checked="checked"'; }?> />
                    <label for="activity-type-satellite" class="label-radio"><?php _e('SATELLITE VIEW', 'gpress'); ?></label>
                    <input id="activity-type-hybrid" type="radio" name="gp_activity_type" autocomplete="off" value="HYBRID"<?php if($activity_type=='HYBRID') { echo ' checked="checked"'; }?> />
                    <label for="activity-type-hybrid" class="label-radio"><?php _e('HYBRID VIEW', 'gpress'); ?></label>
                    <input id="activity-type-terrain" type="radio" name="gp_activity_type" autocomplete="off" value="TERRAIN"<?php if($activity_type=='TERRAIN') { echo ' checked="checked"'; }?> />
                    <label for="activity-type-terrain" class="label-radio"><?php _e('TERRAIN VIEW', 'gpress'); ?></label>
                </li>
            </ul>
            <ul class="gpress-option-list">
                <li class="left-half">
                    <label><?php _e('Activity Map Zoom:', 'gpress'); ?></label>
                </li>
                <li class="right-half">
                    <?php $activity_zoom = get_option('gp_activity_zoom','13'); ?>
                    <input id="activity-zoom-close" type="radio" name="gp_activity_zoom" autocomplete="off" value="18"<?php if($activity_zoom==18) { echo ' checked="checked"'; }?> />
                    <label for="activity-zoom-close" class="label-radio"><?php _e('CLOSE-UP', 'gpress'); ?></label>
                    <input id="activity-zoom-nearby" type="radio" name="gp_activity_zoom" autocomplete="off" value="13"<?php if($activity_zoom==13) { echo ' checked="checked"'; }?> />
                    <label for="activity-zoom-nearby" class="label-radio"><?php _e('NEARBY', 'gpress'); ?></label>
                    <input id="activity-zoom-city" type="radio" name="gp_activity_zoom" autocomplete="off" value="10"<?php if($activity_zoom==10) { echo ' checked="checked"'; }?> />
                    <label for="activity-zoom-city" class="label-radio"><?php _e('CITY VIEW', 'gpress'); ?></label>
                    <input id="activity-zoom-country" type="radio" name="gp_activity_zoom" autocomplete="off" value="5"<?php if($activity_zoom==5) { echo ' checked="checked"'; }?> />
                    <label for="activity-zoom-country" class="label-radio"><?php _e('COUNTRY VIEW', 'gpress'); ?></label>
                </li>
            </ul>
            <?php include(GPRESS_DIR.'/gpress-options/submit.php'); ?>
        </div>
    </div>
</div>