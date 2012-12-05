<?php

// Create source_meta Custom Field

add_action( 'add_meta_boxes', 'source_meta' );
        function source_meta() {
                add_meta_box( 'source_meta', 'Source', 'source_callback', 'external_post', 'normal', 'high' );
                }

        function source_callback( $post ) {
            $source_name = get_post_meta( $post->ID, '_source_name', true);
            echo 'What is the name of the publication that created this story? <em>ex: Philadelphia City Paper</em><br><input type="text" name="source_name" style="width: 100%" value="'.$source_name.'"/>';
            }

// Save contents of source_meta Custom Field

add_action( 'save_post', 'source_save' );
        function source_save( $post_ID ) {
            global $post;
           
            if (isset( $_POST ) ) {
                update_post_meta( $post_ID, '_source_name', strip_tags( $_POST['source_name'] ) );
            
        }
        }      


// Create url_meta Custom Field

add_action( 'add_meta_boxes', 'url_meta' );
        function url_meta() {
                add_meta_box( 'url_meta', 'URL', 'url_callback', 'external_post', 'normal', 'high' );
                add_meta_box( 'url_meta', 'URL', 'url_callback', 'external_tool', 'normal', 'high' );
                }

        function url_callback( $post ) {
            $url_name = get_post_meta( $post->ID, '_url_name', true);
            echo 'What is the URL for the story? <em>http://citypaper.net/storyname/storyname/</em><br><input type="text" name="url_name" style="width: 100%" value="'.$url_name.'"/>';
            }

// Save contents of url_meta Custom Field

add_action( 'save_post', 'url_save' );
        function url_save( $post_ID ) {
            global $post;
           
            if (isset( $_POST ) ) {
                update_post_meta( $post_ID, '_url_name', strip_tags( $_POST['url_name'] ) );
            
        }
        }      


// Enables Featured Images

add_theme_support( 'post-thumbnails' ); 

// http://wordpress.stackexchange.com/questions/15376/how-to-set-default-screen-options

// add_action('user_register', 'set_user_metaboxes');
add_action('admin_init', 'set_user_metaboxes');
function set_user_metaboxes($user_id=NULL) {

    // These are the metakeys we will need to update
    $meta_key['order'] = 'meta-box-order_post';
    $meta_key['hidden'] = 'metaboxhidden_post';

    // So this can be used without hooking into user_register
    if ( ! $user_id)
        $user_id = get_current_user_id(); 

    // Set the default order if it has not been set yet
    if ( ! get_user_meta( $user_id, $meta_key['order'], true) ) {
        $meta_value = array(
            'side' => 'submitdiv,formatdiv,categorydiv,tagsdiv-post_tag,postimagediv',
            'normal' => 'postexcerpt,source_meta,url_meta,postcustom,commentstatusdiv,commentsdiv,trackbacksdiv,slugdiv,authordiv,revisionsdiv',
            'advanced' => '',
        );
        update_user_meta( $user_id, $meta_key['order'], $meta_value );
    }

    // Set the default hiddens if it has not been set yet
    if ( ! get_user_meta( $user_id, $meta_key['hidden'], true) ) {
        $meta_value = array('postcustom','trackbacksdiv','commentstatusdiv','commentsdiv','slugdiv','revisionsdiv');
        update_user_meta( $user_id, $meta_key['hidden'], $meta_value );
    }
}

// http://wp.tutsplus.com/tutorials/theme-development/innovative-uses-of-wordpress-post-types-and-taxonomies/
// Creates "External Post" Post Type

function create_post_type() {  

// register external_post as a Custom Post Type

    register_post_type( 'external_post',  
        array(  
            'labels' => array(  
                'name' => __( 'External Posts' ),  
                'singular_name' => __( 'External Post' )  
            ),  
        'public' => true,  
        'menu_position' => 5,  
        'supports' => array( 'title', 'excerpt'),
        'rewrite' => array('slug' => 'external')  
        )  
    );  

// connect external_post to category taxonomy

    register_taxonomy_for_object_type('category', 'external_post');

// register wp_tool as a Custom Post Type

    register_post_type( 'wp_tool',  
        array(  
            'labels' => array(  
                'name' => __( 'WordPress Tools' ),  
                'singular_name' => __( 'WordPress Tool' )  
            ),  
        'public' => true,  
        'menu_position' => 5,  
        'supports' => array( 'title', 'editor', 'excerpt', 'thumbnail'),
        'rewrite' => array('slug' => 'tool')  
        )  
    );  

// connect wp_tool to category taxonomy

    register_taxonomy_for_object_type('category', 'wp_tool');

// register external_tool as a Custom Post Type

    register_post_type( 'external_tool',  
        array(  
            'labels' => array(  
                'name' => __( 'External Tools' ),  
                'singular_name' => __( 'External Tool' )  
            ),  
        'public' => true,  
        'menu_position' => 5,  
        'supports' => array( 'title', 'excerpt', 'thumbnail'),
        'rewrite' => array('slug' => 'tool')  
        )  
    );  

// connect external_tool to category taxonomy

    register_taxonomy_for_object_type('category', 'external_tool');


}  
add_action( 'init', 'create_post_type' ); 

// http://wordpress.stackexchange.com/questions/9211/changing-admin-menu-labels

function change_post_menu_label() {
    global $menu;
    global $submenu;
    $menu[5][0] = 'Original Reporting';
    $submenu['edit.php'][5][0] = 'Original Reporting';
    echo '';
}

    add_action( 'admin_menu', 'change_post_menu_label' );

    add_post_type_support( 'external_post', 'source_meta' );

// Prevents HTML from being stripped for category description
// http://wcdocs.woothemes.com/snippets/allow-html-in-term-category-tag-descriptions/

foreach ( array( 'pre_term_description' ) as $filter ) {
    remove_filter( $filter, 'wp_filter_kses' );
}
 
foreach ( array( 'term_description' ) as $filter ) {
    remove_filter( $filter, 'wp_kses_data' );
}


?>
