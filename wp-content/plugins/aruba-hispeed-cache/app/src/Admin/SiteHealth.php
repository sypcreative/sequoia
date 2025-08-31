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

namespace ArubaSPA\HiSpeedCache\Admin;

use ArubaSPA\HiSpeedCache\Traits\Instance;
use ArubaSPA\HiSpeedCache\Traits\HasContainer;
use ArubaSPA\HiSpeedCache\Helper\Functions as F;

if ( ! \class_exists( __NAMESPACE__ . 'SiteHealth' ) ) {
	/**
	 * Add adbmin bar function to purge the cache.
	 */
	class SiteHealth {
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
			if ( $this->container->get_parameter( 'app.requirements.is_legacy_post_61' ) ) {
				\add_filter( 'site_status_page_cache_supported_cache_headers', array( $this, 'add_supported_cache_headers' ), 100, 1 );
			}
		}

		/**
		 * Add new header to the existing list of cache headers supported by core.
		 *
		 * @param  array $cache_headers The list of supported cache headers.
		 *
		 * @return array
		 */
		public function add_supported_cache_headers( $cache_headers ) {
			// Add new header to the existing list.
			$cache_headers['x-aruba-cache'] = static function ( $header_value ) {
				return false !== strpos( strtolower( $header_value ), 'hit' );
			};
			return $cache_headers;
		}

	}
}
