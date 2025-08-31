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

namespace ArubaSPA\HiSpeedCache\Events\Comments;

use ArubaSPA\HiSpeedCache\Traits\Instance;
use ArubaSPA\HiSpeedCache\Events\AbstractEvents as AbstractEvent;

if ( ! \class_exists( __NAMESPACE__ . 'PurgeProxyCacheOnNewComment' ) ) {

	/**
	 * PurgePageOnNewComment It is the class that handles the event of 'wp_insert_comment'
	 *
	 * Is a class that has no real direct use. But it is handled by the core of this plugin.
	 * It is used to queue the action of clearing the proxy cache to the 'wp_insert_comment' action.
	 *
	 * @package Aruba-HiSpeed-Cache
	 * @author  Aruba Developer <hispeedcache.developer@aruba.it>
	 * @version 2.0.0
	 * @access public
	 * @since  0.0.1
	 */
	class PurgeProxyCacheOnNewComment extends AbstractEvent {
		use Instance;

		/**
		 * Does the Event support the given request or condition?
		 *
		 * If this returns true, boostrap() will be called. If false, the class will be skipped.
		 *
		 * @return boolean
		 */
		public function support() {
			$option = $this->container->get_service( 'ahsc_get_option' );
			return (bool) $option( 'ahsc_purge_page_on_new_comment' );
		}

		/**
		 * Set up method for admin bar link.
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
			\add_action( 'wp_insert_comment', array( $this, 'ahsc_purge_page_on_new_comment' ), 200, 2 );
			\add_action( 'rest_insert_comment', array( $this, 'ahsc_purge_page_on_new_comment_rest' ), 200, 3 );
		}

		/**
		 * Dequeues/unSubscribes proxy cache cleaning action if conditions are supported.
		 *
		 * @access public
		 * @return void
		 */
		public function unsubscribe() {
			\remove_action( 'wp_insert_comment', array( $this, 'ahsc_purge_page_on_new_comment' ), 200 );
			\remove_action( 'rest_insert_comment', array( $this, 'ahsc_purge_page_on_new_comment_rest' ), 200 );
		}


		/**
		 * Issues a call, via the 'WpPurger' class, to the proxy cache cleaner.
		 *
		 * @param int         $id      the comment id.
		 * @param \WP_Comment $comment comment object.
		 *
		 * @return void
		 */
		public function ahsc_purge_page_on_new_comment( $id, $comment ) {

			$cleaner = $this->get_purger();
			$option  = $this->container->get_service( 'ahsc_get_option' );

			if ( $option( 'ahsc_purge_homepage_on_edit' ) ) {
				// Logger.
				$this->log( 'hook::wp_insert_comment::home', __NAMESPACE__ . '::' . __FUNCTION__, 'debug' );
				// Logger.
				$cleaner->purgeAll();
				return;
			}

			$_post_id = $comment->comment_post_ID;
			$target   = \get_permalink( $_post_id );

			// Logger.
			$this->log( 'hook::wp_insert_comment::' . $target, __NAMESPACE__ . '::' . __FUNCTION__, 'debug' );
			// Logger.
			$cleaner->purgeUrl( $target );

		}

		/**
		 * Wrap for rest insert comment.
		 *
		 * @param  WP_Comment      $comment .
		 * @param  WP_REST_Request $request .
		 * @param  boolean         $creating .
		 * @return void
		 */
		public function ahsc_purge_page_on_new_comment_rest( $comment, $request, $creating ) {
			$this->ahsc_purge_page_on_new_comment( $comment->comment_ID, $comment );
		}
	}
}
