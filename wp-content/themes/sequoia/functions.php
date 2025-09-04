<?php

/**
 * sequoia functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package sequoia
 */

if (! defined('_S_VERSION')) {
	// Replace the version number of the theme on each release.
	define('_S_VERSION', '1.0.0');
}

/**
 * Configura los valores predeterminados del tema y registra la compatibilidad con varias funciones de WordPress.
 * Tenga en cuenta que esta función está enlazada con el gancho after_setup_theme, que
 * se ejecuta antes del gancho de inicio. El gancho de inicio es demasiado tarde para algunas funciones, como
 * para la indicación de soporte para miniaturas de publicaciones.
 */
function sequoia_setup()
{

	/**
	 * Deje que WordPress administre el título del documento.
	 * Al agregar compatibilidad con temas, declaramos que este tema no utiliza un
	 * etiqueta <título> codificada de forma rígida en el encabezado del documento, y espera que WordPress
	 * proporcionarlo para nosotros.
	 */
	add_theme_support('title-tag');

	/**
	 * Habilite el soporte para Publicar miniaturas en publicaciones y páginas.
	 *
	 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
	 */
	add_theme_support('post-thumbnails');

	/**
	 * Menús que se van a usar en este theme
	 */
	register_nav_menus(
		array(
			'menu-principal'   => esc_html__('Menú principal', 'sequoia'),
			'menu-footer'    => esc_html__('Footer', 'sequoia'),
		)
	);
}

add_action('after_setup_theme', 'sequoia_setup');

/**
 * Encolar scripts y estilos.
 */
require get_template_directory() . '/inc/template-enqueued.php';
require get_template_directory() . '/inc/template-functions.php';

if (defined('JETPACK__VERSION')) {
	require get_template_directory() . '/inc/jetpack.php';
}

require get_template_directory() . '/inc/acf-config.php';
require get_template_directory() . '/inc/custom-config.php';
require get_template_directory() . '/inc/custom-post-taxonomy.php';
require get_template_directory() . '/inc/ajax/ajax-config.php';
require get_template_directory() . '/inc/navs/custom-nav-walker.php';
require get_template_directory() . '/inc/navs/custom-nav-menu.php';
require get_template_directory() . '/inc/gtm-functions.php';

function dump($data)
{
	echo '<pre class="text-white bg-black w-max fs-7 py-5">';
	var_dump($data);
	echo '</pre>';
}

function aurora_add_viewport_meta()
{
	if (is_admin()) return; // solo front-end
	echo '<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.4">' . PHP_EOL;
}
add_action('wp_head', 'aurora_add_viewport_meta', 0); // prioridad baja = lo más arriba posible