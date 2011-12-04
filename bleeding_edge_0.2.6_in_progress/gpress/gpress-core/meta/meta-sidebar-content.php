<table width="100%" border="0" cellspacing="0" cellpadding="0" class="gpress_meta_sidebar_table">
  <tr>
    <td width="50%">
        <label><?php echo __('Map Type:', 'gpress'); ?></label>
        <input type="radio" name="<?php echo $gpress_map_id; ?>[map_type]" value="ROADMAP" <?php if(($meta['map_type'] == 'ROADMAP') || ($meta['map_type'] == '')) { ?> checked="checked" <?php } ?> /><span class="input_label"><?php _e('Roadmap','gpress'); ?></span><br />
        <input type="radio" name="<?php echo $gpress_map_id; ?>[map_type]" value="SATELLITE" <?php if($meta['map_type'] == 'SATELLITE') { ?> checked="checked" <?php } ?> /><span class="input_label"><?php _e('Satellite','gpress'); ?></span><br />
        <input type="radio" name="<?php echo $gpress_map_id; ?>[map_type]" value="HYBRID" <?php if($meta['map_type'] == 'HYBRID') { ?> checked="checked" <?php } ?> /><span class="input_label"><?php _e('Hybrid','gpress'); ?></span><br />
        <input type="radio" name="<?php echo $gpress_map_id; ?>[map_type]" value="TERRAIN" <?php if($meta['map_type'] == 'TERRAIN') { ?> checked="checked" <?php } ?> /><span class="input_label"><?php _e('Terrain','gpress'); ?></span><br />
    </td>
    <td width="10px" class="divider">&nbsp;</td>
    <td width="50%">
        <label><?php echo __('Zoom Level:', 'gpress'); ?></label>
        <input type="radio" name="<?php echo $gpress_map_id; ?>[map_zoom]" value="18" <?php if($meta['map_zoom'] == '18') { ?> checked="checked" <?php } ?> /><span class="input_label"><?php _e('Close-Up','gpress'); ?></span><br />
        <input type="radio" name="<?php echo $gpress_map_id; ?>[map_zoom]" value="13" <?php if(($meta['map_zoom'] == '13') || ($meta['map_zoom'] == '' )) { ?> checked="checked" <?php } ?> /><span class="input_label"><?php _e('Nearby','gpress'); ?></span><br />
        <input type="radio" name="<?php echo $gpress_map_id; ?>[map_zoom]" value="10" <?php if($meta['map_zoom'] == '10') { ?> checked="checked" <?php } ?> /><span class="input_label"><?php _e('Cities','gpress'); ?></span><br />
        <input type="radio" name="<?php echo $gpress_map_id; ?>[map_zoom]" value="5" <?php if($meta['map_zoom'] == '5') { ?> checked="checked" <?php } ?> /><span class="input_label"><?php _e('Countries','gpress'); ?></span><br />
    </td>
  </tr>
</table>

<div class="advanced_holder">
    <p><a href="#" id="advanced_settings"><?php echo __('Advanced Settings', 'gpress'); ?></a></p>
    <div id="gpress_advanced_hidden" style="display:none;">
        <span class="advanced_divider"></span>
        <label><?php echo __('Overwrite default height for this map:', 'gpress'); ?></label>
        <input type="text" name="<?php echo $gpress_map_id; ?>[map_height]" value="<?php if(!empty($meta['map_height'])) echo $meta['map_height']; ?>" />
        <p class="advanced_description"><?php echo __('Numbers Only', 'gpress'); ?>)</p>
        
        
        <span class="advanced_divider"></span>
        <label><?php _e('Custom icon URL for this map:','gpress'); ?></label>
        <input type="text" name="<?php echo $gpress_map_id; ?>[icon]" value="<?php if(!empty($meta['icon'])) echo $meta['icon']; ?>" />
        <p class="advanced_description"><?php _e('This <strong>MUST</strong> start with http://<br/>The image <strong>MUST</strong> also be 30px X 30px','gpress'); ?></p>
 
        <span class="advanced_divider"></span>
        <label><?php _e('Custom shadow URL for this map:','gpress'); ?></label>
        <input type="text" name="<?php echo $gpress_map_id; ?>[shadow]" value="<?php if(!empty($meta['shadow'])) echo $meta['shadow']; ?>" />        
        <p class="advanced_description"><?php _e('This <strong>MUST</strong> start with http://<br/>The image <strong>MUST</strong> also be 40px X 40px','gpress'); ?></p>
        
    </div>
</div>