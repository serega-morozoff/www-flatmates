<?php

abstract class eway_xml_common extends eway_base {

  /*
   * Prepare request as XML data
   */
  function prepare_request ($settings, $description, $charge, $credit_card, $address, $order_id, $uid, $mail) {
    // Concatenate the customer address to send to the payment gateway.
    $customer_address = $address['thoroughfare'] . ' ';
    $customer_address .= $address['dependent_locality'] . ' ';
    $customer_address .= $address['locality'] . ' ';
    $customer_address .= $address['country'];
    $cc_name_on_card = $address['name_line'] ? $address['name_line'] : ($address['first_name'] . ' ' . $address['last_name']);
    $this->xml_data = array(
      'ewayCustomerID' => _commerce_payment_eway_get_data('customer_id', $settings),
      'ewayTotalAmount' => intval($charge['amount']),
      'ewayCustomerFirstName' => $address['first_name'],
      'ewayCustomerLastName' => $address['last_name'],
      'ewayCustomerEmail' => $mail,
      'ewayCustomerAddress' => $customer_address,
      'ewayCustomerPostcode' => $address['postal_code'],
      'ewayCustomerInvoiceDescription' => $description,
      'ewayCustomerInvoiceRef' => $order_id,
      'ewayCardHoldersName' => $cc_name_on_card,
      'ewayCardNumber' => _commerce_payment_eway_get_data('cc_number', $settings, $credit_card['number']),
      'ewayCardExpiryMonth' => $credit_card['exp_month'],
      'ewayCardExpiryYear' => $credit_card['exp_year'],
      'ewayTrxnNumber' => '',
      'ewayOption1' => '',
      'ewayOption2' => '',
      'ewayOption3' => '',
    );
  }

  /*
   * Send request to eWAY processor
   */
  function process_request () {
    $xmlstring = '<ewaygateway>';
    foreach ($this->xml_data as $key => $value) {
      $xmlstring .= '<' . $key . '>' . htmlspecialchars(trim($value), ENT_QUOTES, 'UTF-8') . '</' . $key . '>';
    }
    $xmlstring .= '</ewaygateway>';
    $this->return_info = $this->do_process_request($xmlstring, $this->url);
  }

  /**
   * Actually send the request to eWAY.
   */
  function do_process_request($xml, $url) {

    $response = drupal_http_request($url, array(
      'max_redirects' => 0,
      'method' => 'POST',
      'data' => $xml,
    ));

    // Send the data out over the wire
    if (isset($response->code) && $response->code !== '200') {
      drupal_set_message(t('eWay Payment API Request failed: %error', array('%error' => $response->error)), 'error');
      watchdog('commerce_paymeny_eway', 'eWay Payment API Request failed: %error', array('%error' => $response->error), WATCHDOG_ERROR, NULL);
      return FALSE;
    }
    else {
      $xtr = simplexml_load_string($response->data);
      $response_details = explode(',', $xtr->ewayTrxnError);

      $eway_response['txReturnNo'] = $response_details[0];
      $eway_response['txReturnDescription'] = $response_details[1];
      $eway_response['txStatus'] = strtolower($xtr->ewayTrxnStatus);
      $eway_response['txTransactionNumber'] = $xtr->ewayTrxnNumber;
      $eway_response['txOption1'] = $xtr->ewayTrxnOption1;
      $eway_response['txOption2'] = $xtr->ewayTrxnOption2;
      $eway_response['txOption3'] = $xtr->ewayTrxnOption3;
      $eway_response['txAmount'] = $xtr->ewayReturnAmount;
      $eway_response['txAuthCode'] = $xtr->ewayAuthCode;
      $eway_response['txInvoiceReference'] = $xtr->ewayTrxnReference;

      $return_array = array(
        'eway_response' => $eway_response,
      );

      if ($this->log_to_watchdog) {
        watchdog('commerce_payment_eway', 'XML request: %request, response: %response', array('%request' => var_export($xml, TRUE), '%response' => var_export($eway_response, TRUE)));
      }

      return $return_array;
    }
  }

}
