<?php //@phpcs:ignore WordPress.Files.FileName.NotHyphenatedLowercase
/**
 * Aruba HiSpeed Cache Container
 *
 * @category Wordpress-plugin
 * @author   Aruba Developer <hispeedcache.developer@aruba.it>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @see      Null
 * @since    2.0.0
 * @package  ArubaHispeedCache
 */

namespace ArubaSPA\HiSpeedCache\Container;

use ArubaSPA\HiSpeedCache\Traits\Instance;
use ArubaSPA\HiSpeedCache\Container\ParamiterBag;

/**
 * Undocumented class
 */
class ContainerBuilder {
	use Instance;

	/**
	 * Parameter Bag.
	 *
	 * @since    2.0.0
	 *
	 * @var \ArubaSPA\HiSpeedCache\Container\ParamiterBag
	 */
	protected $parameter_bag;

	/**
	 * Services Bag
	 *
	 * @since    2.0.0
	 *
	 * @var array
	 */
	protected $services = array();

	//@phpcs:ignore Squiz.Commenting.FunctionComment.Missing
	public function __construct() {
		$this->parameter_bag = new ParamiterBag();
	}

	/**
	 * Get Parameters - Grab all the parameter bag items in the container.
	 *
	 * @since    2.0.0
	 *
	 * @return array
	 */
	public function get_parameters() {
		return $this->parameter_bag->all();
	}

	/**
	 * Get Parameter - Grabs a single item from the parameter bag present in the container.
	 *
	 * @since    2.0.0
	 *
	 * @param  string $key Key to the item(s) to be fetched.
	 * @return string|array|null
	 */
	public function get_parameter( $key ) {
		if ( $this->parameter_bag->has( $key ) ) {
			return $this->parameter_bag->get( $key );
		}

		return null;
	}

	/**
	 * Get Services - Get all registered services in the container
	 *
	 * @since    2.0.0
	 *
	 * @return array
	 */
	public function get_services() {
		return $this->services;
	}

	/**
	 * Get Service - Get a specific service registered in the container.
	 *
	 * @since    2.0.0
	 *
	 * @param  string $key Unique identifier of the service sought.
	 * @return mixed|null
	 */
	public function get_service( $key ) {
		if ( $this->has_service( $key ) ) {
			return $this->services[ $key ];
		}
		return null;
	}

	/**
	 * Has Service - Checks whether a service is registered in the container.
	 *
	 * @since    2.0.0
	 *
	 * @param  string $key Unique identifier of the service sought.
	 * @return boolean
	 */
	public function has_service( $key ) {
		return \array_key_exists( $key, $this->services );
	}

	/**
	 * Set parameterbag - Add elemento to parameter bag
	 *
	 * @param  array       $param_values Valueo of element.
	 * @param  string|null $key Key of element.
	 * @param  bool        $group Group array.
	 * @return void
	 * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
	 */
	public function set_parameterbag( $param_values, $key = null, $group = false ) {

		$param_key = ( ! \is_null( $key ) ) ? $key : 'app.generic';

		foreach ( $param_values as $k => $v ) {

			if ( \is_array( $v ) && ! $group ) {
				$param_key = $param_key . '.' . $k;
				$this->set_parameterbag( $v, $param_key );
				continue;
			}

			if ( \is_array( $v ) && $group ) {
				$this->parameter_bag->add( $param_key, $param_values );
				continue;
			}

			if ( \is_string( $param_key ) ) {
				$this->parameter_bag->add( $param_key . '.' . $k, $v );
			}
		}
	}

	/**
	 * Replace all values or values within the given key
	 * with an array or Dot object
	 *
	 * @param  array|string $param_key The key.
	 * @param  array        $param_values The Value.
	 * @return void
	 */
	public function replace_parameterbag( $param_key, $param_values = [] ) {
		$this->parameter_bag->replace( $param_key, $param_values );
	}

	/**
	 * Add Service add service to container
	 *
	 * @param  string        $key unique key for service.
	 * @param  object|string $class serivece or string form static service.
	 * @return void
	 */
	public function set_service( $key, $class ) {
		$this->services[ $key ] = $class;
	}
}
