<?php

add_action( 'init', 'unregister_taxonomy');
function unregister_taxonomy(){
    global $wp_taxonomies;
    $taxonomy = 'test';
    if ( taxonomy_exists( $taxonomy))
        unset( $wp_taxonomies[$taxonomy]);
}

?>
