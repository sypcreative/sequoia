<?php

/**
 * The base configuration for WordPress
 *
 * @package WordPress
 */

// ** Database settings ** //
define('DB_NAME', 'sequoia_db');
define('DB_USER', 'root');
define('DB_PASSWORD', 'root');
define('DB_HOST', 'localhost');
define('DB_CHARSET', 'utf8');
define('DB_COLLATE', '');

// ** Authentication Unique Keys and Salts ** //
// (général-mente puedes generarlos en https://api.wordpress.org/secret-key/1.1/salt/)
define('AUTH_KEY',         'pon aquí una frase única');
define('SECURE_AUTH_KEY',  'pon aquí una frase única');
define('LOGGED_IN_KEY',    'pon aquí una frase única');
define('NONCE_KEY',        'pon aquí una frase única');
define('AUTH_SALT',        'pon aquí una frase única');
define('SECURE_AUTH_SALT', 'pon aquí una frase única');
define('LOGGED_IN_SALT',   'pon aquí una frase única');
define('NONCE_SALT',       'pon aquí una frase única');

define('WP_HOME',    'http://localhost:8888/sequoia');
define('WP_SITEURL', 'http://localhost:8888/sequoia');
define('FORCE_SSL_ADMIN', false);
// Activar solo para corregir URL automáticamente en login; quítalo después:
define('RELOCATE', true);

// ** Table prefix ** //
$table_prefix = 'wp_';

/**
 * Debugging and performance tweaks
 */
define('WP_DEBUG', true);                    // ya lo tenías
define('WP_DEBUG_LOG', true);                // ya lo tenías
define('WP_DEBUG_DISPLAY', true);           // ya lo tenías

// Carga la versión minimizada y concatenada de scripts en admin para acelerar
define('SCRIPT_DEBUG', false);

// Fuerza concatenación de scripts (útil si tienes plugins que rompen la carga en packs)
define('CONCATENATE_SCRIPTS', true);

// Guarda las consultas a la base de datos en el log para analizarlas
define('SAVEQUERIES', true);

// Aumenta la memoria para PHP dentro de WordPress
define('WP_MEMORY_LIMIT', '256M');
define('WP_MAX_MEMORY_LIMIT', '512M');

// Aumenta el tiempo máximo de ejecución de PHP
if (! ini_get('max_execution_time') || ini_get('max_execution_time' < 300)) {
	@set_time_limit(300);
}

if (! defined('ABSPATH')) {
	define('ABSPATH', __DIR__ . '/');
}

require_once ABSPATH . 'wp-settings.php';
