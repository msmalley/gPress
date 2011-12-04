<table width="100%" border="0" cellspacing="0" cellpadding="0" class="gpress_meta_sidebar_table">
  <tr>
    <td width="50%">
        <label><?php echo __('Map Type:', 'gpress'); ?></label>
        <input type="radio" name="<?php echo $gpress_map_id; ?>[type]" value="ROADMAP" <?php if(($meta['type'] == 'ROADMAP') || ($meta['type'] == '')) { ?> checked="checked" <?php } ?> /><span class="input_label">Roadmap</span><br />
        <input type="radio" name="<?php echo $gpress_map_id; ?>[type]" value="SATELLITE" <?php if($meta['type'] == 'SATELLITE') { ?> checked="checked" <?php } ?> /><span class="input_label">Satellite</span><br />
        <input type="radio" name="<?php echo $gpress_map_id; ?>[type]" value="HYBRID" <?php if($meta['type'] == 'HYBRID') { ?> checked="checked" <?php } ?> /><span class="input_label">Hybrid</span><br />
        <input type="radio" name="<?php echo $gpress_map_id; ?>[type]" value="TERRAIN" <?php if($meta['type'] == 'TERRAIN') { ?> checked="checked" <?php } ?> /><span class="input_label">Terrain</span><br />
    </td>
    <td width="10px" class="divider">&nbsp;</td>
    <td width="50%">
        <label><?php echo __('Zoom Level:', 'gpress'); ?></label>
        <input type="radio" name="<?php echo $gpress_map_id; ?>[zoom]" value="18" <?php if($meta['zoom'] == '18') { ?> checked="checked" <?php } ?> /><span class="input_label">Close-Up</span><br />
        <input type="radio" name="<?php echo $gpress_map_id; ?>[zoom]" value="13" <?php if(($meta['zoom'] == '13') || ($meta['zoom'] == '' )) { ?> checked="checked" <?php } ?> /><span class="input_label">Nearby</span><br />
        <input type="radio" name="<?php echo $gpress_map_id; ?>[zoom]" value="10" <?php if($meta['zoom'] == '10') { ?> checked="checked" <?php } ?> /><span class="input_label">Cities</span><br />
        <input type="radio" name="<?php echo $gpress_map_id; ?>[zoom]" value="5" <?php if($meta['zoom'] == '5') { ?> checked="checked" <?php } ?> /><span class="input_label">Countries</span><br />
    </td>
  </tr>
</table>

<div class="advanced_holder">
    <p><a href="#" id="advanced_settings"><?php echo __('Advanced Settings', 'gpress'); ?></a></p>
    <div id="gpress_advanced_hidden" style="display:none;">
        <span class="advanced_divider"></span>
        <label><?php echo __('Overwrite default height for this map:', 'gpress'); ?></label>
        <input type="text" name="<?php echo $gpress_map_id; ?>[height]" value="<?php if(!empty($meta['height'])) echo $meta['height']; ?>" />
        <p class="advanced_description"><?php echo __('Numbers only (defaults to 250)', 'gpress'); ?>)</p>
        
        
        <span class="advanced_divider"></span>
        <label>Custom icon URL for this map:</label>
        <input type="text" name="<?php echo $gpress_map_id; ?>[icon_url]" value="<?php if(!empty($meta['icon_url'])) echo $meta['icon_url']; ?>" />
        <p class="advanced_description">This <strong>MUST</strong> start with http://<br/>The image <strong>MUST</strong> also be 30px X 30px</p>
 
        <span class="advanced_divider"></span>
        <label>Custom shadow URL for this map:</label>
        <input type="text" name="<?php echo $gpress_map_id; ?>[shadow_url]" value="<?php if(!empty($meta['shadow_url'])) echo $meta['shadow_url']; ?>" />        
        <p class="advanced_description">This <strong>MUST</strong> start with http://<br/>The image <strong>MUST</strong> also be 40px X 40px</p>
        
    </div>
</div>