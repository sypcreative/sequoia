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


if ( ! \class_exists( __NAMESPACE__ . 'PurgeProxyCacheOnEditTerm' ) ) {

	/**
	 * PurgeProxyCacheOnEditTerm It is the class that handles the event of 'wp_update_term'
	 *
	 * Is a class that has no real direct use. But it is handled by the core of this plugin.
	 * It is used to queue the action of clearing the proxy cache to the 'edited_term' action.
	 *
	 * @package Aruba-HiSpeed-Cache
	 * @author  Aruba Developer <hispeedcache.developer@aruba.it>
	 * @version 2.0.0
	 * @access public
	 * @since  0.0.1
	 */
	class PurgeProxyCacheOnEditTerm extends AbstractEvent {
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

			return (bool) $option( 'ahsc_purge_archive_on_edit' );
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
			 * Fires after a term has been updated, and the term cache has been cleaned.
			 */
			\add_action( 'edited_term', array( $this, 'ahsc_purge_archive_on_edit' ), 200, 3 );
		}

		/**
		 * Dequeues/unSubscribes proxy cache cleaning action if conditions are supported.
		 *
		 * @access public
		 * @return void
		 */
		public function unsubscribe() {
			\remove_action( 'edited_term', array( $this, 'ahsc_purge_archive_on_edit' ), 200);
		}

		/**
		 * Fires after a term has been updated, and the term cache has been cleaned.
		 *
		 * @see https://developer.wordpress.org/reference/hooks/edited_term/.
		 *
		 * @param int    $term_id  Term ID.
		 * @param int    $tt_id    Term taxonomy ID.
		 * @param string $taxonomy Taxonomy slug.
		 *
		 * array  $args     Arguments passed to wp_update_term() added in 6.1 wp core remove for compatibility wiht 5.6.
		 *
		 * @return void
		 */
		public function ahsc_purge_archive_on_edit( $term_id, $tt_id, $taxonomy ) {

			$cleaner = $this->get_purger();
			$option  = $this->container->get_service( 'ahsc_get_option' );

			if ( $option( 'ahsc_purge_homepage_on_edit' ) ) {
				// Logger.
				$this->log( 'hook::edited_term::home', __NAMESPACE__ . '::' . __FUNCTION__, 'debug' );
				// Logger.
				$cleaner->purgeAll();
				return;
			}

			$target = \get_term_link( $term_id, $taxonomy );

			// Logger.
			$this->log( 'hook::edited_term::' . $target, __NAMESPACE__ . '::' . __FUNCTION__, 'debug' );
			// Logger.
			$cleaner->purgeUrl( $target );
		}
	}
}
