<?php
/**
* Get Property
**/
$path = $_SERVER['DOCUMENT_ROOT'];
chdir($path);
define('DRUPAL_ROOT', getcwd()); //the most important line
require_once './includes/bootstrap.inc';
drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);
/****/
$count_rooms = 0;
$watchdog = 0;
$offset = strval($_GET["offset"]);
$search_index = $_REQUEST['searchIndex'];
if(isset($_REQUEST['features']) && $_REQUEST['features'] != ""){
	$features = substr($_REQUEST['features'], 0, strlen($_REQUEST['features']) - 1);
	$features_array = explode("_", $features);
}
$gender = strtolower($_REQUEST['gender']);
if($search_index != ""){
	$search_index_array = explode("_", $search_index);
}
if($_REQUEST['minRent'] != "any"){
	$min_rent = intval($_REQUEST['minRent']);
}
if($_REQUEST['maxRent'] != "any"){
	$max_rent = intval($_REQUEST['maxRent']);
}
$query = new EntityFieldQuery();
$query->entityCondition('entity_type', 'node');
$query->entityCondition('bundle', 'rooms');
$query->propertyCondition('status', NODE_PUBLISHED);
if(isset($_REQUEST['searchIndex']) && $_REQUEST['searchIndex'] != ""  && $_REQUEST['searchIndex']!="_rooms")$query->fieldCondition('field_address', 'value', $search_index_array, 'IN');
if(isset($_REQUEST['features']) && $_REQUEST['features'] != "")$query->fieldCondition('field_features', 'tid', $features_array, 'IN');
if(isset($_REQUEST['minRent']) && $_REQUEST['minRent'] != "any")$query->fieldCondition('field_weekly_rent', 'value', $min_rent, '>=');
if(isset($_REQUEST['maxRent']) && $_REQUEST['maxRent'] != "any")$query->fieldCondition('field_weekly_rent', 'value', $max_rent, '<=');	
if(isset($_REQUEST['gender']) && $gender!="")$query->fieldCondition('field_gender', 'value', $gender, '=');
if(isset($_REQUEST['bathroomType']) && $_REQUEST['bathroomType']!="any")$query->fieldCondition('field_bathroom_types', 'tid', $_REQUEST['bathroomType'], 'CONTAINS');
if(isset($_REQUEST['stayLength']) && $_REQUEST['stayLength']!="any")$query->fieldCondition('field_stay_length', 'tid', $_REQUEST['stayLength'], 'CONTAINS');
if(isset($_REQUEST['roomType']) && $_REQUEST['roomType']!="any")$query->fieldCondition('field_room_type', 'tid', $_REQUEST['roomType'], 'CONTAINS');
if(isset($_REQUEST['furnishing']) && $_REQUEST['furnishing']!="any")$query->fieldCondition('field_furnushing', 'tid', $_REQUEST['furnishing'], 'CONTAINS');
$result = $query->execute();
$breakpoint = count($result['node']);
while($count_rooms < 16){
	$query = new EntityFieldQuery();
	$query->entityCondition('entity_type', 'node');
	$query->entityCondition('bundle', 'rooms');
	$query->propertyCondition('status', NODE_PUBLISHED);
	
	if(isset($_REQUEST['searchIndex']) && $_REQUEST['searchIndex'] != "" && $_REQUEST['searchIndex']!="_rooms")$query->fieldCondition('field_address', 'value', $search_index_array, 'IN');
	if(isset($_REQUEST['features']) && $_REQUEST['features'] != "")$query->fieldCondition('field_features', 'tid', $features_array, 'IN');
	if(isset($_REQUEST['minRent']) && $_REQUEST['minRent'] != "any")$query->fieldCondition('field_weekly_rent', 'value', $min_rent, '>=');
	if(isset($_REQUEST['maxRent']) && $_REQUEST['maxRent'] != "any")$query->fieldCondition('field_weekly_rent', 'value', $max_rent, '<=');	
	if(isset($_REQUEST['gender']) && $gender!="")$query->fieldCondition('field_gender', 'value', $gender, '=');
	if(isset($_REQUEST['bathroomType']) && $_REQUEST['bathroomType']!="any")$query->fieldCondition('field_bathroom_types', 'tid', $_REQUEST['bathroomType'], 'CONTAINS');
	if(isset($_REQUEST['stayLength']) && $_REQUEST['stayLength']!="any")$query->fieldCondition('field_stay_length', 'tid', $_REQUEST['stayLength'], 'CONTAINS');
	if(isset($_REQUEST['roomType']) && $_REQUEST['roomType']!="any")$query->fieldCondition('field_room_type', 'tid', $_REQUEST['roomType'], 'CONTAINS');
	if(isset($_REQUEST['furnishing']) && $_REQUEST['furnishing']!="any")$query->fieldCondition('field_furnushing', 'tid', $_REQUEST['furnishing'], 'CONTAINS');
	$query->range($offset, 1);
	$result = $query->execute();
	if($breakpoint < $watchdog){
		break;
	}	
	$watchdog ++;
	$nodes = node_load_multiple(array_keys($result['node']));
	foreach ($nodes as $room_node) {
		$query = new EntityFieldQuery();
		$query->entityCondition('entity_type', 'node');
		$query->entityCondition('bundle', 'property');
		$query->propertyCondition('status', NODE_PUBLISHED);
		$query->fieldCondition('field_rooms', 'target_id', $room_node->nid, '=');
		if(isset($_REQUEST['acomodationTypes']) && $_REQUEST['acomodationTypes']!="any"){
			$query->fieldCondition('field_accomodation', 'tid', $_REQUEST['acomodationTypes'], 'CONTAINS');
		}
		$query->range(0, 1);
		$result = $query->execute();		
		//print " Room nid ".$room_node->nid. " ";
		foreach($result['node'] as $r){
			
			$node = node_load($r->nid);
			
			if(count($node) > 0){
				//print "Prop: ". $r->nid." ".$offset." <br/>";
				//echo "<pre>";print_r($node);echo "</pre>";
				$room_title = $node -> title;
				$room_inro = $node -> field_room_introduction['und'][0]['value'];
				$room_owner = user_load($node -> uid);
				$minimal = $room_node -> field_weekly_rent['und'][0]['value'];
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
				if($count_rooms == 0){
					$output = "<div class='property_row_wrap'>";
				}elseif($count_rooms % 4 == 0){
					$output .= "</div><div class='property_row_wrap'>";
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
						$link = l("Authorize", "user");
						$register = t("Please "); $register.=$link; $register.= t(" to send and recieve private message");
						$output .= "<div id='please-authorize' class='front'>$register</div>";
					}else{
						$output.="<div class='send_message'><a href='/messages/new?user=" . $room_owner -> name . "'>send a message</a></div>";
					}
				$output .= "</div>";
				$count_rooms ++;							
				//break;
			}	
		}
		$offset ++;
		//echo "<pre>";print count($node);	echo "</pre>";

	}
}
if($breakpoint <= $watchdog){
	if($breakpoint != 0){
		$output .= "</div><div id='watchdog'></div>";
	}/*else{
		$t = t("<h2 class='no-results'>No results.<br/><br/> Please change search requests.</h2><div id='watchdog'></div>");
		$output = $t;
	}*/
}else{
	$output .= "</div>";
}
echo $output;


?>