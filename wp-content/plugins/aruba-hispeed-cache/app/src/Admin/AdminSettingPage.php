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
use ArubaSPA\HiSpeedCache\Core\ServiceCheck;
use ArubaSPA\HiSpeedCache\Traits\HasContainer;
use ArubaSPA\HiSpeedCache\Helper\Functions as F;

if ( ! \class_exists( __NAMESPACE__ . 'AdminSettingPage' ) ) {
	/**
	 * Add adbmin bar function to purge the cache.
	 */
	class AdminSettingPage {
		use Instance;
		use HasContainer;

		const PARENT_ITEM = 'aruba_spa.php';

		/**
		 * The field bag.
		 *
		 * @var array
		 */
		public $fields = array();

		/**
		 * The parent slug.
		 *
		 * @var string
		 */
		private $parent_slug;

		/**
		 *
		 * @var object ServiceCheck::class
		 */
		private $runtime_check;

		/**
		 * The ajax call params.
		 *
		 * array(
		 * 'ahsc_ajax_url' => \admin_url( 'admin-ajax.php' ),
		 * 'ahsc_topurge'  => 'all',
		 * 'ahsc_nonce'    => \wp_create_nonce( 'ahsc-purge-cache' ),
		 * 'ahsc_confirm'  => \esc_html__( 'You are about to purge the entire cache. Do you want to continue?', 'aruba-hispeed-cache' ),
		 * );
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
			return (bool) $this->container->get_parameter( 'app.constant.ARUBA_HISPEED_CACHE_PLUGIN' ) && \current_user_can( 'manage_options' );
		}

		/**
		 * Method activated if support method returns true.
		 * Used to perform supported actions or queuing actions.
		 *
		 * @return void
		 * @SuppressWarnings(PHPMD.ElseExpression)
		 */
		public function boostrap() {
			if ( \is_multisite() ) {
				\add_action( 'network_admin_menu', array( &$this, 'add_admin_settings_page' ) );
			} else {
				\add_action( 'admin_menu', array( &$this, 'add_admin_settings_page' ) );
			}

			$request = $this->container->get_service( Request::class );
			if ( $request::is_page( 'aruba-hispeed-cache' ) ) {
				\add_action( 'admin_enqueue_scripts', array( $this, 'ahsc_localize_option_page_js' ) );
			}
		}

		/**
		 * Set up method for admin bar link.
		 *
		 * @return void
		 */
		public function setup() {
			$this->parent_slug = ( ! is_multisite() ) ? 'options-general.php' : 'settings.php';

			if ( \class_exists( \ArubaSPA\Core\AdminMenu::class ) ) {
				$this->parent_slug = self::PARENT_ITEM;
			}

			$this->js_param = array(
				'ahsc_ajax_url' => \admin_url( 'admin-ajax.php' ),
				'ahsc_topurge'  => 'all',
				'ahsc_nonce'    => \wp_create_nonce( 'ahsc-purge-cache' ),
				'ahsc_confirm'  => \esc_html__( 'You are about to purge the entire cache. Do you want to continue?', 'aruba-hispeed-cache' ),
			);
		}

		/**
		 * Wrap fro add_submenu_page.
		 *
		 * @see https://developer.wordpress.org/reference/functions/add_submenu_page/
		 *
		 * @return void
		 */
		public function add_admin_settings_page() {
				\add_submenu_page(
					$this->parent_slug,
					__( 'Aruba HiSpeed Cache', 'aruba-hispeed-cache' ),
					__( 'Aruba HiSpeed Cache', 'aruba-hispeed-cache' ),
					'manage_options',
					'aruba-hispeed-cache',
					array( &$this, 'render_setting_page_cb' )
				);
		}

		/**
		 * Render the Setting Admin page.
		 *
		 * @return void
		 */
		public function render_setting_page_cb() {
			if ( ! \current_user_can( 'manage_options' ) ) {
				\wp_die(
					esc_html__( 'Sorry, you need to be an administrator to use HiSpeed Cache.', 'aruba-hispeed-cache' )
				);
			}

			if ( $this->container->get_parameter( 'app.requirements.is_legacy_pre_59' ) ) {
				/**
				 * Save option helper.
				 */
				F\ahsc_save_options( $this->container );

				/**
				 * Generation the form fields.
				 */
				$this->add_fields();

				/**
				 * Check in real time.
				 */
				F\ahsc_has_notice( true ); // remove the transient.

				$this->runtime_check = new ServiceCheck();
				$check               = F\ahsc_get_check_notice( $this->runtime_check->check() );

				if ( ! \is_null( $check ) ) {
					$check->render();
				}

				include $this->container->get_parameter( 'app.constant.ARUBA_HISPEED_CACHE_BASEPATH' ) . 'app' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'admin' . DIRECTORY_SEPARATOR . 'admin-settings-page.php';
				return;
			}

			echo '<div id="ahsc-settings"></div>';
		}

		/**
		 * This method add files to settings form.
		 *
		 * @return void
		 */
		private function add_fields() {
			$this->fields = array();
			$option       = $this->container->get_service( 'ahsc_get_option' );

			$this->fields['sections']['general']['general'] = array(
				'ids'   => array( 'ahsc_enable_purge' ),
				'name'  => \esc_html__( 'Cache purging options', 'aruba-hispeed-cache' ),
				'class' => '',
			);

			$this->fields['ahsc_enable_purge'] = array(
				'name'    => \esc_html__( 'Enable automatic purge of the cache', 'aruba-hispeed-cache' ),
				'type'    => 'checkbox',
				'id'      => 'ahsc_enable_purge',
				'checked' => \checked( $option( 'ahsc_enable_purge' ), true, false ),
			);

			$is_hidden = ! $option( 'ahsc_enable_purge' );

			$this->fields['sections']['general']['settings_tittle'] = array(
				'title' => \esc_html__( 'Automatically purge the entire cache when:', 'aruba-hispeed-cache' ),
				'type'  => 'title',
				'class' => ( $is_hidden ) ? 'hidden' : '',
			);

			$this->fields['sections']['general']['homepage'] = array(
				'ids'   => array( 'ahsc_purge_homepage_on_edit', 'ahsc_purge_homepage_on_del' ),
				'name'  => \esc_html__( 'Home page:', 'aruba-hispeed-cache' ),
				// 'legend' => \esc_html__( 'Home page:', 'aruba-hispeed-cache' ),
				'class' => ( $is_hidden ) ? 'hidden' : '',
			);

			$this->fields['ahsc_purge_homepage_on_edit'] = array(
				'name'    => \wp_kses( __( 'A post (or page/custom post) is modified or added.', 'aruba-hispeed-cache' ), array( 'strong' => array() ) ),
				'type'    => 'checkbox',
				'id'      => 'ahsc_purge_homepage_on_edit',
				'checked' => \checked( $option( 'ahsc_purge_homepage_on_edit' ), 1, false ),
			);

			$this->fields['ahsc_purge_homepage_on_del'] = array(
				'name'    => wp_kses( __( 'a <strong>published post</strong> (or page/custom post) is <strong>cancelled</strong>.', 'aruba-hispeed-cache' ), array( 'strong' => array() ) ),
				'type'    => 'checkbox',
				'id'      => 'ahsc_purge_homepage_on_del',
				'checked' => \checked( $option( 'ahsc_purge_homepage_on_del' ), 1, false ),
			);

			$this->fields['sections']['general']['pages'] = array(
				'ids'   => array( 'ahsc_purge_page_on_mod', 'ahsc_purge_page_on_new_comment', 'ahsc_purge_page_on_deleted_comment' ),
				'name'  => \esc_html__( 'Post/page/custom post:', 'aruba-hispeed-cache' ),
				// 'legend' => \esc_html__( 'Post/page/custom post type:', 'aruba-hispeed-cache' ),
				'class' => ( $is_hidden ) ? 'hidden' : '',
			);

			$this->fields['ahsc_purge_page_on_mod'] = array(
				'name'    => wp_kses( __( 'A post is published', 'aruba-hispeed-cache' ), array( 'strong' => array() ) ),
				'type'    => 'checkbox',
				'id'      => 'ahsc_purge_page_on_mod',
				'checked' => \checked( $option( 'ahsc_purge_page_on_mod' ), 1, false ),
			);

			$this->fields['ahsc_purge_page_on_new_comment'] = array(
				'name'    => wp_kses( __( 'A comment is published', 'aruba-hispeed-cache' ), array( 'strong' => array() ) ),
				'type'    => 'checkbox',
				'id'      => 'ahsc_purge_page_on_new_comment',
				'checked' => \checked( $option( 'ahsc_purge_page_on_new_comment' ), 1, false ),
			);

			$this->fields['ahsc_purge_page_on_deleted_comment'] = array(
				'name'    => wp_kses( __( 'A comment is not approved or is deleted', 'aruba-hispeed-cache' ), array( 'strong' => array() ) ),
				'type'    => 'checkbox',
				'id'      => 'ahsc_purge_page_on_deleted_comment',
				'checked' => \checked( $option( 'ahsc_purge_page_on_deleted_comment' ), 1, false ),
			);

			$this->fields['sections']['general']['archives'] = array(
				'ids'    => array( 'ahsc_purge_archive_on_edit', 'ahsc_purge_archive_on_del' ),
				'name'   => \esc_html__( 'Archives:', 'aruba-hispeed-cache' ),
				'legend' => \esc_html__( '(date, category, tag, author, custom taxonomies)', 'aruba-hispeed-cache' ),
				'class'  => ( $is_hidden ) ? 'hidden' : '',
			);

			$this->fields['ahsc_purge_archive_on_edit'] = array(
				'name'    => wp_kses( __( 'a <strong>post</strong> (or page/custom post) is <strong>modified</strong> or <strong>added</strong>.', 'aruba-hispeed-cache' ), array( 'strong' => array() ) ),
				'type'    => 'checkbox',
				'id'      => 'ahsc_purge_archive_on_edit',
				'checked' => \checked( $option( 'ahsc_purge_archive_on_edit' ), 1, false ),
			);

			$this->fields['ahsc_purge_archive_on_del'] = array(
				'name'    => wp_kses( __( 'A published post (or page/custom post) is deleted', 'aruba-hispeed-cache' ), array( 'strong' => array() ) ),
				'type'    => 'checkbox',
				'id'      => 'ahsc_purge_archive_on_del',
				'checked' => \checked( $option( 'ahsc_purge_archive_on_del' ), 1, false ),
			);

			$this->fields['sections']['general']['comments'] = array(
				'ids'   => array( 'ahsc_purge_archive_on_new_comment', 'ahsc_purge_archive_on_deleted_comment' ),
				'name'  => \esc_html__( 'Comments', 'aruba-hispeed-cache' ),
				// 'legend' => \esc_html__( '(date, category, tag, author, custom taxonomies)', 'aruba-hispeed-cache' ),
				'class' => ( $is_hidden ) ? 'hidden' : '',
			);

			$this->fields['ahsc_purge_archive_on_new_comment'] = array(
				'name'    => wp_kses( __( 'A comment is published', 'aruba-hispeed-cache' ), array( 'strong' => array() ) ),
				'type'    => 'checkbox',
				'id'      => 'ahsc_purge_archive_on_new_comment',
				'checked' => \checked( $option( 'ahsc_purge_archive_on_new_comment' ), 1, false ),
			);

			$this->fields['ahsc_purge_archive_on_deleted_comment'] = array(
				'name'    => wp_kses( __( 'A comment is not approved or is deleted', 'aruba-hispeed-cache' ), array( 'strong' => array() ) ),
				'type'    => 'checkbox',
				'id'      => 'ahsc_purge_archive_on_deleted_comment',
				'checked' => \checked( $option( 'ahsc_purge_archive_on_deleted_comment' ), 1, false ),
			);


			$this->fields['sections']['cache_warmer']['settings_tittle'] = array(
				'title' => \esc_html__( 'Cache Warming:', 'aruba-hispeed-cache' ),
				'type'  => 'title',
				'class' => ( $is_hidden ) ? 'hidden' : '',
			);

			$this->fields['sections']['cache_warmer']['general'] = array(
				'ids'   => array( 'ahsc_cache_warmer' ),
				'name'  => \esc_html__( 'Cache Warming options', 'aruba-hispeed-cache' ),
				'class' => '',
			);

			$this->fields['ahsc_cache_warmer'] = array(
				'name'    => wp_kses( __( '<strong>Enables Cache Warming.</strong>', 'aruba-hispeed-cache' ), array( 'strong' => array() ) ),
				'legend' => wp_kses( __( 'Cache Warming is the process through which webpages are preloaded in the cache so they can be displayed quicker.<br> When the cache is emptied, the homepage data and the last ten posts of the site are automatically renewed to guarantee faster page loading', 'aruba-hispeed-cache' ), array( 'strong' => array(), 'br' => array() ) ),
				'type'    => 'checkbox',
				'id'      => 'ahsc_cache_warmer',
				'checked' => \checked( $option( 'ahsc_cache_warmer' ), 1, false ),
			);

		}

		/**
		 * This method adds and localizes the option-page.js on both the frontend and backend.
		 *
		 * @return void
		 */
		public function ahsc_localize_option_page_js() {
			\wp_add_inline_script( 'ahcs-settings-page', 'const AHSC_OPTIONS_CONFIGS = ' . \wp_json_encode( $this->js_param ), 'before' );
		}
	}
}
