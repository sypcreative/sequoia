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

			// Obtener las categorías del post actual
			$categories     = get_the_category();
			$category_names = [];

			if (!empty($categories)) {
				foreach ($categories as $category) {
					$category_names[] = esc_html($category->name);
				}
			}

			$fecha_modificacion = get_the_modified_date( 'd-m-Y', $post );
			?>
			<div class="hero bg-image h-80-view" style="--bg-image: url('<?= get_the_post_thumbnail_url() ?>')"></div>

			<div class="container-fluid py-5">
				<div class="row py-5">
					<div class="col-12 col-md-8 offset-md-2">
						<div class="row">
							<div class="text-center pb-3">
								<?php if (!empty($category_names)) : ?>
									<p class="text-primary legend"><?= implode(', ', $category_names) ?></p>
								<?php endif; ?>
								<h1 class="h2"><?= get_the_title() ?></h1>
                                <p class="legend"> Actualizado: <?= $fecha_modificacion ?></p>
							</div>
						</div>
						<?php
						// Contenido de la página
						the_content();
						?>
					</div>
				</div>
			</div>
		<?php
		endwhile; // End of the loop.
		?>

	</main><!-- #main -->

<?php
get_sidebar();
get_footer();
