<?php

class Bootstrap_Walker_Nav_Menu extends Walker_Nav_Menu
{
	/**
	 * Método que se ejecuta al iniciar un nivel de lista anidado (submenú)
	 */
	function start_lvl(&$output, $depth = 0, $args = null) {
		if ($depth === 0) {
			$indent = str_repeat("\t", $depth);
			// Agregar clases para el submenú
			$output .= "$indent<ul class=\"accordion-body navbar-nav fs-3 pb-0\">";
		}
	}

	/**
	 * Método que se ejecuta al comenzar un elemento de menú
	 */
	function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {
		$indent = ($depth) ? str_repeat("\t", $depth) : '';

		// Preparar el atributo target si es necesario
		$target = !empty($item->target) ? ' target="' . esc_attr($item->target) . '"' : '';
		$rel    = !empty($item->xfn) ? ' rel="' . esc_attr($item->xfn) . '"' : '';

		if ($depth === 0) {
			if ($args->walker->has_children) {
				// Agregar clases para el elemento de menú con submenú (accordion-item es una suposición)
				$output .= $indent . '<li class="col-12 accordion-item mb-2 mb-md-3 accordion-header accordionCabecera">';
				$output .= '<button class="h5 fs-md-1 bg-white accordion-button p-0 mb-0 collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse' . $item->ID . '" aria-expanded="false" aria-controls="flush-collapse' . $item->ID . '">';
				$output .= '<a class="link-dark h5 st-h2 mb-0 fs-md-1_ fs-md-3" href="' . $item->url . '"' . $target . $rel . '>' . $item->title . '</a>';
				$output .= '</button>';
				$output .= '<div id="flush-collapse' . $item->ID . '" class="accordion-collapse collapse_ collapse" aria-labelledby="flush-heading' . $item->ID . '" data-bs-parent="#accordionFlushExample" style="">';
			}
			else {
				// Agregar clases para el elemento de menú sin submenú
				$output .= $indent . '<li class="col-12 accordion-item mb-2 mb-md-3">';
				$output .= '<a class="link-dark h5 st-h2 mb-0 fs-md-1_ fs-md-3" href="' . $item->url . '"' . $target . $rel . '>' . $item->title . '</a>';
				$output .= '</li>';
			}
		}
		else {
			// Agregar clases para elementos de menú anidados
			$output .= $indent . '<li class="nav-item">';
			$output .= '<a class="nav-link fs-6 link-dark" href="' . $item->url . '"' . $target . $rel . '>' . $item->title . '</a>';
		}
	}

	/**
	 * Método que se ejecuta al finalizar un elemento de menú
	 */
	function end_el(&$output, $item, $depth = 0, $args = null) {
		if ($depth === 0 && $args->walker->has_children) {
			$output .= '</div>';
		}
		$output .= "</li>";
	}

	/**
	 * Método que se ejecuta al finalizar un nivel de lista anidado (submenú)
	 */
	function end_lvl(&$output, $depth = 0, $args = null) {
		if ($depth === 0) {
			$output .= "</ul>";
		}
	}
}

class VisuallyHidden_Walker_Nav_Menu extends Walker_Nav_Menu
{
	function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {
		// Comenzar a generar el elemento del menú
		$output .= '<li class="nav-item">';

		// Preparar el atributo target si es necesario
		$target = !empty($item->target) ? ' target="' . esc_attr($item->target) . '"' : '';

		// Generar el enlace con el posible atributo target
		$output .= '<a class="navbar-text" href="' . esc_url($item->url) . '"' . $target . '>';

		// Agregar el título del menú con la clase para accesibilidad
		$output .= '<span class="visually-hidden">' . esc_html($item->title) . '</span>';

		// Cerrar el enlace
		$output .= '</a>';
	}
}
