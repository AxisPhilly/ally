<?php

function dimox_breadcrumbs() {
  $showOnHome = 0; // 1 - show breadcrumbs on the homepage, 0 - don't show
  $delimiter = '&raquo;'; // delimiter between crumbs
  $home = 'Home'; // text for the 'Home' link
  $showCurrent = 1; // 1 - show current post/page title in breadcrumbs, 0 - don't show
  $before = '<span class="current">'; // tag before the current crumb
  $after = '</span>'; // tag after the current crumb
 
  global $post;
  $homeLink = get_bloginfo('url');
 
  if (is_home() || is_front_page()) {
 
    if ($showOnHome == 1) echo '<div id="crumbs"><a href="' . $homeLink . '">' . $home . '</a></div>';
 
  } else {
 
    echo '<div id="crumbs"><a href="' . $homeLink . '">' . $home . '</a> ' . $delimiter . ' ';
 
    if ( is_category() ) {
      $thisCat = get_category(get_query_var('cat'), false);
      if ($thisCat->parent != 0) echo get_category_parents($thisCat->parent, TRUE, ' ' . $delimiter . ' ');
      echo $before . 'Archive by category "' . single_cat_title('', false) . '"' . $after;
 
    } elseif ( is_search() ) {
      echo $before . 'Search results for "' . get_search_query() . '"' . $after;
 
    } elseif ( is_day() ) {
      echo '<a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a> ' . $delimiter . ' ';
      echo '<a href="' . get_month_link(get_the_time('Y'),get_the_time('m')) . '">' . get_the_time('F') . '</a> ' . $delimiter . ' ';
      echo $before . get_the_time('d') . $after;
 
    } elseif ( is_month() ) {
      echo '<a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a> ' . $delimiter . ' ';
      echo $before . get_the_time('F') . $after;
 
    } elseif ( is_year() ) {
      echo $before . get_the_time('Y') . $after;
 
    } elseif ( is_single() && !is_attachment() ) {
      if ( get_post_type() != 'post' ) {
        $post_type = get_post_type_object(get_post_type());
        $slug = $post_type->rewrite;
        echo '<a href="' . $homeLink . '/' . $slug['slug'] . '/">' . $post_type->labels->singular_name . '</a>';
        if ($showCurrent == 1) echo ' ' . $delimiter . ' ' . $before . get_the_title() . $after;
      } else {
        $cat = get_the_category(); $cat = $cat[0];
        $cats = get_category_parents($cat, TRUE, ' ' . $delimiter . ' ');
        if ($showCurrent == 0) $cats = preg_replace("#^(.+)\s$delimiter\s$#", "$1", $cats);
        echo $cats;
        if ($showCurrent == 1) echo $before . get_the_title() . $after;
      }
 
    } elseif ( !is_single() && !is_page() && get_post_type() != 'post' && !is_404() ) {
      $post_type = get_post_type_object(get_post_type());
      echo $before . $post_type->labels->singular_name . $after;
 
    } elseif ( is_attachment() ) {
      $parent = get_post($post->post_parent);
      $cat = get_the_category($parent->ID); $cat = $cat[0];
      echo get_category_parents($cat, TRUE, ' ' . $delimiter . ' ');
      echo '<a href="' . get_permalink($parent) . '">' . $parent->post_title . '</a>';
      if ($showCurrent == 1) echo ' ' . $delimiter . ' ' . $before . get_the_title() . $after;
 
    } elseif ( is_page() && !$post->post_parent ) {
      if ($showCurrent == 1) echo $before . get_the_title() . $after;
 
    } elseif ( is_page() && $post->post_parent ) {
      $parent_id  = $post->post_parent;
      $breadcrumbs = array();
      while ($parent_id) {
        $page = get_page($parent_id);
        $breadcrumbs[] = '<a href="' . get_permalink($page->ID) . '">' . get_the_title($page->ID) . '</a>';
        $parent_id  = $page->post_parent;
      }
      $breadcrumbs = array_reverse($breadcrumbs);
      for ($i = 0; $i < count($breadcrumbs); $i++) {
        echo $breadcrumbs[$i];
        if ($i != count($breadcrumbs)-1) echo ' ' . $delimiter . ' ';
      }
      if ($showCurrent == 1) echo ' ' . $delimiter . ' ' . $before . get_the_title() . $after;
 
    } elseif ( is_tag() ) {
      echo $before . 'Posts tagged "' . single_tag_title('', false) . '"' . $after;
 
    } elseif ( is_author() ) {
       global $author;
      $userdata = get_userdata($author);
      echo $before . 'Articles posted by ' . $userdata->display_name . $after;
 
    } elseif ( is_404() ) {
      echo $before . 'Error 404' . $after;
    }
 
    if ( get_query_var('paged') ) {
      if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() ) echo ' (';
      echo __('Page') . ' ' . get_query_var('paged');
      if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() ) echo ')';
    }
 
    echo '</div>';
 
  }
} // end dimox_breadcrumbs()


// Create source_meta Custom Field
function source_meta() {
  add_meta_box('source_meta', 'Source', 'source_callback', 'external_post', 'normal', 'high');
}

function source_callback( $post ) {
  $source_name = get_post_meta($post->ID, '_source_name', true);
  echo 'What is the name of the publication that created this story? <em>ex: Philadelphia City Paper</em><br><input type="text" name="source_name" style="width: 100%" value="'.$source_name.'"/>';
}

add_action('add_meta_boxes', 'source_meta');

// Save contents of source_meta Custom Field
function source_save($post_ID) {
  global $post;
           
  if (isset($_POST)) {
    update_post_meta($post_ID, '_source_name', strip_tags($_POST['source_name']));
  }
}

add_action( 'save_post', 'source_save' );

// Create url_meta Custom Field
function url_meta() {
  add_meta_box('url_meta', 'URL', 'url_callback', 'external_post', 'normal', 'high');
  add_meta_box('url_meta', 'URL', 'url_callback', 'external_tool', 'normal', 'high');
}

function url_callback($post) {
  $url_name = get_post_meta($post->ID, '_url_name', true);
  echo 'What is the URL for the story? <em>http://citypaper.net/storyname/storyname/</em><br><input type="text" name="url_name" style="width: 100%" value="'.$url_name.'"/>';
}

add_action('add_meta_boxes', 'url_meta');

// Save contents of url_meta Custom Field
function url_save($post_ID) {
  global $post;
           
  if (isset($_POST)) {
    update_post_meta( $post_ID, '_url_name', strip_tags( $_POST['url_name'] ) );
  }
}      

add_action('save_post', 'url_save');

// Enables Featured Images
add_theme_support('post-thumbnails'); 

// http://wordpress.stackexchange.com/questions/15376/how-to-set-default-screen-options
// add_action('user_register', 'set_user_metaboxes');
function set_user_metaboxes($user_id=NULL) {
  // These are the metakeys we will need to update
  $meta_key['order'] = 'meta-box-order_post';
  $meta_key['hidden'] = 'metaboxhidden_post';

  // So this can be used without hooking into user_register
  if (!$user_id) {
    $user_id = get_current_user_id();
  }

  // Set the default order if it has not been set yet
  if (!get_user_meta( $user_id, $meta_key['order'], true)) {
    $meta_value = array(
      'side' => 'submitdiv,formatdiv,categorydiv,tagsdiv-post_tag,postimagediv',
      'normal' => 'postexcerpt,source_meta,url_meta,postcustom,commentstatusdiv,commentsdiv,trackbacksdiv,slugdiv,authordiv,revisionsdiv',
      'advanced' => '',
    );
    
    update_user_meta( $user_id, $meta_key['order'], $meta_value );
  }

  // Set the default hiddens if it has not been set yet
  if (!get_user_meta( $user_id, $meta_key['hidden'], true)) {
    $meta_value = array('postcustom','trackbacksdiv','commentstatusdiv','commentsdiv','slugdiv','revisionsdiv');
    update_user_meta( $user_id, $meta_key['hidden'], $meta_value );
  }
}

add_action('admin_init', 'set_user_metaboxes');

// http://wp.tutsplus.com/tutorials/theme-development/innovative-uses-of-wordpress-post-types-and-taxonomies/
// Creates "External Post" Post Type
function create_post_type() {  
  // register external_post as a Custom Post Type
  register_post_type( 'external_post',  
    array(  
      'labels' => array(  
        'name' => __('External Posts'),  
        'singular_name' => __('External Post')  
      ),
      'public' => true,
      'menu_position' => 5,
      'supports' => array('title', 'excerpt'),
      'rewrite' => array('slug' => 'external','with_front' => false)  
    )  
  );  

  // connect external_post to category taxonomy
  register_taxonomy_for_object_type('category', 'external_post');

  // register wp_tool as a Custom Post Type
  register_post_type('wp_tool',
    array(  
      'labels' => array(  
        'name' => __('WordPress Tools'),  
        'singular_name' => __('WordPress Tool')  
      ),  
      'public' => true,  
      'menu_position' => 5,  
      'supports' => array('title', 'editor', 'excerpt', 'thumbnail'),
      'rewrite' => array('slug' => 'tool','with_front' => false)  
    )  
  );

  // connect wp_tool to category taxonomy
  register_taxonomy_for_object_type('category', 'wp_tool');

  // register external_tool as a Custom Post Type
  register_post_type('external_tool',
    array(
      'labels' => array(  
          'name' => __('External Tools'),  
          'singular_name' => __('External Tool')  
      ),  
      'public' => true,  
      'menu_position' => 5,  
      'supports' => array( 'title', 'excerpt', 'thumbnail'),
      'rewrite' => array('slug' => 'special','with_front' => false)  
    )  
  );  

  // connect external_tool to category taxonomy
  register_taxonomy_for_object_type('category', 'external_tool');
}  

add_action('init', 'create_post_type'); 

// http://wordpress.stackexchange.com/questions/9211/changing-admin-menu-labels
function change_post_menu_label() {
  global $menu;
  global $submenu;
  $menu[5][0] = 'Original Reporting';
  $submenu['edit.php'][5][0] = 'Original Reporting';
  echo '';
}

add_action('admin_menu', 'change_post_menu_label');

add_post_type_support('external_post', 'source_meta');

// Prevents HTML from being stripped for category description
// http://wcdocs.woothemes.com/snippets/allow-html-in-term-category-tag-descriptions/
foreach (array('pre_term_description') as $filter) {
  remove_filter($filter, 'wp_filter_kses');
}
 
foreach (array('term_description') as $filter) {
  remove_filter($filter, 'wp_kses_data');
}

function check_my_custom_post_thumbnail($check, $post_id, $post_data) {
  if ( $post_id <= 0 ) {
    return false;
  }

  $categories = array(); //categories set by editor
  
  if (is_array($post_data)) {
    $categories = $post_data['post_category'];
  } elseif (is_object($post_data)) {
    $categories = $post_data->post_category;
  }

  $categories_requiring_image = array(12); //array containing IDs of the categories for which you want to require a featured image

  if (is_array($categories) && count(array_intersect($categories_requiring_image, array_values($categories)))) {
    $image = get_the_image( array(
            'post_id' => $post_id,
            'attachment' => false,
            'echo' => false,
            ) );
    return !empty($image);
  } else {
    return true;
  }
}

//TODO: What's the crazy wypiekacz mean?
add_filter('wypiekacz_check_thumbnail', 'check_my_custom_post_thumbnail', 10, 3);

?>