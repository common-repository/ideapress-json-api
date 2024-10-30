<?php
/*
Controller name: IP_Core
Controller description: Basic introspection methods
*/

class JSON_API_IP_Core_Controller {
  
  public function info() {
    global $ip_json_api;
    $php = '';
    if (!empty($ip_json_api->query->controller)) {
      return $ip_json_api->controller_info($ip_json_api->query->controller);
    } else {
      $dir = json_api_dir();
      if (file_exists("$dir/json-api.php")) {
        $php = file_get_contents("$dir/json-api.php");
      } else {
        // Check one directory up, in case json-api.php was moved
        $dir = dirname($dir);
        if (file_exists("$dir/json-api.php")) {
          $php = file_get_contents("$dir/json-api.php");
        }
      }
      if (preg_match('/^\s*Version:\s*(.+)$/m', $php, $matches)) {
        $version = $matches[1];
      } else {
        $version = '(Unknown)';
      }
      $active_controllers = explode(',', get_option('json_api_controllers', 'ip_core'));
      $controllers = array_intersect($ip_json_api->get_controllers(), $active_controllers);
      return array(
        'json_api_version' => $version,
        'controllers' => array_values($controllers)
      );
    }
  }
  
  public function get_recent_posts() {
    global $ip_json_api;
    $posts = $ip_json_api->introspector->get_posts();
    return $this->posts_result($posts);
  }

  public function get_recent_posts_summary() {
    global $ip_json_api;
    $posts = $ip_json_api->introspector->get_posts(FALSE,FALSE, true);
    return $this->posts_result($posts);
  }
  
  public function get_post() {
    global $ip_json_api, $post;
    extract($ip_json_api->query->get(array('id', 'slug', 'post_id', 'post_slug')));
    if ($id || $post_id) {
      if (!$id) {
        $id = $post_id;
      }
      $posts = $ip_json_api->introspector->get_posts(array(
        'p' => $id
      ), true);
    } else if ($slug || $post_slug) {
      if (!$slug) {
        $slug = $post_slug;
      }
      $posts = $ip_json_api->introspector->get_posts(array(
        'name' => $slug
      ), true);
    } else {
      $ip_json_api->error("Include 'id' or 'slug' var in your request.");
    }
    if (count($posts) == 1) {
      $post = $posts[0];
      $previous = get_adjacent_post(false, '', true);
      $next = get_adjacent_post(false, '', false);
      $post = new JSON_API_Post($post);
      $response = array(
        'post' => $post
      );
      if ($previous) {
        $response['previous_url'] = get_permalink($previous->ID);
      }
      if ($next) {
        $response['next_url'] = get_permalink($next->ID);
      }
      return $response;
    } else {
      $ip_json_api->error("Not found.");
    }
  }

  public function get_page() {
    global $ip_json_api;
    extract($ip_json_api->query->get(array('id', 'slug', 'page_id', 'page_slug', 'children')));
    if ($id || $page_id) {
      if (!$id) {
        $id = $page_id;
      }
      $posts = $ip_json_api->introspector->get_posts(array(
        'page_id' => $id
      ));
    } else if ($slug || $page_slug) {
      if (!$slug) {
        $slug = $page_slug;
      }
      $posts = $ip_json_api->introspector->get_posts(array(
        'pagename' => $slug
      ));
    } else {
      $ip_json_api->error("Include 'id' or 'slug' var in your request.");
    }
    
    // Workaround for https://core.trac.wordpress.org/ticket/12647
    if (empty($posts)) {
      $url = $_SERVER['REQUEST_URI'];
      $parsed_url = parse_url($url);
      $path = $parsed_url['path'];
      if (preg_match('#^http://[^/]+(/.+)$#', get_bloginfo('url'), $matches)) {
        $blog_root = $matches[1];
        $path = preg_replace("#^$blog_root#", '', $path);
      }
      if (substr($path, 0, 1) == '/') {
        $path = substr($path, 1);
      }
      $posts = $ip_json_api->introspector->get_posts(array('pagename' => $path));
    }
    
    if (count($posts) == 1) {
      if (!empty($children)) {
        $ip_json_api->introspector->attach_child_posts($posts[0]);
      }
      return array(
        'page' => $posts[0]
      );
    } else {
      $ip_json_api->error("Not found.");
    }
  }
  
  public function get_date_posts() {
    global $ip_json_api;
    if ($ip_json_api->query->date) {
      $date = preg_replace('/\D/', '', $ip_json_api->query->date);
      if (!preg_match('/^\d{4}(\d{2})?(\d{2})?$/', $date)) {
        $ip_json_api->error("Specify a date var in one of 'YYYY' or 'YYYY-MM' or 'YYYY-MM-DD' formats.");
      }
      $request = array('year' => substr($date, 0, 4));
      if (strlen($date) > 4) {
        $request['monthnum'] = (int) substr($date, 4, 2);
      }
      if (strlen($date) > 6) {
        $request['day'] = (int) substr($date, 6, 2);
      }
      $posts = $ip_json_api->introspector->get_posts($request);
    } else {
      $ip_json_api->error("Include 'date' var in your request.");
    }
    return $this->posts_result($posts);
  }
  
  public function get_category_posts() {
    global $ip_json_api;
    $category = $ip_json_api->introspector->get_current_category();
    if (!$category) {
      $ip_json_api->error("Not found.");
    }
    $posts = $ip_json_api->introspector->get_posts(array(
      'cat' => $category->id
    ));
    return $this->posts_object_result($posts, $category);
  }
  
  public function get_tag_posts() {
    global $ip_json_api;
    $tag = $ip_json_api->introspector->get_current_tag();
    if (!$tag) {
      $ip_json_api->error("Not found.");
    }
    $posts = $ip_json_api->introspector->get_posts(array(
      'tag' => $tag->slug
    ));
    return $this->posts_object_result($posts, $tag);
  }
  
  public function get_author_posts() {
    global $ip_json_api;
    $author = $ip_json_api->introspector->get_current_author();
    if (!$author) {
      $ip_json_api->error("Not found.");
    }
    $posts = $ip_json_api->introspector->get_posts(array(
      'author' => $author->id
    ));
    return $this->posts_object_result($posts, $author);
  }
  
  public function get_search_results() {
    global $ip_json_api;
    if ($ip_json_api->query->search) {
      $posts = $ip_json_api->introspector->get_posts(array(
        's' => $ip_json_api->query->search
      ));
    } else {
      $ip_json_api->error("Include 'search' var in your request.");
    }
    return $this->posts_result($posts);
  }
  
  public function get_date_index() {
    global $ip_json_api;
    $permalinks = $ip_json_api->introspector->get_date_archive_permalinks();
    $tree = $ip_json_api->introspector->get_date_archive_tree($permalinks);
    return array(
      'permalinks' => $permalinks,
      'tree' => $tree
    );
  }
  
  public function get_category_index() {
    global $ip_json_api;
    $categories = $ip_json_api->introspector->get_categories();
    return array(
      'count' => count($categories),
      'categories' => $categories
    );
  }
  
  public function get_tag_index() {
    global $ip_json_api;
    $tags = $ip_json_api->introspector->get_tags();
    return array(
      'count' => count($tags),
      'tags' => $tags
    );
  }
  
  public function get_author_index() {
    global $ip_json_api;
    $authors = $ip_json_api->introspector->get_authors();
    return array(
      'count' => count($authors),
      'authors' => array_values($authors)
    );
  }
  
  public function ideapress_server_ping() {
      global $ip_json_api;
    
      $ip_core = FALSE;
      $ip_respond=FALSE;
      if ($ip_json_api->controller_is_active('ip_core')) 
      {
          $ip_core=TRUE;
      }
      if ($ip_json_api->controller_is_active('ip_respond')) 
      {
          $ip_respond=TRUE;
      }
    return array(
      'ip_core' => $ip_core,
      'ip_respond' => $ip_respond
    );
  }

  public function get_page_index() {
    global $ip_json_api;
    $pages = array();
    // Thanks to blinder for the fix!
    $numberposts = empty($ip_json_api->query->count) ? -1 : $ip_json_api->query->count;
    $wp_posts = get_posts(array(
      'post_type' => 'page',
      'post_parent' => 0,
      'order' => 'ASC',
      'orderby' => 'menu_order',
      'numberposts' => $numberposts
    ));
    foreach ($wp_posts as $wp_post) {
      $pages[] = new JSON_API_Post($wp_post);
    }
    foreach ($pages as $page) {
      $ip_json_api->introspector->attach_child_posts($page);
    }
    return array(
      'pages' => $pages
    );
  }
  
  public function get_nonce() {
    global $ip_json_api;
    extract($ip_json_api->query->get(array('controller', 'method')));
    if ($controller && $method) {
      $controller = strtolower($controller);
      if (!in_array($controller, $ip_json_api->get_controllers())) {
        $ip_json_api->error("Unknown controller '$controller'.");
      }
      require_once $ip_json_api->controller_path($controller);
      if (!method_exists($ip_json_api->controller_class($controller), $method)) {
        $ip_json_api->error("Unknown method '$method'.");
      }
      $nonce_id = $ip_json_api->get_nonce_id($controller, $method);
      return array(
        'controller' => $controller,
        'method' => $method,
        'nonce' => wp_create_nonce($nonce_id)
      );
    } else {
      $ip_json_api->error("Include 'controller' and 'method' vars in your request.");
    }
  }
  
  protected function get_object_posts($object, $id_var, $slug_var) {
    global $ip_json_api;
    $object_id = "{$type}_id";
    $object_slug = "{$type}_slug";
    extract($ip_json_api->query->get(array('id', 'slug', $object_id, $object_slug)));
    if ($id || $$object_id) {
      if (!$id) {
        $id = $$object_id;
      }
      $posts = $ip_json_api->introspector->get_posts(array(
        $id_var => $id
      ));
    } else if ($slug || $$object_slug) {
      if (!$slug) {
        $slug = $$object_slug;
      }
      $posts = $ip_json_api->introspector->get_posts(array(
        $slug_var => $slug
      ));
    } else {
      $ip_json_api->error("No $type specified. Include 'id' or 'slug' var in your request.");
    }
    return $posts;
  }
  
  protected function posts_result($posts) {
    global $wp_query;
    return array(
      'count' => count($posts),
      'count_total' => (int) $wp_query->found_posts,
      'pages' => $wp_query->max_num_pages,
      'posts' => $posts
    );
  }
  
  protected function posts_object_result($posts, $object) {
    global $wp_query;
    // Convert something like "JSON_API_Category" into "category"
    $object_key = strtolower(substr(get_class($object), 9));
    return array(
      'count' => count($posts),
      'pages' => (int) $wp_query->max_num_pages,
      $object_key => $object,
      'posts' => $posts
    );
  }
  
}

?>
