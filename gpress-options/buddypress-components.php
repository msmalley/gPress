<div id="normal-sortables" class="meta-box-sortables ui-sortable">
    <div id="bp-components" class="postbox">
        <h3 class="not-sortable"><span><?php _e('BuddyPress Components','gpress'); ?></span></h3>
        <div class="inside">
            <span class="option-intro"><?php _e('<p>These options allow you to control the components available for BuddyPress.</p>','gpress'); ?></span>
            <ul class="gpress-option-list">
                <li class="left-half">
                    <label><?php _e('Show User Location Settings at Sign-Up:', 'gpress'); ?></label>
                </li>
                <li class="right-half">
                    <?php $bp_show_signup = get_option('gp_bp_show_signup','enabled'); ?>
                    <input id="bp-show-signup-enabled" type="radio" name="gp_bp_show_signup" autocomplete="off" value="enabled"<?php if($bp_show_signup=='enabled') { echo ' checked="checked"'; }?> />
                    <label for="bp-show-signup-enabled" class="label-radio"><?php _e('ENABLED', 'gpress'); ?></label>
                    <input id="bp-show-signup-disabled" type="radio" name="gp_bp_show_signup" autocomplete="off" value="disabled"<?php if($bp_show_signup=='disabled') { echo ' checked="checked"'; }?> />
                    <label for="bp-show-signup-disabled" class="label-radio"><?php _e('DISABLED', 'gpress'); ?></label>
                </li>
            </ul>
            <ul class="gpress-option-list">
                <li class="left-half">
                    <label><?php _e('User Rights:', 'gpress'); ?></label>
                </li>
                <li class="right-half">
                    <?php $bp_user_rights = get_option('gp_bp_user_rights','individual'); ?>
                    <input id="bp-user-rights-individual" type="radio" name="gp_bp_user_rights" autocomplete="off" value="individual"<?php if($bp_user_rights=='individual') { echo ' checked="checked"'; }?> />
                    <label for="bp-user-rights-individual" class="label-radio"><?php _e('<strong>INDIVIDUAL SETTINGS</strong><br />Each user can control their own settings...', 'gpress'); ?></label>
                    <input id="bp-user-rights-override" type="radio" name="gp_bp_user_rights" autocomplete="off" value="override"<?php if($bp_user_rights=='override') { echo ' checked="checked"'; }?> />
                    <label for="bp-user-rights-override" class="label-radio"><?php _e('<strong>SITEWIDE OVER-RIDE</strong><br />The default settings control all user settings...', 'gpress'); ?></label>
                </li>
            </ul>
            <ul class="gpress-option-list">
                <li class="left-half">
                    <label><?php _e('Default User Locations on BuddyPress Profiles:', 'gpress'); ?></label>
                </li>
                <li class="right-half">
                    <?php $bp_user_location = get_option('gp_bp_user_location','ABOVE'); ?>
                    <input id="bp-user-location-above" type="radio" name="gp_bp_user_location" autocomplete="off" value="ABOVE"<?php if($bp_user_location=='ABOVE') { echo ' checked="checked"'; }?> />
                    <label for="bp-user-location-above" class="label-radio"><?php _e('ABOVE PROFILE FIELDS', 'gpress'); ?></label>
                    <input id="bp-user-location-below" type="radio" name="gp_bp_user_location" autocomplete="off" value="BELOW"<?php if($bp_user_location=='BELOW') { echo ' checked="checked"'; }?> />
                    <label for="bp-user-location-below" class="label-radio"><?php _e('BELOW PROFILE FIELDS', 'gpress'); ?></label>
                    <input id="bp-user-location-none" type="radio" name="gp_bp_user_location" autocomplete="off" value="NONE"<?php if($bp_user_location=='NONE') { echo ' checked="checked"'; }?> />
                    <label for="bp-user-location-none" class="label-radio"><?php _e('DO NOT DISPLAY', 'gpress'); ?></label>
                </li>
            </ul>
            <ul class="gpress-option-list">
                <li class="left-half">
                    <label><?php _e('Default Closest Address on User Profile:', 'gpress'); ?></label>
                </li>
                <li class="right-half">
                    <?php $bp_show_address = get_option('gp_bp_show_address','enabled'); ?>
                    <input id="bp-show-address-enabled" type="radio" name="gp_bp_show_address" autocomplete="off" value="enabled"<?php if($bp_show_address=='enabled') { echo ' checked="checked"'; }?> />
                    <label for="bp-show-address-enabled" class="label-radio"><?php _e('ENABLED', 'gpress'); ?></label>
                    <input id="bp-show-address-disabled" type="radio" name="gp_bp_show_address" autocomplete="off" value="disabled"<?php if($bp_show_address=='disabled') { echo ' checked="checked"'; }?> />
                    <label for="bp-show-address-disabled" class="label-radio"><?php _e('DISABLED', 'gpress'); ?></label>
                </li>
            </ul>
            <ul class="gpress-option-list">
                <li class="left-half">
                    <label><?php _e('Show Geo-Tagged Posts on User\'s Activity Page:', 'gpress'); ?></label>
                </li>
                <li class="right-half">
                    <?php $bp_show_posts = get_option('gp_bp_show_posts','enabled'); ?>
                    <input id="bp-show-posts-enabled" type="radio" name="gp_bp_show_posts" autocomplete="off" value="enabled"<?php if($bp_show_posts=='enabled') { echo ' checked="checked"'; }?> />
                    <label for="bp-show-posts-enabled" class="label-radio"><?php _e('ENABLED', 'gpress'); ?></label>
                    <input id="bp-show-posts-disabled" type="radio" name="gp_bp_show_posts" autocomplete="off" value="disabled"<?php if($bp_show_posts=='disabled') { echo ' checked="checked"'; }?> />
                    <label for="bp-show-posts-disabled" class="label-radio"><?php _e('DISABLED', 'gpress'); ?></label>
                </li>
            </ul>
            <?php include(GPRESS_DIR.'/gpress-options/submit.php'); ?>
        </div>
    </div>
</div>