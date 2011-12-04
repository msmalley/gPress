<div id="normal-sortables" class="meta-box-sortables ui-sortable">
    <div id="credit-settings" class="postbox">
        <h3 class="not-sortable"><span><?php _e('Credit Settings','gpress'); ?></span></h3>
        <div class="inside">
            <span class="option-intro"><?php _e('<p>These options allow you to remove all references and credits from gPress.</p><p>Although it is hugely appreciated if you provide some form of shout-back or recognition for gPress, we understand that some situations require re-branding, and do not want you to edit core files, so please use these options instead:</p>','gpress'); ?></span>
            <ul class="gpress-option-list">
                <li class="left-half">
                    <label><?php _e('Show Credits', 'gpress'); ?></label>
                </li>
                <li class="right-half">
                    <?php $show_credits = get_option('gp_show_credits','enabled'); ?>
                    <input id="show-credits-enabled" type="radio" name="gp_show_credits" autocomplete="off" value="enabled"<?php if($show_credits=='enabled') { echo ' checked="checked"'; }?> />
                    <label for="show-credits-enabled" class="label-radio"><?php _e('ENABLED', 'gpress'); ?></label>
                    <input id="show-credits-disabled" type="radio" name="gp_show_credits" autocomplete="off" value="disabled"<?php if($show_credits=='disabled') { echo ' checked="checked"'; }?> />
                    <label for="show-credits-disabled" class="label-radio"><?php _e('DISABLED', 'gpress'); ?></label>
                </li>
            </ul>
            <?php include(GPRESS_DIR.'/gpress-options/submit.php'); ?>
        </div>
    </div>
</div>