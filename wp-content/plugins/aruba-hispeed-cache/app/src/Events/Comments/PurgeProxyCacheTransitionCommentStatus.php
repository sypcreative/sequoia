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

use ArubaSPA\HiSpeedCache\Purger\WpPurger;
use ArubaSPA\HiSpeedCache\Traits\Instance;
use ArubaSPA\HiSpeedCache\Traits\HasContainer;
use ArubaSPA\HiSpeedCache\Events\AbstractEvents as AbstractEvent;

if ( ! \class_exists( __NAMESPACE__ . 'PurgeProxyCacheTransitionCommentStatus' ) ) {

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
	class PurgeProxyCacheTransitionCommentStatus extends AbstractEvent {
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
			$option = $this->container->get_service( 'ahsc_get_option' );
			return (bool) $option( 'ahsc_purge_page_on_deleted_comment' ) || $option( 'ahsc_purge_page_on_new_comment' );
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
			\add_action( 'transition_comment_status', array( $this, 'ahsc_purge_page_on_transition_comment_status' ), 200, 3 );
		}

		/**
		 * Dequeues/unSubscribes proxy cache cleaning action if conditions are supported.
		 *
		 * @access public
		 * @return void
		 */
		public function unsubscribe() {
			\remove_action( 'transition_comment_status', array( $this, 'ahsc_purge_page_on_transition_comment_status' ), 200, 3 );
		}

		/**
		 * Ahsc_purge_page_on_transition_comment_status
		 * Purge the cache of item or site on canghe status of the comment
		 *
		 * @param int|string  $new_status new status .
		 * @param int|string  $old_status old status .
		 * @param \WP_Comment $comment    comment object .
		 *
		 * @return void
		 */
		public function ahsc_purge_page_on_transition_comment_status( $new_status, $old_status, $comment ) {

			$cleaner = $this->get_purger();
			$option  = $this->container->get_service( 'ahsc_get_option' );

			$_post_id = $comment->comment_post_ID;
			$target   = \get_permalink( $_post_id );

			if ( true === $option( 'ahsc_purge_page_on_new_comment' ) && 'approved' === $new_status ) {
				// Logger.
				$this->log( 'hook:approved:transition_comment_status::' . $target, __NAMESPACE__ . '::' . __FUNCTION__, 'debug' );
				// Logger.
				$cleaner->purgeUrl( $target );
				return;
			}

			if ( 'trash' === $new_status ) {
				if ( true === $option( 'ahsc_purge_page_on_deleted_comment' ) && 'approved' === $old_status ) {
					// Logger.
					$this->log( 'hook:trash:transition_comment_status::' . $target, __NAMESPACE__ . '::' . __FUNCTION__, 'debug' );
					// Logger.
					$cleaner->purgeUrl( $target );
				}
			}
		}
	}
}
