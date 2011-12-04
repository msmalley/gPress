<div id="normal-sortables" class="meta-box-sortables ui-sortable">
    <div id="misc-settings" class="postbox">
        <h3 class="not-sortable"><span><?php _e('Miscellaneous Settings','gpress'); ?></span></h3>
        <div class="inside">
            <span class="option-intro"><?php _e('<p>Other MISC advanced settings can be found here, such as the removal of jQuery, which is needed for certain map functions but may already be included with your theme, in which case, you may need to remove it to avoid conflicts.</p>','gpress'); ?></span>
            <ul class="gpress-option-list">
                <li class="left-half">
                    <label><?php _e('Add jQuery to Theme:', 'gpress'); ?></label>
                </li>
                <li class="right-half">
                    <?php $misc_jquery = get_option('gp_misc_jquery','yes'); ?>
                    <input id="misc-jquery-yes" type="radio" name="gp_misc_jquery" autocomplete="off" value="yes"<?php if($misc_jquery=='yes') { echo ' checked="checked"'; }?> />
                    <label for="misc-jquery-yes" class="label-radio"><?php _e('YES', 'gpress'); ?></label>
                    <input id="misc-jquery-no" type="radio" name="gp_misc_jquery" autocomplete="off" value="no"<?php if($misc_jquery=='no') { echo ' checked="checked"'; }?> />
                    <label for="misc-jquery-no" class="label-radio"><?php _e('NO', 'gpress'); ?></label>
                </li>
            </ul>
            <?php include(GPRESS_DIR.'/gpress-options/submit.php'); ?>
        </div>
    </div>
</div>