<?php

/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package sequoia
 */

$args_footer = [
	'theme_location'  => 'menu-footer',
	'container'       => 'ul',
	'menu_class'      => 'list-unstyled',
	'items_wrap'      => '<ul id="%1$s" class="%2$s">%3$s</ul>',
	'list_item_class' => '',
	'link_class'      => 'enlace-footer d-block',
	'fallback_cb'     => false,
];
?>

<!-- Footer -->

</div><!-- #page -->

<?php if (!is_page('contacto')) : ?>

	<footer id="site-footer" class="bg-primary text-dark position-fixed">

	</footer>
<?php endif; ?>


<?php wp_footer(); ?>

</div> <!-- cierre de data-barba="container" -->
</div> <!-- cierre de data-barba="container" -->
<!-- <div class="barba-transition-overlay"></div> -->

</body>

</html>