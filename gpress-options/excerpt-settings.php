<div id="normal-sortables" class="meta-box-sortables ui-sortable">
    <div id="excerpt-settings" class="postbox">
        <h3 class="not-sortable"><span><?php _e('Excerpt Settings','gpress'); ?></span></h3>
        <div class="inside">
            <span class="option-intro"><?php _e('<p>These options allow you to control the way that the content and excerpts are handled on the homepage of your theme. Some themes strip-out HTML tags with the excerpts, or use full-content instead - so these options provide fine-grain control over these components.</p>','gpress'); ?></span>
            <ul class="gpress-option-list">
                <li class="left-half">
                    <label><?php _e('Remove Maps from Homepage Content Loop:', 'gpress'); ?></label>
                </li>
                <li class="right-half">
                    <?php $remove_maps_content = get_option('gp_remove_maps_content','no'); ?>
                    <input id="remove-maps-content-no" type="radio" name="gp_remove_maps_content" autocomplete="off" value="no"<?php if($remove_maps_content=='no') { echo ' checked="checked"'; }?> />
                    <label for="remove-maps-content-no" class="label-radio"><?php _e('NO', 'gpress'); ?></label>
                    <input id="remove-maps-content-yes" type="radio" name="gp_remove_maps_content" autocomplete="off" value="yes"<?php if($remove_maps_content=='yes') { echo ' checked="checked"'; }?> />
                    <label for="remove-maps-content-yes" class="label-radio"><?php _e('YES', 'gpress'); ?></label>
                </li>
            </ul>
            <ul class="gpress-option-list">
                <li class="left-half">
                    <label><?php _e('Remove Maps from Excerpts:', 'gpress'); ?></label>
                </li>
                <li class="right-half">
                    <?php $remove_maps_excerpt = get_option('gp_remove_maps_excerpt','no'); ?>
                    <input id="remove-maps-excerpt-no" type="radio" name="gp_remove_maps_excerpt" autocomplete="off" value="no"<?php if($remove_maps_excerpt=='no') { echo ' checked="checked"'; }?> />
                    <label for="remove-maps-excerpt-no" class="label-radio"><?php _e('NO', 'gpress'); ?></label>
                    <input id="remove-maps-excerpt-yes" type="radio" name="gp_remove_maps_excerpt" autocomplete="off" value="yes"<?php if($remove_maps_excerpt=='yes') { echo ' checked="checked"'; }?> />
                    <label for="remove-maps-excerpt-yes" class="label-radio"><?php _e('YES', 'gpress'); ?></label>
                </li>
            </ul>
            <?php include(GPRESS_DIR.'/gpress-options/submit.php'); ?>
        </div>
    </div>
</div>