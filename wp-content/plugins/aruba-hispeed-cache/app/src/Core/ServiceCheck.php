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
namespace ArubaSPA\HiSpeedCache\Core;

use ArubaSPA\HiSpeedCache\Container\ContainerBuilder;

if ( ! \class_exists( __NAMESPACE__ . 'ServiceCheck' ) ) {
	/**
	 * Internationalization and localization definitions
	 *
	 * @package ArubaSPA\HiSpeedCache\Core
	 * @since 1.0.1
	 */
	final class ServiceCheck {

		/**
		 * Target The address you want to test.
		 *
		 * @var string
		 */
		private $target;

		/**
		 * Header Request header parameters.
		 *
		 * @var array
		 */
		private $headers;

		/**
		 * Check_error Control variable to determine
		 * if an error was encountered during the request.
		 *
		 * @var boolean
		 */
		private $check_error = false;

		/**
		 * $status_code The code of the request.
		 * It is used to perform some integrity checks.
		 *
		 * @var integer
		 */
		private $status_code;

		/**
		 * Param for the check.
		 *
		 * @var object
		 */
		public $check_parameters;

		/**
		 * The container instance.
		 *
		 * @var ContainerBuilder
		 */
		private $container;

		/**
		 * The esit list const.
		 */
		const ACTIVE        = 'active';
		const AVAILABLE     = 'available';
		const UNAVAILABLE   = 'unavailable';
		const NOARUBASERVER = 'no-aruba-server';

		/**
		 * ServiceCheck.
		 *
		 * @param string $target Target The address you want to test default is null.
		 *
		 * @since  1.0.1
		 * @return void
		 */
		public function __construct( $target = null ) {
			$this->container = ContainerBuilder::get_instance();

			$this->target = ( \is_null( $target ) ) ?
				$this->container->get_parameter( 'app.constant.HOME_URL' ) :
				$target;

			$this->check_parameters = new \stdClass();
		}

		/**
		 * Set_parameters_to_check Set the parameters to perform the service check.
		 *
		 * @param  boolean $is_aruba_server Set to true if the site is on aruba server.
		 * @param  boolean $service_is_active Set to true if the service is active.
		 * @param  boolean $service_is_activabile Set to true if the service is activabile.
		 * @param  string  $service_status The value of x-aruba-cache header def is ''.
		 * @return void
		 */
		private function set_parameters_to_check(
			$is_aruba_server,
			$service_is_active,
			$service_is_activabile,
			$service_status = ''
		) {
			$this->check_parameters->is_aruba_server       = $is_aruba_server;
			$this->check_parameters->service_is_active     = $service_is_active;
			$this->check_parameters->service_is_activabile = $service_is_activabile;
			$this->check_parameters->service_status        = $service_status;
		}

		/**
		 * Headers_analizer - Analyze the request headers and value the variables.
		 *
		 * @since  1.0.1
		 * @return void
		 */
		private function headers_analizer() {
			$this->get_headers();

			/**
			 * If the request headers are empty or the request
			 * produced a wp_error then I set everything to true.
			 */
			if ( empty( $this->headers ) || $this->check_error ) {
				$this->set_parameters_to_check( true, true, true );
				return;
			}

			/**
			 * If the headers contain 'x-aruba-cache' we are on an aruba server.
			 * If it has value NA we are on servers without cache.
			 */
			if ( array_key_exists( 'x-aruba-cache', $this->headers ) ) {
				$is_active     = true;
				$is_activabile = true;
				if ( 'NA' === $this->headers['x-aruba-cache'] ) {
					$is_activabile = false;
					$is_active     = false;
				}
				$this->set_parameters_to_check( true, $is_active, $is_activabile, $this->headers['x-aruba-cache'] );
				return;
			}

			/**
			 * If the headers do not contain 'x-aruba-cache'
			 * we are not on the aruba server.
			 *
			 * If the 'server' header contains 'aruba-proxy'
			 * the service can be activated.
			 *
			 * If it is different from 'aruba-proxy' we are
			 * not on aruba server or behind cdn.
			 */
			if ( array_key_exists( 'server', $this->headers ) ) {
				switch ( $this->headers['server'] ) {
					case 'aruba-proxy':
						$this->set_parameters_to_check( true, false, true );
						break;
					default:
						$this->set_parameters_to_check( false, false, false );

						if ( array_key_exists( 'x-servername', $this->headers ) && str_contains( $this->headers['x-servername'], 'aruba.it' ) ) {
							$this->set_parameters_to_check( false, false, true );
						}
						break;
				}
				return;
			}
		}

		/**
		 * Check - Check the populated variables and issue a control message.
		 *
		 * @since  1.0.1
		 * @return string
		 */
		public function check() {
			$this->headers_analizer();

			$this->check_parameters->esit = self::ACTIVE;

			if ( $this->check_parameters->is_aruba_server && ! $this->check_parameters->service_is_active ) {
				$this->check_parameters->esit = ( $this->check_parameters->service_is_activabile ) ? self::AVAILABLE : self::UNAVAILABLE;
				return $this->check_parameters;
			}

			if ( ! $this->check_parameters->is_aruba_server && ! $this->check_parameters->service_is_active ) {
				$this->check_parameters->esit = ( $this->check_parameters->service_is_activabile ) ? self::AVAILABLE : self::NOARUBASERVER;
				return $this->check_parameters;
			}

			return $this->check_parameters;
		}

		/**
		 * Debugger - It exposes some elements of the control to
		 * try to resolve any errors. To activate it, just go to the
		 * dominio.tld/wp-admin/options-general.php?page=aruba-hispeed-cache&debug=1
		 *
		 * @return string
		 */
		public function debugger() {
			$this->get_headers();

			$data = array(
				'date'         => \wp_date( 'D, d M Y H:i:s', time() ),
				'target'       => $this->target,
				'headers'      => $this->headers,
				'status_code'  => $this->status_code,
				'check_params' => $this->check_parameters,
			);

			if ( $this->check_error ) {
				$data['error'] = 'Sorry but the call was answered with an error. please try again later';
				unset( $data['headers'] );
				unset( $data['status_code'] );
				unset( $data['check_params'] );
			}

			return var_export( $data, true ); //phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_var_export
		}

		/**
		 * Get_headers - Getter of the headers for the request to perform the check.
		 *
		 * @since  1.0.1
		 * @return bool
		 */
		private function get_headers() {
			$response = \wp_remote_get(
				$this->target,
				array(
					'sslverify'   => false,
					'user-agent'  => 'aruba-ua',
					'httpversion' => '1.1',
					'timeout'     => $this->container->get_parameter( 'app.checker.request_timeout' ),
				)
			);

			if ( \is_array( $response ) && ! \is_wp_error( $response ) ) {
				$this->headers     = $response['headers']->getAll();
				$this->status_code = $response['response']['code'];

				return true;
			}

			if ( \is_wp_error( $response ) ) {
				$this->check_error = true;
			}

			return false;
		}

	}
}
