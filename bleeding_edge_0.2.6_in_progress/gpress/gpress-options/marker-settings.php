<div id="normal-sortables" class="meta-box-sortables ui-sortable">
    <div id="marker-settings" class="postbox">
        <h3 class="not-sortable"><span><?php _e('Marker Settings','gpress'); ?></span></h3>
        <div class="inside">
            <span class="option-intro"><?php _e('<p>These options allow you to upload or link to custom images for markers.</p><p>The markers themselves should be 30px by 30px, whereas the shadows behind them should be 40px by 40px. Both should be transparent PNGs.</p>','gpress'); ?></span>
            <ul class="gpress-option-list">
                <li class="left-half">
                    <label for="marker-url-posts"><?php _e('Marker Icon URL for Posts:', 'gpress'); ?></label>
                </li>
                <li class="right-half">
                    <span class="input-wrapper">
                        <input id="marker-url-posts" type="text" name="gp_marker_url_posts" value="<?php echo get_option('gp_marker_url_posts'); ?>" />
                    </span>
                    <span class="help-text"><?php _e('This should start with http://','gpress'); ?></span>
                </li>
            </ul>
            <ul class="gpress-option-list">
                <li class="left-half">
                    <label for="shadow-url-posts"><?php _e('Shadow Icon URL for Posts:', 'gpress'); ?></label>
                </li>
                <li class="right-half">
                    <span class="input-wrapper">
                        <input id="shadow-url-posts" type="text" name="gp_shadow_url_posts" value="<?php echo get_option('gp_shadow_url_posts'); ?>" />
                    </span>
                    <span class="help-text"><?php _e('This should start with http://','gpress'); ?></span>
                </li>
            </ul>
            <ul class="gpress-option-list">
                <li class="left-half">
                    <label for="marker-url-places"><?php _e('Marker Icon URL for Places:', 'gpress'); ?></label>
                </li>
                <li class="right-half">
                    <span class="input-wrapper">
                        <input id="marker-url-places" type="text" name="gp_marker_url_places" value="<?php echo get_option('gp_marker_url_places'); ?>" />
                    </span>
                    <span class="help-text"><?php _e('This should start with http://','gpress'); ?></span>
                </li>
            </ul>
            <ul class="gpress-option-list">
                <li class="left-half">
                    <label for="shadow-url-places"><?php _e('Shadow Icon URL for Places:', 'gpress'); ?></label>
                </li>
                <li class="right-half">
                    <span class="input-wrapper">
                        <input id="shadow-url-places" type="text" name="gp_shadow_url_places" value="<?php echo get_option('gp_shadow_url_places'); ?>" />
                    </span>
                    <span class="help-text"><?php _e('This should start with http://','gpress'); ?></span>
                </li>
            </ul>
            <ul class="gpress-option-list">
                <li class="left-half">
                    <label for="marker-url-favplaces"><?php _e('Marker Icon URL for Favorite Place Widget:', 'gpress'); ?></label>
                </li>
                <li class="right-half">
                    <span class="input-wrapper">
                        <input id="marker-url-favplaces" type="text" name="gp_marker_url_favplaces" value="<?php echo get_option('gp_marker_url_favplaces'); ?>" />
                    </span>
                    <span class="help-text"><?php _e('This should start with http://','gpress'); ?></span>
                </li>
            </ul>
            <ul class="gpress-option-list">
                <li class="left-half">
                    <label for="shadow-url-favplaces"><?php _e('Shadow Icon URL for Favorite Place Widget:', 'gpress'); ?></label>
                </li>
                <li class="right-half">
                    <span class="input-wrapper">
                        <input id="shadow-url-favplaces" type="text" name="gp_shadow_url_favplaces" value="<?php echo get_option('gp_shadow_url_favplaces'); ?>" />
                    </span>
                    <span class="help-text"><?php _e('This should start with http://','gpress'); ?></span>
                </li>
            </ul>
            <ul class="gpress-option-list">
                <li class="left-half">
                    <label for="marker-url-users"><?php _e('Marker Icon URL for Users:', 'gpress'); ?></label>
                </li>
                <li class="right-half">
                    <span class="input-wrapper">
                        <input id="marker-url-users" type="text" name="gp_marker_url_users" value="<?php echo get_option('gp_marker_url_users'); ?>" />
                    </span>
                    <span class="help-text"><?php _e('This should start with http://','gpress'); ?></span>
                </li>
            </ul>
            <ul class="gpress-option-list">
                <li class="left-half">
                    <label for="shadow-url-users"><?php _e('Shadow Icon URL for Users:', 'gpress'); ?></label>
                </li>
                <li class="right-half">
                    <span class="input-wrapper">
                        <input id="shadow-url-users" type="text" name="gp_shadow_url_users" value="<?php echo get_option('gp_shadow_url_users'); ?>" />
                    </span>
                    <span class="help-text"><?php _e('This should start with http://','gpress'); ?></span>
                </li>
            </ul>
            <?php include(GPRESS_DIR.'/gpress-options/submit.php'); ?>
        </div>
    </div>
</div>