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

namespace ArubaSPA\HiSpeedCache\Helper;

if ( ! \class_exists( __NAMESPACE__ . 'AdminNotice' ) ) {
	/**
	 * Add admin notice to OOP.
	 *
	 * @see form more complete solution https://github.com/TypistTech/wp-admin-notices/blob/master/src/Notice.php
	 */
	class AdminNotice {

		/**
		 * The notice's unique identifier. Also used to permanently dismiss a sticky notice.
		 *
		 * @var string
		 */
		protected $handle;

		/**
		 * The HTML content of the notice.
		 *
		 * @var string
		 */
		protected $content;

		/**
		 * HTML class for the notice.
		 *
		 * @var string
		 */
		protected $html_class;

		/**
		 * The AJAX request's 'action' property for sticky notices.
		 *
		 * @var string
		 */
		protected $action;

		/**
		 * The class list.
		 */
		const HTML_CLASS = array(
			'error'   => 'notice notice-error',
			'warning' => 'notice notice-warning',
			'success' => 'notice notice-success',
			'info '   => 'notice notice-info',
		);

		/**
		 * Notice constructor.
		 *
		 * @param string      $handle  The notice's unique identifier. Also used to permanently dismiss a sticky
		 *                             notice.
		 * @param string      $content The HTML content of the notice.
		 * @param string|null $type    The notice's type. Expecting one of UPDATE_NAG, ERROR, WARNING, INFO, SUCCESS.
		 *                             Default is INFO.
		 * @param bool|null   $is_dismissible if the notice is dismissabile.
		 * @param string|null $action  $action AJAX request's 'action' property for sticky notices.
		 */
		public function __construct( $handle, $content, $type = null, $is_dismissible = null, $action = null ) {
			$this->handle     = sanitize_key( $handle );
			$this->content    = wp_kses_post( $content );
			$this->html_class = ( ! \is_null( $type ) ) ? self::HTML_CLASS[ $type ] : self::HTML_CLASS['info'];
			if ( true === $is_dismissible ) {
				$this->html_class .= ' is-dismissible';
			}
			$this->action = $action;
		}

		/**
		 * Echo notice to screen.
		 *
		 * @return void
		 */
		public function render() {
			printf(
				'<div id="%1$s" class="%2$s"><p>%3$s</p></div>',
				esc_attr( $this->handle ),
				esc_attr( $this->html_class ),
				wp_kses_post( $this->content )
			);
		}

		/**
		 * Echo notice to screen.
		 *
		 * @return void
		 */
		public function render_sticky() {
			printf(
				'<div id="%1$s" data-handle="%1$s" data-action="%2$s" class="%3$s"><p>%4$s</p></div>',
				esc_attr( $this->handle ),
				esc_attr( $this->action ),
				esc_attr( $this->html_class ),
				wp_kses_post( $this->content )
			);
		}

	}
}
