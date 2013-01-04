<?php

function ally_setup() {

  add_editor_style();

  add_theme_support('automatic-feed-links');

  register_nav_menu('primary', __('Primary Menu', 'Ally'));

}

add_action( 'after_setup_theme', 'ally_setup' );

class Walker_Nav_Menu_CMS extends Walker_Nav_Menu {
  function start_el(&$output, $item, $depth, $args) {
    global $wp_query;
    $indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';
    $class_names = $value = '';
    
    // If the item has children, add the dropdown class for foundation
    if ( $args->has_children ) {
      $class_names = "has-dropdown ";
    }
    
    $classes = empty( $item->classes ) ? array() : (array) $item->classes;
    $class_names .= "";
    $class_names = ' class="'. esc_attr( $class_names ) . '"';
    $output .= $indent . '<li id="menu-item-'. $item->ID . '"' . $value . $class_names .'>';
    $attributes  = ! empty( $item->attr_title ) ? ' title="'  . esc_attr( $item->attr_title ) .'"' : '';
    $attributes .= ! empty( $item->target )     ? ' target="' . esc_attr( $item->target     ) .'"' : '';
    $attributes .= ! empty( $item->xfn )        ? ' rel="'    . esc_attr( $item->xfn        ) .'"' : '';
    $attributes .= ! empty( $item->url )        ? ' href="'   . esc_attr( $item->url        ) .'"' : '';
    // if the item has children add these two attributes to the anchor tag
    // if ( $args->has_children ) {
    //     $attributes .= 'class="dropdown-toggle" data-toggle="dropdown"';
    // }

    $item_output = $args->before;
    $item_output .= '<a'. $attributes .'>';
    $item_output .= $args->link_before .apply_filters( 'the_title', $item->title, $item->ID );
    $item_output .= $args->link_after;
    // if the item has children add the caret just before closing the anchor tag
    if ( $args->has_children ) {
      $item_output .= '</a><a href="#" class="dropdown-toggle"><span> </span></a>';
    }
    else {
      $item_output .= '</a>';
    }
    $item_output .= $args->after;
    $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
    }
            
  function start_lvl(&$output, $depth) {
    $indent = str_repeat("\t", $depth);
    $output .= "\n$indent<ul class=\"dropdown\">\n";
  }
            
  function display_element( $element, &$children_elements, $max_depth, $depth=0, $args, &$output ) {
    $id_field = $this->db_fields['id'];
    if ( is_object( $args[0] ) ) {
        $args[0]->has_children = ! empty( $children_elements[$element->$id_field] );
    }
    return parent::display_element( $element, $children_elements, $max_depth, $depth, $args, $output );
  }       
}

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

function add_custom_taxonomies() {
  // Add new "Locations" taxonomy to Posts
  register_taxonomy('meta_info', 'post', array(
    // Hierarchical taxonomy (like categories)
    'hierarchical' => true,
    // This array of options controls the labels displayed in the WordPress Admin UI
    'labels' => array(
      'name' => _x('Meta Information', 'taxonomy general name'),
      'singular_name' => _x('Meta Information', 'taxonomy singular name'),
      'search_items' =>  __('Search Meta Information'),
      'all_items' => __('All Meta Information'),
      'edit_item' => __('Edit Meta Information'),
      'update_item' => __('Update Meta Information'),
      'add_new_item' => __('Add New Meta Information'),
      'new_item_name' => __('New Meta Information Name'),
      'menu_name' => __('Meta Information'),
   ),
    // Control the slugs used for this taxonomy
    'rewrite' => array(
      'slug' => 'meta', // This controls the base slug that will display before each term
      'with_front' => false, // Don't display the category base before "/locations/"
      'hierarchical' => true // This will allow URL's like "/locations/boston/cambridge/"
    ),
  ));
}
add_action( 'init', 'add_custom_taxonomies');

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
  register_taxonomy_for_object_type('meta_info', 'external_tool');
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
      'supports' => array('title', 'editor', 'excerpt', 'thumbnail', 'post-formats', 'revisions', 'meta_info'),
      'rewrite' => array('slug' => 'tool','with_front' => false)  
    )  
  );

  // connect wp_tool to category taxonomy
  register_taxonomy_for_object_type('meta_info', 'wp_tool');
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
      'supports' => array( 'title', 'excerpt', 'thumbnail', 'revisions'),
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

function the_post_thumbnail_caption() {
  global $post;
  $thumbnail_id = get_post_thumbnail_id($post->ID);
  $thumbnail_image = get_posts(array('p' => $thumbnail_id, 'post_type' => 'attachment'));
  if ($thumbnail_image && isset($thumbnail_image[0])) {
    echo '<span>'.$thumbnail_image[0]->post_excerpt.'</span>';
  }
}

// Broken: The function dont_publish does not work correctly. If you try to remove "featured" selection from category, it still gives error message.

// function dont_publish() {
//   if (in_category(12)){
//     if (!has_post_thumbnail()) {
//       wp_die('A post with the category "featured" must have a featured image. Please add a featured image or remove the category "featured".');
//     }
//   }
// }

// add_action( 'pre_post_update', 'dont_publish' );
  
function get_slug(){
  $url = $_SERVER["REQUEST_URI"];
  $url_explode= explode('/', $url);
  $slug = $url_explode[sizeof($url_explode)-2];
  return($slug);
}

function extra_contact_info($contactmethods) {
  unset($contactmethods['aim']);
  unset($contactmethods['yim']);
  unset($contactmethods['jabber']);
  $contactmethods['phone'] = 'Phone';
  $contactmethods['twitter'] = 'Twitter';
  return $contactmethods;
}

add_filter('user_contactmethods', 'extra_contact_info');

// Enter the id of a category in the meta_info custom taxonomy. Returns true if a post has a particular piece of meta_info.
function list_of_meta_tags(){
  $list = array();
  $num = 0;
  $termsObjects = get_the_terms($post->ID, 'meta_info', '');
  foreach ($termsObjects as $v) {
    $list[$num] = $v->slug;
    $num++;
  }
  return($list);
}

function my_admin_notice(){
  global $pagenow;
  $meta_tags = list_of_meta_tags('featured');
  if ( $pagenow == 'post.php' ) {
    if (!in_array('featured', $meta_tags)){
      if (!has_post_thumbnail()) {
      echo '<div class="error">
         <p>A post with the category "featured" must have a featured image. Please add a featured image or remove the category "featured".</p>
      </div>';
      }
    }
  }
}

add_action('admin_notices', 'my_admin_notice');

?>