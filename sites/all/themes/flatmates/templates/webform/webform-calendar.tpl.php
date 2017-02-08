<?php

/**
 * @file
 * Theme the button for the date component date popup.
 */
?>
<!-- id must match the label's for -->
<?php if(arg(0) == "node" && arg(1) == "49"){?>
	<?php //$idKey = str_replace('_', '-', $component['form_key']); ?>
	<input type="text" id="edit-submitted-move-date" class="form-text <?php print implode(' ', $calendar_classes); ?>" alt="<?php print t('Open popup calendar'); ?>" title="<?php print t('Open popup calendar'); ?>" />
<?php } ?>
