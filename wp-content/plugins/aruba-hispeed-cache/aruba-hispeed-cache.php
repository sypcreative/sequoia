<?php
/**
 * Aruba HiSpeed Cache
 * php version 5.6
 *
 * @category Wordpress-plugin
 *
 * @author   Aruba Developer <hispeedcache.developer@aruba.it>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 *
 * @see     Null
 * @since    1.0.0
 *
 * @wordpress-plugin
 * Plugin Name:       Aruba HiSpeed Cache
 * Version:           2.0.8
 * Plugin URI:        https://hosting.aruba.it/wordpress.aspx
 *
 * @phpcs:ignore Generic.Files.LineLength.TooLong
 * Description:       Aruba HiSpeed Cache interfaces directly with the Aruba HiSpeed Cache service of the Aruba hosting platform and automates its management.
 * Author:            Aruba.it
 * Author URI:        https://www.aruba.it/
 * Text Domain:       aruba-hispeed-cache
 * Domain Path:       languages
 * License:           GPL v3
 * Requires at least: 5.4
 * Tested up to:      6.5
 * Requires PHP:      5.6
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @package ArubaHispeedCache
 */

namespace ArubaSPA;

use ArubaSPA\HiSpeedCache\Container\ContainerBuilder;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( \file_exists( __DIR__ . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'autoload' . DIRECTORY_SEPARATOR . 'autoload.php' ) ) {
	require __DIR__ . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'autoload' . DIRECTORY_SEPARATOR . 'autoload.php';
}


HiSpeedCache\ArubaHispeedCache::get_instance()
->init_setup( __DIR__ . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'config.php', __FILE__ );

/**
 * Adding methods to "activate" hooks
 */
\register_activation_hook(
	__FILE__,
	array( HiSpeedCache\ArubaHispeedCache::get_instance(), 'activation' )
);

/**
 * Adding methods to "deactivate" hooks
 */
\register_deactivation_hook(
	__FILE__,
	array( HiSpeedCache\ArubaHispeedCache::get_instance(), 'deactivation' )
);

if ( HiSpeedCache\ArubaHispeedCache::get_instance()->is_initialized ) {
	\add_action(
		'plugins_loaded',
		array( HiSpeedCache\ArubaHispeedCache::get_instance(), 'plugin_register_services' )
	);

	\add_action(
		'init',
		array( HiSpeedCache\ArubaHispeedCache::get_instance(), 'plugin_event_subscriber' )
	);

	\add_action( 'activated_plugin', array( HiSpeedCache\ArubaHispeedCache::get_instance(), 'check_hispeed_cache_services' ), 20, 1 );
}
