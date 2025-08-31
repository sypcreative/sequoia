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

namespace ArubaSPA\HiSpeedCache\Events\PostType;

use ArubaSPA\HiSpeedCache\Purger\WpPurger;
use ArubaSPA\HiSpeedCache\Request\Request;
use ArubaSPA\HiSpeedCache\Traits\Instance;
use ArubaSPA\HiSpeedCache\Traits\HasContainer;
use ArubaSPA\HiSpeedCache\Helper\Functions as F;
use ArubaSPA\HiSpeedCache\Events\AbstractEvents as AbstractEvent;


if ( ! \class_exists( __NAMESPACE__ . 'PurgeProxyCacheOnTransitionStatus' ) ) {

	/**
	 * PurgeProxyCacheOnTransitionStatus It is the class that handles the event of 'wp_transition_post_status'
	 *
	 * Is a class that has no real direct use. But it is handled by the core of this plugin.
	 * It is used to queue the action of clearing the proxy cache to the 'transition_post_status' action.
	 *
	 * @package Aruba-HiSpeed-Cache
	 * @author  Aruba Developer <hispeedcache.developer@aruba.it>
	 * @version 2.0.0
	 * @access public
	 * @since  0.0.1
	 */
	class PurgeProxyCacheOnTransitionStatus extends AbstractEvent {
		use Instance;
		//use HasContainer;

		/**
		 * $is_json, Variable its purpose and determine whether a call is a json call or not.
		 *
		 * @var boolean
		 */
		private $is_json = false;

		/**
		 * The list of target to clean.
		 *
		 * @var array
		 */
		private $target = array();

		/**
		 * Allowed Cases to fire the cleaner action.
		 *
		 * @var array
		 */
		private $allowed_cases = array( 'publish', 'future', 'trash' );

		/**
		 * Does the Event support the given request or condition?
		 *
		 * If this returns true, boostrap() will be called. If false, the class will be skipped.
		 *
		 * @return boolean
		 */
		public function support() {
			if ( 'nav-menus.php' === $this->container->get_service( Request::class )->current_page() ) {
				return false;
			}

			$plugin_option = $this->container->get_service( 'ahsc_get_option' );

			return (bool) $plugin_option( 'ahsc_purge_homepage_on_edit' ) ||
			$plugin_option( 'ahsc_purge_homepage_on_del' ) ||
			$plugin_option( 'ahsc_purge_page_on_mod' ) ||
			$plugin_option( 'ahsc_purge_archive_on_edit' ) ||
			$plugin_option( 'ahsc_purge_archive_on_del' );
		}

		/**
		 * Set up method for admin.
		 *
		 * @return void
		 */
		public function setup() {
			if ( $this->container->get_service( Request::class )->is_request_type( 'rest' ) ) {
				$this->is_json = true;
			}
		}

		/**
		 * Queues/Subscribes proxy cache cleaning action if conditions are supported.
		 *
		 * @access public
		 * @return void
		 */
		public function subscribe() {
			if ( ! $this->is_purged() ) {
				\add_action( 'transition_post_status', array( $this, 'ahsc_transition_post_status' ), 20, 3 );
				\add_action( 'pre_post_update', array( $this, 'get_terms_target' ), 20, 1 );
			}
		}

		/**
		 * Dequeues/unSubscribes proxy cache cleaning action if conditions are supported.
		 *
		 * @access public
		 * @return void
		 */
		public function unsubscribe() {
			\remove_action( 'transition_post_status', array( $this, 'ahsc_transition_post_status' ), 20 );
			\remove_action( 'pre_post_update', array( $this, 'get_terms_target' ), 20 );
		}

		/**
		 * Fires after a post type has been updated, and the term cache has been cleaned.
		 *
		 * @see https://developer.wordpress.org/reference/hooks/transition_post_status/
		 *
		 * @param int|string $new_status new status.
		 * @param int|string $old_status old status.
		 * @param \WP_Post   $post       Post object.
		 *
		 * @return void
		 */
		public function ahsc_transition_post_status( $new_status, $old_status, $post ) { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.FoundInExtendedClassBeforeLastUsed

			/**
			 * Disable the purge action if the transient 'ahsc_is_purged' is set.
			 */
			if ( $this->is_purged() ) {
				return;
			}

			/**
			 * Disable the purge action on auto save.
			 *
			 * @see https://developer.wordpress.org/reference/functions/wp_is_post_autosave/
			 */
			if ( \wp_is_post_autosave( $post ) ) {
				return;
			}

			/**
			 * Disable the purge action if the new status is not present in allorave casese array.
			 */
			if ( ! \in_array( $new_status, $this->allowed_cases, true ) ) {
				return;
			}

			/**
			 * For json call
			 */
			if ( $this->is_json ) {
				$do_purge             = F\ahsc_has_transient( 'ahsc_do_purge_deferred' );
				$do_purge_log_message = 'Transint presente';

				if ( ! $do_purge ) {
					$do_purge_log_message = 'Transint non presente presente lo imposto';
					F\ahsc_set_transient( 'ahsc_do_purge_deferred', \time(), MINUTE_IN_SECONDS );

					$cache_warmer = $this->get_purger();
					$cache_warmer->cache_warmer();
				}

				$this->log( $do_purge_log_message, 'deferred', 'info' );
				return;
			}

			$cleaner       = $this->get_purger();
			$plugin_option = $this->container->get_service( 'ahsc_get_option' );

			$options = array(
				'log_function'         => __FUNCTION__,
				'log_option'           => 'ahsc_purge',
				'is_publish_or_future' => \in_array( $new_status, array( 'publish', 'future' ), true ),
				'is_trashed'           => 'trash' === $new_status,
				'post'                 => $post,
			);

			/**
			 * If home page cleaning is set, there is no point in going any further, the entire site cache will be cleaned.
			 */
			if ( true === $options['is_publish_or_future'] && $plugin_option( 'ahsc_purge_homepage_on_edit' ) ) {
				// Logger.
				$this->log( 'hook::transition_post_status::home::' . (string) $options['log_option'], __NAMESPACE__ . '::' . (string) $options['log_function'], 'debug' );
				// Logger.
				$cleaner->purgeAll();

				F\ahsc_set_transient( 'ahsc_is_purged', \time(), MINUTE_IN_SECONDS );
				return;
			}

			if ( true === $options['is_trashed'] && $plugin_option( 'ahsc_purge_homepage_on_del' ) ) {
				// Logger.
				$this->log( 'hook::transition_post_status::home::ahsc_purge_archive_on_del', __NAMESPACE__ . '::' . __FUNCTION__, 'debug' );
				// Logger.
				$cleaner->purgeAll();

				F\ahsc_set_transient( 'ahsc_is_purged', \time(), MINUTE_IN_SECONDS );
				return;
			}

			/**
			 * Edit item
			 */
			if ( $plugin_option( 'ahsc_purge_page_on_mod' ) ) {
				$options['log_option'] = $options['log_option'] . '_page_on_mod';
				$options['target']     = \get_permalink( $post->ID );

				$this->post_mod_cache_cleaner( $options );
				return;
			}

			$taxonomies = \get_object_taxonomies( $post->post_type );
			if ( empty( $taxonomies ) ) {
				return;
			}

			if ( $plugin_option( 'ahsc_purge_archive_on_edit' ) && true === $options['is_publish_or_future'] ) {
				$options['log_option'] = $options['log_option'] . '_archive_on_edit';
				$options['target']     = $this->target;

				$this->post_mod_cache_cleaner( $options );
				return;
			}

			/**
			 * Delete items
			 */
			if ( $plugin_option( 'ahsc_purge_archive_on_del' ) && true === $options['is_trashed'] ) {
				$options['log_option'] = $options['log_option'] . '_archive_on_del';
				$options['target']     = $this->target;

				$this->post_mod_cache_cleaner( $options );
				return;
			}
		}

		/**
		 * Proxy cache cleaner based on passed options.
		 *
		 * @param  array $arg .
		 * @return void
		 */
		protected function post_mod_cache_cleaner( $arg ) {
			$cleaner = $this->get_purger();
			$target  = $arg['target'];

			if ( ! \is_array( $target ) ) {

				// Logger.
				$this->log(
					'hook::transition_post_status::' . (string) $arg['log_option'] . '::' . $target,
					__NAMESPACE__ . '::' . (string) $arg['log_function'],
					'debug'
				);
				// Logger.

				$cleaner->purgeUrl( $target );
			}

			if ( \is_array( $target ) ) {
				// Logger.
				$this->log(
					'hook::transition_post_status::' . (string) $arg['log_option'] . "::\n" . \implode( "::\n", $target ),
					__NAMESPACE__ . '::' . (string) $arg['log_function'],
					'debug'
				);
				// Logger.
				$cleaner->purgeUrls( $target );
			}

			F\ahsc_set_transient( 'ahsc_is_purged', \time(), MINUTE_IN_SECONDS );
		}

		/**
		 * Returns the list of taxonomies to be passed as targets.
		 *
		 * @param int $post_id The taxonomies lists.
		 * @return void
		 */
		public function get_terms_target( $post_id ) {

			$post_type  = \get_post_type( $post_id );
			$taxonomies = \get_object_taxonomies( $post_type );
			$target     = array();

			foreach ( $taxonomies as $tax ) {
				$post_term_list = \get_the_terms( $post_id, $tax );

				if ( false === $post_term_list ) {
					continue;
				}

				foreach ( \get_the_terms( $post_id, $tax ) as $tt ) {
					$target[] = \get_term_link( $tt->term_id, $tax );
				}
			}

			$this->target = $target;
		}
	}
}
