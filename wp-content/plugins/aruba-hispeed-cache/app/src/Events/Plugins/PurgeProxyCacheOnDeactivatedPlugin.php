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

namespace ArubaSPA\HiSpeedCache\Events\Plugins;

use ArubaSPA\HiSpeedCache\Purger\WpPurger;
use ArubaSPA\HiSpeedCache\Request\Request;
use ArubaSPA\HiSpeedCache\Traits\Instance;
use ArubaSPA\HiSpeedCache\Traits\HasContainer;
use ArubaSPA\HiSpeedCache\Helper\Functions as F;
use ArubaSPA\HiSpeedCache\Events\Plugins\AbstractPluginEvent;


if ( ! \class_exists( __NAMESPACE__ . 'PurgeProxyCacheOnDeactivatedPlugin' ) ) {

	/**
	 * PurgeProxyCacheOnDeactivatedPlugin It is the class that handles the event of 'deactivate_plugins()'
	 *
	 * Is a class that has no real direct use. But it is handled by the core of this plugin.
	 * It is used to queue the action of clearing the proxy cache to the 'deactivate_plugin' action.
	 *
	 * @see https://developer.wordpress.org/reference/hooks/deactivate_plugin/
	 *
	 * @package Aruba-HiSpeed-Cache
	 * @author  Aruba Developer <hispeedcache.developer@aruba.it>
	 * @version 2.0.0
	 * @access public
	 * @since  1.2.0
	 */
	class PurgeProxyCacheOnDeactivatedPlugin extends AbstractPluginEvent {
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
			\add_action( 'deactivate_plugin', array( $this, 'ahsc_purge_on_plugin_actions' ), 200, 1 );
		}

		/**
		 * Dequeues/unSubscribes proxy cache cleaning action if conditions are supported.
		 *
		 * @access public
		 * @return void
		 */
		public function unsubscribe() {
			\remove_action( 'deactivate_plugin', array( $this, 'ahsc_purge_on_plugin_actions' ), 200 );
		}

	}
}
