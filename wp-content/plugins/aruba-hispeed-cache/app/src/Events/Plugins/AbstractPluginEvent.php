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

use ArubaSPA\HiSpeedCache\Events\AbstractEvents as AbstractEvent;

if ( ! \class_exists( __NAMESPACE__ . 'AbstractPluginEvent' ) ) {
	// phpcs:disable WordPress.NamingConventions

	/**
	 * AbstractPluginEvent.
	 */
	abstract class AbstractPluginEvent extends AbstractEvent {
		/**
		 * Ahsc_purge_on_plugin_actions
		 * Purge cache on plugin activation, deativation
		 *
		 * @param string $plugin The plugin name/file.
		 *
		 * @since 1.2.0
		 *
		 * @return void
		 */
		public function ahsc_purge_on_plugin_actions( $plugin ) {

			$cleaner = $this->get_purger();
			$option  = $this->container->get_service( 'ahsc_get_option' );

			/**
			 * If home page cleaning is set, there is no point in going any further, the entire site cache will be cleaned.
			 */
			if ( $option( 'ahsc_purge_homepage_on_edit' ) || $option( 'ahsc_purge_homepage_on_del' ) ) {
				// Logger.
				$this->log( 'hook::' . \current_filter() . '::home::' . $plugin, __NAMESPACE__ . '::' . __FUNCTION__, 'debug' );
				// Logger.
				$cleaner->purgeAll();
				return;
			}
		}
	}
	// phpcs:enable
}
