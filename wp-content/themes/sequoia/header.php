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

$logo    = get_option('syp_company_logo', '');

$menu_principal = [
	'theme_location' => 'menu-principal',
	'container'      => 'ul',
	'menu_class'     => 'navbar-nav mx-auto py-2 py-md-0',
	'walker'         => new PrimaryMenu_Walker_Nav_Menu(),
	'fallback_cb'    => false,
];

$navbar_scheme = (is_page(['services', 'contact', 'blog'])) ? 'navbar-scheme--light' : 'navbar-scheme--dark';
?>
<!doctype html>
<html <?php language_attributes(); ?>>

<head>
	<?php wp_head(); ?>
</head>

<body <?php body_class($navbar_scheme); ?>>
	<div class="barba-transition-overlay"></div>
	<div data-barba="wrapper">
		<?php wp_body_open(); ?>
		<div id="page" class="site" data-barba="container" data-barba-namespace="<?php echo get_post_field('post_name', get_post()); ?>">
			<!-- Nav Cabecera -->
			<header id="siteHeader" class="position-fixed w-100 z-4">
				<div class="container-fluid">
					<div class="row">
						<div class="col-12">
							<nav class="navbar navbar-light position-relative" id="menuCabecera">
								<div class="container-fluid py-1">

									<!-- Logo izquierda -->
									<a class="navbar-brand" href="/home">
										<?= syp_render_inline_logo([
											'class' => 'brand-logo',
											'title' => get_bloginfo('name'),
										]); ?>
									</a>

									<!-- Hamburguesa y botón contacto a la derecha -->
									<div class="d-flex align-items-center ms-auto">
										<button class="navbar-toggle btn btn-primary opacity-" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
											aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
											<svg width="21" height="17" viewBox="0 0 28 24" fill="none" xmlns="http://www.w3.org/2000/svg">
												<rect x="12" y="20" width="16" height="3.2" fill="black" />
												<rect x="6" y="10" width="16" height="3.2" fill="black" />
												<rect width="16" height="3.2" fill="black" />
											</svg>

										</button>
										<a href="<?= esc_url(home_url('/contact')); ?>" class="btn btn-green ms-3 text-light d-none d-md-block">
											Get in touch
										</a>
									</div>

								</div>
							</nav>
						</div>
					</div>
				</div>
			</header>
			<div id="navbarNav" class="collapse position-relative z-4">
				<div class="position-fixed top-0 start-0 vw-100 vh-100 bg-primary">
					<div class="container h-90 position-relative d-flex flex-column p-5">
						<!-- fila superior: logo + botón cerrar -->
						<div class="d-flex justify-content-between align-items-start pt-3">
							<a class="navbar-brand" href="<?= esc_url(home_url('/')); ?>">
								<?= syp_render_inline_logo([
									'class' => 'brand-logo text-white',
									'title' => get_bloginfo('name'),
								]); ?>
							</a>

							<!-- botón cerrar -->
							<button class="btn btn-light rounded-4 px-3 py-2 shadow-0"
								type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
								aria-label="Close menu">×</button>
						</div>

						<!-- enlaces centrados -->
						<div class="d-flex flex-md-row flex-column h-100 justify-content-between align-items-start align-items-md-end">
							<div class="d-flex flex-column justify-content-end h-100 ">
								<?php
								wp_nav_menu([
									'theme_location' => 'menu-principal',
									'container'      => false,
									'menu_class'     => 'nav flex-column gap-2 jumbo text-uppercase',
									'fallback_cb'    => false,
									'link_class'     => 'text-secondary', // 👈 clases al <a>
								]);
								?>
							</div>

							<!-- botón contacto abajo-derecha -->
							<div class="p-md-4 p-0 pt-5">
								<a href="<?= esc_url(home_url('/contact')); ?>" class="btn btn-green text-white px-4">
									Get in touch
								</a>
							</div>
						</div>
					</div>
				</div>
			</div>