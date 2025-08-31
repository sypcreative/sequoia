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
use ArubaSPA\HiSpeedCache\Traits\HasContainer;

if ( ! \class_exists( __NAMESPACE__ . 'AdminBar' ) ) {
	/**
	 * Add adbmin bar function to purge the cache.
	 */
	class AdminBar {
		use Instance;
		use HasContainer;

		const PARENT_ITEM = 'aruba_spa';

		/**
		 * The title to display on admin bar link.
		 *
		 * @var string
		 */
		private $title = '';

		/**
		 * The Url to purge.
		 *
		 * @var string
		 */
		private $topurge = '';

		/**
		 * The ajax call params.
		 *
		 * array (
		 * 'ahsc_ajax_url' => \admin_url( 'admin-ajax.php' ),
		 * 'ahsc_topurge'  => $this->topurge,
		 * 'ahsc_nonce'    => \wp_create_nonce( 'ahsc-purge-cache' ),
		 * )
		 *
		 * @var array
		 */
		private $js_param = array();

		/**
		 * Does the class support the given request or condition?
		 *
		 * If this returns true, boostrap() will be called. If false, the class will be skipped.
		 *
		 * @return boolean
		 */
		public function support() {
			// TODO: check if the manage_options is appropriate perm.
			$option = $this->container->get_service( 'ahsc_get_option' );
			return (bool) $option( 'ahsc_enable_purge' ) && \current_user_can( 'manage_options' );
		}

		/**
		 * Method activated if support method returns true.
		 * Used to perform supported actions or queuing actions.
		 *
		 * @return void
		 */
		public function boostrap() {
			// TODO: Thinking about whether to discriminate by is_network_admin.
			// @see https://developer.wordpress.org/reference/functions/is_network_admin/.
			\add_action( 'admin_bar_menu', array( $this, 'add_admin_bar_menu_links' ), 100 );

			// Customize the toolbar.js script.
			\add_action( 'wp_enqueue_scripts', array( $this, 'ahsc_localize_toolbar_js' ) );
			\add_action( 'admin_enqueue_scripts', array( $this, 'ahsc_localize_toolbar_js' ) );
		}

		/**
		 * Set up method for admin bar link.
		 *
		 * @return void
		 */
		public function setup() {
			$this->topurge = 'current-url';
			$this->title   = __( 'Purge page cache', 'aruba-hispeed-cache' );

			if ( \is_admin() ) {
				$this->topurge = 'all';
				$this->title   = __( 'Purge Cache', 'aruba-hispeed-cache' );
			}

			$this->js_param = array(
				'ahsc_ajax_url' => \admin_url( 'admin-ajax.php' ),
				'ahsc_topurge'  => $this->topurge,
				'ahsc_nonce'    => \wp_create_nonce( 'ahsc-purge-cache' ),
			);
		}

		/**
		 * Netodo che aggiunge action link alla admin bar.
		 *
		 * @doc see https://developer.wordpress.org/reference/hooks/admin_bar_menu/
		 *
		 * @param  WP_Admin_Bar $wp_admin_bar The WP_Admin_Bar instance, passed by reference.
		 * @return void
		 */
		public function add_admin_bar_menu_links( $wp_admin_bar ) {
			$wp_admin_bar->add_menu(
				array(
					'id'     => 'ahsc-purge-link',
					'parent' => ( ! \is_null( $wp_admin_bar->get_node( self::PARENT_ITEM ) ) ) ? self::PARENT_ITEM : false,
					'title'  => $this->get_title(),
					'meta'   => array(
						'title'        => $this->title,
						'data-topurge' => $this->topurge,
						'onclick'      => 'ahscBtnPurger(); return;'
					),
				)
			);
		}

		/**
		 * Get the title whit icon.
		 *
		 * @return string
		 */
		private function get_title() {
			$title = '<span class="ab-icon ahsc-ab-icon" aria-hidden="true"></span><span class="ab-label">' . $this->title . '</span>';
			return $title;
		}

		/**
		 * This method adds and localizes the toolbar.js on both the frontend and backend.
		 *
		 * @return void
		 */
		public function ahsc_localize_toolbar_js() {
			\wp_add_inline_script( 'ahcs-toolbar', 'const AHSC_TOOLBAR = ' . \wp_json_encode( $this->js_param ), 'before' );
		}

	}
}
