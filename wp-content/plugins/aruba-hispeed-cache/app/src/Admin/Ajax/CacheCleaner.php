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

namespace ArubaSPA\HiSpeedCache\Admin\Ajax;

use ArubaSPA\HiSpeedCache\Purger\WpPurger;
use ArubaSPA\HiSpeedCache\Traits\Instance;
use ArubaSPA\HiSpeedCache\Traits\HasContainer;

if ( ! \class_exists( __NAMESPACE__ . 'CacheCleaner' ) ) {
	/**
	 * Add CacheCleaner to ajax
	 */
	class CacheCleaner {
		use Instance;
		use HasContainer;

		/**
		 * Does the class support the given request or condition?
		 *
		 * If this returns true, boostrap() will be called. If false, the class will be skipped.
		 *
		 * @return boolean
		 */
		public function support() {
			$option = $this->container->get_service( 'ahsc_get_option' );
			return (bool) $option( 'ahsc_enable_purge' );
		}

		/**
		 * Method activated if support method returns true.
		 * Used to perform supported actions or queuing actions.
		 *
		 * @return void
		 */
		public function boostrap() {
			\add_action( 'wp_ajax_ahcs_clear_cache', array( $this, 'ahsc_tool_bar_purge' ), 100 );
		}

		/**
		 * Set up method for admin bar link.
		 *
		 * @return void
		 */
		public function setup() {}

		/**
		 * Medoto connected to WP's ajax handler to handle calls to cleaning APIs.
		 *
		 * @return void
		 *
		 * @SuppressWarnings(PHPMD.ElseExpression)
		 */
		public function ahsc_tool_bar_purge() {

			if ( isset( $_POST['ahsc_nonce'] ) && ! \wp_verify_nonce( \sanitize_text_field( \wp_unslash( $_POST['ahsc_nonce'] ) ), 'ahsc-purge-cache' ) ) {
				wp_die( wp_json_encode( $this->container->get_parameter( 'app.ajax_messages.security_error' ) ) );
			}

			$cleaner = $this->container->get_service( WpPurger::class );
			$cleaner->get_container();

			if ( isset( $_POST['ahsc_to_purge'] ) ) {
				$to_purge = \urldecode( \wp_unslash( $_POST['ahsc_to_purge'] ) ); // @phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized

				if ( 'all' === $to_purge ) {
					$cleaner->purgeAll();
				} else {
					$cleaner->purgeUrl( \esc_url_raw( $to_purge ) );
				}

				// Don't forget to stop execution afterward.
				wp_die( wp_json_encode( $this->container->get_parameter( 'app.ajax_messages.success' ) ) );
			}

			// Don't forget to stop execution afterward.
			wp_die( wp_json_encode( $this->container->get_parameter( 'app.ajax_messages.warning' ) ) );
		}

	}
}
