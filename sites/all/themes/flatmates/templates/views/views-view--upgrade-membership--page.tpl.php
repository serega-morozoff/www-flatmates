<?php

/**
 * @file
 * Main view template.
 *
 * Variables available:
 * - $classes_array: An array of classes determined in
 *   template_preprocess_views_view(). Default classes are:
 *     .view
 *     .view-[css_name]
 *     .view-id-[view_name]
 *     .view-display-id-[display_name]
 *     .view-dom-id-[dom_id]
 * - $classes: A string version of $classes_array for use in the class attribute
 * - $css_name: A css-safe version of the view name.
 * - $css_class: The user-specified classes names, if any
 * - $header: The view header
 * - $footer: The view footer
 * - $rows: The results of the view query, if any
 * - $empty: The empty text to display if the view is empty
 * - $pager: The pager next/prev links to display, if any
 * - $exposed: Exposed widget form/info to display
 * - $feed_icon: Feed icon to display, if any
 * - $more: A link to view more, if any
 *
 * @ingroup views_templates
 */
?>
<div class="<?php print $classes; ?>">
  <?php print render($title_prefix); ?>
  <?php if ($title): ?>
    <?php print $title; ?>
  <?php endif; ?>
  <?php print render($title_suffix); ?>
  <?php if ($header): ?>
    <div class="view-header">
      <?php print $header; ?>
    </div>
  <?php endif; ?>

  <?php if ($exposed): ?>
    <div class="view-filters">
      <?php print $exposed; ?>
    </div>
  <?php endif; ?>

  <?php if ($attachment_before): ?>
    <div class="attachment attachment-before">
      <?php print $attachment_before; ?>
    </div>
  <?php endif; ?>

  <?php if ($rows): ?>
    <div class="view-content">
			<div class="labels col-md-3">
			<table>
				<tr>
					<td>
						
						<div class="views-field views-field-field-title"><div class="field-content"><?php print t("Features list");?></div></div>  
						<div class="views-field views-field-field-create-lists"><div class="field-content"><?php print t("Create Lists");?></div></div>  
						<div class="views-field views-field-field-browser-all-lists"><div class="field-content"><?php print t("Browser all Lists");?></div></div>  
						<div class="views-field views-field-field-send-messages-to-paid-memb"><div class="field-content"><?php print t("Send messages to paid member");?></div></div>  
						<div class="views-field views-field-field-receive-messages-from-paid"><div class="field-content"><?php print t("Receive messages from paid member");?></div></div>  
						<div class="views-field views-field-field-access-mobile-number-of-pa"><div class="field-content"><?php print t("Access mobile number of paid member");?></div></div>  
						<div class="views-field views-field-field-anyone-can-access-social-m"><div class="field-content"><?php print t("Anyone can access social media content of this member");?></div></div>  
						<div class="views-field views-field-field-price-display"><div class="field-content"><?php print t("Price");?></div></div>  
						<div class="views-field views-field-field-benefits"><div class="field-content"><?php print t("Benefits");?></div></div>  
						<div class="views-field views-field-field-upload-photos-per-list"><div class="field-content"><?php print t("Upload photos per list");?></div></div>  
					</td>
				</tr>
			</table>
			</div>
				<?php print $rows; ?>
    </div>
  <?php elseif ($empty): ?>
    <div class="view-empty">
      <?php print $empty; ?>
    </div>
  <?php endif; ?>

  <?php if ($pager): ?>
    <?php print $pager; ?>
  <?php endif; ?>

  <?php if ($attachment_after): ?>
    <div class="attachment attachment-after">
      <?php print $attachment_after; ?>
    </div>
  <?php endif; ?>

  <?php if ($more): ?>
    <?php print $more; ?>
  <?php endif; ?>

  <?php if ($footer): ?>
    <div class="view-footer">
      <?php print $footer; ?>
    </div>
  <?php endif; ?>

  <?php if ($feed_icon): ?>
    <div class="feed-icon">
      <?php print $feed_icon; ?>
    </div>
  <?php endif; ?>

</div><?php /* class view */ ?>