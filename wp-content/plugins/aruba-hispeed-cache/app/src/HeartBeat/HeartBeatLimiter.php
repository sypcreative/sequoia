<?php //@phpcs:ignore WordPress.Files.FileName.NotHyphenatedLowercase
/**
 * ArubaHiSpeedCacheWpPurger
 * php version 5.6
 *
 * @category Wordpress-plugin
 * @package  Aruba-HiSpeed-Cache
 * @author   Aruba Developer <hispeedcache.developer@aruba.it>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     \ArubaHiSpeedCache\Run_Aruba_Hispeed_cache()
 * @since    1.1.3
 */

namespace ArubaSPA\HiSpeedCache\HeartBeat;

use ArubaSPA\HiSpeedCache\Traits\Instance;
use ArubaSPA\HiSpeedCache\Traits\HasContainer;

if ( ! \class_exists( __NAMESPACE__ . 'HeartBeatLimiter' ) ) {
	/**
	 * Add adbmin bar function to purge the cache.
	 */
	class HeartBeatLimiter {
		use Instance;
		use HasContainer;

		/**
		 * Does the class support the given request or condition?
		 *
		 * If this returns true, boostrap() will be called. If false, the class will be skipped.
		 *
		 * @return boolean
		 */
		public function support() {
			return (bool) $this->container->get_parameter( 'app.constant.ARUBA_HISPEED_CACHE_PLUGIN' );
		}

		/**
		 * Method activated if support method returns true.
		 * Used to perform supported actions or queuing actions.
		 *
		 * @return void
		 */
		public function boostrap() {
			if ( ! is_admin() ) {
				\add_action( 'init', array( $this, 'stop_heartbeat_on_frontend' ) );
			}

			if ( is_admin() ) {
				\add_filter( 'heartbeat_settings', array( $this, 'set_heartbeat_time_interval' ), 200, 1 );
			}
		}

		/**
		 * Set up method for admin bar link.
		 *
		 * @return void
		 */
		public function setup() {}

		/**
		 * Deregister the heartbeat script only in the frontend.
		 *
		 * @return void
		 */
		public function stop_heartbeat_on_frontend() {
			\wp_deregister_script( 'heartbeat' );
		}

		/**
		 * Limit heartbeat requests to every 120 seconds only in the frontend.
		 *
		 * @see https://developer.wordpress.org/reference/hooks/heartbeat_settings/
		 *
		 * @param  array $settings The settings array.
		 *
		 * @return array $settings
		 */
		public function set_heartbeat_time_interval( $settings ) {
			$settings['interval'] = 120;
			return $settings;
		}
	}
}
