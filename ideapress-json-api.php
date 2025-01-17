<?php
/*
Plugin Name: IdeaPress JSON API
Description: A RESTful API used by IdeaPress Multi-screen applications for WordPress
Version: 1.0.0
Author: ideanotion
Author URI: http://ideanotion.net/
*/

$dir = json_api_dir();
@include_once "$dir/singletons/api.php";
@include_once "$dir/singletons/query.php";
@include_once "$dir/singletons/introspector.php";
@include_once "$dir/singletons/response.php";
@include_once "$dir/models/post.php";
@include_once "$dir/models/comment.php";
@include_once "$dir/models/category.php";
@include_once "$dir/models/tag.php";
@include_once "$dir/models/author.php";
@include_once "$dir/models/attachment.php";

function json_api_init() {
  global $ip_json_api;
  if (phpversion() < 5) {
    add_action('admin_notices', 'json_api_php_version_warning');
    return;
  }
  if (!class_exists('JSON_API')) {
    add_action('admin_notices', 'json_api_class_warning');
    return;
  }
  add_filter('rewrite_rules_array', 'json_api_rewrites');
  $ip_json_api = new JSON_API();
}

function json_api_php_version_warning() {
  echo "<div id=\"json-api-warning\" class=\"updated fade\"><p>Sorry, JSON API requires PHP version 5.0 or greater.</p></div>";
}

function json_api_class_warning() {
  echo "<div id=\"json-api-warning\" class=\"updated fade\"><p>Oops, JSON_API class not found. If you've defined a JSON_API_DIR constant, double check that the path is correct.</p></div>";
}

function json_api_activation() {
  // Add the rewrite rule on activation
  global $wp_rewrite;
  add_filter('rewrite_rules_array', 'json_api_rewrites');
  $wp_rewrite->flush_rules();

  json_api_init();
  global $ip_json_api;
  $available_controllers = $ip_json_api->get_controllers();
    $active_controllers = explode(',', get_option('json_api_controllers', 'ip_core'));
    
    if (count($active_controllers) == 1 && empty($active_controllers[0])) {
      $active_controllers = array();
    }

      $controllers = array('ip_core','ip_respond');
        
        
        foreach ($controllers as $controller) {
          if (in_array($controller, $available_controllers)) {
            if (!in_array($controller, $active_controllers)) {
              $active_controllers[] = $controller;
            } 
          }
        }
        $ip_json_api->save_option('json_api_controllers', implode(',', $active_controllers));
}

function json_api_deactivation() {
  // Remove the rewrite rule on deactivation
  global $wp_rewrite;
  $wp_rewrite->flush_rules();
}

function json_api_rewrites($wp_rules) {
  $base = get_option('json_api_base', 'api');
  if (empty($base)) {
    return $wp_rules;
  }
  $ip_json_api_rules = array(
    "$base\$" => 'index.php?json=info',
    "$base/(.+)\$" => 'index.php?json=$matches[1]'
  );
  return array_merge($ip_json_api_rules, $wp_rules);
}

function json_api_dir() {
  if (defined('JSON_API_DIR') && file_exists(JSON_API_DIR)) {
    return JSON_API_DIR;
  } else {
    return dirname(__FILE__);
  }
}

// Add initialization and activation hooks
add_action('init', 'json_api_init');
register_activation_hook("$dir/json-api.php", 'json_api_activation');
register_deactivation_hook("$dir/json-api.php", 'json_api_deactivation');

?>
