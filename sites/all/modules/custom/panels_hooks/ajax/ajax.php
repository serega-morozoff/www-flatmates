<?php
/** bootstrap Drupal **/
$path = $_SERVER['DOCUMENT_ROOT'];
chdir($path);
define('DRUPAL_ROOT', getcwd()); //the most important line
require_once './includes/bootstrap.inc';
drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);

			$query = "SELECT users.uid AS uid, users.created AS users_created
								FROM 
								{users} users
								LEFT JOIN {field_data_field_landlord} field_data_field_landlord ON users.uid = field_data_field_landlord.entity_id AND field_data_field_landlord.field_landlord_value = '1'
								WHERE (( (users.status <> '0') AND (field_data_field_landlord.field_landlord_value IS NULL ) ))
								ORDER BY users_created DESC
								LIMIT 4 OFFSET 12";
			$users = db_query($query, array())->fetchAll();

				foreach($users as $single){
					
					$user = user_load($single->uid);
					$uid = $single->uid;
					$location = "Looking in: ";
					foreach($user -> field_search_location['und'] as $term){
						$location .= get_taxonomy_name($term['tid']);
						$location .= ", ";
					}
					$seaker = "";
					foreach($user -> field_seeker_about['und'] as $term){
						$seaker .= get_taxonomy_name($term['tid']);
						$seaker .= ", ";
					}
					if(isset($user -> field_l['und'][0]['tid'])){
						$job = get_taxonomy_name($user -> field_l['und'][0]['tid']);
						$job_img = get_taxonomy_img($user -> field_l['und'][0]['tid']);
					}
					if(isset($user -> field_buisy['und'][0]['tid'])){
						$free = get_taxonomy_name($user -> field_buisy['und'][0]['tid']);
						$free_img = get_taxonomy_img($user -> field_buisy['und'][0]['tid']);
					}
					if(isset($user -> field_seeker_type['und'][0]['tid'])){
						$seeker_type = get_taxonomy_name($user -> field_seeker_type['und'][0]['tid']);
						$seeker_type_img = get_taxonomy_img($user -> field_seeker_type['und'][0]['tid']);
					}
					$accomodation = "";
					$accomodation_img = "";
					if(isset($user -> field_accomodation['und'][0]['tid'])){
						$accomodation = get_taxonomy_name($user -> field_accomodation['und'][0]['tid']);
						$accomodation_img = get_taxonomy_img($user -> field_accomodation['und'][0]['tid']);
					}
					$location = substr($location, 0, strlen($location) - 2);
					$img_path = get_image_by_uri($user -> field_image['und'][0]['uri'], "user_picture");
					$age = get_age($user -> field_date_of_birth['und'][0]['value']);
					$output .= "<div class='row'>";
						$output.="<div class='user_image'><img class='user_picture' src='" . $img_path . "' alt=" . $user -> field_first_name['und'][0]['value'] . "&nbsp;" . $user -> field_last_name['und'][0]['value'] . "/>";
						if($user -> field_cost['und'][0]['value']){$output.="<div class='user_cost'>" . $user -> field_cost['und'][0]['value'] . "</div>";}
						$output .="</div>";
						$output.="<div class='user_name'>" . $user -> field_first_name['und'][0]['value'] . "&nbsp;" . $user -> field_last_name['und'][0]['value'] . "</div>";
						if($user -> field_gender['und'][0]['value']){$output.="<div class='gender-birth'><span class='user_gender " . $user -> field_gender['und'][0]['value'] . "'>" . $user -> field_gender['und'][0]['value'] . "</span></div>";}
						if($age){$output.="<span class='user_birth'>, " . $age . "yrs </span></div>";}
						if($location){$output.="<div class='user_location'>" . $location . "</div>";}
						if($seaker){$output.="<div class='user_about'>" . $seaker . "</div>";}
						if($free){$output.="<div class='user_free inline_block'><img src='" . $free_img . "' alt='' /><span>" . $free . "</span></div>";}
						if($seeker_type){$output.="<div class='user_type inline_block'><img src='" . $seeker_type_img . "' alt='' /><span>" . $seeker_type . "</span></div>";}
						if($accomodation){$output.="<div class='user_accomodation inline_block'><img src='" . $accomodation_img . "' alt='' /><span>" . $accomodation . "</span></div>";}
						if($job){$output.="<div class='user_job'><img src='" . $job_img . "' width = '16' height = '16' alt='' /><span>Working " . $job . "</span></div>";}
						$output.="<div class='send_message'><a href='/messages/new?user=" . $user -> name . "'>send a message</a></div>";
					$output .= "</div>";
				}
				echo $output;
?>