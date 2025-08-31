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

use ArubaSPA\HiSpeedCache\Core\Setup;
use ArubaSPA\HiSpeedCache\Traits\Instance;
use ArubaSPA\HiSpeedCache\Helper\AdminNotice;
use ArubaSPA\HiSpeedCache\Traits\HasContainer;
use ArubaSPA\HiSpeedCache\Container\ContainerBuilder;

if ( ! \class_exists( __NAMESPACE__ . 'Requirements' ) ) {
	/**
	 * Internationalization and localization definitions
	 *
	 * @package ArubaSPA\HiSpeedCache\Core
	 * @since 2.0.0
	 */
	final class Requirements {
		use Instance;
		use HasContainer;

		/**
		 * Flag to check wp version end php version.
		 *
		 * @var boolean
		 */
		private $check = true;

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
		 * Set up method for admin bar link.
		 *
		 * @return void
		 */
		public function setup() {
			$this->required_wp_version();
			$this->required_php_version();
		}

		/**
		 * Method activated if support method returns true.
		 * Used to perform supported actions or queuing actions.
		 *
		 * @return void
		 */
		public function boostrap() {
			if ( ! $this->check ) {
				$this->container->replace_parameterbag( 'app.constant', array( 'ARUBA_HISPEED_CACHE_PLUGIN' => false ) );
				\add_action(
					'admin_init',
					function () {
						Setup::deactivate_me();
					}
				);
			}
		}

		/**
		 * Compares WP versions and add admin_notice if it's not compatible
		 *
		 * @return void
		 */
		private function required_wp_version() {
			global $wp_version;

			$wp_min_version = $this->container->get_parameter( 'app.requirements.minimum_wp' );

			if ( ! \version_compare( $wp_version, $wp_min_version, '>=' ) ) {

				$content = \sprintf(
					// translators: %s: the wp min required version.
					\esc_html__( 'Sorry, Aruba HiSpeed Cache requires WordPress %s or higher.', 'aruba-hispeed-cache' ),
					$wp_min_version
				);

				$notice = new AdminNotice( 'ahs_wp_version', $content, 'error' );

				\add_action( 'admin_notices', array( $notice, 'render' ) );
				\add_action( 'network_admin_notices', array( $notice, 'render' ) );

				$this->check = false;
			}
		}

		/**
		 * Compares PHP versions and add admin_notice if it's not compatible
		 *
		 * @return void
		 */
		private function required_php_version() {
			$php_min_version = $this->container->get_parameter( 'app.requirements.minimum_php' );

			if ( ! \version_compare( phpversion(), $php_min_version, '>=' ) ) {

				$content = \sprintf(
					// translators: %s: the min php version required.
					\esc_html__( 'Sorry, Aruba HiSpeed Cache requires PHP %s or higher.', 'aruba-hispeed-cache' ),
					$php_min_version
				);

				$notice = new AdminNotice( 'ahs_wp_version', $content, 'error' );

				\add_action( 'admin_notices', array( $notice, 'render' ) );
				\add_action( 'network_admin_notices', array( $notice, 'render' ) );

				$this->check = false;
			}
		}

	}
}
