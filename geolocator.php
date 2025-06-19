<?php
/*
Plugin Name: Geo Locator Manager
Plugin URI: http://URI_Of_Page_Describing_Plugin_and_Updates
Description: A brief description of the Plugin.
Version: 1.0
Author: Prismart
Author URI: http://URI_Of_The_Plugin_Author
License: A "Slug" license name e.g. GPL2
*/

require_once('src/includes/GeoLocatorManagerService.php');

function geolocator_admin_menu(){
	add_menu_page(
		__('geolocator','geolocator'),
		__('geolocator','geolocator'),
		'manage_options',
		'hello-react',
		'geolocator_admin_menu_callback',
		'dashicons-welcome-widgets-menus'
	);
}

function geolocator_admin_menu_callback(){
	echo '<div id="manager-page"></div>';
}

register_activation_hook(__FILE__, 'geolocator_create_table');
function geolocator_create_table()
{
	global $wpdb;

	$table_name = $wpdb->prefix . "geolocator";
	$charset_collate = $wpdb->get_charset_collate();

	$sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        name varchar(255) NOT NULL,
        description varchar(255) NOT NULL,
        created_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY  (id)
    ) $charset_collate;";

	require_once ABSPATH . 'wp-admin/includes/upgrade.php';
	dbDelta($sql);
}


register_deactivation_hook(__FILE__, 'geolocator_drop_table');
function geolocator_drop_table()
{
	global $wpdb;
	$table_name = $wpdb->prefix . "geolocator";
	$sql = "DROP TABLE IF EXISTS $table_name";
	$wpdb->query($sql);
}


function geolocator_enqueue_scripts( $admin_page ){
	if( $admin_page !== 'toplevel_page_hello-react' ){
		return;
	}

	$asset_file = plugin_dir_path( __FILE__ ) . 'build/index.asset.php';

	if ( ! file_exists( $asset_file ) ) {
		return;
	}


	$asset = include $asset_file;

	// Ensure wp-element is in dependencies
	if (!in_array('wp-element', $asset['dependencies'])) {
		$asset['dependencies'][] = 'wp-element';
	}

	wp_enqueue_script(
		'geolocator-script',
		plugins_url( 'build/index.js', __FILE__ ),
		$asset['dependencies'],
		$asset['version'],
		true
	);

	$css_handle = is_rtl() ? 'hello-react-style-rtl' : 'hello-react-style';
	$css_file = is_rtl() ? 'build/index-rtl.css' : 'build/index.css';
	wp_enqueue_style(
		$css_handle,
		plugins_url( $css_file, __FILE__ ),
		array_filter(
			$asset['dependencies'],
			function ( $style ) {
				return wp_style_is( $style, 'registered' );
			}
		),
		$asset['version']
	);
}


function geolocator_register_example_routes() {
	// Instantiate the service class inside the function
	$geoLocatorManagerService = new GeoLocatorManagerService();

	register_rest_route( 'hello-world/v1', '/phrase', array(
		'methods'  => WP_REST_Server::READABLE,
		'callback' => array($geoLocatorManagerService, 'GetGeoLocatorData')
	) );

	register_rest_route( 'geolocator/api', '/data', array(
		'methods'  => WP_REST_Server::READABLE,
		'callback' => array($geoLocatorManagerService, 'GetGeoLocatorData')
	) );

	register_rest_route( 'geolocator/api', '/data/(?P<id>\d+)', array(
		'methods'  => WP_REST_Server::CREATABLE,
		'callback' => array($geoLocatorManagerService, 'SelectDataById'),
		'args'     => array(
			'id' => array(
				'description' => 'The ID of the data to retrieve',
				'type'        => 'integer',
				'required'    => true,
			),
		),
	));
}

add_action( 'rest_api_init', 'geolocator_register_example_routes' );
add_action( 'admin_enqueue_scripts', 'geolocator_enqueue_scripts' );
add_action('admin_menu', 'geolocator_admin_menu');

