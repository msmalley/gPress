<div id="normal-sortables" class="meta-box-sortables ui-sortable">
    <div id="loop-settings" class="postbox">
        <h3 class="not-sortable"><span><?php _e('Loop Settings','gpress'); ?></span></h3>
        <div class="inside">
            <span class="option-intro"><?php _e('<p>These allow you to add or remove the places shown on the homepage by controlling the post types querried. If loops start acting strange, please switch to "POSTS ONLY", which removes the the filters.</p><p>Please note that some plugins are unable to handle post_type arrays, and only work when querrying one type, such as either posts or places.</p>','gpress'); ?></span>
            <ul class="gpress-option-list">
                <li class="left-half">
                    <label><?php _e('Homepage Loop:', 'gpress'); ?></label>
                </li>
                <li class="right-half">
                    <?php $homepage_loop = get_option('gp_homepage_loop','BOTH'); ?>
                    <input id="home-loop-both" type="radio" name="gp_homepage_loop" autocomplete="off" value="BOTH"<?php if($homepage_loop=='BOTH') { echo ' checked="checked"'; }?> />
                    <label for="home-loop-both" class="label-radio"><?php _e('BOTH POSTS AND PLACES', 'gpress'); ?></label>
                    <input id="home-loop-posts" type="radio" name="gp_homepage_loop" autocomplete="off" value="POSTS"<?php if($homepage_loop=='POSTS') { echo ' checked="checked"'; }?> />
                    <label for="home-loop-posts" class="label-radio"><?php _e('POSTS ONLY', 'gpress'); ?></label>
                    <input id="home-loop-places" type="radio" name="gp_homepage_loop" autocomplete="off" value="PLACES"<?php if($homepage_loop=='PLACES') { echo ' checked="checked"'; }?> />
                    <label for="home-loop-places" class="label-radio"><?php _e('PLACES ONLY', 'gpress'); ?></label>
                </li>
            </ul>
            <?php include(GPRESS_DIR.'/gpress-options/submit.php'); ?>
        </div>
    </div>
</div>