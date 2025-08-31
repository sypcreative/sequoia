<?php

// Action
add_action('wp_ajax_filtroAscensores', 'filtroAscensores');
add_action('wp_ajax_nopriv_filtroAscensores', 'filtroAscensores');

function filtroAscensores() {
	// Iniciar el almacenamiento en búfer de salida

	$category = $_POST['category'];

	if (empty($category)) {
		$category = $_POST['category_actual'];
	}

	$args_productos = array(
		'post_type'      => 'productos',
		'tax_query'      => array(
			array(
				'taxonomy' => 'categoria-de-ascensor',
				'field'    => 'slug',
				'terms'    => $category,
			),
		),
		'posts_per_page' => -1,
		'orderby'        => 'title',
		'order'          => 'ASC',
	);

	$query_productos = new WP_Query($args_productos);

	// Segunda consulta para 'accesibilidad' con la taxonomía 'categoria-de-accesibilidad'
	// Si es una taxonomia de productos no deberia traer nada
	$args_accesibilidad = array(
		'post_type'      => 'accesibilidad',
		'tax_query'      => array(
			array(
				'taxonomy' => 'categoria-de-accesibilidad',
				'field'    => 'slug',
				'terms'    => $category,
			),
		),
		'posts_per_page' => -1,
		'orderby'        => 'title',
		'order'          => 'ASC',
	);

	$query_accesibilidad = new WP_Query($args_accesibilidad);


	ob_start();
	if (!$query_productos->have_posts() && !$query_accesibilidad->have_posts()) {
		echo 'No se encontraron productos.';
	}
	else {
		if ($query_productos->have_posts()) {
			while ($query_productos->have_posts()) {
				$query_productos->the_post();
				get_template_part('template-parts/card-swiper-ascensores');
			}
		}
		if ($query_accesibilidad->have_posts()) {
			while ($query_accesibilidad->have_posts()) {
				$query_accesibilidad->the_post();
				get_template_part('template-parts/card-swiper-ascensores');
			}
		}
	}
	wp_reset_postdata(); // Restaurar los datos originales del Post

	$response = ob_get_clean();

	$res = [
		'html' => $response,
	];

	// Convertir el array en formato JSON
	$json_response = json_encode($res);

	// Configurar las cabeceras para indicar que la respuesta es JSON
	header('Content-Type: application/json');

	// Enviar la respuesta JSON como respuesta AJAX
	echo $json_response;

	wp_die(); // Esta función es más apropiada para finalizar una llamada AJAX
}