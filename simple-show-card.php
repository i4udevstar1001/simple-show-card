<?php
/**
 * Plugin Name: Simple Show Card
 * Plugin URI:  
 * Description: Show credit card info
 * Version:     1.0
 * Author:      Kevin Lee
 * Author URI:  https://github.com/i4udevstar1001
 * @package Simple Show Card
*/

// Frontend section
if ( ! class_exists( 'SimpleShowCard' ) ) {
	require_once( dirname( __FILE__ ) . '/lib/class-simple-show-card.php' );
}

// Admin section
if ( ! class_exists( 'SimpleShowCardAdmin' ) ) {
	require_once( dirname( __FILE__ ) . '/lib/class-simple-show-card-admin.php' );
}
?>