<?php

App::uses('CakeLog', 'Log');

class RcauthSourceUtils
{
// Wrapper for curl

  /**
   * @param $url      The URL used to address the request
   * @param $fields   List of query parameters in a key=>value array format
   * @param $error
   * @param $info
   * @return bool|string
   * @throws Exception
   */
  public static function do_curl($url, $fields, &$error, &$info)
  {
    //url-ify the data for the POST
    $fields_string = "";
    foreach ($fields as $key => $value) {
      $fields_string .= $key . '=' . $value . '&';
    }
    rtrim($fields_string, '&');
    // open connection
    $ch = curl_init();

    // set the url, number of POST vars, POST data
    // Content-type: application/x-www-form-urlencoded => is the default approach for post requests
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, count($fields));
    curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
    curl_setopt($ch, CURLOPT_VERBOSE, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 3000);

    // execute post
    $response = curl_exec($ch);
    $status_code = "";
    // fixme: Make curl throw an dnot return the errors
    $error = "";
    if (empty($response)) {
      // probably connection error
      $error = curl_error($ch);
      if (Configure::read('debug')) {
        CakeLog::write('error', __METHOD__ . ':: Http Request Failed::' . $error);
      }
    }

    $info = curl_getinfo($ch);

    // close connection
    curl_close($ch);
    // return success
    return $response;
  }
}

?>