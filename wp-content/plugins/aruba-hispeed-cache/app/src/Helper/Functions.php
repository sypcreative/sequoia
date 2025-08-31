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

namespace ArubaSPA\HiSpeedCache\Helper\Functions;

use ArubaSPA\HiSpeedCache\Core\ServiceCheck;
use ArubaSPA\HiSpeedCache\Helper\AdminNotice;
use ArubaSPA\HiSpeedCache\Container\ContainerBuilder;

if ( ! \function_exists( 'ahsc_get_site_home_url' ) ) {
	/**
	 * Return the complete home url of site.
	 *
	 * @return string
	 */
	function ahsc_get_site_home_url() {
		$home_uri = \trailingslashit( \home_url() );

		if ( \function_exists( 'icl_get_home_url' ) ) {
			$home_uri = \trailingslashit( \icl_get_home_url() );
		}

		return $home_uri;
	}
}

if ( ! \function_exists( 'ahsc_get_debug_file_uri' ) ) {
	/**
	 * Return the complete url of dbFile
	 *
	 * @param  ContainerBuilder $container The Container.
	 * @return string
	 */
	function ahsc_get_debug_file_uri( $container ) {
		if ( $container->has_service( 'logger' ) ) {
			$logger = $container->get_service( 'logger' ); // For php 5.6 compatibility.
			if ( \file_exists( $logger::get_log_file_path_name() ) ) {
				return $logger::get_log_file_url( $container->get_parameter( 'app.constant.ARUBA_HISPEED_CACHE_BASEURL' ) . 'app/src/Debug/log/' );
			}
		}

		return 'false';
	}
}

if ( ! \function_exists( 'ahsc_get_debug_file_content' ) ) {
	/**
	 * Return the contente of log file.
	 *
	 * @return string
	 */
	function ahsc_get_debug_file_content() {
		global $wp_filesystem;
		\WP_Filesystem();
		$container = ContainerBuilder::get_instance();

		if ( $container->has_service( 'logger' ) ) {
			$logger = $container->get_service( 'logger' ); // For php 5.6 compatibility.
			if ( \file_exists( $logger::get_log_file_path_name() ) ) {
				return $wp_filesystem->get_contents( $logger::get_log_file_path_name() );
			}
		}

		return false;
	}
}

if ( ! \function_exists( 'ahsc_has_debug' ) ) {
	/**
	 * Return if the WP_DEBUG is set to true or if the quary string have debug flag.
	 *
	 * @return string
	 */
	function ahsc_has_debug() {
		if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
			return WP_DEBUG;
		}

		if ( isset( $_GET['debug'] ) && '1' === $_GET['debug'] ) { //phpcs:ignore WordPress.Security.NonceVerification.Recommended
			return true;
		}

		return false;
	}
}

if ( ! \function_exists( 'ahsc_save_options' ) ) {
	/**
	 * Undocumented function
	 *
	 * @param  ContainerBuilder $container The container.
	 * @return void
	 */
	function ahsc_save_options( $container ) {
		if ( isset( $_SERVER['REQUEST_METHOD'] ) && 'POST' === $_SERVER['REQUEST_METHOD'] ) {
			if ( isset( $_POST['ahsc_settings_save'] ) && isset( $_POST['ahs-settings-nonce'] ) && \wp_verify_nonce( \sanitize_key( \wp_unslash( $_POST['ahs-settings-nonce'] ) ), 'ahs-save-settings-nonce' ) ) {
				$new_options = array();
				foreach ( array_keys( $container->get_parameter( 'app.options_list' ) ) as $opt_key ) {
					$new_options[ $opt_key ] = ( isset( $_POST[ $opt_key ] ) ) ? true : false;
				}

				if ( \update_site_option( $container->get_parameter( 'app.constant.ARUBA_HISPEED_CACHE_OPTIONS_NAME' ), $new_options ) ) {
					$content = \esc_html__( 'Settings saved.', 'aruba-hispeed-cache' );
					$notice  = new AdminNotice( 'ahs_settings_saved', $content, 'success', true );
					$notice->render();

					// Replace the options.
					$container->replace_parameterbag( 'app.constant.ARUBA_HISPEED_CACHE_OPTIONS', \get_site_option( 'aruba_hispeed_cache_options' ) );
				}
			}
		}
	}
}

if ( ! \function_exists( 'ahsc_get_option' ) ) {

	/**
	 * Return the current plugin option by option key.
	 *
	 * @param  string $opt_key The option key to get.
	 * @return bool|null
	 *
	 *  @SuppressWarnings(PHPMD.StaticAccess)
	 */
	function ahsc_get_option( $opt_key ) {
		$container = ContainerBuilder::get_instance();

		if ( \is_array( $container->get_parameter( 'app.constant.ARUBA_HISPEED_CACHE_OPTIONS' ) ) ) {
			if ( \array_key_exists( $opt_key, $container->get_parameter( 'app.constant.ARUBA_HISPEED_CACHE_OPTIONS' ) ) ) {
				return $container->get_parameter( 'app.constant.ARUBA_HISPEED_CACHE_OPTIONS' )[ $opt_key ];
			}
		}

		return null;
	}
}

/**
 * Notices.
 */
if ( ! \function_exists( 'ahsc_get_check_notice' ) ) {

	/**
	 * Return null or the admin notice to render.
	 *
	 * @param  string $notice_type The option key to get.
	 * @return null|AdminNotice
	 *
	 *  @SuppressWarnings(PHPMD.StaticAccess)
	 */
	function ahsc_get_check_notice( $notice_type ) {
		$container = ContainerBuilder::get_instance();

		$localize_link = $container->get_service( 'localize_link' ); // For php 5.6 compatibility.
		$notice = null;

		switch ( $notice_type->esit ) {
			case ServiceCheck::AVAILABLE:
				$notice['handle']  = 'ahsc-service-warning';
				$notice['type']    = 'warning';
				$notice['message'] = \sprintf(
					\wp_kses(
						// translators: %1$s: the pca url.
						// translators: %2$s: the guide url.
						__( '<strong>The HiSpeed Cache service is not enabled.</strong> To activate it, go to your domain <a href="%1$s" rel="nofollow" target="_blank">control panel</a> (verifying the status may take up to 15 minutes). For further details <a href="%2$s" rel="nofollow" target="_blank">see our guide</a>.', 'aruba-hispeed-cache' ),
						array(
							'strong' => array(),
							'a'      => array(
								'href'   => array(),
								'target' => array(),
								'rel'    => array(),
							),
						)
					),
					esc_html( $localize_link( 'link_aruba_pca' ) ),
					esc_html( $localize_link( 'link_guide' ) )
				);
				break;
			case ServiceCheck::UNAVAILABLE:
				$notice['handle']  = 'ahsc-service-error';
				$notice['type']    = 'error';
				$notice['message'] = \sprintf(
					\wp_kses(
						// translators: %s: the assistance url.
						__( '<strong>The HiSpeed Cache service with which the plugin interfaces is not available on the server that hosts your website.</strong> To use HiSpeed Cache and the plugin, please contact <a href="%s" rel="nofollow" target="_blank">support</a>.', 'aruba-hispeed-cache' ),
						array(
							'strong' => array(),
							'a'      => array(
								'href'   => array(),
								'target' => array(),
								'rel'    => array(),
							),
						)
					),
					esc_html( $localize_link( 'link_assistance' ) )
				);
				break;
			case ServiceCheck::NOARUBASERVER:
				$notice['handle']  = 'ahsc-service-error';
				$notice['type']    = 'error';
				$notice['message'] = \sprintf(
					\wp_kses(
						// translators: %s: the hosting truck url.
						__( '<strong>The Aruba HiSpeed Cache plugin cannot be used because your WordPress website is not hosted on an Aruba hosting platform.</strong> Buy an <a href="%s" rel="nofollow" target="_blank">Aruba Hosting service</a> and migrate your website to use the plugin.', 'aruba-hispeed-cache' ),
						array(
							'strong' => array(),
							'a'      => array(
								'href'   => array(),
								'target' => array(),
								'rel'    => array(),
							),
						)
					),
					esc_html( $localize_link( 'link_hosting_truck' ) )
				);
				break;
		}

		if ( ServiceCheck::ACTIVE !== $notice_type->esit ) {
			$notice = new AdminNotice( $notice['handle'], $notice['message'], $notice['type'], true );
		}

		return $notice;
	}
}

if ( ! \function_exists( 'ahsc_gutenberg_get_check_notice' ) ) {

	/**
	 * Return null or the admin notice to render.
	 *
	 * @param  string $notice_type The option key to get.
	 * @return null|object
	 *
	 *  @SuppressWarnings(PHPMD.StaticAccess)
	 */
	function ahsc_gutenberg_get_check_notice( $notice_type ) {
		$container = ContainerBuilder::get_instance();

		$localize_link = $container->get_service( 'localize_link' ); // For php 5.6 compatibility.
		$notice = null;

		switch ( $notice_type->esit ) {
			case ServiceCheck::AVAILABLE:
				$notice['handle']  = 'ahsc-service-warning';
				$notice['type']    = 'warning';
				$notice['message'] = \sprintf(
					\wp_kses(
						// translators: %1$s: the pca url.
						// translators: %2$s: the guide url.
						__( '<strong>The HiSpeed Cache feature is not enabled.</strong> To enable it, go to your domain <a href="%1$s" rel="nofollow" target="_blank">control panel</a> (verifying the request may take up to 15 minutes). For further details <a href="%2$s" rel="nofollow" target="_blank">see our guide</a>.', 'aruba-hispeed-cache' ),
						array(
							'strong' => array(),
							'a'      => array(
								'href'   => array(),
								'target' => array(),
								'rel'    => array(),
							),
						)
					),
					esc_html( $localize_link( 'link_aruba_pca' ) ),
					esc_html( $localize_link( 'link_guide' ) )
				);
				break;
			case ServiceCheck::UNAVAILABLE:
				$notice['handle']  = 'ahsc-service-error';
				$notice['type']    = 'error';
				$notice['message'] = \sprintf(
					\wp_kses(
						// translators: %s: the assistance url.
						__( '<strong>The HiSpeed Cache service with which the plugin interfaces is not available on the server that hosts your website.</strong> To use HiSpeed Cache and the plugin, please contact <a href="%s" rel="nofollow" target="_blank">support</a>.', 'aruba-hispeed-cache' ),
						array(
							'strong' => array(),
							'a'      => array(
								'href'   => array(),
								'target' => array(),
								'rel'    => array(),
							),
						)
					),
					esc_html( $localize_link( 'link_assistance' ) )
				);
				break;
			case ServiceCheck::NOARUBASERVER:
				$notice['handle']  = 'ahsc-service-error';
				$notice['type']    = 'error';
				$notice['message'] = \sprintf(
					\wp_kses(
						// translators: %s: the hosting truck url.
						__( '<strong>The Aruba HiSpeed Cache plugin cannot be used because your WordPress website is not hosted on an Aruba hosting platform.</strong> Buy an <a href="%s" rel="nofollow" target="_blank">Aruba Hosting service</a> and migrate your website to use the plugin.', 'aruba-hispeed-cache' ),
						array(
							'strong' => array(),
							'a'      => array(
								'href'   => array(),
								'target' => array(),
								'rel'    => array(),
							),
						)
					),
					esc_html( $localize_link( 'link_hosting_truck' ) )
				);
				break;
		}

		$notice_type->notice = $notice;
		return $notice_type;
	}
}

/**
 * Transient Helper.
 */

if ( ! \function_exists( 'ahsc_has_transient' ) ) {

	/**
	 * It checks whether a transinet exists if yes, it returns the value otherwise it returns false.
	 *
	 * @param  string $transient .
	 * @return mixed
	 */
	function ahsc_has_transient( $transient ) {
		$transient_value = ( \is_multisite() ) ? \get_site_transient( (string) $transient ) : \get_transient( (string) $transient );
		return ( false !== $transient_value ) ? $transient_value : false;
	}
}

if ( ! \function_exists( 'ahsc_set_transient' ) ) {

	/**
	 * Undocumented function
	 *
	 * @param  string  $transient .
	 * @param  mixed   $value .
	 * @param  integer $expiration .
	 * @return bool
	 */
	function ahsc_set_transient( $transient, $value, $expiration = 0 ) {
		return ( \is_multisite() ) ?
		\set_site_transient( $transient, $value, $expiration ) :
		\set_transient( $transient, $value, $expiration );
	}
}

if ( ! \function_exists( 'ahsc_delete_transient' ) ) {

	/**
	 * Undocumented function
	 *
	 * @param  string $transient .
	 * @return bool
	 */
	function ahsc_delete_transient( $transient ) {
		return ( \is_multisite() ) ?
		\delete_site_transient( $transient ) :
		\delete_transient( $transient );
	}
}

if ( ! \function_exists( 'ahsc_has_notice' ) ) {

	/**
	 * Return bool if transient notice check is set.
	 *
	 * @param  bool $remove Set to true to clean the transinte if present.
	 * @return mixed
	 *
	 * @SuppressWarnings(PHPMD.StaticAccess)
	 */
	function ahsc_has_notice( $remove = null ) {
		$container = ContainerBuilder::get_instance();

		$has_notice = ahsc_has_transient( $container->get_parameter( 'app.checker.transient_name' ) );

		if ( false !== $has_notice && true === $remove ) {
			if ( \is_multisite() ) {
				\delete_site_transient( $container->get_parameter( 'app.checker.transient_name' ) );
				return false;
			}

			\delete_transient( $container->get_parameter( 'app.checker.transient_name' ) );
			return false;
		}

		return $has_notice;
	}
}

if ( ! \function_exists('ahsc_current_theme_is_fse_theme' ) ) {
	/**
	 * The function checks if the current theme is a Full Site Editing (FSE) theme in PHP.
	 *
	 * @return bool value indicating whether the current theme is a Full Site Editing (FSE) theme.
	 */
	function ahsc_current_theme_is_fse_theme() {
		if ( function_exists( 'wp_is_block_theme' ) ) {
			return (bool) wp_is_block_theme();
		}
		if ( function_exists( 'gutenberg_is_fse_theme' ) ) {
			return (bool) gutenberg_is_fse_theme();
		}
		return false;
	}
}
