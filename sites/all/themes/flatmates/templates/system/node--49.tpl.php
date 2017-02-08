<?php
function get_tree($vid){
	$tree = taxonomy_get_tree($vid);
	return $tree;
}
function get_tids_by_machine_name($machine_name){
	$vid = taxonomy_vocabulary_machine_name_load($machine_name);
	$tree = get_tree($vid->vid);
	return $tree;
}
$represent_who = get_tids_by_machine_name("represent_who");
$preffered_accomodation = get_tids_by_machine_name("accommodation_type");
$stay_length = get_tids_by_machine_name("rd_stay_length");

$represent_who_vid = taxonomy_vocabulary_machine_name_load("represent_who");
$preffered_accomodation_vid = taxonomy_vocabulary_machine_name_load("accommodation_type");
$stay_length_vid = taxonomy_vocabulary_machine_name_load("rd_stay_length");

global $user;
?>
<?php if($user->uid != 0){?>
	<div class="container">
		<div id="general-details">
			<h2><?php print t("General Details")?></h2>
			<div class="wrap">
				<div class="col-md-4 col-sm-4 col-xs-12 represent-who">
					<label for="represent-who" class="control-label"><?php print t("Represent Who"); ?></label>
					<select name="represent-who" id="vid_<?php echo $represent_who_vid->vid; ?>">
						<?php foreach($represent_who as $ele){?>
							<option value="<?php print $ele -> tid?>"><?php print $ele -> name?></option>
						<?php } ?>
					</select>
				</div>
				<div class="col-md-4 col-sm-4 col-xs-12 tenant-name">
					<div class="field-required error"><?php print t("You forgot about the required field!")?></div>
					<label for="tenant-name" class="control-label required"><?php print t("Name"); ?><span class="form-required"> *</span></label>
					<input type="text" id="tenant_name" name="tenant-name" />
				</div>
				<div class="col-md-4 col-sm-4 col-xs-12 tenant-gender">
					<div class="row">
						<div class="col-md-8 col-sm-8 col-xs-6 gender">
							<label class="control-label"><?php print t("Gender") ?></label>
							<input type="radio" id="radio01" name="radio" />
							<label for="radio01"><span><?php print ("Male")?></span></label>
							<input type="radio" id="radio02" name="radio" />
							<label for="radio02"><span><?php print ("Female")?></span></label>
						</div>
						<div class="col-md-4 col-sm-4 col-xs-6 age">
							<label for="tenant-age" class="control-label"><?php print t("Age"); ?></label>
							<input id="tenant-age" type="text" name="tenant-age" />
						</div>
					</div>
				</div>
				<div id="create-preferred-accomodation" class="col-md-4 col-sm-4 col-xs-12">
					<label for="tenant-age" class="control-label"><?php print t("My preferred accommodation type"); ?></label>
					<div class="selected"></div>
					<div class="select">				
						<select name="preferred-accomodation" id="vid_<?php echo $preffered_accomodation_vid->vid; ?>">
							<?php foreach($preffered_accomodation as $ele){?>
								<option value="<?php print $ele -> tid?>"><?php print $ele -> name?></option>
							<?php } ?>
						</select>	
						<div class="add-more-wrap">
							<span class="add-more"><?php print t("Add more");?></span>
						</div>
					</div>
				</div>
				<div class="col-md-4 col-sm-4 col-xs-12" id="with-g-point">
					<div class="field-required error"><?php print t("You forgot about the required field!")?></div>
					<label for="represent-who" class="control-label required"><?php print t("Where would you like to live?"); ?><span class="form-required"> *</span></label>
					<div class="selected"></div>
					<input  id="g_autocomplete" type="text" class="accomodation" />
					<div class="add-more-wrap">
						<span class="add-more"><?php print t("Add one more place");?></span>
					</div>
				</div>	
				<div id="max-budget" class="col-md-4 col-sm-4 col-xs-12">
					<div class="field-required error"><?php print t("You forgot about the required field!")?></div>
					<label for="weekly-rent" class="control-label required"><?php print t("Max budget per week, $"); ?><span class="form-required"> *</span></label>
					<input type="number" step="1" min=0 max=15000 id="weekly-rent" name="weekly-rent" />
				</div>
				<div id="move-date" class="col-md-4 col-sm-4 col-xs-12">
					<?php
						$block = module_invoke('webform', 'block_view', 'client-block-50');
						print render($block['content']);
					?>
				</div>
				<div id="preffered-length" class="col-md-4 col-sm-4 col-xs-12">
					<div class="row">
						<label for="preffered-length-number" class="control-label"><?php print t("Preferred Stay Lenght"); ?></label>
						<div class="col-md-8 col-sm-8 col-xs-8 fl_none">
							<input name = "preffered-length-number" type="number" id="number" step="1" min=0 max=180 maxlength=150 /></div>
						<div class="col-md-4 col-sm-4 col-xs-4 fl_none">
							<select name="preffered-length-text" id="vid_<?php echo $stay_length_vid->vid; ?>">
								<?php foreach($stay_length as $ele){?>
									<option value="<?php print $ele -> tid?>"><?php print $ele -> name?></option>
								<?php } ?>
							</select>					
						</div>
					</div>
				</div>
			</div>
		</div>
		
		<div id="node-form">
			<h2><?php print t("Upload Photos")?></h2>
			<?php
					$block = module_invoke('formblock', 'block_view', 'accomodation');
					print render($block['content']);		
			?>
		</div>
	</div>
<?php }else{ ?>
<?php
	header("Location: /user");
	/*$link = l("Authorize", "user");
	$register = t("Please "); $register.=$link; $register.= t(" to find Your accomodation");
	$output = "<div id='please-authorize' class='center'>$register</div>";*/
?>
<?php //print $output;?>
<?php } ?>