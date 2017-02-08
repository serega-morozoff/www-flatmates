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
/**
*Search Rooms
**/
/**
* Any Rooms - Rooms count?
* Sort
*	Weekly Rent, Gender,  
**/

$rd_room_type = get_tids_by_machine_name("rd_room_type");
$rd_bathroom = get_tids_by_machine_name("rd_bathroom");
$rd_furnushing = get_tids_by_machine_name("rd_furnushing");
$preffered_accomodation = get_tids_by_machine_name("accommodation_type");
$features = get_tids_by_machine_name("features");
$seeker_type = get_tids_by_machine_name("seeker_about");
$professions = get_tids_by_machine_name("professions");
$field_stay_length = get_tids_by_machine_name("rd_stay_length");

$rd_room_type_vid = taxonomy_vocabulary_machine_name_load("rd_room_type");
$rd_bathroom_vid = taxonomy_vocabulary_machine_name_load("rd_bathroom");
$rd_furnushing_vid = taxonomy_vocabulary_machine_name_load("rd_furnushing");
$preffered_accomodation_vid = taxonomy_vocabulary_machine_name_load("accommodation_type");
$features_vid = taxonomy_vocabulary_machine_name_load("features");
$seeker_type_vid = taxonomy_vocabulary_machine_name_load("seeker_about");
$professions_vid = taxonomy_vocabulary_machine_name_load("professions");
$professions_vid = taxonomy_vocabulary_machine_name_load("professions");
$field_stay_length_vid = taxonomy_vocabulary_machine_name_load("rd_stay_length");

?>
<div class="pop-up" id="advanced">
	<div id="close"></div>
	<div id="search-rooms">
		<div class="filters">
			<div class="col-md-6 col-sm-6 col-xs-6" id="weekly-rent">
				<div class="row">
					<label class="control-label col-md-12 col-sm-12 col-xs-12"><?php print t("Weekly Rent") ?></label>
					<div class="col-md-6 col-sm-6 col-xs-6 right_7" class="min_rent">
						<select name="min-rent" id="min-rent">
							<option value="any"><?php print  t("Min Rent") ?></option>
							<option value="20">20</option>
							<option value="50">50</option>
							<option value="100">100</option>
							<option value="150">150</option>
							<option value="250">250</option>
						</select>
					</div>
					<div class="col-md-6 col-sm-6 col-xs-6 left_7" class="max_rent">
						<select name="max-rent" id="max-rent">
							<option value="any"><?php print  t("Max Rent") ?></option>
							<option value="50">50</option>
							<option value="150">150</option>
							<option value="200">200</option>
							<option value="250">250</option>
							<option value="350">350</option>
						</select>			
					</div>
				</div>
			</div>
			<div class="col-md-6 col-sm-6 col-xs-6">
				<label for="sort-priority" class="control-label"><?php print t("Sort Priority") ?></label>
				<select name="sort-priority" id="sort-priority">
					<option value="any"><?php print t("Select")?></option>
					<option value="Asc">Asc</option>
					<option value="Desc">Desc</option>
				</select>			
			</div>
		</div>
		<div class="advanced-filters">
			<div class="title">
				<h2><?php print t("Advanced Filters"); ?></h2>
			</div>
			<div class="content">
				<div class="col-md-6 col-sm-6 col-xs-6 selector" id="gender">
					<select name="gender">
						<option value="any"><?php print t("Any Gender") ?></option>
						<option value="Male"><?php print t("Male") ?></option>
						<option value="Female"><?php print t("Female") ?></option>
					</select>			
				</div>
				<div class="col-md-6 col-sm-6 col-xs-12 selector" id="room_type">
					<select name="room-type" id="vid_<?php echo $rd_room_type_vid->vid; ?>">
						<option value="any"><?php print t("Any Rooms") ?></option>
						<?php foreach($rd_room_type as $ele){?>
							<option value="<?php print $ele -> tid?>"><?php print $ele -> name?></option>
						<?php } ?>
					</select>			
				</div>		
				<div class="col-md-6 col-sm-6 col-xs-12 selector" id="bathroom">
					<select name="bathroom-type" id="vid_<?php echo $rd_bathroom_vid->vid; ?>">
						<option value="any"><?php print t("Any Bathroom Types") ?></option>
						<?php foreach($rd_bathroom as $ele){?>
							<option value="<?php print $ele -> tid?>"><?php print $ele -> name?></option>
						<?php } ?>
					</select>			
				</div>
				<div class="col-md-6 col-sm-6 col-xs-12 selector" id="furnishing">
					<select name="furnishing" id="vid_<?php echo $rd_furnushing_vid->vid; ?>">
						<option value="any"><?php print t("Any Furnishing") ?></option>
						<?php foreach($rd_furnushing as $ele){?>
							<option value="<?php print $ele -> tid?>"><?php print $ele -> name?></option>
						<?php } ?>
					</select>
				</div>		
				<div class="col-md-6 col-sm-6 col-xs-12 selector" id="preffered-stay-length">
					<select name="preffered-stay-length" id="vid_<?php echo $field_stay_length_vid->vid; ?>">
						<option value="any"><?php print t("Any Stay Lengths") ?></option>
						<?php foreach($field_stay_length as $ele){?>
							<option value="<?php print $ele -> tid?>"><?php print $ele -> name?></option>
						<?php } ?>
					</select>					
				</div>	
				<div class="col-md-6 col-sm-6 col-xs-12 selector" id="preferred-accomodation">
					<select name="preferred-accomodation" id="vid_<?php echo $preffered_accomodation_vid->vid; ?>">
						<option value="any"><?php print t("Any Accomodation") ?></option>
						<?php foreach($preffered_accomodation as $ele){?>
							<option value="<?php print $ele -> tid?>"><?php print $ele -> name?></option>
						<?php } ?>
					</select>	
				</div>		
				<div class="col-md-12 col-sm-12 col-xs-12 checkbox">
					<?php foreach($features as $key=>$ele){?>
						<label class="control-label col-md-6 col-sm-6 col-xs-12" for="feature-<?php print $key ?>">
							<input type="checkbox" id="feature-<?php print $key ?>" name="option<?php print $key ?>" value="<?php print $ele-> tid?>"><?php print $ele->name ?>
						</label>
					<?php } ?>
				</div>
			</div>
		</div>
	</div>
	<div class="form-action rooms">
		<button class="submit" value="<?php print t("Make search");?>" ><?php print t("Make search");?></button>
	</div>	
</div>