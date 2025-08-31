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

namespace ArubaSPA\HiSpeedCache\Admin;

use ArubaSPA\HiSpeedCache\Traits\Instance;
use ArubaSPA\HiSpeedCache\Core\ServiceCheck;
use ArubaSPA\HiSpeedCache\Traits\HasContainer;
use ArubaSPA\HiSpeedCache\Helper\Functions as F;

if ( ! \class_exists( __NAMESPACE__ . 'AdminAssets' ) ) {
	/**
	 * Add adbmin bar function to purge the cache.
	 */
	class AdminAssets {
		use Instance;
		use HasContainer;

		const ADMIN_PAGE = 'settings_page_aruba-hispeed-cache';

		/**
		 * Does the class support the given request or condition?
		 *
		 * If this returns true, boostrap() will be called. If false, the class will be skipped.
		 *
		 * @return boolean
		 */
		public function support() {
			return (bool) $this->container->get_parameter( 'app.constant.ARUBA_HISPEED_CACHE_PLUGIN' );
		}

		/**
		 * Method activated if support method returns true.
		 * Used to perform supported actions or queuing actions.
		 *
		 * @return void
		 */
		public function boostrap() {
			// Admin Bar.
			$option = $this->container->get_service( 'ahsc_get_option' );
			if ( $option( 'ahsc_enable_purge' ) ) {
				if ( current_user_can( 'manage_options' ) ) {
					\add_action( 'wp_after_admin_bar_render', array( $this, 'ahsc_adminbar_inline_style' ), 100 );
					\add_action( 'wp_enqueue_scripts', array( $this, 'ahsc_enqueue_toolbar_js' ) );
				}
				\add_action( 'admin_enqueue_scripts', array( $this, 'ahsc_enqueue_toolbar_js' ) );
			}

			\add_action( 'admin_enqueue_scripts', array( $this, 'ahsc_enqueue_admin_js_css' ) );

		}

		/**
		 * Set up method for admin bar link.
		 *
		 * @return void
		 */
		public function setup() {}

		/**
		 * Add in line style after the adbmin tool bar.
		 *
		 * @return void
		 */
		public function ahsc_adminbar_inline_style() {
			$icon = "#wp-admin-bar-ahsc-purge-link .ahsc-ab-icon:before {
				content: '\\f17e';
				top: 4px;
			}";

			$loader = "#ahsc-loader-toolbar{
				display: none;
				background: rgba(255, 255, 255, .7) url('data:image/gif;base64,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') no-repeat center;
				position: fixed;
				top: 0;
				left: 0;
				width: 100%;
				height: 100%;
				z-index: 9998;
			}";

			\printf(
				'<style type="text/css">
				%1$s
				%2$s
				</style>',
				$icon, //@phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				$loader //@phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			);
		}

		/**
		 * This method adds and localizes the toolbar.js on both the frontend and backend.
		 *
		 * @return void
		 */
		public function ahsc_enqueue_toolbar_js() {
			\wp_enqueue_script(
				'ahcs-toolbar',
				$this->container->get_parameter( 'app.constant.ARUBA_HISPEED_CACHE_BASEURL' ) . 'app/assets/js/toolbar.js',
				array(),
				time(),
				true
			);
		}

		/**
		 * Enquee admin js & stile.
		 *
		 * @param  string $hook The hook name.
		 * @return void
		 */
		public function ahsc_enqueue_admin_js_css( $hook ) {

			// if ( self::ADMIN_PAGE !== $hook ) {
			// 	return;
			// }

			if ( $this->container->get_parameter( 'app.requirements.is_legacy_pre_59' ) ) {

				if ( F\ahsc_has_notice() ) {
					\wp_enqueue_style(
						'ahcs-settings-page-notice',
						$this->container->get_parameter( 'app.constant.ARUBA_HISPEED_CACHE_BASEURL' ) . 'app/assets/css/ahsc-notice.css',
						array(),
						time()
					);
				}

				if ( self::ADMIN_PAGE !== $hook ) {
					return;
				}

				\wp_enqueue_style(
					'ahcs-settings-page',
					$this->container->get_parameter( 'app.constant.ARUBA_HISPEED_CACHE_BASEURL' ) . 'app/assets/css/option-page.css',
					array(),
					time()
				);

				\wp_enqueue_script(
					'ahcs-settings-page',
					$this->container->get_parameter( 'app.constant.ARUBA_HISPEED_CACHE_BASEURL' ) . 'app/assets/js/option-page.js',
					array(),
					time(),
					true
				);
			}

			if ( self::ADMIN_PAGE !== $hook ) {
				return;
			}

			if ( ! $this->container->get_parameter( 'app.requirements.is_legacy_pre_59' ) ) {
				// https://wholesomecode.ltd/create-a-settings-page-using-wordpress-block-editor-gutenberg-components
				// Block UI.
				$script_asset = require $this->container->get_parameter( 'app.constant.ARUBA_HISPEED_CACHE_BASEPATH' ) . 'app' . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'dashboard' . DIRECTORY_SEPARATOR . 'admin.asset.php';

				\wp_enqueue_script(
					'block-ahcs-settings-page-js',
					$this->container->get_parameter( 'app.constant.ARUBA_HISPEED_CACHE_BASEURL' ) . 'app/assets/dashboard/admin.js',
					$script_asset['dependencies'],
					$script_asset['version'],
					true
				);

				\wp_set_script_translations(
					'block-ahcs-settings-page-js',
					'aruba-hispeed-cache',
					$this->container->get_parameter('app.constant.ARUBA_HISPEED_CACHE_BASEPATH') . 'languages'
				);

				\wp_enqueue_style(
					'block-ahcs-settings-page-css',
					$this->container->get_parameter( 'app.constant.ARUBA_HISPEED_CACHE_BASEURL' ) . 'app/assets/dashboard/style-admin.css',
					array( 'wp-components' ),
					$script_asset['version']
				);

				$localized_ahscisleGutenberg = array(
						'version'    => $this->container->get_parameter( 'app.constant.ARUBA_HISPEED_CACHE_VERSION' ),
						'assetsPath' => $this->container->get_parameter( 'app.constant.ARUBA_HISPEED_CACHE_BASEPATH' ) . 'app' . DIRECTORY_SEPARATOR . 'assets',
						'logosrc'    => $this->container->get_parameter('app.constant.ARUBA_HISPEED_CACHE_BASEURL') . 'app/assets/imgs/icon-256x256.png',
						'servCheck'  => F\ahsc_gutenberg_get_check_notice( ( new ServiceCheck() )->check() ),
						'homePage'   => F\ahsc_get_site_home_url(),
						'purger'     => array(
							'ahsc_ajax_url' => \admin_url( 'admin-ajax.php' ),
							'ahsc_topurge'  => 'all',
							'ahsc_nonce'    => \wp_create_nonce( 'ahsc-purge-cache' ),
						),
				);

				if( null != $this->container->get_parameter('app.generic.debug.enabled') ) {
					$localized_ahscisleGutenberg['isDebug'] = ( $this->container->get_parameter( 'app.generic.debug.enabled' ) ) ? 'true' : 'false';
					$localized_ahscisleGutenberg['duBugFile']  = F\ahsc_get_debug_file_content();
				}

				\wp_localize_script(
					'block-ahcs-settings-page-js',
					'ahscisleGutenberg',
					$localized_ahscisleGutenberg
				);
			}
		}
	}
}
