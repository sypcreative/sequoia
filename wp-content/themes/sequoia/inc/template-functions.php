<?php
/**
 * Funciones que mejoran el tema al conectarse a WordPress
 *
 * @package sequoia
 */

/**
 * Agrega clases personalizadas a la matriz de clases de cuerpo.
 *
 * @param array $classes Clases para el elemento del body.
 *
 * @return array
 */
function sequoia_body_classes($classes) {
	// Agrega una clase de hfeed a páginas no singulares.
	if (!is_singular()) {
		$classes[] = 'hfeed';
	}

	// Agrega una clase de sin barra lateral cuando no hay una barra lateral presente.
	if (!is_active_sidebar('sidebar-1')) {
		$classes[] = 'no-sidebar';
	}

	return $classes;
}

add_filter('body_class', 'sequoia_body_classes');

/**
 * Agregue un encabezado de descubrimiento automático de URL de pingback para publicaciones, páginas o archivos adjuntos individuales.
 */
function sequoia_pingback_header() {
	if (is_singular() && pings_open()) {
		printf('<link rel="pingback" href="%s">', esc_url(get_bloginfo('pingback_url')));
	}
}

add_action('wp_head', 'sequoia_pingback_header');

/**
 * Añadimos thumnail por defecto en empleos
 *
 * @param $value
 * @param $post_id
 * @param $meta_key
 * @param $single
 *
 * @return mixed
 */
function custom_default_thumbnail($value, $post_id, $meta_key, $single) {
	// Verifica si la meta solicitada es la imagen destacada y si no tiene valor
	if ($meta_key === '_thumbnail_id' && empty($value)) {
		// Verifica si el post es del tipo de publicación personalizada deseado
		$post_type = get_post_type($post_id);
		if ($post_type === 'empleos') {
			// Retorna el ID de la imagen por defecto.
			return 5165; //Nombre: empleos_single
		}
	}

	return $value;
}

add_filter('get_post_metadata', 'custom_default_thumbnail', 10, 4);

/**
 * Añadimos que aquellos enlaces del editor de menus que tengan "Nueva ventana"
 * sigan dicho proceso
 *
 * @param $atts
 * @param $item
 * @param $args
 *
 * @return mixed
 */
function abrir_enlaces_menu_nueva_ventana($atts, $item, $args) {
	if ($item->target) {
		$atts['target'] = '_blank';
		$atts['rel']    = 'noopener noreferrer';
	}

	return $atts;
}

add_filter('nav_menu_link_attributes', 'abrir_enlaces_menu_nueva_ventana', 10, 3);


/**
 * Establece la imagen destacada de un post desde un campo ACF
 *
 * @param int $post_id ID del post.
 * @param WP_Post $post Post object.
 * @param bool $update Indica si el post se está actualizando o no.
 */
function set_featured_image_from_acf_field($post_id, $post, $update) {
	// Comprobar si el post ya tiene una imagen destacada
	if (!has_post_thumbnail($post_id) && $post_id) {
		// Obtener el valor del campo ACF
		// $hero_general_imagen_escritorio = get_field('hero_general_imagen_escritorio', $post_id);

		// Comprobar si el campo ACF tiene un valor
		if ($hero_general_imagen_escritorio) {
			// Obtener el ID de la imagen
			$image_id = $hero_general_imagen_escritorio['ID'];

			// Establecer la imagen como imagen destacada del post
			set_post_thumbnail($post_id, $image_id);
		}
	}
}

add_action('save_post', 'set_featured_image_from_acf_field', 10, 3);


// Method 1: Filter.
function my_acf_google_map_api($api) {
	$api['key'] = 'AIzaSyBVFp7rOsdigQAvYQTmaINR74hW06j3C0g';

	return $api;
}

add_filter('acf/fields/google_map/api', 'my_acf_google_map_api');

// Method 2: Setting.
function my_acf_init() {
	acf_update_setting('google_api_key', 'AIzaSyBVFp7rOsdigQAvYQTmaINR74hW06j3C0g');
}

add_action('acf/init', 'my_acf_init');


function tiene_hijos_cpt($post_type) {
	global $post;

	// Argumentos para get_posts
	$args = array(
		'post_type'      => $post_type,
		'posts_per_page' => -1,
		'post_parent'    => $post->ID,
		'fields'         => 'ids'  // Traer solo los IDs para mejorar el rendimiento
	);

	$hijos = get_posts($args);

	// Si hay posts hijos, retorna verdadero; de lo contrario, falso
	return !empty($hijos);
}

/**
 * Limpia una cadena de texto eliminando tildes, convirtiéndola a minúsculas y eliminando caracteres especiales.
 *
 * Esta función toma una cadena de texto y realiza los siguientes pasos para limpiarla:
 * 1. Elimina las tildes de los caracteres.
 * 2. Convierte la cadena a minúsculas.
 * 3. Elimina todos los caracteres especiales, dejando solo letras, números y espacios.
 *
 * @param string $string La cadena de texto que se desea limpiar.
 *
 * @return string La cadena limpia sin tildes, en minúsculas y sin caracteres especiales.
 */
function remove_special_characters( $string )
{
	// Eliminar tildes
	$string = iconv( 'UTF-8', 'ASCII//TRANSLIT', $string );

	// Convertir la cadena a minúsculas
	$string = mb_strtolower( $string, 'UTF-8' );

	// Eliminar caracteres especiales y dejar solo letras, números y espacios
	$string = preg_replace( '/[^\p{L}\p{N}\s]+/u', '', $string );

	return $string;
}


add_filter('woocommerce_add_to_cart_fragments', 'sequoia_mini_cart_subtotal_fragment');

function sequoia_mini_cart_subtotal_fragment($fragments)
{

	ob_start();
?>
	<strong class="mc-subtotal-amount">
		<?php echo WC()->cart->get_cart_subtotal(); ?>
	</strong>
<?php
	$fragments['strong.mc-subtotal-amount'] = ob_get_clean(); // selector ➜ HTML

	return $fragments;
}

add_action('wp_ajax_sequoia_update_qty',        'sequoia_update_qty');
add_action('wp_ajax_nopriv_sequoia_update_qty', 'sequoia_update_qty');

function sequoia_update_qty()
{

	check_ajax_referer('sequoia_update_cart', 'security');

	$key = wc_clean(wp_unslash($_POST['key'] ?? ''));
	$q   = max(0, intval($_POST['qty'] ?? 0));

	if ($key && isset(WC()->cart->get_cart()[$key])) {
		WC()->cart->set_quantity($key, $q, true);
	}

	$fragments = [];

	/* subtotal de la línea */
	if ($item = WC()->cart->get_cart()[$key] ?? null) {
		$prod                 = $item['data'];
		$fragments['#subtotal-' . $key] =
			WC()->cart->get_product_subtotal($prod, $q);
	}

	/* bloque totales completo */
	ob_start();
	wc_get_template('cart/cart-totals.php');
	$fragments['#cart-totals-wrapper'] = ob_get_clean();
	$fragments['#cart-total-amount'] = wc_price(WC()->cart->get_total('edit'));
	$fragments['#cart-subtotal-amount'] = wc_price(WC()->cart->get_subtotal());
	wp_send_json_success(['fragments' => $fragments]);
}
