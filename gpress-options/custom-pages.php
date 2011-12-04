<div id="normal-sortables" class="meta-box-sortables ui-sortable">
    <div id="language-and-lingo" class="postbox credits">
        <h3 class="not-sortable"><span><?php _e('Custom Page Settings','gpress'); ?></span></h3>
        <div class="inside">
            <span class="option-intro"><?php _e('<p>This allows you to add certain gPress features to specific pages without needing to manually create page templates.</p>','gpress'); ?></span>
            <?php $gp_page_id_submit = get_option('gp_page_id_submit'); ?>
            <ul class="gpress-option-list">
                <li class="left-half">
                    <label for="front_id_submit"><?php _e('Front-End Place Submission Page ID:', 'gpress'); ?></label>
                </li>
                <li class="right-half">
                    <span class="input-wrapper">
                        <input id="front_id_submit" type="text" name="gp_page_id_submit" value="<?php echo $gp_page_id_submit; ?>" />
                    </span>
                    <span class="help-text"><?php _e('This should be a number representing the ID of the page you would like to use for front-end place submission.','gpress'); ?></span>
                </li>
            </ul>
            <?php include(GPRESS_DIR.'/gpress-options/submit.php'); ?>
        </div>
    </div>
</div>