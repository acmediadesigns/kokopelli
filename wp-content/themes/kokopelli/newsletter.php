<?php

require_once("{$_SERVER['DOCUMENT_ROOT']}/wp-load.php");

global $wpdb;

// Check if email param is present
$email = $_GET['email'];

if($email)
{
  // Check if email is valid
  if(filter_var($email, FILTER_VALIDATE_EMAIL))
  {
    $existing_query = "SELECT `email_address` FROM `newsletter` WHERE `email_address` = '{$email}'";
    $existing = $wpdb->get_results($existing_query);

    // Check if email is used
    if($wpdb->num_rows == 0)
    {
      $wpdb->insert('newsletter', [
        'email_address' => $email,
        'created' => date('Y-m-d H:s:i')
      ]);
    }

    $result = ['code' => 100, 'message' => 'Thank you for signing up!'];
  }
  else {
    $result = ['code' => 102, 'message' => 'Please provide a valid email address'];
  }
}
else {
  $result = ['code' => 101, 'message' => 'Please provide a email address'];
}

echo json_encode($result);