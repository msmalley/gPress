<div id="normal-sortables" class="meta-box-sortables ui-sortable">
    <div id="module-control" class="postbox">
        <h3 class="not-sortable"><span><?php _e('Module Control','gpress'); ?></span></h3>
        <div class="inside">
            <span class="option-intro"><?php _e('<p>These options allow you to control the BASE gPress modules used by WordPress.</p>','gpress'); ?></span>
            <ul class="gpress-option-list">
                <li class="left-half">
                    <label><?php _e('Geo-Tagged Posts:', 'gpress'); ?></label>
                </li>
                <li class="right-half">
                    <?php $module_posts = get_option('gp_module_posts','enabled'); ?>
                    <input id="module-posts-enabled" type="radio" name="gp_module_posts" autocomplete="off" value="enabled"<?php if($module_posts=='enabled') { echo ' checked="checked"'; }?> />
                    <label for="module-posts-enabled" class="label-radio"><?php _e('ENABLED', 'gpress'); ?></label>
                    <input id="module-posts-disabled" type="radio" name="gp_module_posts" autocomplete="off" value="disabled"<?php if($module_posts=='disabled') { echo ' checked="checked"'; }?> />
                    <label for="module-posts-disabled" class="label-radio"><?php _e('DISABLED', 'gpress'); ?></label>
                </li>
            </ul>
            <ul class="gpress-option-list">
                <li class="left-half">
                    <label><?php _e('geoRSS:', 'gpress'); ?></label>
                </li>
                <li class="right-half">
                    <?php $module_rss = get_option('gp_module_rss','enabled'); ?>
                    <input id="module-rss-enabled" type="radio" name="gp_module_rss" autocomplete="off" value="enabled"<?php if($module_rss=='enabled') { echo ' checked="checked"'; }?> />
                    <label for="module-rss-enabled" class="label-radio"><?php _e('ENABLED', 'gpress'); ?></label>
                    <input id="module-rss-disabled" type="radio" name="gp_module_rss" autocomplete="off" value="disabled"<?php if($module_rss=='disabled') { echo ' checked="checked"'; }?> />
                    <label for="module-rss-disabled" class="label-radio"><?php _e('DISABLED', 'gpress'); ?></label>
                </li>
            </ul>
            <ul class="gpress-option-list">
                <li class="left-half">
                    <label><?php _e('Places:', 'gpress'); ?></label>
                </li>
                <li class="right-half">
                    <?php $module_places = get_option('gp_module_places','enabled'); ?>
                    <input id="module-places-enabled" type="radio" name="gp_module_places" autocomplete="off" value="enabled"<?php if($module_places=='enabled') { echo ' checked="checked"'; }?> />
                    <label for="module-places-enabled" class="label-radio"><?php _e('ENABLED', 'gpress'); ?></label>
                    <input id="module-places-disabled" type="radio" name="gp_module_places" autocomplete="off" value="disabled"<?php if($module_places=='disabled') { echo ' checked="checked"'; }?> />
                    <label for="module-places-disabled" class="label-radio"><?php _e('DISABLED', 'gpress'); ?></label>
                </li>
            </ul>
            <?php include(GPRESS_DIR.'/gpress-options/submit.php'); ?>
        </div>
    </div>
</div>