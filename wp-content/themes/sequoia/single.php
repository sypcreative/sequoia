<?php

/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package sequoia
 */
// $hero = get_field('hero_general_selector');
get_header();
?>

<main id="primary" class="site-main">

	<?php
	while (have_posts()) :
		the_post();

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

	endwhile; // End of the loop.
	?>

</main><!-- #main -->

<?php
get_sidebar();
get_footer();
