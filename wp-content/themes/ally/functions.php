<?php

// http://wordpress.stackexchange.com/questions/3801/add-custom-fields-to-custom-post-type-rss
function add_custom_fields_to_rss() {
  global $post;
  $post_url = get_post_meta($post->ID, '_url_name', true);
  $post_source = get_post_meta($post->ID, '_source_name', true);              
      if(get_post_type() == 'external_post') {
      ?>
        <source url="<?php echo $post_url ?>"><?php echo $post_source ?></source>        
        <tags><?php
            $posttags = get_the_tags();
            $tag_list = array();
              if ($posttags) {
                foreach($posttags as $tag) {
                  array_push($tag_list, $tag->name);
                }
              }
              echo implode(', ', $tag_list);
          ?></tags>
      <?php
  }
}
add_action('rss2_item', 'add_custom_fields_to_rss');

//Loads css file into text editor in WP-Admin
add_editor_style('editor-style.css');

function disqus_embed($disqus_shortname) {
    global $post;
    wp_enqueue_script('disqus_embed','http://'.$disqus_shortname.'.disqus.com/embed.js');
    echo '<div id="disqus_thread"></div>
    <script type="text/javascript">
        var disqus_shortname = "'.$disqus_shortname.'";
        var disqus_title = "'.$post->post_title.'";
        var disqus_url = "'.get_permalink($post->ID).'";
        var disqus_identifier = "'.$disqus_shortname.'-'.$post->ID.'";
    </script>';
}

function ally_setup() {

  add_editor_style();

  add_theme_support('automatic-feed-links');

  register_nav_menu('primary', __('Primary Menu', 'Ally'));

  // Enables Featured Images
  add_theme_support('post-thumbnails'); 

}

add_action( 'after_setup_theme', 'ally_setup' );


//http://developer.wordpress.com/2012/05/14/querying-posts-without-query_posts/
/**
 * Filter the home page posts, and remove any featured post ID's from it. Hooked
 * onto the 'pre_get_posts' action, this changes the parameters of the query
 * before it gets any posts.
 * 
 * @global array $featured_post_id
 * @param WP_Query $query
 * @return WP_Query Possibly modified WP_query
 */
function modify_home_feed_posts_query($query = false) {
  // Bail if not home, not a query, not main query, or no featured posts
  if (!is_home() || !is_a($query, 'WP_Query' ) || !$query->is_main_query() || !get_home_featured_posts_ids())
    return;

  // Exclude featured posts from the main query
  $query->set('post__not_in', get_home_featured_posts_ids());
  $query->set('post_type', array('post', 'wp_tool', 'external_tool'));

  // Note the we aren't returning anything.
  // 'pre_get_posts' is a byref action; we're modifying the query directly.
}
add_action('pre_get_posts', 'modify_home_feed_posts_query');

/**
 * Test to see if any posts meet our conditions for featuring posts.
 *
 * We store the results of the loop in a transient, to prevent running this
 * extra query on every page load. The results are an array of post ID's that
 * match the result above. This gives us a quick way to loop through featured
 * posts again later without needing to query additional times later.
 */
function get_home_featured_posts_ids() {
  if (false === ($featured_post_ids = get_transient('featured_post_ids'))) {
    $featured_args = array(
      'orderby' => 'date', 
      'order' => 'DESC',
      'posts_per_page' => 5,
      'meta_info' => 'featured',
      'meta_key' => '_thumbnail_id',
      'post_status' => 'publish',
      'post_type' => array('post', 'external_tool', 'wp_tool')
    );

    // The Featured Posts query.
    $featured = new WP_Query($featured_args);

    // Proceed only if published posts with thumbnails exist
    if ($featured->have_posts()) {
      while ( $featured->have_posts()) {
        $featured->the_post();
        if ( has_post_thumbnail( $featured->post->ID ) ) {
          $featured_post_ids[] = $featured->post->ID;
        }
      }

      set_transient('featured_post_ids', $featured_post_ids);
    }
  }

  return $featured_post_ids;
}

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

// Create companion_meta Custom Field
function companion_meta() {
  add_meta_box('companion_meta', 'Companion', 'companion_callback', 'people_project', 'normal', 'high');
}

function companion_callback( $post ) {
  $companion_name = get_post_meta($post->ID, '_companion_name', true);
  $companion_name_field_names = array("Author", "Tag", "Category");
  echo 'What type of Person or Project is this?<br><select name="companion_name"><option value=""></option>';
  foreach ($companion_name_field_names as $key => $companion) {
      echo '<option value="'. $companion . '"';
      if ($companion == $companion_name) echo ' selected="selected"';
      echo '>'. $companion .'</option>';
  }
  echo '</select>';
}

add_action('add_meta_boxes', 'companion_meta');

// Save contents of companion_meta Custom Field
function companion_save($post_ID) {
  global $post;
  
  if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) {
        return $post_id;
  }

  if (isset($_POST)) {
    update_post_meta($post_ID, '_companion_name', strip_tags($_POST['companion_name']));
  }
}

add_action( 'save_post', 'companion_save' );

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
           
  if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) {
        return $post_id;
  }

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
  echo 'What is the URL? <em>http://citypaper.net/storyname/storyname/</em><br><input type="text" name="url_name" style="width: 100%" value="'.$url_name.'"/>';
}

add_action('add_meta_boxes', 'url_meta');

// Save contents of url_meta Custom Field
function url_save($post_ID) {
  global $post;

  if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) {
        return $post_id;
  }
           
  if (isset($_POST)) {
    update_post_meta( $post_ID, '_url_name', strip_tags( $_POST['url_name'] ) );
  }
}      

add_action('save_post', 'url_save');

// Create pull_quote Custom Field
function pull_quote_meta() {
  add_meta_box('pull_quote_meta', 'Pull Quote', 'pull_quote_callback', 'discussion', 'normal', 'high');
}

function pull_quote_callback($post) {
  $pull_quote_name = get_post_meta($post->ID, '_pull_quote_name', true);
  echo 'Copy and paste the pull quote (aka featured comment) for the story. Please do not include quotation marks.<br><input type="text" name="pull_quote_name" style="width: 100%" value="'.$pull_quote_name.'"/>';
}

add_action('add_meta_boxes', 'pull_quote_meta');

// Save contents of pull_quote Custom Field
function pull_quote_save($post_ID) {
  global $post;

  if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) {
        return $post_id;
  }
           
  if (isset($_POST)) {
    update_post_meta( $post_ID, '_pull_quote_name', strip_tags( $_POST['pull_quote_name'] ) );
  }
}      

add_action('save_post', 'pull_quote_save');

// Create pull_quote_url Custom Field
function pull_quote_url_meta() {
  add_meta_box('pull_quote_url_meta', 'Pull Quote URL', 'pull_quote_url_callback', 'discussion', 'normal', 'high');
}

function pull_quote_url_callback($post) {
  $pull_quote_url_name = get_post_meta($post->ID, '_pull_quote_url_name', true);
  echo 'What is URL for the pull quote comment?<br><input type="text" name="pull_quote_url_name" style="width: 100%" value="'.$pull_quote_url_name.'"/>';
}

add_action('add_meta_boxes', 'pull_quote_url_meta');

// Save contents of pull_quote_url Custom Field
function pull_quote_url_save($post_ID) {
  global $post;

  if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) {
        return $post_id;
  }
           
  if (isset($_POST)) {
    update_post_meta( $post_ID, '_pull_quote_url_name', strip_tags( $_POST['pull_quote_url_name'] ) );
  }
}      

add_action('save_post', 'pull_quote_url_save');

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

  register_taxonomy('column_info', 'post', array(
    // Hierarchical taxonomy (like categories)
    'hierarchical' => true,
    // This array of options controls the labels displayed in the WordPress Admin UI
    'labels' => array(
      'name' => _x('Column Information', 'taxonomy general name'),
      'singular_name' => _x('Column Information', 'taxonomy singular name'),
      'search_items' =>  __('Search Column Information'),
      'all_items' => __('All Column Information'),
      'edit_item' => __('Edit Column Information'),
      'update_item' => __('Update Column Information'),
      'add_new_item' => __('Add New Column Information'),
      'new_item_name' => __('New Column Information Name'),
      'menu_name' => __('Column Information'),
   ),
    // Control the slugs used for this taxonomy
    'rewrite' => array(
      'slug' => 'commentary', // This controls the base slug that will display before each term
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
  register_taxonomy_for_object_type('category', 'external_post');
  register_taxonomy_for_object_type('post_tag', 'external_post');


  // register wp_tool as a Custom Post Type
  register_post_type('wp_tool',
    array(  
      'labels' => array(  
        'name' => __('WordPress Tools'),  
        'singular_name' => __('WordPress Tool')  
      ),  
      'public' => true,  
      'menu_position' => 5,  
      'supports' => array('title', 'author', 'editor', 'excerpt', 'thumbnail', 'post-formats', 'revisions', 'meta_info'),
      'rewrite' => array('slug' => 'tool','with_front' => false)  
    )  
  );

  // connect wp_tool to category taxonomy
  register_taxonomy_for_object_type('meta_info', 'wp_tool');
  register_taxonomy_for_object_type('category', 'wp_tool');


  // reregister default post so we can set a custom slug
  register_post_type('post', array(
      'labels' => array(
          'name_admin_bar' => _x('Post', 'add new on admin bar' ),
      ),
      'public'  => true,
      '_builtin' => false, 
      '_edit_link' => 'post.php?post=%d', 
      'capability_type' => 'post',
      'map_meta_cap' => true,
      'show_in_menu' => false,
      'hierarchical' => false,
      'rewrite' => array('slug' => 'article'),
      'query_var' => false,
      'supports' => array('title', 'editor', 'author', 'thumbnail', 'excerpt', 'trackbacks', 'custom-fields', 'comments', 'revisions', 'post-formats', 'column_info'),
  )); 

  // register external_tool as a Custom Post Type
  register_post_type('external_tool',
    array(
      'labels' => array(  
          'name' => __('External Tools'),  
          'singular_name' => __('External Tool')  
      ),  
      'public' => true,  
      'menu_position' => 5,  
      'supports' => array('title', 'author', 'excerpt', 'thumbnail', 'revisions', 'meta_info'),
      'rewrite' => array('slug' => 'special','with_front' => false)  
    )  
  );  

  // connect external_tool to category taxonomy
  register_taxonomy_for_object_type('category', 'external_tool');
  register_taxonomy_for_object_type('meta_info', 'external_tool');


  // register city_journal as a Custom Post Type
  register_post_type('city_journal',
    array(
      'labels' => array(  
          'name' => __('CityJournal Entry'),
          'singular_name' => __('CityJournal Entry')
      ),  
      'public' => true,  
      'menu_position' => 5,  
      'supports' => array('title', 'editor', 'author', 'excerpt', 'thumbnail', 'revisions', 'meta_info'),
      'rewrite' => array('slug' => 'cityjournal','with_front' => false)      
    )  
  );  

  // connect city_journal to category taxonomy
  register_taxonomy_for_object_type('category', 'city_journal');
  register_taxonomy_for_object_type('meta_info', 'city_journal');  


  // register people_project as a Custom Post Type
  register_post_type('people_project',
    array(
      'labels' => array(  
          'name' => __('People & Projects'),
          'singular_name' => __('People & Project')
      ),  
      'public' => true,  
      'menu_position' => 5,  
      'supports' => array('title', 'excerpt', 'thumbnail', 'meta_info'),
    )  
  );  

  // connect people_project to category taxonomy
  register_taxonomy_for_object_type('meta_info', 'people_project');  


  register_post_type('discussion',
    array(  
      'labels' => array(  
        'name' => __('Discussions'),  
        'singular_name' => __('Discussion')  
      ),  
      'public' => true,  
      'menu_position' => 5,  
      'supports' => array('title', 'editor', 'excerpt', 'thumbnail', 'post-formats', 'revisions', 'meta_info', 'comments'),
      'rewrite' => array('slug' => 'discussion','with_front' => false)  
    )  
  );

  // connect discussion to category taxonomy
  register_taxonomy_for_object_type('meta_info', 'discussion');
  register_taxonomy_for_object_type('category', 'discussion');

}  

add_action('init', 'create_post_type'); 

add_post_type_support('external_post', 'source_meta');

// Prevents HTML from being stripped for category description
// http://wcdocs.woothemes.com/snippets/allow-html-in-term-category-tag-descriptions/
foreach (array('pre_term_description') as $filter) {
  remove_filter($filter, 'wp_filter_kses');
}
 
foreach (array('term_description') as $filter) {
  remove_filter($filter, 'wp_kses_data');
}
  
function get_slug(){
  $url = $_SERVER["REQUEST_URI"];
  $url_explode= explode('/', $url);
  $slug = $url_explode[sizeof($url_explode)-2];
  return($slug);
}

function get_author(){
  $url = $_SERVER["REQUEST_URI"];
  $url_explode= explode('/', $url);
  $slug = $url_explode[sizeof($url_explode)-3];
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

function my_admin_notice(){
  global $pagenow;
  $meta_tags = get_post_meta_tags('featured');
  $post_type = get_post_type( $post->ID );
  if (($pagenow == 'post.php')&&(in_array('featured', $meta_tags))&&(!has_post_thumbnail())&&(($post_type!="discussion"))) {
      echo '<div class="error">
         <p>A post with the category "featured" must have a featured image. Please add a featured image or remove the category "featured".</p>
      </div>';
  }
}

add_action('admin_notices', 'my_admin_notice');

// Crops medium image in the same way that thumbnails are cropped. Maintains a fixed height and width across all medium images.

if(false === get_option("medium_crop")) {
    add_option("medium_crop", "1");
} else {
    update_option("medium_crop", "1");
}

// TEMPLATE HELPER FUNCTIONS

// Returns a list of meta tags for a post
function get_post_meta_tags() {
  global $post;
  $list = array();
  $num = 0;
  $termsObjects = get_the_terms('', 'meta_info', '');
  if (!empty($termsObjects)){
    foreach ($termsObjects as $v) {
      $list[$num] = $v->slug;
      $num++;
    }
  }
  return($list);
}

// Returns a list of meta tags for a post
function get_column() {
  global $post;
  $list = array();
  $num = 0;
  $termsObjects = get_the_terms('', 'column_info', '');
  if (!empty($termsObjects)){
    foreach ($termsObjects as $v) {
      $list[$num] = $v->slug;
      $num++;
    }
  }
  return($list);
}

// used to pull project category for homepage, search results, and other category symbology
function list_categories() {
  $project_parent_category = get_category_by_slug('project');
  $project_parent_category_id=$project_parent_category->term_id;
  $categories=get_the_category();
  $count = 0;
  foreach($categories as $category){
    if($category->category_parent==$project_parent_category_id){
      if ($count == 0) {
        echo '<div class="category-symbology">';

        if(get_post_type(get_the_id()) == 'discussion') { echo '<i class="social-foundicon-chat"> </i> '; }
      }
      echo "<a class='one-category' href='/project/". $category->slug . "'>" . $category->name . "</a>";
      if ($count == 0) {
        echo '</div>';
      }  
      $count++;
    }
  }

  //emit a chat symbol for discussion
  if($count == 0 && get_post_type(get_the_id()) == 'discussion') {
    generate_discussion_w_no_category();
  }
}

// emit a discussion symbol for discussions not in a category
function generate_discussion_w_no_category() {
  echo '<div class="category-symbology"><i class="social-foundicon-chat"> </i> Discussion</div>';
}

// Checks whether or not a post is part of a project
function in_project($post_id) {
  $categories = get_the_category($post_id);
  $project = get_category_by_slug('project');

  foreach($categories as $category) {
    if($category->category_parent == $project->term_id) {
      return TRUE;
    }
  }

  return FALSE;
}

// Echo's out a video or image for a post
function get_media($post_id, $size) {
  if(has_post_video($post_id)) {
    if ($size=="large")
      the_post_video(array(676, 429));
    else
      the_post_video();
  } elseif (has_post_thumbnail($post_id)) {
    $img_id = get_post_thumbnail_id($post_id);
    $image = wp_get_attachment_image_src($img_id, $size);
    $alt = get_post_meta($img_id, '_wp_attachment_image_alt', true);
    echo '<img src="'. $image[0] . '" alt="' . $alt . '">';
  }
}

/**
 * Add a "Google Plus" field to Co-Authors Plus Guest Author
 */
add_filter( 'coauthors_guest_author_fields', 'capx_filter_guest_author_fields', 10, 2 );

function capx_filter_guest_author_fields( $fields_to_return, $groups ) {
  if ( in_array( 'all', $groups ) || in_array( 'contact-info', $groups ) ) {
    $fields_to_return[] = array(
          'key'      => 'twitter',
          'label'    => 'Twitter',
          'group'    => 'contact-info',
    );
  }  
  if ( in_array( 'all', $groups ) || in_array( 'contact-info', $groups ) ) {
    $fields_to_return[] = array(
          'key'      => 'phone',
          'label'    => 'Phone',
          'group'    => 'contact-info',
    );
  }  
  return $fields_to_return;
}

?>