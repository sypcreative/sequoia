<?php
/**
 * Manages the uninstallation process of the
 * Aruba HiSpeed Cache Plugin for more information
 * php version 5.6
 *
 * @category Wordpress-plugin
 * @package  Aruba-HiSpeed-Cache
 * @author   Aruba Developer <hispeedcache.developer@aruba.it>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://developer.wordpress.org/plugins/plugin-basics/uninstall-methods/
 */

namespace ArubaSPA\HiSpeedCache;

// If uninstall.php is not called by WordPress, die.
if ( ! \defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

\delete_site_option( 'aruba_hispeed_cache_options' );
