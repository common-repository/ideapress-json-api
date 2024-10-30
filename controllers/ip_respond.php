<?php
/*
Controller name: IP_Respond
Controller description: Comment/trackback submission methods
*/

class JSON_API_IP_Respond_Controller {
  
  function submit_comment() {
    global $ip_json_api;
    nocache_headers();
    if (empty($_REQUEST['post_id'])) {
      $ip_json_api->error("No post specified. Include 'post_id' var in your request.");
    } else if (empty($_REQUEST['name']) ||
               empty($_REQUEST['email']) ||
               empty($_REQUEST['content'])) {
      $ip_json_api->error("Please include all required arguments (name, email, content).");
    } else if (!is_email($_REQUEST['email'])) {
      $ip_json_api->error("Please enter a valid email address.");
    }
    $pending = new JSON_API_Comment();
    return $pending->handle_submission();
  }
  
}

?>
