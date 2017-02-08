<?php
/** bootstrap Drupal **/
//field_property_type
$path = $_SERVER['DOCUMENT_ROOT'];
chdir($path);
define('DRUPAL_ROOT', getcwd()); //the most important line
require_once './includes/bootstrap.inc';
drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);
/**
* Get Users
**/	
$last_user_id = $_REQUEST['lastuid'];
//$search_index = $_REQUEST['searchIndex'];
$search_index = 11;
$post = $_POST;
//dpm($search_index);
for($i = 0; $i<2; $i++){
					$query = new EntityFieldQuery();
					$query->entityCondition('entity_type', 'node')
						->entityCondition('bundle', 'accomodation')
						->propertyCondition('status', NODE_PUBLISHED)
						->range($_GET["offset"], 4)
					;
					$result = $query->execute();
					$nodes = node_load_multiple(array_keys($result['node']));		
					foreach($nodes as $node){
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
									//if($key == 2) break;
								}
								$tooltip_etc = substr($tooltip_etc, 0, strlen($tooltip_etc) - 2);
								$etc_output = "<div class='about'><img src='".$etc_img."' alt='".$etc_name."'/><span data-toggle='tooltip' title='$tooltip_etc' >" . $etc_name . "</span></div>";
							}						
							if(count($node->field_image) > 0){
								$link = l(theme('image_style', array('path' => $node->field_image['und'][0]['uri'], 'style_name' => 'user_list')), 'node/' . $node->nid, array('html' => TRUE));
							}else{
								$link = l(theme('image_style', array('path' => $node->field_choose_photo_from_my_image['und'][0]['uri'], 'style_name' => 'user_list')), 'node/' . $node->nid, array('html' => TRUE));								
							}
							$age = $node -> field_age['und'][0]['value'];//get_age($user_ -> field_date_of_birth['und'][0]['value']);
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
								//<a  href="blogs/my-example?width=600&height=600" rel="gallery">My Example</a>
								$output.="<div class='send_message'><a class='colorbox-node' href='/user?width=600&height=600'>send a message</a></div>";
								//$output.="<div class='send_message'><a class='colorbox-node' href='node/28' data-href='node/28?width=600&height=600'>send a message</a></div>";
							}else{
								$output.="<div class='send_message'><a href='/messages/new?user=" . $user_ -> name . "'>send a message</a></div>";
							}
							$output .= "</div>";
							$last_user_id = $single->uid;
						}
					}
				//}
				$output .= "</div>";

			
/**
* Get Property
**/			
			$query = new EntityFieldQuery();
			$query->entityCondition('entity_type', 'node')
				->entityCondition('bundle', 'property')
				->propertyCondition('status', NODE_PUBLISHED)
				->range($_GET["offset"], 4)
			;
			$result = $query->execute();
			$nodes = node_load_multiple(array_keys($result['node']));
			$output .= "<div class='property_row_wrap'>";				
				foreach ($nodes as $node) {
					$nids = array();
					foreach($node->field_rooms['und'] as $one_room){
						$nids[] = $one_room['target_id'];
					}
					$room_nodes = node_load_multiple($nids);
					$weekly_rent = array();
					foreach($room_nodes as $key=>$room_node){
						$weekly_rent[$key] = $room_node -> field_weekly_rent['und'][0]['value'];
					}
					$minimal = 10000;
					$minimal_key = 0;
					$mi = min($weekly_rent);
					foreach($weekly_rent as $key=>$rent){
						if($rent < $minimal){
							$minimal = $rent;
							$minimal_key = $key;
						}
					}
					$room_node = $room_nodes[$minimal_key];
					$room_title = $node -> title;
					$room_inro = $node -> field_room_introduction['und'][0]['value'];
					$room_owner = user_load($node -> uid);
					foreach($room_owner -> roles as $key=>$role){
						if($key > 2){
							$room_status = ucfirst($role);
							$room_status_class = strtolower($room_status);
							$tid = taxonomy_get_term_by_name($role);
							$tooltip = "Administrator";
							foreach($tid as $t){
								$tooltip = $t->description;
							}							
						}
					}		
					if(isset($node -> field_bedrooms['und'][0]['tid'])){
						$bedrooms = get_taxonomy_name($node -> field_bedrooms['und'][0]['tid']);
						$bedrooms_img = get_taxonomy_img($node -> field_bedrooms['und'][0]['tid']);
					}					
					if(isset($node -> field_bathrooms['und'][0]['tid'])){
						$bathrooms = get_taxonomy_name($node -> field_bathrooms['und'][0]['tid']);
						$bathrooms_img = get_taxonomy_img($node -> field_bathrooms['und'][0]['tid']);
					}
					if(isset($node -> field_bathrooms['und'][0]['tid'])){
						$waterclosets = get_taxonomy_name($node -> field_property_type['und'][0]['tid']);
						$waterclosets_img = get_taxonomy_img($node -> field_property_type['und'][0]['tid']);
					}			
					if(isset($node -> field_another)){
						$etc_output = "";
						foreach($node -> field_another['und'] as $key => $etc_tid){
							$etc_name = get_taxonomy_name($etc_tid['tid']);
							$etc_img = get_taxonomy_img($etc_tid['tid']);
							$etc_output .= "<img src='".$etc_img."' alt='".$etc_name."'/><span>".$etc_name."</span>";
						}
					}						
					$output .= "<div class='rows property col-md-3 col-sm-6 col-xs-12'>";
						$alias_room = drupal_get_path_alias('node/'.$room_node->nid);
						$alias_property = drupal_get_path_alias('node/'.$node->nid);
						$link =  l(theme_image_style(array('path' => $room_node -> field_image['und'][0]['uri'], 'style_name' => 'property_picture')), $alias_room."/".$alias_property, array('html' => TRUE));
						//$output .= "<div class='room_picture'><a href='/node/" . $node -> nid . "'><img src='" . $img_path . "' alt='" . $img_path . "' /></a></div>";
						$output .= "<div class='room_picture'><div class='rent'>$".$minimal."</div>" . $link . "</div>";
						$link = l($room_title, $alias_room."/".$alias_property);
						$output .= "<div class='room_title'>" . $link . "</div>";
						$intro_title = t("Room introduction");
						$output .= "<div class='room_intro'>" . $room_node -> field_room_introduction['und'][0]['value'] . "</div>";
						$output .= "<div class='intro_suffix'>" . $intro_title . "</div>";
						$output .= "<div class='room_owner_role " . $room_status_class . "'><span data-toggle='tooltip' title='$tooltip'>" . $room_status . "</span></div>";
						$output .= "<div class='room_owner_role facilities'>";
							$output .= "<img src='".$bedrooms_img."' alt='rooms' />&nbsp<span>" . $bedrooms . "</span>";
							$output .= "<img src='".$bathrooms_img."' alt='rooms' />&nbsp<span>" . $bathrooms . "</span>";
							$output .= "<img src='".$waterclosets_img."' alt='rooms' />&nbsp<span>" . $waterclosets . "</span>";
							$output .= $etc_output;
						$output .= "</div>";
						if($user->uid == 0){
							$output.="<div class='send_message'><a class='colorbox-node' href='/user?width=600&height=600'>send a message</a></div>";
						}else{
							$output.="<div class='send_message'><a href='/messages/new?user=" . $room_owner -> name . "'>send a message</a></div>";
						}
					$output .= "</div>";
				}
			$output .= "</div>";	
			$_GET["offset"] = strval($_GET["offset"]) + 4;
}			
			$output .= "<input type='hidden' class='last_user_id' value='".$last_user_id."'>";
			echo $output;
?>
<?php
/** bootstrap Drupal **/
/*$path = $_SERVER['DOCUMENT_ROOT'];
chdir($path);
define('DRUPAL_ROOT', getcwd()); //the most important line
require_once './includes/bootstrap.inc';
drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);*/
/**
* Get Users
**/	
/*$last_user_id = $_REQUEST['lastuid'];

for($i = 0; $i<2; $i++){
			$query = "SELECT users.uid AS uid, users.created AS users_created
								FROM 
								{users} users
								LEFT JOIN {field_data_field_landlord} field_data_field_landlord ON users.uid = field_data_field_landlord.entity_id AND field_data_field_landlord.field_landlord_value = '1'
								WHERE (( (users.status <> '0') AND (users.uid <> '1')
								AND (users.uid > '".$last_user_id."')
								))
								ORDER BY users_created ASC";
			$users = db_query($query, array())->fetchAll();
				if($i == 0){
					$output = "<div class='users_row_wrap'>";
				}else{
					$output .= "<div class='users_row_wrap'>";
				}		
				$node_count = 0;
				foreach($users as $key=>$single){
					$query = new EntityFieldQuery();
					$query->entityCondition('entity_type', 'node')
						->entityCondition('bundle', 'accomodation')
						->propertyCondition('status', NODE_PUBLISHED)
						->propertyCondition('uid', $single->uid)
						->range(0, 1)
					;
					$result = $query->execute();
					$nodes = node_load_multiple(array_keys($result['node']));		
					foreach($nodes as $node){
						if(count($node) > 0){
							if($node_count > 3){
								break;
							}							
							$node_count ++;
							$user_ = user_load($node->uid);
							$location = "Looking in: ";
							foreach($node -> field_search_location_property['und'] as $term){
								$location .= get_taxonomy_name($term['tid']);
								$location .= ", ";
							}
							$seaker = "";
							foreach($node -> field_seeker_about['und'] as $term){
								$seaker .= get_taxonomy_name($term['tid']);
								$seaker .= ", ";
							}
							foreach($user_ -> roles as $key=>$role){
								if($key > 2){
									$status = $role;
									$status_class = strtolower($status);
								}
							}
							if(isset($node -> field_seeker_type['und'][0]['tid'])){
								$seeker_type = get_taxonomy_name($node -> field_seeker_type['und'][0]['tid']);
								$seeker_type_img = get_taxonomy_img($node -> field_seeker_type['und'][0]['tid']);
							}
							$etc_output = "";
							if(isset($node -> field_features)){
								foreach($node -> field_features['und'] as $key => $etc_tid){
									$etc_name = get_taxonomy_name($etc_tid['tid']);
									$etc_img = get_taxonomy_img($etc_tid['tid']);
									$etc_output .= "<div class='about'><img src='".$etc_img."' alt='".$etc_name."'/>  <span>".$etc_name."</span></div>";
									if($key == 2) break;
								}
							}						
							$location = substr($location, 0, strlen($location) - 2);
							$link = l(theme('image_style', array('path' => $user_->picture->uri, 'style_name' => 'user_list')), 'node/' . $node->nid, array('html' => TRUE));
							$age = get_age($user_ -> field_date_of_birth['und'][0]['value']);
							$output .= "<div class='rows users col-md-3 col-sm-6 col-xs-12'>";
							$output.="<div class='user_image'>".$link;
							if($user_ -> field_cost['und'][0]['value']){$output.="<div class='user_cost'>$" . $user_ -> field_cost['und'][0]['value'] . "</div>";}
							$output .="</div>";
							$name = getLandrordTitle($user_->field_first_name['und'][0]['value'], $user_->field_last_name['und'][0]['value'], $user_->name);
							//$output.="<div class='user_name'>" . $node -> title . "</div>";
							$output.="<div class='user_name'>" . $name . "</div>";
							if($user_ -> field_gender['und'][0]['value']){
								$gender = ucfirst($user_ -> field_gender['und'][0]['value']);
								$output.="<div class='gender-birth'><span class='user_gender " . $user_ -> field_gender['und'][0]['value'] . "'>" . $gender . "</span>";
								$output.="<span class='user_birth'>, " . $age . "yrs </span></div>";
							}
							if($location){$output.="<div class='user_location'>" . $location . "</div>";}
							if($seaker){$output.="<div class='user_about'>" . $seaker . "</div>";}
							if($status){$output.="<div class='user_role inline_block ".$status_class."'><span>" . $status . "</span></div>";}
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
				}
				$output .= "</div>";*/

			
/**
* Get Property
**/			
			/*$query = new EntityFieldQuery();
			$query->entityCondition('entity_type', 'node')
				->entityCondition('bundle', 'property')
				->propertyCondition('status', NODE_PUBLISHED)
				->range($_GET["offset"], 4)
			;
			$result = $query->execute();
			$nodes = node_load_multiple(array_keys($result['node']));
			$output .= "<div class='property_row_wrap'>";				
				foreach ($nodes as $node) {
					$nids = array();
					foreach($node->field_rooms['und'] as $one_room){
						$nids[] = $one_room['target_id'];
					}
					$room_nodes = node_load_multiple($nids);
					$weekly_rent = array();
					foreach($room_nodes as $key=>$room_node){
						$weekly_rent[$key] = $room_node -> field_weekly_rent['und'][0]['value'];
					}
					$minimal = 10000;
					$minimal_key = 0;
					$mi = min($weekly_rent);
					foreach($weekly_rent as $key=>$rent){
						if($rent < $minimal){
							$minimal = $rent;
							$minimal_key = $key;
						}
					}
					$room_node = $room_nodes[$minimal_key];
					$room_title = $node -> title;
					$room_inro = $node -> field_room_introduction['und'][0]['value'];
					$room_owner = user_load($node -> uid);
					foreach($room_owner -> roles as $key=>$role){
						if($key > 2){
							$room_status = ucfirst($role);
							$room_status_class = strtolower($room_status);
						}
					}		
					if(isset($node -> field_bedrooms['und'][0]['tid'])){
						$bedrooms = get_taxonomy_name($node -> field_bedrooms['und'][0]['tid']);
						$bedrooms_img = get_taxonomy_img($node -> field_bedrooms['und'][0]['tid']);
					}					
					if(isset($node -> field_bathrooms['und'][0]['tid'])){
						$bathrooms = get_taxonomy_name($node -> field_bathrooms['und'][0]['tid']);
						$bathrooms_img = get_taxonomy_img($node -> field_bathrooms['und'][0]['tid']);
					}
					if(isset($node -> field_bathrooms['und'][0]['tid'])){
						$waterclosets = get_taxonomy_name($node -> field_waterclosets['und'][0]['tid']);
						$waterclosets_img = get_taxonomy_img($node -> field_waterclosets['und'][0]['tid']);
					}			
					if(isset($node -> field_another)){
						$etc_output = "";
						foreach($node -> field_another['und'] as $key => $etc_tid){
							$etc_name = get_taxonomy_name($etc_tid['tid']);
							$etc_img = get_taxonomy_img($etc_tid['tid']);
							$etc_output .= "<img src='".$etc_img."' alt='".$etc_name."'/><span>".$etc_name."</span>";
						}
					}						
					$output .= "<div class='rows property col-md-3 col-sm-6 col-xs-12'>";
						$alias_room = drupal_get_path_alias('node/'.$room_node->nid);
						$alias_property = drupal_get_path_alias('node/'.$node->nid);
						$link =  l(theme_image_style(array('path' => $room_node -> field_image['und'][0]['uri'], 'style_name' => 'property_picture')), $alias_room."/".$alias_property, array('html' => TRUE));
						//$output .= "<div class='room_picture'><a href='/node/" . $node -> nid . "'><img src='" . $img_path . "' alt='" . $img_path . "' /></a></div>";
						$output .= "<div class='room_picture'><div class='rent'>$".$minimal."</div>" . $link . "</div>";
						$output .= "<div class='room_title'>" . $room_title . "</div>";
						$intro_title = t("Room introduction");
						$output .= "<div class='room_intro'>" . $room_node -> field_room_introduction['und'][0]['value'] . "</div>";
						$output .= "<div class='intro_suffix'>" . $intro_title . "</div>";
						$output .= "<div class='room_owner_role " . $room_status_class . "'>" . $room_status . "</div>";
						$output .= "<div class='room_owner_role facilities'>";
							$output .= "<img src='".$bedrooms_img."' alt='rooms' />&nbsp<span>" . $bedrooms . "</span>";
							$output .= "<img src='".$bathrooms_img."' alt='rooms' />&nbsp<span>" . $bathrooms . "</span>";
							$output .= "<img src='".$waterclosets_img."' alt='rooms' />&nbsp<span>" . $waterclosets . "</span>";
							$output .= $etc_output;
						$output .= "</div>";
						if($user->uid == 0){
							$link = l("Authorize", "user");
							$register = t("Please "); $register.=$link; $register.= t(" to send and recieve private message");
							$output .= "<div id='please-authorize' class='front'>$register</div>";
						}else{
							$output.="<div class='send_message'><a href='/messages/new?user=" . $room_owner -> name . "'>send a message</a></div>";
						}
					$output .= "</div>";
				}
			$output .= "</div>";	
			$_GET["offset"] = strval($_GET["offset"]) + 4;
}			
			$output .= "<input type='hidden' class='last_user_id' value='".$last_user_id."'>";
			echo $output;*/
?>