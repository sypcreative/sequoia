<?php //@phpcs:ignore WordPress.Files.FileName.NotHyphenatedLowercase
/**
 * Aruba HiSpeed Cache
 *
 * @category Wordpress-plugin
 * @author   Aruba Developer <hispeedcache.developer@aruba.it>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @see      Null
 * @since    2.0.0
 * @package  ArubaHispeedCache
 */
namespace ArubaSPA\HiSpeedCache\Core;

use ArubaSPA\HiSpeedCache\Traits\Instance;
use ArubaSPA\HiSpeedCache\Container\ContainerBuilder;

if ( ! \class_exists( __NAMESPACE__ . 'Setup' ) ) {
	/**
	 * Plugin setup hooks (activation, deactivation, uninstall)
	 *
	 * @package ArubaSPA\HiSpeedCache\Core
	 * @since 2.0.0
	 */
	final class Setup {

		/**
		 * Run only once after plugin is activated.
		 *
		 * @docs https://developer.wordpress.org/reference/functions/register_activation_hook/
		 *
		 * @param  \ArubaSPA\HiSpeedCache\Container\ContainerBuilder $config The config.
		 * @return void
		 */
		public static function activation( ContainerBuilder $config ) {
			// Get the option.
			$options = $config->get_parameter( 'app.constant.ARUBA_HISPEED_CACHE_OPTIONS' );

			if ( ! $options ) {
				$options = array_map(
					function( $opt ) {
						return $opt['default'];
					},
					$config->get_parameter( 'app.options_list' )
				);
			}

			\update_site_option( $config->get_parameter( 'app.constant.ARUBA_HISPEED_CACHE_OPTIONS_NAME' ), $options );
			\update_site_option( 'aruba_hispeed_cache_version', $config->get_parameter( 'app.constant.ARUBA_HISPEED_CACHE_VERSION' ) );
		}

		/**
		 * Run only once after plugin is deactivated.
		 *
		 * @docs https://developer.wordpress.org/reference/functions/register_activation_hook/
		 *
		 * @param  \ArubaSPA\HiSpeedCache\Container\ContainerBuilder $config The config.
		 * @return void
		 */
		public static function deactivation( ContainerBuilder $config ) {
			// Deactivation action.
		}

		/**
		 * Deactivates this plugin, hook this function on admin_init.
		 *
		 * @since 1.0.1
		 *
		 * @return void
		 */
		public static function deactivate_me() {
			if ( \function_exists( 'deactivate_plugins' ) ) {
				\deactivate_plugins( ContainerBuilder::get_instance()->get_parameter( 'app.constant.ARUBA_HISPEED_CACHE_BASENAME' ) );
			}
		}
	}
}
