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

namespace ArubaSPA\HiSpeedCache;

use ArubaSPA\HiSpeedCache\Core\I18n;
use ArubaSPA\HiSpeedCache\Purger\WpPurger;
use ArubaSPA\HiSpeedCache\Request\Request;
use ArubaSPA\HiSpeedCache\Traits\Instance;
use ArubaSPA\HiSpeedCache\Core\ServiceCheck;
use ArubaSPA\HiSpeedCache\Helper\Functions as F;
use ArubaSPA\HiSpeedCache\Container\ContainerBuilder;

if ( ! \class_exists( __NAMESPACE__ . 'ArubaHispeedCache' ) ) {

	/**
	 * ArubaHispeedCache
	 *
	 * This class provides the basis for implementing all business logic for Aruba HiSpeed Cache wp plugin.
	 * Strongly inspired by frameworks such as synfony or laravel.
	 */
	class ArubaHispeedCache {
		use Instance;

		/**
		 * The Aruba HiSpeed Cache Service Contaniner.
		 *
		 * @var \ArubaSPA\HiSpeedCache\Container\ContainerBuilder
		 */
		private $container;

		/**
		 * The loades Service.
		 *
		 * @var array
		 */
		private $services_loader = array();

		/**
		 * The loades Events.
		 *
		 * @var array
		 */
		private $events_loader = array();

		/**
		 * The loades Events.
		 *
		 * @var array
		 */
		public $is_initialized = false;

		/**
		 * Create a ArubaHispeedCache container instance.
		 *
		 * @SuppressWarnings(PHPMD.StaticAccess)
		 */
		public function __construct() {
			$this->container = ContainerBuilder::get_instance();
		}

		/**
		 * Init the plugin config setup.
		 *
		 * @param  string $config_file The path of confing file.
		 * @param  string $file The __FILE__ constat.
		 * @return void
		 * @SuppressWarnings(PHPMD.StaticAccess)
		 */
		public function init_setup( $config_file = null, $file = null ) {
			if ( null != $config = $this->resolve_config( $config_file, $file ) ) {
				$this->container->set_parameterbag( $config['constant'], 'app.constant' );
				$this->container->set_parameterbag( $config['options_list'], 'app.options_list', true );
				$this->container->set_parameterbag( $config['core'], 'app.core' );
				$this->container->set_parameterbag( $config['requirements'], 'app.requirements' );
				$this->container->set_parameterbag( $config['ajax'], 'app.ajax_messages', true );
				$this->container->set_parameterbag( $config['checker'], 'app.checker' );

				// $env = getenv();
				// $this->container->set_parameterbag( $env, 'env' );

				// Load the service.
				$this->plugin_load_services( $config );

				$this->events_loader = $config['events'];

				$this->is_initialized = true;
			}
		}

		/**
		 * Aruba HiSpeed Cache Service Loader.
		 *
		 * @param  array $configs List of services registered in the config file to be uploaded.
		 * @return void
		 *
		 * @SuppressWarnings(PHPMD.StaticAccess)
		 */
		public function plugin_load_services( $configs ) {

			// Request.
			$request = Request::get_instance();
			$this->container->set_service( Request::class, $request );
			$this->container->set_parameterbag(
				array(
					'type'     => $request->get_request_type(),
					'page_now' => $request->current_page(),
				),
				'request'
			);

			// WP Purger.
			$purger = WpPurger::get_instance();
			$purger->setPurger( $configs['purger'] );
			$this->container->set_service( WpPurger::class, $purger );

			if( \file_exists( \dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'Debug' . DIRECTORY_SEPARATOR . 'Enable.php' ) ) {
				require_once( \dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'Debug' . DIRECTORY_SEPARATOR . 'Enable.php' );
			}

			// Link Helper.
			$links = $configs['links'];
			$this->container->set_service(
				'localize_link',
				function ( $link ) use ( $links ) {
					$locale = substr( \get_locale(), 0, 2 ); // return it_IT -> it.

					if ( array_key_exists( $locale, $links[ $link ] ) ) {
						return $links[ $link ][ $locale ];
					}

					return $links[ $link ]['it'];
				}
			);

			$this->container->set_service(
				'ahsc_get_option',
				function ( $opt_key ) {
					return F\ahsc_get_option( $opt_key );
				}
			);

			$this->services_loader = $configs['services'];
		}

		/**
		 * Verify that the service serves or is supported, and in house queue the actions.
		 * Is hooked to the wp plugins_loaded action.
		 *
		 * @see https://developer.wordpress.org/reference/hooks/plugins_loaded/
		 * @return void
		 */
		public function plugin_register_services() {
			// Register the supporterd service.
			foreach ( $this->services_loader as $service ) {
				$class = $this->resolve_service( $service );

				if ( \in_array( \ArubaSPA\HiSpeedCache\Traits\HasContainer::class, class_uses( $class ), true ) ) {
					$class->get_container();
				}

				if ( \method_exists( $class, 'support' ) ) {
					if ( $class->support() ) {
						$this->container->set_service( $service, $class );
					}
					continue;
				}

				$this->container->set_service( $service, $class );
			}

			foreach ( $this->container->get_services() as $registered_service ) {

				if ( false === $this->container->get_parameter( 'app.constant.ARUBA_HISPEED_CACHE_PLUGIN' ) ) {
					continue;
				}

				if ( \method_exists( $registered_service, 'setup' ) ) {
					$registered_service->setup();
				}

				if ( \method_exists( $registered_service, 'boostrap' ) ) {
					$registered_service->boostrap();
				}
			}
		}

		/**
		 * It contains all the logic to subscribe events/hooks is hooked to the wp init action.
		 *
		 * @see https://developer.wordpress.org/reference/hooks/init/
		 * @return void
		 */
		public function plugin_event_subscriber() {

			// Stop propagation if is heartbeat request.
			$request = $this->container->get_service( Request::class );
			if ( $request->is_heartbeat() ) {
				return;
			}

			if ( false === F\ahsc_get_option( 'ahsc_enable_purge' ) ) {
				return;
			}

			$loaded_events = array();

			foreach ( $this->events_loader as $event ) {

				$resolved_event = $event::get_instance();

				if ( $resolved_event instanceof \ArubaSPA\HiSpeedCache\Events\AbstractEvents ) {
					$resolved_event->get_container();
				}

				if ( \in_array( \ArubaSPA\HiSpeedCache\Traits\HasContainer::class, class_uses( $resolved_event ), true ) ) {
					$resolved_event->get_container();
				}

				if ( \method_exists( $resolved_event, 'support' ) ) {
					if ( $resolved_event->support() ) {
						$resolved_event->setup();
						$resolved_event->subscribe();
						$loaded_events[] = $event;
					}
				}
			}

			$this->container->set_parameterbag( $loaded_events, 'loaded_events' );
		}

		/**
		 * Dedicated method during plugin activation. This mode sets all options active.
		 * If imported options already exist it updates them but does not activate deactivated options.
		 *
		 * @return void
		 *
		 * @SuppressWarnings(PHPMD.StaticAccess)
		 */
		public function activation() {
			Core\Setup::activation( $this->container );
		}

		/**
		 * Dedicated method during plugin deactivation.
		 *
		 * @return void
		 *
		 * @SuppressWarnings(PHPMD.StaticAccess)
		 */
		public function deactivation() {
			Core\Setup::deactivation( $this->container );
		}

		/**
		 * Resolve che config of the plugin.
		 *
		 * @param  string $config_file The path of confing file.
		 * @param  string $file The __FILE__ constat.
		 * @return array
		 */
		private function resolve_config( $config_file, $file ) {
			$file   = $file;

			$config = null;
			if( !isset($_GET['customize_changeset_uuid'] ) ) {
				$config = require $config_file;
			}

			return $config;
		}

		/**
		 * Resolve the service for the plugin.
		 *
		 * @param  string $service The service class name.
		 * @return object|string
		 */
		private function resolve_service( $service ) {
			if ( \in_array( \ArubaSPA\HiSpeedCache\Traits\Instance::class, class_uses( $service ), true ) ) {
				$class = $service::get_instance();
				return $class;
			}

			return $service;
		}

		/**
		 * Check_hispeed_cache_services - check if aruba hispeed cache service is activable or is a aruba server.
		 *
		 * @param  string $plugin The plugin fine relative path.
		 * @return void
		 *
		 * @SuppressWarnings(PHPMD.StaticAccess)
		 */
		public function check_hispeed_cache_services( $plugin ) {
			if ( 'aruba-hispeed-cache/aruba-hispeed-cache.php' === $plugin ) {
				$runtime_check = new ServiceCheck();
				$check         = $runtime_check->check();

				if ( \is_multisite() ) {
					\set_site_transient(
						$this->container->get_parameter( 'app.checker.transient_name' ),
						$check,
						$this->container->get_parameter( 'app.checker.transient_life_time' )
					);

					return;
				}

				\set_transient(
					$this->container->get_parameter( 'app.checker.transient_name' ),
					$check,
					$this->container->get_parameter( 'app.checker.transient_life_time' )
				);

				return;
			}
		}
	}
}
