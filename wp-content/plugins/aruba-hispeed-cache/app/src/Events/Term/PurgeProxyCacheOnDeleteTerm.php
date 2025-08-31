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

namespace ArubaSPA\HiSpeedCache\Events\Term;

use ArubaSPA\HiSpeedCache\Purger\WpPurger;
use ArubaSPA\HiSpeedCache\Request\Request;
use ArubaSPA\HiSpeedCache\Traits\Instance;
use ArubaSPA\HiSpeedCache\Traits\HasContainer;
use ArubaSPA\HiSpeedCache\Events\AbstractEvents as AbstractEvent;


if ( ! \class_exists( __NAMESPACE__ . 'PurgeProxyCacheOnDeleteTerm' ) ) {

	/**
	 * PurgeProxyCacheOnDeleteTerm It is the class that handles the event of 'wp_delete_term'
	 *
	 * Is a class that has no real direct use. But it is handled by the core of this plugin.
	 * It is used to queue the action of clearing the proxy cache to the 'delete_term' action.
	 *
	 * @package Aruba-HiSpeed-Cache
	 * @author  Aruba Developer <hispeedcache.developer@aruba.it>
	 * @version 2.0.0
	 * @access public
	 * @since  0.0.1
	 */
	class PurgeProxyCacheOnDeleteTerm extends AbstractEvent {
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

			if ( 'nav-menus.php' === $this->container->get_service( Request::class )->current_page() ) {
				return false;
			}

			$option = $this->container->get_service( 'ahsc_get_option' );

			return (bool) $option( 'ahsc_purge_archive_on_del' );
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
			/**
			 * Fires when deleting a term, before any modifications are made to posts or terms.
			 */
			\add_action( 'pre_delete_term', array( $this, 'ahsc_set_term_uri' ), 200, 2 );

			/**
			 * Fires after a term has been delete, and the term cache has been cleaned.
			 */
			\add_action( 'delete_term', array( $this, 'ahsc_purge_archive_on_delete' ), 200, 0 );
		}

		/**
		 * Dequeues/unSubscribes proxy cache cleaning action if conditions are supported.
		 *
		 * @access public
		 * @return void
		 */
		public function unsubscribe() {
			\remove_action( 'pre_delete_term', array( $this, 'ahsc_set_term_uri' ), 200);
			\remove_action( 'delete_term', array( $this, 'ahsc_purge_archive_on_delete' ), 200);
		}

		/**
		 * Fires when deleting a term, before any modifications are made to posts or terms.
		 *
		 * @param int    $term Term ID.
		 * @param string $taxonomy Taxonomy name.
		 * @return void
		 */
		public function ahsc_set_term_uri( $term, $taxonomy ) {
			$this->target = \get_term_link( $term, $taxonomy );
		}

		/**
		 * Fires after a term has been updated, and the term cache has been cleaned.
		 *
		 * @see https://developer.wordpress.org/reference/hooks/delete_term/.
		 *
		 * param int     $term Term ID.
		 * param int     $tt_id Term taxonomy ID.
		 * param string  $taxonomy Taxonomy slug.
		 * param WP_Term $deleted_term  Copy of the already-deleted term.
		 * param array   $object_ids List of term object IDs.
		 *
		 * @return void
		 */
		public function ahsc_purge_archive_on_delete() {
			$cleaner = $this->get_purger();
			$option  = $this->container->get_service( 'ahsc_get_option' );

			if ( $option( 'ahsc_purge_homepage_on_del' ) ) {
				// Logger.
				$this->log( 'hook::edited_term::home', __NAMESPACE__ . '::' . __FUNCTION__, 'debug' );
				// Logger.
				$cleaner->purgeAll();
				return;
			}

			$target = $this->target;

			// Logger.
			$this->log( 'hook::edited_term::' . $target, __NAMESPACE__ . '::' . __FUNCTION__, 'debug' );
			// Logger.
			$cleaner->purgeUrl( $target );
		}
	}
}
