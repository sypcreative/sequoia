<?php
/**
 * Mejoras las para funciones de wp_nav_menu de wordpress
 *
 * @package sequoia
 */

/**
 * Agrega una clase a los items de las listas si la propiedad 'list_item_class' existe en el objeto 'args'.
 *
 * @param array $classes Las clases CSS existentes para el ítem de la lista de menú.
 * @param object $item El objeto del ítem de la lista de menú.
 * @param object $args Los argumentos pasados al ítem de la lista de menú.
 * @return array La lista actualizada de clases CSS para el ítem de la lista de menú.
 */
function add_menu_list_item_class($classes, $item, $args) {
	if (property_exists($args, 'list_item_class')) {
		$classes[] = $args->list_item_class;
	}
	return $classes;
}

add_filter('nav_menu_css_class', 'add_menu_list_item_class', 1, 3);

/**
 * Agrega una clase a los enlaces del menú si la propiedad 'link_class' existe en el objeto 'args'.
 *
 * @param array $atts The attributes of the menu link.
 * @param object $item El objeto del ítem de la lista de menú.
 * @param object $args Los argumentos pasados al ítem de la lista de menú.
 * @return array La lista actualizada de clases CSS para el ítem de la lista de menú.
 */
function add_menu_link_class($atts, $item, $args) {
	if (property_exists($args, 'link_class')) {
		$atts['class'] = $args->link_class;
	}
	return $atts;
}

add_filter('nav_menu_link_attributes', 'add_menu_link_class', 1, 3);