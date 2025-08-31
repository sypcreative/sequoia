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
use ArubaSPA\HiSpeedCache\Request\Request;
use ArubaSPA\HiSpeedCache\Traits\Instance;
use ArubaSPA\HiSpeedCache\Traits\HasContainer;
use ArubaSPA\HiSpeedCache\Helper\Functions as F;
use ArubaSPA\HiSpeedCache\Events\AbstractEvents as AbstractEvent;

if ( ! \class_exists( __NAMESPACE__ . 'BulkActionManager' ) ) {

	/**
	 * PurgeProxyCacheOnSwitchTheme It is the class that handles the event of 'check_admin_referer()()'
	 *
	 * Is a class that has no real direct use. But it is handled by the core of this plugin.
	 * It is used to queue the action of clearing the proxy cache to the 'check_admin_referer' action.
	 *
	 * @see https://developer.wordpress.org/reference/hooks/check_admin_referer/
	 *
	 * @package Aruba-HiSpeed-Cache
	 * @author  Aruba Developer <hispeedcache.developer@aruba.it>
	 * @version 2.0.0
	 * @access public
	 * @since  1.2.0
	 */
	class BulkActionManager extends AbstractEvent {
		use Instance;
		//use HasContainer;

		/**
		 * Manager array.
		 *
		 * @var array
		 */
		private $manager = array();

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
		public function setup() {
			/**
			 * A array
			 * Key string the bulk action nonce.
			 * Value array the list of event/s to remove.
			 */

			$plugin_action = array(
				\ArubaSPA\HiSpeedCache\Events\Plugins\PurgeProxyCacheOnActivatedPlugin::class,
				\ArubaSPA\HiSpeedCache\Events\Plugins\PurgeProxyCacheOnDeactivatedPlugin::class,
				\ArubaSPA\HiSpeedCache\Events\Plugins\PurgeProxyCacheOnDeletePlugin::class,
			);

			$this->manager = array(
				'bulk-tags'          => array(
					\ArubaSPA\HiSpeedCache\Events\Term\PurgeProxyCacheOnEditTerm::class,
					\ArubaSPA\HiSpeedCache\Events\Term\PurgeProxyCacheOnEditNavMenu::class,
					\ArubaSPA\HiSpeedCache\Events\Term\PurgeProxyCacheOnDeleteTerm::class,
				),
				'bulk-posts'          => array(
					\ArubaSPA\HiSpeedCache\Events\PostType\PurgeProxyCacheOnTransitionStatus::class,
					\ArubaSPA\HiSpeedCache\Events\PostType\PurgeProxyCacheOnPostUpdated::class,
				),
				'bulk-comments'       => array(
					\ArubaSPA\HiSpeedCache\Events\Comments\PurgeProxyCacheOnNewComment::class,
					\ArubaSPA\HiSpeedCache\Events\Comments\PurgeProxyCacheOnDeletedComment::class,
					\ArubaSPA\HiSpeedCache\Events\Comments\PurgeProxyCacheTransitionCommentStatus::class,
				),
				'bulk-plugins'        => $plugin_action,
				'bulk-update-plugins' => $plugin_action,
			);
		}

		/**
		 * Queues/Subscribes proxy cache cleaning action if conditions are supported.
		 *
		 * @access public
		 * @return void
		 */
		public function subscribe() {
			\add_action( 'check_admin_referer', array( $this, 'ahsc_bulk_manager'), 200, 2 );
			\add_action( 'check_ajax_referer', array( $this, 'ahsc_ajax_manager'), 200, 2 );
		}

		/**
		 * Dequeues/unSubscribes proxy cache cleaning action if conditions are supported.
		 *
		 * @access public
		 * @return void
		 */
		public function unsubscribe() {}

		/**
		 * The filter hook is applied in the default check_admin_referrer() function after the nonce has been checked for security purposes; this allows a plugin to force WordPress to die for extra security reasons. Note that check_admin_referrer is also a “pluggable” function, meaning that plugins can override it.
		 *
		 * @param string    $action The nonce action.
		 * @param false|int $result False if the nonce is invalid, 1 if the nonce is valid and generated between 0-12 hours ago, 2 if the nonce is valid and generated between 12-24 hours ago.
		 * @return void
		 */
		public function ahsc_bulk_manager( $action, $result ) {

			if ( false === \strpos( $action, 'bulk' ) ) {
				return;
			}

			if ( false === \array_key_exists( $action, $this->manager ) ) {
				$this->log( 'Bulk action detected action:' . $action, 'info' );
				return;
			}

			$hooks = $this->manager[ $action ];

			foreach ( $hooks as $hook ) {
				$this->log( 'Remove the event ' . $hook, 'Removing' );
				$event = $hook::get_instance();
				$event->unsubscribe();
			}

			//
			$do_purge             = F\ahsc_has_transient( 'ahsc_do_purge_deferred' );
			$do_purge_log_message = 'Transint presente';

			if ( ! $do_purge ) {
				$do_purge_log_message = 'Transint non presente presente lo imposto';
				F\ahsc_set_transient( 'ahsc_do_purge_deferred', \time(), MINUTE_IN_SECONDS );
			}

			$this->log( $do_purge_log_message, 'deferred', 'info' );

			$cache_warmer = $this->get_purger();
			$cache_warmer->cache_warmer();
		}

		/**
		 * The filter hook is applied in the default check_admin_referrer() function after the nonce has been checked for security purposes; this allows a plugin to force WordPress to die for extra security reasons. Note that check_admin_referrer is also a “pluggable” function, meaning that plugins can override it.
		 *
		 * @param string    $action The nonce action.
		 * @param false|int $result False if the nonce is invalid, 1 if the nonce is valid and generated between 0-12 hours ago, 2 if the nonce is valid and generated between 12-24 hours ago.
		 * @return void
		 */
		public function ahsc_ajax_manager( $action, $result ) {

			$ajax_manager = array(
				'updates'
			);

			if ( false === \array_key_exists( $action, $ajax_manager ) ) {
				$this->log( 'Ajax action detected action:' . $action, 'info' );
				return;
			}

			$do_purge             = F\ahsc_has_transient( 'ahsc_do_purge_deferred' );
			$do_purge_log_message = 'Transint presente';

			if ( ! $do_purge ) {
				$do_purge_log_message = 'Transint non presente presente lo imposto';
				F\ahsc_set_transient( 'ahsc_do_purge_deferred', \time(), MINUTE_IN_SECONDS );
			}

			$this->log( $do_purge_log_message, 'deferred', 'info' );

			$cache_warmer = $this->get_purger();
			$cache_warmer->cache_warmer();
		}

	}
}
