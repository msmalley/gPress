<div id="normal-sortables" class="meta-box-sortables ui-sortable">
    <div id="language-and-lingo" class="postbox credits">
        <h3 class="not-sortable"><span><?php _e('Language &amp; Lingo','gpress'); ?></span></h3>
        <div class="inside">
            <span class="option-intro"><?php _e('<p>If you have defined WPLANG and the "gpress-lang" folder contains the relevant .mo file, gPress will automatically switch languages. However, if you want to switch to a custom .mo file, you should add the file to "wp-content/gpress" as this will keep it safe and prevent it from being over-written with automatic upgrades, but please note that after switching language and clicking "SAVE CHANGES", you will need to refresh the page once again to ensure everything gets flushed and refreshed properly.</p>','gpress'); ?></span>
            <ul class="gpress-option-list">
                <li class="left-half">
                    <label for="custom-mo"><?php _e('Name of Custom MO File:', 'gpress'); ?></label>
                </li>
                <li class="right-half">
                    <span class="input-wrapper">
                        <input id="custom-mo" type="text" name="gp_custom_mo" value="<?php echo get_option('gp_custom_mo'); ?>" />
                    </span>
                    <span class="help-text"><?php _e('Relative to "/wp-content/gpress/"','gpress'); ?></span>
                </li>
            </ul>
            <?php include(GPRESS_DIR.'/gpress-options/submit.php'); ?>
        </div>
    </div>
</div>