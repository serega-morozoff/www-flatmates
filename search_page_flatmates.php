<?php
/** bootstrap Drupal **/
$path = $_SERVER['DOCUMENT_ROOT'];
chdir($path);
define('DRUPAL_ROOT', getcwd()); //the most important line
require_once './includes/bootstrap.inc';
drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);
/**
* Get Users
**/	
$last_user_id = $_REQUEST['lastuid'];
$search_index = $_REQUEST['searchIndex'];
$gender = strtolower($_REQUEST['gender']);
if($_REQUEST['minAge'] != ""){
	$min_age = intval($_REQUEST['minAge']);
}
if($_REQUEST['maxAge'] != ""){
	$max_age = intval($_REQUEST['maxAge']);
}
if($_REQUEST['minRent'] != "any"){
	$min_rent = intval($_REQUEST['minRent']);
}
if($_REQUEST['maxRent'] != "any"){
	$max_rent = intval($_REQUEST['maxRent']);
}
if($_REQUEST['minStay'] != ""){
	$min_stay = intval($_REQUEST['minStay']);
}
if($_REQUEST['maxStay'] != ""){
	$max_stay = intval($_REQUEST['maxStay']);
}
$watchdog = strval($_GET["offset"]);
$acomodationTypes = $_REQUEST['acomodationTypes'];
$seeker_about = $_REQUEST['peopleTypes'];
$search_index_array = explode("_", $search_index);

					$query = new EntityFieldQuery();
					$query->entityCondition('entity_type', 'node');
					$query->entityCondition('bundle', 'accomodation');
					$query->propertyCondition('status', NODE_PUBLISHED);
					if(isset($_REQUEST['searchIndex']) && $_REQUEST['searchIndex']!="" && $_REQUEST['searchIndex']!="_flatmates")$query->fieldCondition('field_preffered_locations_google', 'value', $search_index_array, 'IN');
					if(isset($_REQUEST['gender']) && $gender!="")$query->fieldCondition('field_gender', 'value', $gender, '=');
					if(isset($_REQUEST['minAge']) && $_REQUEST['minAge'] != "")$query->fieldCondition('field_age', 'value', $min_age, '>=');
					if(isset($_REQUEST['maxAge']) && $_REQUEST['maxAge'] != "")$query->fieldCondition('field_age', 'value', $max_age, '<=');
					if(isset($_REQUEST['minRent']) && $_REQUEST['minRent'] != "any")$query->fieldCondition('field_rent_budget', 'value', $min_rent, '>=');
					if(isset($_REQUEST['maxRent']) && $_REQUEST['maxRent'] != "any")$query->fieldCondition('field_rent_budget', 'value', $max_rent, '<=');	
					if(isset($_REQUEST['minStay']) && $_REQUEST['minStay'] != "" && $_REQUEST['minStay'] != 0)$query->fieldCondition('field_stay_length_number', 'value', $min_stay, '>=');
					if(isset($_REQUEST['maxStay']) && $_REQUEST['maxStay'] != "" && $_REQUEST['maxStay'] != 0)$query->fieldCondition('field_stay_length_number', 'value', $max_stay, '<=');
					if(isset($_REQUEST['acomodationTypes']) && $acomodationTypes!="any")$query->fieldCondition('field_accomodation', 'tid', $acomodationTypes, 'CONTAINS');
					if(isset($_REQUEST['peopleTypes']) && $seeker_about!="any")$query->fieldCondition('field_seeker_about', 'tid', $seeker_about, 'CONTAINS');
					$result = $query->execute();					
					$breakpoint = count($result['node']);
					$query = new EntityFieldQuery();
					$query->entityCondition('entity_type', 'node');
					$query->entityCondition('bundle', 'accomodation');
					$query->propertyCondition('status', NODE_PUBLISHED);
					if(isset($_REQUEST['searchIndex']) && $_REQUEST['searchIndex']!="" && $_REQUEST['searchIndex']!="_flatmates")$query->fieldCondition('field_preffered_locations_google', 'value', $search_index_array, 'IN');
					if(isset($_REQUEST['gender']) && $gender!="")$query->fieldCondition('field_gender', 'value', $gender, '=');
					if(isset($_REQUEST['minAge']) && $_REQUEST['minAge'] != "")$query->fieldCondition('field_age', 'value', $min_age, '>=');
					if(isset($_REQUEST['maxAge']) && $_REQUEST['maxAge'] != "")$query->fieldCondition('field_age', 'value', $max_age, '<=');
					if(isset($_REQUEST['minRent']) && $_REQUEST['minRent'] != "any")$query->fieldCondition('field_rent_budget', 'value', $min_rent, '>=');
					if(isset($_REQUEST['maxRent']) && $_REQUEST['maxRent'] != "any")$query->fieldCondition('field_rent_budget', 'value', $max_rent, '<=');	
					if(isset($_REQUEST['minStay']) && $_REQUEST['minStay'] != "" && $_REQUEST['minStay'] != 0)$query->fieldCondition('field_stay_length_number', 'value', $min_stay, '>=');
					if(isset($_REQUEST['maxStay']) && $_REQUEST['maxStay'] != "" && $_REQUEST['maxStay'] != 0)$query->fieldCondition('field_stay_length_number', 'value', $max_stay, '<=');
					if(isset($_REQUEST['acomodationTypes']) && $acomodationTypes!="any")$query->fieldCondition('field_accomodation', 'tid', $acomodationTypes, 'CONTAINS');
					if(isset($_REQUEST['peopleTypes']) && $seeker_about!="any")$query->fieldCondition('field_seeker_about', 'tid', $seeker_about, 'CONTAINS');
					$query->range($_GET["offset"], 16);
					$result = $query->execute();
					$nodes = node_load_multiple(array_keys($result['node']));		
					foreach($nodes as $node){
						$watchdog ++;
						if(count($node) > 0){
							$user_ = user_load($node->uid);
							$location = "Looking in: ";
							$location .= $node -> field_preffered_locations_google['und'][0]['value'];
							$location_tooltip = "";
							foreach($node -> field_preffered_locations_google['und'] as $prefer){
								$location_tooltip .= $prefer['value'].", ";
							}
							$location_tooltip = substr($location_tooltip, 0, strlen($location_tooltip) - 2);							
							$seaker = "";
							foreach($node -> field_seeker_about['und'] as $term){
								$seaker .= get_taxonomy_name($term['tid']);
								$seaker .= ", ";
							}
							foreach($user_ -> roles as $key=>$role){
								if($key > 2){
									$status = $role;
									$status_class = strtolower($status);
									$tid = taxonomy_get_term_by_name($role);
									$tooltip = "Administrator";
									foreach($tid as $t){
										$tooltip = $t->description;
									}
								}
							}
							if(isset($node -> field_seeker_type['und'][0]['tid'])){
								$seeker_type = get_taxonomy_name($node -> field_seeker_type['und'][0]['tid']);
								$seeker_type_img = get_taxonomy_img($node -> field_seeker_type['und'][0]['tid']);
							}
							$etc_output = "";
							if(isset($node -> field_about_me_tax)){
								$trig = 0;
								$tooltip_etc = "";
								foreach($node -> field_about_me_tax['und'] as $key => $etc_tid){
									if(($etc_tid['tid'] == '84' || $etc_tid['tid'] == '88' || $etc_tid['tid'] == '92') && $trig == 0){
										$trig = 1;
										$etc_name = get_taxonomy_name($etc_tid['tid']);
										$etc_img = get_taxonomy_img($etc_tid['tid']);
									}else{
										$tooltip_etc .= get_taxonomy_name($etc_tid['tid']). ", ";
									}
								}
								$etc_output = "<div class='about'><img src='".$etc_img."' alt='".$etc_name."'/><span data-toggle='tooltip' title='$tooltip_etc' >" . $etc_name . "</span></div>";
							}											
							if(count($node->field_image) > 0){
								$link = l(theme('image_style', array('path' => $node->field_image['und'][0]['uri'], 'style_name' => 'user_list')), 'node/' . $node->nid, array('html' => TRUE));
							}else{
								$link = l(theme('image_style', array('path' => $node->field_choose_photo_from_my_image['und'][0]['uri'], 'style_name' => 'user_list')), 'node/' . $node->nid, array('html' => TRUE));								
							}
							$age = $node -> field_age['und'][0]['value'];
							$output .= "<div class='rows users col-md-3 col-sm-6 col-xs-12'>";
							$output.="<div class='user_image'>".$link;
							if($node -> field_rent_budget['und'][0]['value']){$output.="<div class='user_cost'>$" . $node -> field_rent_budget['und'][0]['value'] . "</div>";}
							$output .="</div>";
							$name = $node -> title;
							$link = l($name, 'node/' . $node->nid);
							$output.="<div class='user_name'>" . $link . "</div>";
							if($node -> field_gender['und'][0]['value']){
								$gender = ucfirst($node -> field_gender['und'][0]['value']);
								$output.="<div class='gender-birth'><span class='user_gender " . $node -> field_gender['und'][0]['value'] . "'>" . $gender . "</span>";
								$output.="<span class='user_birth'>, " . $age . "yrs </span></div>";
							}
							if($location){$output.="<div class='user_location'><span data-toggle='tooltip' title='$location_tooltip' >" . $location . "</span></div>";}
							if($seaker){$output.="<div class='user_about'>" . $seaker . "</div>";}
							if($status){$output.="<div class='user_role inline_block ".$status_class."'><span data-toggle='tooltip' title='$tooltip' >" . $status . "</span></div>";}
							if($seeker_type){$output.="<div class='user_type inline_block'><img src='" . $seeker_type_img . "' alt='' /><span>" . $seeker_type . "</span></div>";}
							if($etc_output != ""){$output.="<div class='user_job'>".$etc_output."</span></div>";}
							if($user->uid == 0){
								$link = l("Authorize", "user");
								$register = t("Please "); $register.=$link; $register.= t(" to send and recieve private message");
								$output .= "<div id='please-authorize' class='front'>$register</div>";
							}else{
								$output.="<div class='send_message'><a href='/messages/new?user=" . $user_ -> name . "'>send a message</a></div>";
							}
							$output .= "</div>";
							$last_user_id = $single->uid;
						}
					}
				//}
				if($breakpoint <= $watchdog){
					if($breakpoint != 0){
						$output .= "</div><div id='watchdog'></div>";
					}//else{
						//$t = t("<h2 class='no-results'>No results.<br/><br/> Please change search requests.</h2><div id='watchdog'></div>");
						//$output = $t;
					//}
				}else{
					$output .= "</div>";
				}

			
/**
* Get Property
**/			
			$_GET["offset"] = strval($_GET["offset"]) + 16;
		
			//$output .= "<input type='hidden' class='last_user_id' value='".$last_user_id."'>";
			echo $output;
?>