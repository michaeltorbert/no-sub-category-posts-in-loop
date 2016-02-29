<?php
/*
Plugin Name: No Sub-Category Posts in Loop
Plugin URI: http://fullthrottledevelopment.com/no-sub-category-posts-in-loop/
Description: This plugin allows you to only display post from the current category in your loop (no posts from sub cats)
Version: 0.4
Author: Michael Torbert
Author URI: http://semperfiwebdesign.com/
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/

/**
 * Removes subcategory posts from category arhcives
 * 
 * @package No_Sub_Category_Posts_In_Loop
 * @version 0.5
*/

/* Release History
 0.5 - Fixed bug that broke plugin in 3.1. Props @ollybenson (http://gln.to/oshcw)
 0.4 - Fixed bug introduced with WordPress 3.1. Added inline docs. Removed filter after main query is built.
 0.3 - Modified directory structure so that plugin may be added and activated from wp-admin
 0.2 - Forgot to define a global, preventing posts from appearing that should. (thanks to http://redfootwebdesign.com for the heads up!)
 0.1 - Initial Release
*/

/**
 * Constant holding the version number
 * @since 0.1
 */
define( 'FT_NSCP_Version' , '0.4' );

/**
 * Constant holding the plugin directory path
 * @since 0.1
 */
define('FT_NSCP_PATH' , WP_CONTENT_DIR.'/plugins/'.plugin_basename(dirname(__FILE__)) );

/**
 * Constant holding the plugin directory URL
 */
define( 'FT_NSCP_URL' , WP_CONTENT_URL.'/plugins/'.plugin_basename(dirname(__FILE__)) );


// Setup form security
if ( !function_exists('wp_nonce_field') ) {
    function ft_nscp_nonce_field($action = -1) { return; }
    $ft_nscp_nonce = -1;
} else {
	if( !function_exists( 'ft_nscp_nonce_field' ) ) {
	function ft_nscp_nonce_field($action = -1,$name = 'ft_nscp-update-checkers') { return wp_nonce_field($action,$name); }
	define('FT_NSCP_NONCE' , 'ft_nscp-update-checkers');
	}
}

/**
 * Filter's the query's where clause

 * @param str incoming WHERE clause
 * @global $wp_query WordPress query object
 * @global $wpdb WordPress database access object
 * @global $wp_version WordPress version number
 * @retuns str Modified or original WHERE clause
 * @since 0.1
 */
function ft_nscp_mod_where( $where ){ 
	global $wp_query, $wpdb, $wp_version;

	// Fire off if we're viewing a category archive
	if ( is_category() ){
		
		// Get children categories of current cat if they exist
		if ( $excludes = get_categories( "child_of=" . $wp_query->get( 'cat' ) ) ) {
			
			// For each child, add just the ID to an array
			foreach ( $excludes as $key => $value ) {

				$exs[] = $value->term_taxonomy_id;
			
			}
		
		}
		
		// If array exists, remove posts in child categories from query.
		if ( isset( $exs ) && is_array( $exs ) ) {

			// WP Query changed in 3.1
			if ( version_compare( $wp_version, 3.1, '<' ) )
				$where .= " AND " . $wpdb->prefix . "term_taxonomy.term_id NOT IN ( ". implode( ",", $exs ) . " ) ";
			else
				$where .= " AND " . $wpdb->prefix . "term_relationships.term_taxonomy_id NOT IN ( ". implode( ",", $exs ) . " ) ";

		}
	}

	return $where;

}

/**
 * Removes the filter after the main query has been built to not interfere with widgets
 *
 * @since 0.4
 */
function ft_nscp_remove_filter() {

	remove_filter( 'posts_where', 'ft_nscp_mod_where' );

}

if ( ! is_admin() ) {

	add_filter( 'posts_where', 'ft_nscp_mod_where' );
	add_action( 'template_redirect', 'ft_nscp_remove_filter' );

}

/**
 * Adds ability to donate to plugin development
 *
 * @since 0.4
 */
function ft_ncsp_donate() {

	// Kill notice
	if ( isset( $_GET['remove_ncsp_donate'] ) )
		update_option( 'ft_ncsp_show_donate', '0.4' );

	// Look for wp_option
	if ( ! $show_donation_link = get_option( 'ft_ncsp_show_donate' ) ) {
		update_option( 'ft_nscp_show_donate', 'yes' );
		$show_donation_link = 'yes';
	}

	if ( 'yes' == $show_donation_link )
		add_action( 'admin_notices', 'ft_ncsp_donate_notice' );

}
add_action( 'admin_init', 'ft_ncsp_donate' );

/**
 * This displays the option to donate via paypal
 *
 * @since 0.4
 */
function ft_ncsp_donate_notice() {

	$paypal_link = 'https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=mrtorbert%40gmail%2ecom&item_name=All%20In%20One%20SEO%20Pack&item_number=Support%20Open%20Source&no_shipping=0&no_note=1&tax=0&currency_code=USD&lc=US&bn=PP%2dDonationsBF&charset=UTF%2d8';
	$no_thanks = esc_url( admin_url( 'plugins.php?remove_ncsp_donate=true' ) );
	echo "<div class='update-nag'>" . sprintf( __( "Thanks for upgrading the 'No subcats in loops' plugin. Would you consider sending the developer $5.00 USD to sustain development? <a href='%s'>Yes, take me to PayPal</a> | <a href='%s'>No thanks</a> | <a href='%s'>I already did</a>." ), $paypal_link, $no_thanks, $no_thanks ) . "</div>";

}
