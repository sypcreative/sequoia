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

namespace ArubaSPA\HiSpeedCache\Events\Deferred;

use ArubaSPA\HiSpeedCache\Purger\WpPurger;
use ArubaSPA\HiSpeedCache\Request\Request;
use ArubaSPA\HiSpeedCache\Traits\Instance;
use ArubaSPA\HiSpeedCache\Traits\HasContainer;
use ArubaSPA\HiSpeedCache\Helper\Functions as F;
use ArubaSPA\HiSpeedCache\Events\AbstractEvents as AbstractEvent;


if ( ! \class_exists( __NAMESPACE__ . 'PurgeProxyCacheInDeferredMode' ) ) {

	/**
	 * PurgeProxyCacheInDeferredMode This is the class that handles the event of cleaning the proxy cache in a deferred manner.
	 *
	 * It is closely connected with the various events of this plugin.
	 *
	 * @package Aruba-HiSpeed-Cache
	 * @author  Aruba Developer <hispeedcache.developer@aruba.it>
	 * @version 2.0.0
	 * @access public
	 * @since  1.0.3
	 */
	class PurgeProxyCacheInDeferredMode extends AbstractEvent {
		use Instance;
		//use HasContainer;

		/**
		 * Does the Event support the given request or condition?
		 *
		 * If this returns true, boostrap() will be called. If false, the class will be skipped.
		 *
		 * @return boolean
		 */
		public function support() {
			return $this->container->get_service( Request::class )->is_request_type( 'admin' );
		}

		/**
		 * Set up method for admin.
		 *
		 * @return void
		 */
		public function setup() {}

		/**
		 * Queues/Subscribes proxy cache cleaning action if conditions are supported.
		 *
		 * @access public
		 * @return void
		 */
		public function subscribe() {
			if ( true === $this->container->get_service( Request::class )->is_request_type( 'rest' ) ||
			true === $this->container->get_service( Request::class )->is_request_type( 'ajax' ) ) {
				return;
			}
			\add_action( 'init', array( $this, 'ahsc_deferred_purge' ), 200, 0 );
		}

		/**
		 * Dequeues/unSubscribes proxy cache cleaning action if conditions are supported.
		 *
		 * @access public
		 * @return void
		 */
		public function unsubscribe() {}

		/**
		 * Fires after a post type has been updated, and the term cache has been cleaned.
		 *
		 * @see https://developer.wordpress.org/reference/hooks/post_updated/
		 * or
		 * @see https://github.com/WordPress/WordPress/blob/master/wp-includes/post.php
		 *
		 * param int     $post_ID Post ID.
		 * param WP_Post $post_after Post object following the update.
		 * param WP_Post $post_before Post object before the update.
		 *
		 * @return void
		 */
		public function ahsc_deferred_purge() {

			$cleaner = $this->get_purger();

			/**
			 * I check whether the cleaning transient is present in case. I remove it and clear the entire proxy cache.
			 */
			$do_purge = F\ahsc_has_transient( 'ahsc_do_purge_deferred' );

			if ( $do_purge ) {
				/**
				 * I clear the entire proxy cache.
				 */
				// Logger.
				$this->log( __NAMESPACE__ . '::' . __FUNCTION__, 'debug' );
				// Logger.
				$cleaner->purgeAll();

				F\ahsc_delete_transient( 'ahsc_do_purge_deferred' );
			}
		}

	}
}
