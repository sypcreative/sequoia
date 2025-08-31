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

use ArubaSPA\HiSpeedCache\Request\Request;
use ArubaSPA\HiSpeedCache\Traits\Instance;
use ArubaSPA\HiSpeedCache\Traits\HasContainer;
use ArubaSPA\HiSpeedCache\Helper\Functions as F;

if ( ! \class_exists( __NAMESPACE__ . 'AdminNotice' ) ) {
	/**
	 * Add adbmin bar function to purge the cache.
	 */
	class AdminNotice {
		use Instance;
		use HasContainer;

		public $has_notice;

		/**
		 * Does the class support the given request or condition?
		 *
		 * If this returns true, boostrap() will be called. If false, the class will be skipped.
		 *
		 * @return boolean
		 */
		public function support() {
			// If I'm on the plugin admin page, I return false so I don't have a double notification.
			$request = $this->container->get_service( Request::class );
			if ( $request::is_page( 'aruba-hispeed-cache' ) ) {
				return false;
			}

			// Checking whether the control being activated has generated a transient.
			// If yes return true and active the class otherwise the class is inactive.
			$this->has_notice = F\ahsc_has_notice();

			if ( ! $this->has_notice ) {
				return false;
			}

			return true;
		}

		/**
		 * Method activated if support method returns true.
		 * Used to perform supported actions or queuing actions.
		 *
		 * @return void
		 */
		public function boostrap() {
			add_action( 'admin_notices', array( $this, 'check_hispeed_cache_notices' ) );
		}

		/**
		 * Render della notifica in base alla logica presente in ahsc_get_check_notice.
		 *
		 * @return void
		 */
		public function check_hispeed_cache_notices() {
			$check = F\ahsc_get_check_notice( $this->has_notice );

			if ( ! \is_null( $check ) ) {
				$check->render();
			}

		}

	}
}
