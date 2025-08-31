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

namespace ArubaSPA\HiSpeedCache\Events\Themes;

use ArubaSPA\HiSpeedCache\Purger\WpPurger;
use ArubaSPA\HiSpeedCache\Request\Request;
use ArubaSPA\HiSpeedCache\Traits\Instance;
use ArubaSPA\HiSpeedCache\Traits\HasContainer;
use ArubaSPA\HiSpeedCache\Helper\Functions as F;
use ArubaSPA\HiSpeedCache\Events\AbstractEvents as AbstractEvent;

if ( ! \class_exists( __NAMESPACE__ . 'PurgeProxyCacheOnSwitchTheme' ) ) {

	/**
	 * PurgeProxyCacheOnSwitchTheme It is the class that handles the event of 'switch_theme()()'
	 *
	 * Is a class that has no real direct use. But it is handled by the core of this plugin.
	 * It is used to queue the action of clearing the proxy cache to the 'switch_theme' action.
	 *
	 * @see https://developer.wordpress.org/reference/hooks/switch_theme/
	 *
	 * @package Aruba-HiSpeed-Cache
	 * @author  Aruba Developer <hispeedcache.developer@aruba.it>
	 * @version 2.0.0
	 * @access public
	 * @since  1.2.0
	 */
	class PurgeProxyCacheOnSwitchTheme extends AbstractEvent {
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
			return true;
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
			\add_action( 'switch_theme', array( $this, 'ahsc_purge_on_switch_theme' ), 200, 3 );
		}

		/**
		 * Dequeues/unSubscribes proxy cache cleaning action if conditions are supported.
		 *
		 * @access public
		 * @return void
		 */
		public function unsubscribe() {
			\remove_action( 'switch_theme', array( $this, 'ahsc_purge_on_switch_theme' ), 200, 3 );
		}

		/**
		 * Fires after the theme is switched.
		 *
		 * @param  string   $new_name Name of the new theme.
		 * @param  WP_Theme $new_theme Instance of the new theme.
		 * @param  WP_Theme $old_theme Instance of the old theme.
		 * @return void
		 */
		public function ahsc_purge_on_switch_theme( $new_name, $new_theme, $old_theme ) {
			$cleaner = $this->get_purger();
			$option = $this->container->get_service( 'ahsc_get_option' );

			$previous_name = $old_theme->__get( 'title' );
			$next_name     = $new_theme->__get( 'title' );

			/**
			 * If home page cleaning is set, there is no point in going any further, the entire site cache will be cleaned.
			 */
			if ( $option( 'ahsc_purge_homepage_on_edit' ) || $option( 'ahsc_purge_homepage_on_del' ) ) {
				// Logger.
				$this->log( 'hook::switch_theme::home::' . $previous_name . '_to_' . $next_name, __NAMESPACE__ . '::' . __FUNCTION__, 'debug' );
				// Logger.
				$cleaner->purgeAll();
				return;
			}
		}

	}
}
