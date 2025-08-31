<?php
/**
 * Funciones para hacer push al datalayer
 *
 * @package sequoia
 */

/**
 * Añadir etiquetas a un elemento, en el html, para el dataLayer y recogerlos con javascript
 *
 * Esta función genera atributos de datos para un elemento HTML basado en los datos proporcionados.
 * Los atributos de datos se utilizan para el dataLayer de Google Analytics y se pueden recoger con JavaScript.
 *
 * @param array $datos Los datos que se utilizarán para generar los atributos de datos.
 *
 * @return string Una cadena de texto que representa los atributos de datos generados.
 */
function htmlDatalayer($datos): string {
	$result = '';

	if (isset($datos['event_name'])) {
		$result .= "data-event-name='" . $datos['event_name'] . "'";
	}

	if (isset($datos['paginas_category'])) {
		$result .= "data-page-category='" . $datos['paginas_category'] . "'";
	}

	if (isset($datos['paginas_section'])) {
		$result .= "data-page-section='" . esc_html($datos['paginas_section']) . "'";
	}

	if (isset($datos['paginas_subsection'])) {
		$result .= "data-page-subsection='" . $datos['paginas_subsection'] . "'";
	}

	if (isset($datos['paginas_type'])) {
		$result .= "data-page-type='" . esc_html($datos['paginas_type']) . "'";
	}

	return $result;
}

/**
 * Construye un array con datos para el DataLayer de Google Analytics al cargar la página.
 *
 * Esta función recopila información de la página actual y la estructura en un array para su uso en el DataLayer de Google Analytics.
 * La información recopilada incluye la categoría de página, sección, subsección y tipo de página.
 * Algunos valores son obtenidos de campos personalizados en WordPress, mientras que otros son inferidos según la página actual.
 * Se realiza una limpieza de la cadena de título para eliminar tildes y caracteres especiales.
 *
 * @return array El array con los datos para el DataLayer de Google Analytics.
 */
function datalayerOnLoad() {
	// Obtiene el protocolo actual (http o https)
	$protocolo = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';

	// Obtiene la URL actual y la incluye en el array de datos
	$url_actual = $protocolo . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

	// Obtiene los valores de los campos personalizados o asigna una cadena vacía si no están definidos
	// $paginas_categoria  = get_field('ga_datalayer_paginas_categoria') ?? '';
	// $paginas_seccion    = get_field('ga_datalayer_paginas_seccion') ?? '';
	// $paginas_subseccion = get_field('ga_datalayer_paginas_subseccion') ?? '';
	// $paginas_tipo       = get_field('ga_datalayer_paginas_tipo') ?? '';

	// Construye un array inicial con los valores de los campos personalizados y la URL actual
	// $args_data_agrupacion = [
	// 	'language'       => 'es',
	// 	'path'           => $url_actual,
	// 	'pageCategory'   => $paginas_categoria,  // Categoría de la página (ver tabla)
	// 	'pageSection'    => $paginas_seccion,   // Sección de la página (ver tabla)
	// 	'pageSubsection' => $paginas_subseccion,    // Subsección de la página (ver tabla)
	// 	'pageType'       => $paginas_tipo, // Tipo de página (ver tabla)
	// ];

	// Obtiene el título de la publicación actual y lo limpia
	$the_title = get_the_title();
	$the_title = remove_special_characters($the_title);

	// Modifica el array de datos según el tipo de página
	if (is_singular(['tarifas_3', 'tarifas_movil', 'tarifas_fibra'])) {
		// Define la categoría según si se trata de un tema hijo o no
		if (is_child_theme()) {
			$category = 'empresas';
		}
		else {
			$category = 'particulares';
		}
		// Actualiza los valores en el array de datos para reflejar la ficha de producto
		$args_data_agrupacion['pageCategory']   = $category;
		$args_data_agrupacion['pageSection']    = 'producto';
		$args_data_agrupacion['pageSubsection'] = 'ficha de producto';
		$args_data_agrupacion['pageType']       = $the_title;
	}

	// Modifica el array de datos si la página utiliza una plantilla específica de tarifas
	if (is_page_template('template-tarifas.php')) {
		// Actualiza los valores en el array de datos para reflejar la página de catálogo de tarifas
		$args_data_agrupacion['pageSection']    = 'producto';
		$args_data_agrupacion['pageSubsection'] = 'catálogo';
	}

	// return $args_data_agrupacion;
}
