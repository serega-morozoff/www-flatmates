<?php
/**
 * @file
 * The primary PHP file for this theme.
 */
 /**
 *HOOKS
 */
function flatmates_form_search_block_form_alter(&$form, &$form_state, $form_id) {
	$form['#suffix'] = '<div class="suffix">more then 10,000 rooms<br>and potential tenants</div>';
	$form['search_block_form']['#attributes']['placeholder'] = t('search share accommodation or potential tenant');
	$form['#action'] = 'search';
	$search = t("Make search");
	$prefix = '<div class="expanded">';
	$prefix .= '<div id="close"></div><div id="locations"></div>';
	$prefix .= '<div id="switcher">';
	$prefix .= '<div class="switch rooms">Rooms</div>';
	$prefix .= '<div class="switch flatmates">Flatmates</div>';
	$prefix .= '</div></div><div id="advanced-search"></div>';
	$form['name']["#prefix"] = "<div class='prefix'>" . $prefix . "<div class='make_search'>".$search."</div></div>";	
}
 /**
 *Custom Function
 */
function get_taxonomy_name($tid){
	$term = taxonomy_term_load($tid);
	$name = $term -> name;
	return $name;
}

function get_image_by_uri($uri, $style_name){
	$path = file_create_url($uri);
	$uri = image_style_url($style_name, $path);
	return $path;
}

function get_taxonomy_img($tid){
	$term = taxonomy_term_load($tid);
	$path = image_style_url('taxonomy_get_100', $term->field_image[LANGUAGE_NONE][0]['uri']);
	return $path;
}

function get_age($bithdayDate){
 $bithdayDate =	explode(" ", $bithdayDate);
 $date = new DateTime($bithdayDate[0]);
 $now = new DateTime('now');
 $interval = $now->diff($date);
 return $interval->y;
}

function getStayLength(){
	$user_ = user_load(arg(1));
	if($user_->field_in_date && $user_->field_move_date){
		$d = explode(" ", $user_->field_in_date['und'][0]['value']);
		$date_1 = new DateTime($d[0]);
		$d = explode(" ", $user_->field_move_date['und'][0]['value']);
		$date_2 = new DateTime($d[0]);
		$interval = date_diff($date_1, $date_2);
		$date_str = $interval->format('%d');
		$date_int = intval($date_str);
		if($date_int > 30){
			echo $interval->format('%m months');
		}else{
			echo $interval->format('%d days');
		}
	}else{
		$output = t("Nevermind");
		echo $output;
	}
}

function getTenantTitle(){
	$user_ = user_load(arg(1));
	$age = "";
	$gender = "";
	$output = "";
	if($user_ -> field_date_of_birth){
			$age = get_age($user_ -> field_date_of_birth['und'][0]['value']);
			$age .= t("yrs");
		}
	if($user_ -> field_gender){
		$gender = $user_ -> field_gender['und'][0]['value'];
		$gender = ucfirst($gender);
	}
	$last_name = "";
	$first_name = "";
	if($user_ -> field_first_name){
		$first_name = $user_ -> field_first_name['und'][0]['value'];
	}
	if($user_ -> field_last_name){
		$last_name = $user_ -> field_last_name['und'][0]['value'];
	}	
	if($last_name == "" && $first_name == ""){
		$output .= $user_ -> name. ", ";
	}elseif($last_name != "" && $first_name != ""){
		$output .= $last_name.", ".$first_name." ";
	}elseif($last_name != "" && $first_name == ""){
		$output .= $first_name;
	}else{
		$output .= $last_name.", ";
	}
	$output .= $gender." ".$age;
	return $output;
}

function getTenantTitleByID($id){
	$age = "";
	$gender = "";
	$output = "";
	$user_ = user_load($id);
	if($user_ -> field_date_of_birth){
			$age = get_age($user_ -> field_date_of_birth['und'][0]['value']);
			$age .= t("yrs");
		}
	if($user_ -> field_gender){
		$gender = $user_ -> field_gender['und'][0]['value'];
		$gender = ucfirst($gender);
	}
	$last_name = "";
	$first_name = "";
	if($user_ -> field_first_name){
		$first_name = $user_ -> field_first_name['und'][0]['value'];
	}
	if($user_ -> field_last_name){
		$last_name = $user_ -> field_last_name['und'][0]['value'];
	}	
	if($last_name == "" && $first_name == ""){
		$output .= $user_ -> name. ", ";
	}elseif($last_name != "" && $first_name != ""){
		$output .= $last_name." ".$first_name.", ";
	}elseif($last_name != "" && $first_name == ""){
		$output .= $first_name;
	}else{
		$output .= $last_name.", ";
	}
	$output .= $gender.", ".$age;
	return $output;	
}

function getLandrordTitle($first_name, $last_name, $name){
	if($last_name == "" && $first_name == ""){
		$output = $name;
	}elseif($last_name != "" && $first_name != ""){
		$output = $last_name." ".$first_name;
	}elseif($last_name != "" && $first_name == ""){
		$output = $first_name;
	}else{
		$output = $last_name;
	}
	return $output;	
}

function _get_last_id($tableName, $fieldName) {
	$select = db_select($tableName, 'o');
	$fields = array(
			$fieldName,
	);
	$select->fields('o', $fields);
	$result = $select->orderBy($fieldName)->execute()->fetchAll();
	$last = count($result) - 1;
	return $result[$last];
}

function _get_first_id($tableName, $fieldName) {
	$select = db_select($tableName, 'o');
	$fields = array(
			$fieldName,
	);
	$select->fields('o', $fields);
	$result = $select->orderBy($fieldName)->execute()->fetchAll();
	$last = 0;
	return $result[$last];
}

function _get_last_nid($tableName, $fieldName) {
	$select = db_select($tableName, 'o');
	$fields = array(
			$fieldName,
	);
	$select->fields('o', $fields);
	$result = $select->condition('type', "property",'=')->orderBy($fieldName)->execute()->fetchAll();
	$last = count($result) - 1;
	return $result[$last];
}

function _get_first_nid($tableName, $fieldName) {
	$select = db_select($tableName, 'o');
	$fields = array(
			$fieldName,
	);
	$select->fields('o', $fields);
	$result = $select->condition('type', "property",'=')->orderBy($fieldName)->execute()->fetchAll();
	$last = 0;
	return $result[$last];
}

function _get_all_nids($tableName, $fieldName) {
	$select = db_select($tableName, 'o');
	$fields = array(
			$fieldName,
	);
	$select->fields('o', $fields);
	$result = $select->condition('type', "property",'=')->orderBy($fieldName)->execute()->fetchAll();
	return $result;
}


function nextUser(){
	$next_user = 1;
	$lastId = _get_last_id('users' , 'uid');			
	$last = $lastId->uid;			
	for($i = intval(arg(1)) + 1; $i <= $last; $i++){
		$next_user = $i;
		$checku = user_load($next_user);
		if($checku || $i > $last){
			break;
		}
	}
	if($next_user == 1){
		$next_user = 12;
	}
	$output = "<div class='nav-next'><a href='/user/".$next_user."'><img src='/sites/all/themes/flatmates/img/bg-next.png' alt='' width='31' height='31' /></a></div>";	
	return $output;
}

function prevUser(){
	$prev_user = 2;
	$lastId = _get_last_id('users' , 'uid');
	$last = $lastId->uid;		
	for($i = intval(arg(1)) - 1; $i > 2; $i--){
		$prev_user = $i;
		$checku = user_load($prev_user);
		if($checku || $i == 1){
			break;
		}
	}	
	if($prev_user == 3){
		$prev_user = $last;
	}
	$output = "<div class='nav-prev'><a href='/user/".$prev_user."'><img src='/sites/all/themes/flatmates/img/bg-prev.png' alt='' width='31' height='31' /></a></div>";
	return $output;
}

function nextNode(){
	$nids = _get_all_nids('node' , 'nid');			
	$current = arg(1);
	while ($arr_name = current($nids)) {
			if ($arr_name->nid == $current) {
					$kurrent_key = intval(key($nids)) + 1;
			}
			next($nids);
	}
	end($nids);
	$key = key($nids);
	if($kurrent_key > $key){
		$kurrent_key = 0;
	}	
	$output = "<div class='nav-next'><a href='/node/".$nids[$kurrent_key]->nid."'><img src='/sites/all/themes/flatmates/img/bg-next.png' alt='' width='31' height='31' /></a></div>";	
	return $output;
}

function prevNode(){
	$nids = _get_all_nids('node' , 'nid');			
	$current = arg(1);
	while ($arr_name = current($nids)) {
			if ($arr_name->nid == $current) {
					$kurrent_key = intval(key($nids)) - 1;
			}
			next($nids);
	}
	end($nids);
	$key = key($nids);
	if($kurrent_key < 0){
		$kurrent_key = $key;
	}
	$output = "<div class='nav-prev'><a href='/node/".$nids[$kurrent_key]->nid."'><img src='/sites/all/themes/flatmates/img/bg-prev.png' alt='' width='31' height='31' /></a></div>";
	return $output;
}

function propertyOwner(){
	$node = node_load(arg(1));
	$room_owner = user_load($node->uid);
	$n = getLandrordTitle($room_owner -> field_first_name['und'][0]['value'], $room_owner -> field_last_name['und'][0]['value'], $room_owner -> name);
	foreach($room_owner -> roles as $key=>$role){
		if($key > 2){
			$room_status = ucfirst($role);
			$room_status_class = strtolower($room_status);
		}
	}	
	$link =  l($n, 'user/' . $room_owner->uid, array('html' => TRUE));
	$output = "<span class='property_owner_name'>".$link."</span><span class='property_owner_status ".$room_status_class."'>".$room_status."</span>";	
	return $output;
}

function flatmates_privatemsg_list_field__participants($variables) {
    $thread = $variables['thread'];
    $field = array();

    if ($thread['has_tokens']) {
        $message = privatemsg_message_load($thread['thread_id']);
    }
    if (!isset($message)) {
        $message = privatemsg_message_load($thread['thread_id']);
    }

    $participants = _privatemsg_generate_user_array($thread['participants'], -4);

    $field['data'] = theme('user_picture', array('account' => $message->author));
    $field['data'] .= '<div class="user-name">'. _privatemsg_format_participants($participants, 3, TRUE).'</div>';

    $field['class'] = 'privatemsg-list-participants';

    return $field;
}