<?php

/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package sequoia
 */
// $hero = get_field('hero');
get_header();
?>
<main id="primary" class="site-main">

	<?php
	if ($hero == "home") {
		get_template_part('template-parts/blocks/2-home/block-home-hero');
	} elseif ($hero == "nosotros") {
		get_template_part('template-parts/blocks/4-about/block-hero-nosotros');
	} else if ($hero == "blog") {
		get_template_part('template-parts/blocks/3-blog/block-hero-blog');
	} else {
		// echo '<div class="py-5"></div>';
	}
	// Contenido de la página
	the_content();
	?>

</main><!-- #main -->

<?php
get_sidebar();
get_footer();
