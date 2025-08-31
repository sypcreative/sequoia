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
use ArubaSPA\HiSpeedCache\Helper\Functions as F;
use ArubaSPA\HiSpeedCache\Events\AbstractEvents as AbstractEvent;


if ( ! \class_exists( __NAMESPACE__ . 'PurgeProxyCacheOnEditNavMenu' ) ) {

	/**
	 * PurgeProxyCacheOnEditNavMenu It is the class that handles the event of 'wp_update_nav_menu_object'
	 *
	 * Is a class that has no real direct use. But it is handled by the core of this plugin.
	 * It is used to queue the action of clearing the proxy cache to the 'wp_update_nav_menu' action.
	 *
	 * @package Aruba-HiSpeed-Cache
	 * @author  Aruba Developer <hispeedcache.developer@aruba.it>
	 * @version 2.0.0
	 * @access public
	 * @since  1.2.1
	 */
	class PurgeProxyCacheOnEditNavMenu extends AbstractEvent {
		use Instance;
		//use HasContainer;

		/**
		 * $is_purged = Variable its purpose and determine whether the purger is fired.
		 *
		 * @var boolean
		 */
		private $is_purged = false;

		/**
		 * Does the Event support the given request or condition?
		 *
		 * If this returns true, boostrap() will be called. If false, the class will be skipped.
		 *
		 * @return boolean
		 */
		public function support() {
			if ( ! F\ahsc_current_theme_is_fse_theme() &&
			'nav-menus.php' === $this->container->get_service( Request::class )->current_page() ) {

				$option = $this->container->get_service( 'ahsc_get_option' );

				return (bool) $option( 'ahsc_purge_archive_on_edit' ) ||
				$option( 'ahsc_purge_archive_on_del' );

			}
			return false;
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
			 * Fires when edit a nav menu, before any modifications are made to posts or terms.
			 */
			\add_action( 'wp_update_nav_menu', array( $this, 'ahsc_update_nav_menu' ), 200, 0 );
		}

		/**
		 * Dequeues/unSubscribes proxy cache cleaning action if conditions are supported.
		 *
		 * @access public
		 * @return void
		 */
		public function unsubscribe() {
			\remove_action( 'wp_update_nav_menu', array( $this, 'ahsc_update_nav_menu' ), 200 );
		}

		/**
		 * Fires after a nav menu has been updated, and the term cache has been cleaned.
		 *
		 * @see https://developer.wordpress.org/reference/hooks/wp_update_nav_menu/
		 *
		 * param int   $menu_id   ID of the updated menu.
		 * param array $menu_data An array of menu data.
		 *
		 * @return void
		 */
		public function ahsc_update_nav_menu() {

			if ( ! $this->is_purged ) {
				$cleaner = $this->get_purger();

				// Logger.
				$this->log( 'hook::wp_update_nav_menu::home', __NAMESPACE__ . '::' . __FUNCTION__, 'debug' );
				// Logger.
				$cleaner->purgeAll();
			}

			$this->is_purged = true;

		}
	}
}
