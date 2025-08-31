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
namespace ArubaSPA\HiSpeedCache\CacheWarmer\Ajax;

use ArubaSPA\HiSpeedCache\Traits\Instance;
use ArubaSPA\HiSpeedCache\Traits\HasContainer;

if ( ! \class_exists( __NAMESPACE__ . 'CacheWarmer' ) ) {
	/**
	 * Add CacheCleaner to ajax
	 */
	class CacheWarmer {
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
			\add_action( 'wp_ajax_ahcs_cache_warmer', array( $this, 'ahsc_cache_warmer_ajax_action' ), 100 );
			\add_action( 'wp_ajax_nopriv_ahcs_cache_warmer', array( $this, 'ahsc_cache_warmer_ajax_action' ), 100 );
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
		public function ahsc_cache_warmer_ajax_action() {
			$do_warmer = array();

			if ( isset( $_POST['ahsc_cw_nonce'] ) && ! \wp_verify_nonce( \sanitize_text_field( \wp_unslash( $_POST['ahsc_cw_nonce'] ) ), 'ahsc-cache-warmer' ) ) {
				wp_die( wp_json_encode( $this->container->get_parameter( 'app.ajax_messages.security_error' ) ) );
			}

			// If a static page has not been set as the site's home.
			if ( 'posts' === \get_option( 'show_on_front' )) {
				$do_warmer[] = \get_home_url( null, '/' );
			}

			// If a static page has been set as the site's home.
			if ( 'page' === get_option( 'show_on_front' ) ) {
				$do_warmer[] = \get_permalink( \get_option( 'page_on_front' ) );
				$blog_list = \get_option( 'page_for_posts' );

				// I check whether the two urls are different. If no page is set as 'article page', the same url is returned.
				if ( '0' != $blog_list ) {
					$do_warmer[] = \get_post_type_archive_link( 'post' ) ;
				}
			}

			if( class_exists( 'woocommerce' ) ) {
				$do_warmer[] = get_permalink( wc_get_page_id( 'shop' ) );
			}

			$recent_posts = wp_get_recent_posts(array(
				'numberposts' => 10, // Number of recent posts
				'post_status' => 'publish' // Get only the published posts
			));

			foreach ($recent_posts as $recent_post) {
				$do_warmer[] = get_permalink( $recent_post['ID'] );
			}

			foreach ( $do_warmer as $warmer_item ) {
				\wp_remote_get( $warmer_item );
			}

			wp_die( wp_json_encode( array('esit' => true, 'items' => $do_warmer) ) );

		}

	}
}
