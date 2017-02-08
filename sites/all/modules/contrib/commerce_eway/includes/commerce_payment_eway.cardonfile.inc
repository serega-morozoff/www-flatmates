<?php
/**
 * @file
* Helper functions to integrate commerce_payment_eway with commerce_cardonfile
* Which allows for commerc_recurring support using eWAY token payments
*/


/*
* Commerce_cardonfile payment method delete callback
* called from user/1/stored-payment-methods/19/delete
*/
function commerce_payment_eway_cardonfile_delete_callback($form, $form_state, $payment_method, $card_data) {
  db_delete('commerce_card_data')
    ->condition('uid', $card_data['uid'])
    ->condition('card_id', $card_data['card_id'])
    ->execute();
    
  db_delete('commerce_eway_token')
    ->condition('uid', $card_data['uid'])
    ->condition('eway_id', $card_data['remote_id'])
    ->execute();
}

/*
* Commerce_cardonfile payment method update callback
* called from user/1/stored-payment-methods/19/update
*/
function commerce_payment_eway_cardonfile_update_callback($form, $form_state, $payment_method, $card_data) {
  module_load_include('class.inc', 'commerce_payment_eway', 'includes/eway_base');
  module_load_include('class.inc', 'commerce_payment_eway', 'includes/eway_token');
  $processor = new eway_token($payment_method['settings']);
  
  if (!is_numeric($card_data['remote_id'])) {
    return FALSE;
  }
    
  $update_fields = array(
    'CCNameOnCard' => $card_data['card_name'],
    'CCExpiryMonth' => sprintf('%02d', $card_data['card_exp_month']),
    'CCExpiryYear' => substr($card_data['card_exp_year'], -2),
  ); 
  
  if ($form_state['values']['credit_card']['number'] != $form['credit_card']['number']['#default_value']) {
    $update_fields['CCNumber'] = $form_state['values']['credit_card']['number'];
  }
  
  if ( $processor->update_customer($card_data['remote_id'], $update_fields) != 'true') {
    drupal_set_message($processor->last_response['txReturnDescription'], 'error');
  }
  
  return TRUE;
}

/*
* Commerce_cardonfile payment method charge callback
* called from commerce_recurring/commerce_recurring.rules.inc/commerce_recurring_process_payment
*/
function commerce_payment_eway_cardonfile_charge_callback($order, $parent_order) {
  $card_data = current(commerce_cardonfile_data_load_multiple($order->uid));
  if ($card_data['remote_id']) {
    // Build a description to send to the payment gateway.
    $description = theme('commerce_payment_eway_transaction_description', array('order' => $order));
    // Trim the description to eWay's limit of 10000 characters,
    // which is ridiculously long anyway.
    $description = substr($description, 0, 10000);
    $payment_method = commerce_payment_method_instance_load($parent_order->data['payment_method']);    
    $order_wrapper = entity_metadata_wrapper('commerce_order', $order);
    $charge = array(
     'amount' => $order_wrapper->commerce_order_total->amount->value(),
     'currency_code' => $order_wrapper->commerce_order_total->currency_code->value(),
    );
    module_load_include('class.inc', 'commerce_payment_eway', 'includes/eway_base');
    module_load_include('class.inc', 'commerce_payment_eway', 'includes/eway_token');
    $processor = new eway_token($payment_method['settings']);
    $processor->process_payment($card_data['remote_id'], $charge['amount'], $order->order_id, $description);
    $transaction = _commerce_payment_eway_save_transaction($payment_method['settings'], $order, $charge, $processor->return_info);
    if ($transaction->status == COMMERCE_PAYMENT_STATUS_SUCCESS) {
      watchdog('commerce_payment_eway_cardonfile', 'eway charge completed for customer ' . $card_data['remote_id'] . ' amount:' . $charge['amount']);
      commerce_checkout_complete($order);
      return TRUE;
    }
    else {
      watchdog('commerce_payment_eway_cardonfile', 'eway charge failed for customer ' . $card_data['remote_id'] . ' amount:' . $charge['amount'], 'error');
    }
  }
  else {
    watchdog('commerce_payment_eway_cardonfile', 'customer is not set up in eway with a valid token uid: ' . $order->uid, 'error');
  }
}

/*
* Helper function for eway to save credit card data to commerce_cadronfile's commerce_card_data table
* called from commerce_payment_eway_create_customer for newly created users
* and from commerce_payment_eway_submit_form_submit for already logged in users
*/
function commerce_payment_eway_cardonfile_save($uid, $remote_id, $card_data) {
  if ($uid && $remote_id) {
    $card_data = array(
      'uid' => $uid,
      'payment_method' => 'commerce_payment_eway',
      'instance_id' => 'commerce_payment_eway|commerce_payment_commerce_payment_eway',
      'remote_id' => $remote_id,
      'card_type' => 'card',
      'card_name' => $card_data['CCNameOnCard'],
      'card_number' => substr($card_data['CCNumber'], -4),
      'card_exp_month' => $card_data['CCExpiryMonth'],
      'card_exp_year' => (($card_data['CCExpiryYear']<100)?'20':'') . $card_data['CCExpiryYear'],
      'status' => 1,
    );
    
    //because we only want to support a single credit card per user - make sure we remove any old entries
    db_delete('commerce_card_data')
    ->condition('uid', $uid)
    ->execute();
    
    commerce_cardonfile_data_save($card_data);
  }
}
