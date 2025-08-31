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

namespace ArubaSPA\HiSpeedCache\Core;

use ArubaSPA\HiSpeedCache\Traits\Instance;
use ArubaSPA\HiSpeedCache\Traits\HasContainer;

if ( ! \class_exists( __NAMESPACE__ . 'ActionLinks' ) ) {
	/**
	 * Add adbmin bar function to purge the cache.
	 */
	class ActionLinks {
		use Instance;
		use HasContainer;

		const PARENT_ITEM = 'aruba_spa.php';

		/**
		 * The plugin name.
		 *
		 * @var string
		 */
		private $plugin_name;

		/**
		 * The setting page parent.
		 *
		 * @var string
		 */
		private $setting_page;

		/**
		 * Does the class support the given request or condition?
		 *
		 * If this returns true, boostrap() will be called. If false, the class will be skipped.
		 *
		 * @return boolean
		 */
		public function support() {
			return true;
		}

		/**
		 * Method activated if support method returns true.
		 * Used to perform supported actions or queuing actions.
		 *
		 * @return void
		 */
		public function boostrap() {
			if ( \is_multisite() ) {
				\add_filter( 'network_admin_plugin_action_links_' . $this->plugin_name, array( &$this, 'add_plugin_action_links' ) );
			} else {
				\add_filter( 'plugin_action_links_' . $this->plugin_name, array( &$this, 'add_plugin_action_links' ) );
			}
		}

		/**
		 * Set up method for admin bar link.
		 *
		 * @return void
		 */
		public function setup() {
			$this->plugin_name = $this->container->get_parameter( 'app.constant.ARUBA_HISPEED_CACHE_BASENAME' );

			$this->setting_page = ( ! is_multisite() ) ? 'options-general.php' : 'settings.php';

			if ( \class_exists( \ArubaSPA\Core\AdminMenu::class ) ) {
				$this->setting_page = self::PARENT_ITEM;
			}
		}

		/**
		 * Callback form hooks plugin_action_links_plugin_file.
		 *
		 * @see https://developer.wordpress.org/reference/hooks/plugin_action_links_plugin_file/
		 *
		 * @param  array $actions The action link list.
		 * @return array
		 */
		public function add_plugin_action_links( $actions ) {

			$localized_link = $this->container->get_service( 'localize_link' );

			$settings_link = \sprintf(
				'<a href="%s">%s</a>',
				\network_admin_url( $this->setting_page . '?page=aruba-hispeed-cache' ),
				\__( 'Settings', 'aruba-hispeed-cache' )
			);

			$support_link = \sprintf(
				'<a href="%s" target="_blank">%s</a>',
				$localized_link( 'link_assistance' ),
				\__( 'Customer support', 'aruba-hispeed-cache' )
			);

			\array_unshift( $actions, $settings_link, $support_link );

			return $actions;
		}

	}
}
