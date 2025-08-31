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
 * @since    2.0.0
 */

namespace ArubaSPA\HiSpeedCache\Request;

use ArubaSPA\HiSpeedCache\Traits\Instance;

if ( ! \class_exists( __NAMESPACE__ . 'Request' ) ) {

	/**
	 * The requester class to determine what we request; used to determine
	 * the type of request
	 */
	class Request {
		use Instance;

		const INSTALLING_WP = 'installing_wp';
		const FRONTEND      = 'frontend';
		const ADMIN         = 'admin';
		const REST          = 'rest';
		const JSONP         = 'jsonp';
		const AJAX          = 'ajax';
		const XML           = 'xml';
		const CRON          = 'cron';
		const CLI           = 'cli';
		const HEARTBEAT     = 'heartbeat';
		const WCAJAX        = 'wcajax';

		/**
		 * What type of request is this?
		 *
		 * @param  string $type The type to check.
		 * @return boolean|null
		 */
		public function is_request_type( $type ) {
			$checker = "is_$type";

			if ( ! \method_exists( $this::get_instance(), $checker ) ) {
				return null;
			}

			return $this->$checker();
		}

		/**
		 * What type of request is this?
		 *
		 * @return bool
		 * @since 1.0.0
		 */
		public function get_request_type() {
			$checks = array(
				'is_installing_wp' => self::INSTALLING_WP,
				'is_rest'          => self::REST,
				'is_jsonp'         => self::JSONP,
				'is_heartbeat'     => self::HEARTBEAT,
				'is_ajax'          => self::AJAX,
				'is_xml'           => self::XML,
				'is_cron'          => self::CRON,
				'is_cli'           => self::CLI,
				'is_frontend'      => self::FRONTEND,
				'is_admin'         => self::ADMIN,
			);

			foreach ( $checks as $check => $value ) {
				if ( $this->$check() ) {
					return $value;
				}
			}
		}

		/**
		 * Retrun the current page file.
		 *
		 * @return string
		 */
		public function current_page() {
			global $pagenow;
			return $pagenow;
		}

		/**
		 * Determines if given page slug matches the 'page' GET query variable.
		 *
		 * @since  2.0.0
		 *
		 * @param  string $page Page slug.
		 * @return boolean
		 */
		public static function is_page( $page ) {
			return isset( $_GET['page'] ) && $page === $_GET['page']; //phpcs:ignore WordPress.Security.NonceVerification.Recommended
		}

		/**
		 * Is installing WP
		 *
		 * @return bool
		 */
		public function is_installing_wp() {
			return defined( 'WP_INSTALLING' );
		}

		/**
		 * Is frontend
		 *
		 * @return bool
		 */
		public function is_frontend() {
			return ! $this->is_admin() && ! $this->is_cron() && ! $this->is_rest() && ! $this->is_cli();
		}

		/**
		 * Is admin
		 *
		 * @return bool
		 * @since 1.0.0
		 */
		public function is_admin() {

			// The is_user_logged_in method is present in init hook.
			if ( \function_exists( 'is_user_logged_in' ) ) {
				return \is_user_logged_in() && \is_admin();
			}

			return \is_admin();
		}

		/**
		 * Is rest
		 *
		 * @return bool
		 */
		public function is_rest() {
			if ( defined( 'REST_REQUEST' ) && REST_REQUEST ) {
				return defined( 'REST_REQUEST' );
			}

			if ( true === wp_is_json_request() ) {
				return true;
			}

			// if ( function_exists( 'wp_is_json_media_type' ) ) {
			// 	return \wp_is_json_media_type();
			// }

			if ( isset( $_SERVER['REQUEST_URI'] ) ) {
				// If false probably a CLI request.
				$rest_prefix         = trailingslashit( rest_get_url_prefix() );
				$is_rest_api_request = strpos( sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) ), $rest_prefix ) !== false;

				return $is_rest_api_request;
			}

			return false;
		}

		/**
		 * Is Jsonp
		 *
		 * @return bool
		 * @since 1.0.1
		 */
		public function is_jsonp() {
			return ( function_exists( 'wp_is_jsonp_request' ) ) ? wp_is_jsonp_request() : false;
		}

		/**
		 * Is Ajax
		 *
		 * @return bool
		 * @since 1.0.1
		 */
		public function is_ajax() {
			return ( function_exists( 'wp_doing_ajax' ) && wp_doing_ajax() ) || defined( 'DOING_AJAX' );
		}

		/**
		 * Is Xml
		 *
		 * @return bool
		 * @since 1.0.1
		 */
		public function is_xml() {
			return ( function_exists( 'wp_is_xml_request' ) ) ? wp_is_xml_request() : false;
		}

		/**
		 * Is cron
		 *
		 * @return bool
		 * @since 1.0.0
		 */
		public function is_cron() {
			return ( function_exists( 'wp_doing_cron' ) && wp_doing_cron() ) || defined( 'DOING_CRON' );
		}

		/**
		 * Is cli
		 *
		 * @return bool
		 * @since 1.0.0
		 */
		public function is_cli() {
			return defined( 'WP_CLI' ) && WP_CLI; // phpcs:disable ImportDetection.Imports.RequireImports.Symbol -- this constant is global
		}

		/**
		 * Is heartbeat
		 *
		 * @return bool
		 * @since 1.0.0
		 */
		public function is_heartbeat() {
			if ( isset( $_REQUEST['filter_action'] ) && ! empty( $_REQUEST['filter_action'] ) ) { // phpcs:ignore
				return false;
			}

			if ( isset( $_REQUEST['action'] ) && -1 != $_REQUEST['action'] && 'heartbeat' === $_REQUEST['action'] ) { // phpcs:ignore
				return true;
			}

			return false;
		}

		/**
		 * The function checks if the current request is an AJAX request and returns the value of the 'wc-ajax'
		 * parameter if it exists.
		 *
		 * @return string|false the value of `['wc-ajax']` if it is set and not empty. Otherwise, it returns
		 * `false`.
		 */
		public function is_wcajax() {
			if( $this->is_ajax() ) {
				if ( isset( $_REQUEST['wc-ajax'] ) && ! empty( $_REQUEST['wc-ajax'] ) ) { // phpcs:ignore
					return $_REQUEST['wc-ajax'];
				}
			}

			return false;
		}

	}
}
