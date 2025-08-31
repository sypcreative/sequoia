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

namespace ArubaSPA\HiSpeedCache\Events\PostType;

use ArubaSPA\HiSpeedCache\Purger\WpPurger;
use ArubaSPA\HiSpeedCache\Request\Request;
use ArubaSPA\HiSpeedCache\Traits\Instance;
use ArubaSPA\HiSpeedCache\Traits\HasContainer;
use ArubaSPA\HiSpeedCache\Helper\Functions as F;
use ArubaSPA\HiSpeedCache\Events\AbstractEvents as AbstractEvent;


if ( ! \class_exists( __NAMESPACE__ . 'PurgeProxyCacheOnPostUpdated' ) ) {

	/**
	 * PurgeProxyCacheOnPostUpdated It is the class that handles the event of 'wp_insert_post()'
	 *
	 * Is a class that has no real direct use. But it is handled by the core of this plugin.
	 * It is used to queue the action of clearing the proxy cache to the 'post_updated' action.
	 *
	 * @package Aruba-HiSpeed-Cache
	 * @author  Aruba Developer <hispeedcache.developer@aruba.it>
	 * @version 2.0.0
	 * @access public
	 * @since  1.0.3
	 */
	class PurgeProxyCacheOnPostUpdated extends AbstractEvent {
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

			if( 'checkout' == $this->container->get_service( Request::class )->is_wcajax() ) {
				return false;
			}

			if ( 'nav-menus.php' === $this->container->get_service( Request::class )->current_page() ) {
				return false;
			}

			$option = $this->container->get_service( 'ahsc_get_option' );

			return (bool) $option( 'ahsc_purge_homepage_on_edit' ) ||
			$option( 'ahsc_purge_homepage_on_del' ) ||
			$option( 'ahsc_purge_page_on_mod' ) ||
			$option( 'ahsc_purge_archive_on_edit' ) ||
			$option( 'ahsc_purge_archive_on_del' );
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
			\add_action( 'post_updated', array( $this, 'ahsc_post_updated' ), 20, 3 );
		}

		/**
		 * Dequeues/unSubscribes proxy cache cleaning action if conditions are supported.
		 *
		 * @access public
		 * @return void
		 */
		public function unsubscribe() {
			\remove_action( 'post_updated', array( $this, 'ahsc_post_updated' ), 20, 3 );
		}

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
		public function ahsc_post_updated($post_ID, $post_after, $post_before) {

			/**
			 * I check whether the cleaning transient "ahsc_do_purge_deferred" is present in case I inhibit this action.
			 */
			$do_purge = F\ahsc_has_transient( 'ahsc_do_purge_deferred' );

			if ( $do_purge ) {
				return;
			}

			/**
			 * Disable the purge action if the transient 'ahsc_is_purged' is set.
			 */
			if ( $this->is_purged() ) {
				return;
			}

			$cleaner = $this->get_purger();
			$option  = $this->container->get_service( 'ahsc_get_option' );

			/**
			 * If home page cleaning is set, there is no point in going any further, the entire site cache will be cleaned.
			 */
			if ( $option( 'ahsc_purge_homepage_on_edit' ) || $option( 'ahsc_purge_homepage_on_del' ) ) {
				// Logger.
				$this->log( 'hook::post_updated::home', __NAMESPACE__ . '::' . __FUNCTION__, 'debug' );
				// Logger.
				$cleaner->purgeAll();
				return;
			}
		}

	}
}
