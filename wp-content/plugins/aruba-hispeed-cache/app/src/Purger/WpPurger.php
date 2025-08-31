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

namespace ArubaSPA\HiSpeedCache\Purger;

use ArubaSPA\HiSpeedCache\Debug\Logger;
use ArubaSPA\HiSpeedCache\Traits\Instance;
use ArubaSPA\HiSpeedCache\Traits\HasContainer;
use ArubaSPA\HiSpeedCache\Helper\Functions as F;
use ArubaSPA\HiSpeedCache\Purger\AbstractPurger;

if ( ! \class_exists( __NAMESPACE__ . 'WpPurger' ) ) {
	// phpcs:disable WordPress.NamingConventions
	/**
	 * Undocumented class
	 */
	class WpPurger extends AbstractPurger {
		use Instance;
		use HasContainer;

		/**
		 * Purge the cache of a single page
		 *
		 * @param  string $url The url to purge.
		 * @return void
		 */
		public function purgeUrl( $url ) {
			// $site_url = $this->getParseSiteUrl();
			// $host = $site_url['host'];

			$_url = \filter_var( $url, FILTER_SANITIZE_URL );

			// Logger.
			if ( $this->container->has_service( 'logger' ) ) {
				$logger = $this->container->get_service( 'logger' );
				$logger::info( 'targhet ' . $_url, 'purgeUrl()' );
			}
			// Logger.

			$this->doRemoteGet( $_url );

			\delete_expired_transients(true);
			$this->flush_wp_object_cache();

			$this->cache_warmer();
		}

		/**
		 * Purge the cache of a list of pages
		 *
		 * @param  array $urls The urls to purge.
		 * @return void
		 */
		public function purgeUrls( $urls ) {
			foreach ( $urls as $url ) {
				$this->doRemoteGet( $url );

				// Logger.
				if ( $this->container->has_service( 'logger' ) ) {
					$logger = $this->container->get_service( 'logger' );
					$logger::info( 'targhet ' . $url, 'purgeUrl()' );
				}
				// Logger.
			}

			\delete_expired_transients(true);
			$this->flush_wp_object_cache();

			$this->cache_warmer();
		}

		/**
		 * Purge the alla chace of site
		 *
		 * @return void
		 */
		public function purgeAll() {
			// Logger.
			if ( $this->container->has_service( 'logger' ) ) {
				$logger = $this->container->get_service( 'logger' );
				$logger::info( 'targhet /', 'purgeAll()' );
			}
			// Logger.

			$this->doRemoteGet( '/' );

			\delete_expired_transients(true);
			$this->flush_wp_object_cache();

			$this->cache_warmer();
		}

		/**
		 * DoRemoteGet
		 *
		 * @param string $target path to purge.
		 *
		 * @return void
		 */
		public function doRemoteGet( $target = '/' ) {
			$purgeUrl = $this->preparePurgeRequestUri( $target );

			$blog_id = null;

			if ( is_multisite() ) {
				$blog_id = \get_current_blog_id();
			}

			$host = \wp_parse_url( \get_site_url( $blog_id ) );
			$host = $host['host'];

			// Logger.
			if ( $this->container->has_service( 'logger' ) ) {
				$logger = $this->container->get_service( 'logger' );
				$logger::info( $purgeUrl, '_targhet' );
			}
			// Logger.

			\wp_remote_get(
				$purgeUrl,
				array(
					'timeout' => $this->timeOut,
					'headers' => array(
						'Host' => $host,
					),
				)
			);
		}

		/**
		 * Whap form wp_cache_flush.
		 *
		 * @see https://developer.wordpress.org/reference/functions/wp_cache_flush/.
		 *
		 * @return bool
		 */
		public function flush_wp_object_cache() {
			$wp_object_cache = \wp_cache_flush();
			if ( $wp_object_cache ) {
				// Logger.
				if ( $this->container->has_service( 'logger' ) ) {
					$logger = $this->container->get_service( 'logger' );
					$logger::info( 'flush wp_object_cache with success....' );
				}
				// Logger.
			}
			return $wp_object_cache;
		}

		/**
		 * This function adds a transient that will be read with each subsequent request and will trigger the cache warming action.
		 * If the ahsc_cache_warmer option is set to true.
		 *
		 * @return void
		 */
		public function cache_warmer() {
			$option = $this->container->get_service( 'ahsc_get_option' );
			$cache_warmer =  (bool) $option( 'ahsc_cache_warmer' );

			if ( $cache_warmer ) {
				$do_warmer             = F\ahsc_has_transient( 'ahsc_do_cache_warmer' );
				$do_warmer_log_message = 'Transint warmer present';

				if ( ! $do_warmer ) {
					$do_warmer_log_message = 'Transint warmer non presente presente lo imposto';
					F\ahsc_set_transient( 'ahsc_do_cache_warmer', \time(), MINUTE_IN_SECONDS );
				}

				// Logger.
				if ( $this->container->has_service( 'logger' ) ) {
					$logger = $this->container->get_service( 'logger' );
					$logger::info( $do_warmer_log_message, 'Cache Warmer' );
				}
				// Logger.
			}
		}

	}
	// phpcs:enable
}
