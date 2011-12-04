<div id="normal-sortables" class="meta-box-sortables ui-sortable">
    <div id="module-control" class="postbox">
        <h3 class="not-sortable"><span><?php _e('Geo-Tagged Posts','gpress'); ?></span></h3>
        <div class="inside">
            <span class="option-intro"><?php _e('<p>These options allow you to fine-tune the geo-tagged post functionality.</p>','gpress'); ?></span>
            <ul class="gpress-option-list">
                <li class="left-half">
                    <label><?php _e('Force Geo-Tagged Posting Capability:', 'gpress'); ?></label>
                </li>
                <li class="right-half">
                    <?php $force_geo = get_option('gp_force_geo','disabled'); ?>
                    <input id="force-geo-enabled" type="radio" name="gp_force_geo" autocomplete="off" value="enabled"<?php if($force_geo=='enabled') { echo ' checked="checked"'; }?> />
                    <label for="force-geo-enabled" class="label-radio"><?php _e('ENABLED', 'gpress'); ?></label>
                    <input id="force-geo-disabled" type="radio" name="gp_force_geo" autocomplete="off" value="disabled"<?php if($force_geo=='disabled') { echo ' checked="checked"'; }?> />
                    <label for="force-geo-disabled" class="label-radio"><?php _e('DISABLED', 'gpress'); ?></label>
                </li>
            </ul>
            <?php include(GPRESS_DIR.'/gpress-options/submit.php'); ?>
        </div>
    </div>
</div>