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

namespace ArubaSPA\HiSpeedCache\Events;

use ArubaSPA\HiSpeedCache\Purger\WpPurger;
use ArubaSPA\HiSpeedCache\Traits\HasContainer;
use ArubaSPA\HiSpeedCache\Helper\Functions as F;

if ( ! \class_exists( __NAMESPACE__ . 'AbstractEvents' ) ) {
	// phpcs:disable WordPress.NamingConventions
	/**
	 * AbstracPurger.
	 */
	abstract class AbstractEvents {
		use HasContainer;

		/**
		 * Does the Event support the given request or condition?
		 *
		 * If this returns true, boostrap() will be called. If false, the class will be skipped.
		 *
		 * This function must return a bool value.
		 */
		abstract public function support();

		/**
		 * Set up method for admin bar link.
		 *
		 * @return void
		 */
		abstract public function setup();

		/**
		 * Queues/Subscribes proxy cache cleaning action if conditions are supported.
		 *
		 * @access public
		 * @return void
		 */
		abstract public function subscribe();

		/**
		 * Dequeues/unSubscribes proxy cache cleaning action if conditions are supported.
		 *
		 * @access public
		 * @return void
		 */
		abstract public function unsubscribe();

		/**
		 * Get the purger class tool
		 *
		 * @return WpPurger
		 */
		public function get_purger() {
			$cleaner = $this->container->get_service( WpPurger::class );
			$cleaner->get_container();

			return $cleaner;
		}

		/**
		 * Write a debug info in to file.
		 *
		 * @param  string $message the messagge.
		 * @param  string $name the name of message.
		 * @param  string $type the type of log debug|info|warning|error.
		 * @return void
		 */
		public function log( $message, $name = '', $type = 'info' ) {
			if ( $this->container->has_service( 'logger' ) ) {

				$logger = $this->container->get_service( 'logger' );

				switch ( $type ) {
					case 'debug':
						$logger::debug( $message, $name );
						break;
					case 'info':
						$logger::info( $message, $name );
						break;
					case 'warning':
						$logger::warning( $message, $name );
						break;
					case 'error':
						$logger::error( $message, $name );
						break;
				}
			}
		}

		/**
		 * Is_purged
		 */
		public function is_purged() {
			if ( F\ahsc_has_transient( 'ahsc_is_purged' ) ) {
				return true;
			}

			return false;
		}
	}
	// phpcs:enable
}
