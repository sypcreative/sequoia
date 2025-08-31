<?php
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

return array(
    /**
     * Array of service providers you wish to enable.
     */
    'services' => array(
        ArubaSPA\HiSpeedCache\Core\Requirements::class,
        ArubaSPA\HiSpeedCache\Core\I18n::class,
        ArubaSPA\HiSpeedCache\Core\ActionLinks::class,
        // Service Check.
        ArubaSPA\HiSpeedCache\Core\ServiceCheck::class,
        // Settings.
        ArubaSPA\HiSpeedCache\Settings\RegisterSettings::class,
        ArubaSPA\HiSpeedCache\Settings\MigrationsManagers::class,
        // Helper.
        ArubaSPA\HiSpeedCache\Helper\AdminNotice::class,
        // HeartBeat.
        ArubaSPA\HiSpeedCache\HeartBeat\HeartBeatLimiter::class,
        // Admin.
        ArubaSPA\HiSpeedCache\Admin\AdminAssets::class,
        ArubaSPA\HiSpeedCache\Admin\AdminNotice::class,
        ArubaSPA\HiSpeedCache\Admin\AdminBar::class,
        ArubaSPA\HiSpeedCache\Admin\AdminSettingPage::class,
        ArubaSPA\HiSpeedCache\Admin\SiteHealth::class,
        // CacheWarmer.
        ArubaSPA\HiSpeedCache\CacheWarmer\CacheWarmerManager::class,
        // Ajax.
        ArubaSPA\HiSpeedCache\Admin\Ajax\CacheCleaner::class,
        ArubaSPA\HiSpeedCache\CacheWarmer\Ajax\CacheWarmer::class,
    ),

    'events' => array(
        ArubaSPA\HiSpeedCache\Events\BulkActionManager::class,
        ArubaSPA\HiSpeedCache\Events\Comments\PurgeProxyCacheOnNewComment::class,
        ArubaSPA\HiSpeedCache\Events\Comments\PurgeProxyCacheOnDeletedComment::class,
        ArubaSPA\HiSpeedCache\Events\Comments\PurgeProxyCacheTransitionCommentStatus::class,
        ArubaSPA\HiSpeedCache\Events\Term\PurgeProxyCacheOnEditTerm::class,
        ArubaSPA\HiSpeedCache\Events\Term\PurgeProxyCacheOnEditNavMenu::class,
        ArubaSPA\HiSpeedCache\Events\Term\PurgeProxyCacheOnDeleteTerm::class,
        ArubaSPA\HiSpeedCache\Events\PostType\PurgeProxyCacheOnTransitionStatus::class,
        ArubaSPA\HiSpeedCache\Events\PostType\PurgeProxyCacheOnPostUpdated::class,
        ArubaSPA\HiSpeedCache\Events\Deferred\PurgeProxyCacheInDeferredMode::class,
        ArubaSPA\HiSpeedCache\Events\Plugins\PurgeProxyCacheOnActivatedPlugin::class,
        ArubaSPA\HiSpeedCache\Events\Plugins\PurgeProxyCacheOnDeactivatedPlugin::class,
        ArubaSPA\HiSpeedCache\Events\Plugins\PurgeProxyCacheOnDeletePlugin::class,
        ArubaSPA\HiSpeedCache\Events\Themes\PurgeProxyCacheOnSwitchTheme::class,
    ),

    'core' => array(
        'plugin_name' => 'aruba-hispeed-cache',
    ),

    'requirements' => array(
        'minimum_php' => '5.6',
        'minimum_wp' => '5.4',
        'is_legacy_pre_59' => version_compare(get_bloginfo('version'), '5.8.22', '<='),
        'is_legacy_post_61' => version_compare(get_bloginfo('version'), '6.1.0', '>='),
        //'is_block_theme'    => ( function_exists( 'wp_is_block_theme' ) ) ? wp_is_block_theme() : false,
    ),

    'checker' => array(
        'transient_name' => 'ahsc_activation_check',
        'transient_life_time' => 15 * MINUTE_IN_SECONDS,
        'request_timeout' => 15,
    ),

    'constant' => array(
        'ARUBA_HISPEED_CACHE_VERSION' => '2.0.8',
        'ARUBA_HISPEED_CACHE_PLUGIN' => true,
        'ARUBA_HISPEED_CACHE_FILE' => $file,
        'ARUBA_HISPEED_CACHE_BASEPATH' => \plugin_dir_path($file),
        'ARUBA_HISPEED_CACHE_BASEURL' => \plugin_dir_url($file),
        'ARUBA_HISPEED_CACHE_BASENAME' => \plugin_basename($file),
        'ARUBA_HISPEED_CACHE_OPTIONS_NAME' => 'aruba_hispeed_cache_options',
        'HOME_URL' => \get_home_url(null, '/'),
        'ARUBA_HISPEED_CACHE_OPTIONS' => \get_site_option('aruba_hispeed_cache_options'),
    ),

    //phpcs:disable
    'options_list' => array(
        'ahsc_enable_purge' => array('type' => array('type' => 'boolean'), 'default' => true),
        'ahsc_purge_homepage_on_edit' => array('type' => array('type' => 'boolean'), 'default' => true),
        'ahsc_purge_homepage_on_del' => array('type' => array('type' => 'boolean'), 'default' => true),
        'ahsc_purge_archive_on_edit' => array('type' => array('type' => 'boolean'), 'default' => true),
        'ahsc_purge_archive_on_del' => array('type' => array('type' => 'boolean'), 'default' => true),
        'ahsc_purge_archive_on_new_comment' => array('type' => array('type' => 'boolean'), 'default' => true), // non utilizzata
        'ahsc_purge_archive_on_deleted_comment' => array('type' => array('type' => 'boolean'), 'default' => true), // non utilizzata
        'ahsc_purge_page_on_mod' => array('type' => array('type' => 'boolean'), 'default' => true),
        'ahsc_purge_page_on_new_comment' => array('type' => array('type' => 'boolean'), 'default' => true),
        'ahsc_purge_page_on_deleted_comment' => array('type' => array('type' => 'boolean'), 'default' => true),
        'ahsc_cache_warmer' => array('type' => array('type' => 'boolean'), 'default' => true),
    ),
    //phpcs:enable

    'ajax' => array(
        'security_error' => array(
            'code' => 404,
            'message' => __('An error occurred. Please try again later or contact support.', 'aruba-hispeed-cache'),
            'type' => 'error',
        ),
        'success' => array(
            'code' => 200,
            'message' => __('Cache purged.', 'aruba-hispeed-cache'),
            'type' => 'success',
        ),
        'warning' => array(
            'code' => 202,
            'message' => __('An error occurred. Please try again later or contact support.', 'aruba-hispeed-cache'),
            'type' => 'warning',
        ),
    ),

    'purger' => array(
        'server_host' => '127.0.0.1',
        'server_port' => '8889',
        'time_out' => 5,
    ),

    'links' => array(
        'link_base' => array(
            'it' => 'https://hosting.aruba.it/',
            'en' => 'https://hosting.aruba.it/en/',
            'es' => 'https://hosting.aruba.it/es/',
        ),
        'link_guide' => array(
            'it' => 'https://guide.hosting.aruba.it/hosting/cache-manager/gestione-cache.aspx',
        ),
        'link_assistance' => array(
            'it' => 'https://assistenza.aruba.it/home.aspx',
            'en' => 'https://assistenza.aruba.it/en/home.aspx',
            'es' => 'https://assistenza.aruba.it/es/home.aspx',
        ),
        'link_hosting_truck' => array(
            'it' => 'https://hosting.aruba.it/home.aspx?utm_source=pannello-wp&utm_medium=error-bar&utm_campain=aruba-hispeed-cache',
            'en' => 'https://hosting.aruba.it/en/home.aspx?utm_source=pannello-wp&utm_medium=error-bar&utm_campain=aruba-hispeed-cache',
            'es' => 'https://hosting.aruba.it/es/home.aspx?utm_source=pannello-wp&utm_medium=error-bar&utm_campain=aruba-hispeed-cache',
        ),
        'link_aruba_pca' => array(
            'it' => 'https://admin.aruba.it/PannelloAdmin/Login.aspx?Lang=it',
            'en' => 'https://admin.aruba.it/PannelloAdmin/login.aspx?Op=ChangeLanguage&Lang=EN',
            'es' => 'https://admin.aruba.it/PannelloAdmin/login.aspx?Op=ChangeLanguage&Lang=ES',
        ),
    ),
);
