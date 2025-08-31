<?php
/**
 * Clases para generar menús complejos con WordPress, se usan en wp_nav_menu()
 *
 * @package sequoia
 */

class PrimaryMenu_Walker_Nav_Menu extends Walker_Nav_Menu
{
	// Muestra el elemento del menú, marcando si tiene hijos
	function display_element($element, &$children_elements, $max_depth, $depth, $args, &$output) {
		$element->hasChildren = isset($children_elements[$element->ID]) && !empty($children_elements[$element->ID]);
		return parent::display_element($element, $children_elements, $max_depth, $depth, $args, $output);
	}

	// Inicia la creación del elemento del menú
	function start_el(&$output, $data_object, $depth = 0, $args = null, $current_object_id = 0) {
		$class_names = $data_object->hasChildren ? ' nav-item dropdown ' : ' nav-item ';

		// Obtener las clases del elemento del menú
		$classes = empty($data_object->classes) ? [] : (array)$data_object->classes;
		// Agregar las clases del editor de menús de WordPress
		$class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $data_object, $args, $depth));

		// Aplica la clase 'active' si el elemento del menú es la página actual
		$link_color   = is_child_theme() ? ' link-beige' : '';
		$link_classes = 'nav-link' . $link_color;
		if ($data_object->current) {
			$link_classes .= ' active';
		}

		$dropdown_class = $data_object->hasChildren ? ($depth > 0 ? ' dropend' : ' dropdown justify-content-md-center') : ($depth > 0 ? '' : ' justify-content-md-center');

		$output .= sprintf('<li class="%s nav-item%s d-md-flex">', esc_attr($class_names), esc_attr($dropdown_class));

		if ($data_object->hasChildren) {
			$toggle_class = $depth > 0 ? ' dropend-toggle  fs-5 fs-md-4 d-none d-md-block py-2' : ' dropdown-toggle fs-4  py-2 py-md-3'; //primero secundarios, luego principal
			$output       .= sprintf( // Dropdown toggle de menu principal.
				'<a href="%s" class="%s %s d-md-flex align-items-center legend-md" id="navbarDropdownMenuLink-%s" role="button" aria-haspopup="true" aria-expanded="false">%s</a>',
				esc_url($data_object->url),
				esc_attr($link_classes),
				esc_attr($toggle_class),
				esc_attr($data_object->ID),
				esc_html($data_object->title)
			);
			if ($depth > 0) {
				$output .= sprintf( // Dropdown toggle de menus secundarios.
					'<a href="%s" class="%s d-md-none ps-2 fs-5 fs-md-4 d-md-flex justify-content-md-center legend-md py-2">%s</a>',
					esc_url($data_object->url),
					esc_attr($link_classes),
					esc_html($data_object->title)
				);
			}
		}
		else { // Elementos que no son dropdow
//			$toggle_class = $depth > 0 ? '  fs-5 ps-md-0 fs-md-4' : ' fs-4';
			$toggle_class = $depth > 1 ? ' d-none d-md-block ps-2 ps-md-3 fs-5 fs-md-4 py-2' : ($depth == 1 ? ' ps-2 ps-md-3 fs-5 fs-md-4 py-2' : ' fs-4 py-3');

			$output .= sprintf(
				'<a href="%s" class="%s%s d-md-flex justify-content-md-center legend-md">%s</a>',
				esc_url($data_object->url),
				esc_attr($link_classes),
				esc_attr($toggle_class),
				esc_html($data_object->title)
			);
		}
	}

	// Inicia la creación del nivel del menú
	function start_lvl(&$output, $depth = 0, $args = null) {
		$menu_class = $depth == 0 ? 'dropdown-vertical mt-md-4 d-none' : 'dropend-horizontal pt-0';

		// Aplica pt-3 solo a elementos del menú de nivel superior
		$padding_class = $depth == 0 ? 'pt-md-5 pt-0' : 'd-none';

		$output .= sprintf(
			'<div class="dropdown-menu dropdown-cta %s container bg-transparent %s" style="width: max-content">',
			esc_attr($menu_class),
			esc_attr($padding_class)
		);
		$output .= '<div class="d-flex w-100 m-auto bg-light rounded-4 justify-content-center">';
		$output .= '<ul class="nav-menu w-100 list-unstyled py-2">';
	}

	// Termina la creación del nivel del menú
	function end_lvl(&$output, $depth = 0, $args = null) {
		$output .= '</ul></div></div>';
	}

	// Termina la creación del elemento del menú
	function end_el(&$output, $data_object, $depth = 0, $args = null, $current_object_id = 0) {
		$output .= '</li>';
	}
}

class VisuallyHidden_Walker_Nav_Menu extends Walker_Nav_Menu
{
	// Inicia la creación del elemento del menú con atributos de enlace
	function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {
		$atts = [];

		// Configura los atributos del enlace del elemento del menú
		$atts['title']  = !empty($item->attr_title) ? $item->attr_title : '';
		$atts['target'] = !empty($item->target) ? $item->target : '';
		$atts['rel']    = !empty($item->xfn) ? $item->xfn : '';
		$atts['href']   = !empty($item->url) ? $item->url : '';

		// Formatea los atributos como una cadena
		$attributes = '';
		foreach ($atts as $attr => $value) {
			if (!empty($value)) {
				$value      = esc_attr($value);
				$attributes .= " {$attr}='{$value}'";
			}
		}

		// Genera el HTML de salida
		$output .= '<li class="nav-item">';
		$output .= "<a {$attributes}>";
		$output .= '<span class="visually-hidden">' . esc_html($item->title) . '</span>';
		$output .= '</a>';
	}
}

?>
