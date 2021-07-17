<?php

/**
 * Varnish Cache Cleaner.
 *
 * @package   Cache_Cleaner
 * @author    Eni Sinanaj <enisinanaj@outlook.com>
 * @link      https://enisinanal.com
 * @copyright 2013-2019 Eni Sinanaj
 *
 * @wordpress-plugin
 * Plugin Name: CacheCleaner
 * Plugin URI: http://enisinanaj.com/cachecleaner
 * Description: Clean FastCGI cache for NGinX and eventually Varnish Cache server 
 * Version: 1.0
 * Author: Eni Sinanaj
 * Author URI: http://enisinanaj.com
 */

// load basic path to the plugin
define( 'CCLEANER_BASE', plugin_basename( __FILE__ ) ); // plugin base as used by WordPress to identify it
define( 'CCLEANER_BASE_PATH', plugin_dir_path( __FILE__ ) );
define( 'CCLEANER_BASE_URL', plugin_dir_url( __FILE__ ) );
define( 'CCLEANER_BASE_DIR', dirname( CCLEANER_BASE ) ); // directory of the plugin without any paths
// general and global slug, e.g. to store options in WP
define( 'CCLEANER_SLUG', 'wp-cache-cleaner' );
define( 'CCLEANER_VERSION', '1.0' );

/*----------------------------------------------------------------------------*
 * Autoloading, modules and functions
 *----------------------------------------------------------------------------*/

// load public functions (might be used by modules, other plugins or theme)
//require_once CCLEANER_BASE_PATH . 'settings_page.php';

add_action( 'save_post', 'clean_cache' );
function clean_cache($post_ID = null, $post = null, $update = null) {
	
	$headers = array (
        "Host: my_host_name.com", // IMPORTANT
        "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8",
        "Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.3",
        "Accept-Encoding: gzip,deflate,sdch",
        "Accept-Language: it-IT,it;q=0.8,en-US;q=0.6,en;q=0.4",
        "Cache-Control: max-age=0",
        "Connection: keep-alive",
    );
	
	$curlOptionList = array(
			CURLOPT_URL                     => 'http://varnish_url_or_ip',
			CURLOPT_HTTPHEADER              => $headers,
			CURLOPT_CUSTOMREQUEST           => "CLEANFULLCACHE",
			CURLOPT_VERBOSE                 => true,
			CURLOPT_RETURNTRANSFER          => true,
			CURLOPT_NOBODY                  => true,
			CURLOPT_CONNECTTIMEOUT_MS       => 2000,
	);
	
    $curlHandler = curl_init();
    curl_setopt_array( $curlHandler, $curlOptionList );
    curl_exec( $curlHandler );
    curl_close( $curlHandler );
}