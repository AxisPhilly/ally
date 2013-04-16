<?php
/*
Plugin Name: Reenable Shortlink Item In Admin Toolbar
Plugin URI: http://krislagraff.com/sdl.zip
Description: This plugin will display the shortlink option in admin bar.
Version: 1.1
Author: Kristopher Lagraff
Author URI: http://krislagraff.com
License: GPL2
*/

// This hooks the custom css to wp_head

add_action( 'wp_head', 'shortguy_link_color' );
function shortguy_link_color() {
	?>
	<style type="text/css" media="screen">
		#wpadminbar .shortlink-input{
			color: #000;
			font-weight:bold;
		}

	</style>
	<?php
}

// Action for menu item

add_action( 'admin_bar_menu', 'wp_admin_bar_shortlink_menu',90 )
?>