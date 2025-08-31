<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package sequoia
 */

get_header();
?>

	<main id="primary" class="site-main">

		<section class="error-404 not-found">
			<header class="page-header">
				<div class="container-fluid ">
					<div class="row h-80-view justify-content-center align-content-center">
						<div class="col-11 col-md-8 md-5 text-center">
							<h1 class="page-title"><?= __('Página no encontrada', 'sequoia'); ?></h1>
							<a href="<?= get_home_url() ?>" class="btn btn-primary"><?= __('Volver a la página principal', 'sequoia') ?></a>
						</div>
					</div>
				</div>
			</header><!-- .page-header -->
		</section><!-- .error-404 -->

	</main><!-- #main -->

<?php
get_footer();
