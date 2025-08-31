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

namespace ArubaSPA\HiSpeedCache\CacheWarmer;

use ArubaSPA\HiSpeedCache\Traits\Instance;
use ArubaSPA\HiSpeedCache\Core\ServiceCheck;
use ArubaSPA\HiSpeedCache\Traits\HasContainer;
use ArubaSPA\HiSpeedCache\Helper\Functions as F;

if ( ! \class_exists( __NAMESPACE__ . 'CacheWarmerManager' ) ) {
	/**
	 * Add adbmin bar function to purge the cache.
	 */
	class CacheWarmerManager {
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
			return (bool) $option( 'ahsc_cache_warmer' );
		}

		/**
		 * Method activated if support method returns true.
		 * Used to perform supported actions or queuing actions.
		 *
		 * @return void
		 */
		public function boostrap() {
			/**
			 * I check whether the cleaning transient is present in case. I remove it and clear the entire proxy cache.
			 */
			$do_purge = F\ahsc_has_transient( 'ahsc_do_cache_warmer' );

			if ( $do_purge ) {
				\add_action( 'init', array( $this, 'init_subscriber') );
			}
		}

		/**
		 * Set up method for admin bar link.
		 *
		 * @return void
		 */
		public function setup() {}

		public function init_subscriber() {
			\add_action('admin_footer', array( $this, 'ahsc_cache_warmer_runner') );
			\add_action('wp_footer', array( $this, 'ahsc_cache_warmer_runner'));
		}

		public function ahsc_cache_warmer_runner() {

			$ajax_uri = \admin_url( 'admin-ajax.php' );
			$action   = 'ahcs_cache_warmer';
			$nonce    = \wp_create_nonce( 'ahsc-cache-warmer' );

			$js_runner = <<<EOF
<script>
	( function() {
		const data = new FormData();
		data.append("action", "$action");
		data.append("ahsc_cw_nonce", "$nonce" );

		fetch( "$ajax_uri", {method: "POST",
			credentials: "same-origin",
			body: data}
		).then( r => r.json() ).then( rr => console.log('Chace Rigenerata') );
	}());
</script>
EOF;

				print($js_runner);

				F\ahsc_delete_transient('ahsc_do_cache_warmer');
		}
	}
}
