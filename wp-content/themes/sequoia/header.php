<?php

/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package sequoia
 */

$menu_principal = [
	'theme_location' => 'menu-principal',
	'container'      => 'ul',
	'menu_class'     => 'navbar-nav mx-auto py-2 py-md-0',
	'walker'         => new PrimaryMenu_Walker_Nav_Menu(),
	'fallback_cb'    => false,
];
?>
<!doctype html>
<html <?php language_attributes(); ?>>

<head>
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
	<div class="barba-transition-overlay"></div>
	<div data-barba="wrapper">
		<?php wp_body_open(); ?>
		<div id="page" class="site" data-barba="container" data-barba-namespace="<?php echo get_post_field('post_name', get_post()); ?>">
			<!-- Nav Cabecera -->
			<header class="position-fixed w-100 z-4">
				<div class="container-fluid">
					<div class="row">
						<div class="col-12">
							<nav class="navbar navbar-light position-relative" id="menuCabecera">
								<div class="container-fluid py-1">

									<!-- Logo izquierda -->
									<a class="navbar-brand" href="<?= esc_url(get_home_url()); ?>">
										<img src="" alt="sequoia Logo" style="height: 40px;">
									</a>

									<!-- Hamburguesa y botón contacto a la derecha -->
									<div class="d-flex align-items-center ms-auto">
										<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
											aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
											<span class="navbar-toggler-icon"></span>
										</button>
										<a href="<?= esc_url(home_url('/contact')); ?>" class="btn btn-green ms-3 text-light">
											Get in touch
										</a>
									</div>

									<!-- Menú colapsable -->
									<div class="collapse navbar-collapse" id="navbarNav">
										<?php
										wp_nav_menu([
											'theme_location' => 'menu-principal',
											'container'      => false,
											'menu_class'     => 'navbar-nav ms-auto text-uppercase',
											'fallback_cb'    => false,
										]);
										?>
									</div>

								</div>
							</nav>
						</div>
					</div>
				</div>
			</header>