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

namespace ArubaSPA\HiSpeedCache\Settings;

use ArubaSPA\HiSpeedCache\Request\Request;
use ArubaSPA\HiSpeedCache\Traits\Instance;
use ArubaSPA\HiSpeedCache\Traits\HasContainer;

if ( ! \class_exists( __NAMESPACE__ . 'RegisterSettings' ) ) {
	/**
	 * Add adbmin bar function to purge the cache.
	 */
	class RegisterSettings {
		use Instance;
		use HasContainer;

		const T_STRING = 'string';
		const T_BOOL   = 'boolean';
		const T_INT    = 'integer';
		const T_NUM    = 'number';
		const T_ARRAY  = 'array';
		const T_OBJECT = 'object';

		/**
		 * The field bag.
		 *
		 * @var array
		 */
		public $fields = array();

		/**
		 * Option group name.
		 *
		 * @var string
		 */
		public $option_group;

		/**
		 * The properties array
		 *
		 * @var array
		 */
		public $properties = array();

		/**
		 * Does the class support the given request or condition?
		 *
		 * If this returns true, boostrap() will be called. If false, the class will be skipped.
		 *
		 * @return boolean
		 */
		public function support() {
			return (bool) $this->container->get_parameter( 'app.constant.ARUBA_HISPEED_CACHE_PLUGIN' );
		}

		/**
		 * Method activated if support method returns true.
		 * Used to perform supported actions or queuing actions.
		 *
		 * @return void
		 */
		public function boostrap() {
			\add_action( 'admin_init', array( &$this, 'register_ahsc_settings' ) );
			\add_action( 'rest_api_init', array( &$this, 'register_ahsc_settings' ) );
		}

		/**
		 * Set up method for admin bar link.
		 *
		 * @return void
		 */
		public function setup() {
			$this->option_group = $this->container->get_parameter( 'app.constant.ARUBA_HISPEED_CACHE_OPTIONS_NAME' );

			$this->properties   = array_map(
				function( $opt ) {
					return $opt['type'];
				},
				$this->container->get_parameter( 'app.options_list' )
			);
		}

		/**
		 * Register plugin options or settings.
		 *
		 * @return void
		 */
		public function register_ahsc_settings() {
			\register_setting(
				$this->option_group,
				$this->option_group,
				array(
					'type'         => 'object',
					'show_in_rest' => array(
						'schema' => array(
							'type'                 => 'object',
							'properties'           => $this->properties,
							'additionalProperties' => array(
								'type' => 'boolean',
							),
						),
					),
				)
			);
		}
	}
}
