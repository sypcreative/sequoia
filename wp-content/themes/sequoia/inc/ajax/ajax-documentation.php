<?php
// TODO: Unificar todo en una funcion que envie por ajax: html, numero de paginas que quedan,

// Action
add_action('wp_ajax_moreDocs', 'moreDocs');
add_action('wp_ajax_nopriv_moreDocs', 'moreDocs');

function moreDocs() {
	$category = $_POST['category'];
	$type     = $_POST['type'];

	$args = array(
		'paged'          => $_POST['page'],
		'post_type'      => 'documentos',
		'orderby'        => 'title',        // Ordena por título
		'order'          => 'ASC',            // Orden ascendente (A-Z)
		'post_status'    => 'publish',
		'posts_per_page' => 8,  // Número de posts por página
		'tax_query'      => array(),
	);

	if (isset($category) && $category !== 'all') {
		$args['tax_query'][] = array(
			'taxonomy' => 'categoria-de-documento',
			'field'    => 'slug',
			'terms'    => $category,
		);
	}
	if (isset($type) && $type !== 'all') {
		$args['tax_query'][] = array(
			'taxonomy' => 'tipo-de-documento',
			'field'    => 'slug',
			'terms'    => $type,
		);
	}


	// The Query
	$the_query = new WP_Query($args);

	// The Loop
	if ($the_query->have_posts()) {
		ob_start(); // Iniciar el almacenamiento en búfer de salida

		while ($the_query->have_posts()) {
			$the_query->the_post();
			get_template_part('template-parts/card-documentacion');
		}

		$response = ob_get_clean(); // Capturar la salida en una variable y detener el almacenamiento en búfer

		$res = [
			'html' => $response,
			'more' => $the_query->max_num_pages - $_POST['page'], // Paginas que quedan por
		];

		// Convertir el array en formato JSON
		$json_response = json_encode($res);

		// Configurar las cabeceras para indicar que la respuesta es JSON
		header('Content-Type: application/json');

		// Enviar la respuesta JSON como respuesta AJAX
		echo $json_response;
	}
	else {
		// no posts found
		echo 'No se encontraron documentos.';
	}

	/* Restaurar los datos originales del Post */
	wp_reset_postdata();

	wp_die();
}


// Process ajax request
add_action('wp_ajax_nopriv_filterDocs_ajax', 'filterDocs_ajax');
add_action('wp_ajax_filterDocs_ajax', 'filterDocs_ajax');
function filterDocs_ajax() {
	$category = $_POST['category'];
	$type     = $_POST['type'];

	$args = array(
		'posts_per_page' => 8,  // Número de posts por página
		'post_type'      => 'documentos',
		'orderby'        => 'title',        // Ordena por título
		'order'          => 'ASC',            // Orden ascendente (A-Z)
		'post_status'    => 'publish',
		'tax_query'      => array(),
	);

	if (isset($category) && $category !== 'all') {
		$args['tax_query'][] = array(
			'taxonomy' => 'categoria-de-documento',
			'field'    => 'slug',
			'terms'    => $category,
		);
	}
	if (isset($type) && $type !== 'all') {
		$args['tax_query'][] = array(
			'taxonomy' => 'tipo-de-documento',
			'field'    => 'slug',
			'terms'    => $type,
		);
	}


	// The Query
	$the_query = new WP_Query($args);

	// The Loop
	if ($the_query->have_posts()) {
		ob_start(); // Iniciar el almacenamiento en búfer de salida

		while ($the_query->have_posts()) {
			$the_query->the_post();
			get_template_part('template-parts/card-documentacion');
		}

		$response = ob_get_clean(); // Capturar la salida en una variable y detener el almacenamiento en búfer

		$res = [
			'html' => $response,
			'more' => $the_query->max_num_pages - $_POST['page'], // Paginas que quedan por delante
		];

		// Convertir el array en formato JSON
		$json_response = json_encode($res);

		// Configurar las cabeceras para indicar que la respuesta es JSON
		header('Content-Type: application/json');

		// Enviar la respuesta JSON como respuesta AJAX
		echo $json_response;

	}
	else {
		// no posts found
		$res = [
			'html' => '<h2>' . __("No se han encontrado documentos", "sequoia") . '</h2>',
			'more' => $the_query->max_num_pages - $_POST['page'], // Paginas que quedan por delante
		];
		// Convertir el array en formato JSON
		$json_response = json_encode($res);

		// Configurar las cabeceras para indicar que la respuesta es JSON
		header('Content-Type: application/json');

		// Enviar la respuesta JSON como respuesta AJAX
		echo $json_response;
	}

	wp_die(); // Finalizar la ejecución de WordPress
}



// Agrega el menú a tu plantilla
