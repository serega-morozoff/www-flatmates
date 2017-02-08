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
	<div id="search-flatmates">
		<div class="filters">
			<div class="col-md-6 col-sm-6 col-xs-6 selector gender">
				<label for="gender" class="control-label"><?php print t("Gender") ?></label>
				<select name="gender">
					<option value="any"><?php print t("Any Gender") ?></option>
					<option value="Male"><?php print t("Male") ?></option>
					<option value="Female"><?php print t("Female") ?></option>
				</select>			
			</div>
			<div class="col-md-6 col-sm-6 col-xs-6">
				<label for="sort-priority" class="control-label"><?php print t("Sort Priority") ?></label>
				<select name="sort-priority" class="sort-priority">
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
				<div class="col-md-6 col-sm-6 col-xs-6" class="age">
					<div class="row">
						<div class="col-md-6 col-sm-6 col-xs-6 min_age">
							<label class="control-label"><?php print t("Min Age") ?></label>
							<input type="number" class="min_age" min="16" >
						</div>
						<div class="col-md-6 col-sm-6 col-xs-6 max-age">
							<label class="control-label"><?php print t("Max Age") ?></label>
							<input type="number" class="max_age"  min="16" >
						</div>
					</div>
				</div>			
				<div class="col-md-6 col-sm-6 col-xs-6" class="weekly-rent">
					<div class="row">
						<div class="col-md-6 col-sm-6 col-xs-6 min_rent">
							<label class="control-label"><?php print t("Min Rent") ?></label>
							<select name="min-rent" id="min-rent">
								<option value="any"><?php print  t("Min Rent") ?></option>
								<option value="20">20</option>
								<option value="50">50</option>
								<option value="100">100</option>
								<option value="150">150</option>
								<option value="250">250</option>
							</select>
						</div>
						<div class="col-md-6 col-sm-6 col-xs-6 max_rent">
							<label class="control-label"><?php print t("Max Rent") ?></label>
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



				<div class="col-md-6 col-sm-6 col-xs-12 preferred-accomodation">
					<select name="preferred-accomodation" id="_vid_<?php echo $preffered_accomodation_vid->vid; ?>">
						<option value="any"><?php print t("Accomodation Types") ?></option>
						<?php foreach($preffered_accomodation as $ele){?>
							<option value="<?php print $ele -> tid?>"><?php print $ele -> name?></option>
						<?php } ?>
					</select>	
				</div>	
				<div class="col-md-6 col-sm-6 col-xs-6 stay">
					<div class="row">
						<div class="col-md-6 col-sm-6 col-xs-6 min_stay">
							<label class="control-label"><?php print t("Min Stay") ?></label>
							<input type="number" class="min_stay" min="16" >
						</div>
						<div class="col-md-6 col-sm-6 col-xs-6 max_stay">
							<label class="control-label"><?php print t("Max Stay") ?></label>
							<input type="number" class="max_stay"  min="16" >
						</div>
					</div>
				</div>	
				
				<div class="col-md-6 col-sm-6 col-xs-12 selector" id="people_type">
					<select name="people-type" id="vid_<?php echo $seeker_type_vid->vid; ?>">
						<option value="any"><?php print t("People Types") ?></option>
						<?php foreach($seeker_type as $ele){?>
							<option value="<?php print $ele -> tid?>"><?php print $ele -> name?></option>
						<?php } ?>
					</select>			
				</div>		
				<div class="col-md-6 col-sm-6 col-xs-12 selector" id="professions">
					<select name="professions-type" id="vid_<?php echo $professions_vid->vid; ?>">
						<option value="any"><?php print t("Professions") ?></option>
						<?php foreach($professions as $ele){?>
							<option value="<?php print $ele -> tid?>"><?php print $ele -> name?></option>
						<?php } ?>
					</select>			
				</div>
			</div>
		</div>
	</div>
	<div class="form-action flatmates">
		<button class="submit" value="<?php print t("Make search");?>" ><?php print t("Make search");?></button>
	</div>
</div>