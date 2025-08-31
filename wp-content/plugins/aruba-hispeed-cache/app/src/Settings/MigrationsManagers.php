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

namespace ArubaSPA\HiSpeedCache\Settings;

use ArubaSPA\HiSpeedCache\Request\Request;
use ArubaSPA\HiSpeedCache\Traits\Instance;
use ArubaSPA\HiSpeedCache\Traits\HasContainer;

if ( ! \class_exists( __NAMESPACE__ . 'MigrationsManagers' ) ) {
	/**
	 * Add adbmin bar function to purge the cache.
	 */
	class MigrationsManagers {
		use Instance;
		use HasContainer;

		/**
		 * value returned by get_site_option( 'aruba_hispeed_cache_version' ).
		 *
		 * @var string
		 */
		private $db_version;

		/**
		 * value set in app.constant.ARUBA_HISPEED_CACHE_VERSION.
		 *
		 * @var string
		 */
		private $current_ver;

		/**
		 * Does the class support the given request or condition?
		 *
		 * If this returns true, boostrap() will be called. If false, the class will be skipped.
		 *
		 * @return boolean
		 */
		public function support() {
			return $this->container->get_service( Request::class )->is_request_type( 'admin' );
		}

		/**
		 * Method activated if support method returns true.
		 * Used to perform supported actions or queuing actions.
		 *
		 * @return void
		 */
		public function boostrap() {

			if ( \version_compare( $this->db_version, $this->current_ver, '==' ) ) {
				return;
			}

			if ( ! $this->db_version || \version_compare( $this->db_version, $this->current_ver, '<' )) {
				$this->migrate_db_to_v2();
			}

			$this->migrate_version();

		}

		/**
		 * Set up method for admin bar link.
		 *
		 * @return void
		 */
		public function setup() {
			$this->db_version  = \get_site_option( 'aruba_hispeed_cache_version' );
			$this->current_ver = $this->container->get_parameter( 'app.constant.ARUBA_HISPEED_CACHE_VERSION' );
		}

		/**
		 * This function aims to convert the settings present in the data base to make them conform to version 2.
		 * It is assumed that the users have customized the default settings
		 * so we need to manage evaluate the settings present and handle the various cases.
		 *
		 * @return void
		 */
		private function migrate_db_to_v2() {
			$old_db_data = \get_site_option( $this->container->get_parameter( 'app.constant.ARUBA_HISPEED_CACHE_OPTIONS_NAME' ) );
			$old_db_data = ( \is_array( $old_db_data ) ) ? $old_db_data : array();

			$migrated_to_vs_opts = array();

			foreach ( $this->container->get_parameter( 'app.options_list' ) as $key => $value ) {
				if ( ! \array_key_exists( $key, $old_db_data ) ) {
					$migrated_to_vs_opts[ $key ] = $value['default'];
				} else {
					$migrated_to_vs_opts[ $key ] = ( '1' == $old_db_data[ $key ]) ? true : false;
				}
			}

			\update_site_option( $this->container->get_parameter( 'app.constant.ARUBA_HISPEED_CACHE_OPTIONS_NAME' ), $migrated_to_vs_opts );
		}

		private function migrate_version() {
			\update_site_option( 'aruba_hispeed_cache_version', (string) $this->container->get_parameter( 'app.constant.ARUBA_HISPEED_CACHE_VERSION' ) );
		}

	}
}
