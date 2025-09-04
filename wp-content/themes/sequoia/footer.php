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

$name    = get_option('syp_company_name', '');
$address = get_option('syp_company_address', '');
$email   = sanitize_email(get_option('syp_company_email', ''));
$social  = get_option('syp_social_links', []);

// Busca Linkedin (si existe en tu array)
$linkedin = '';
if (is_array($social)) {
	foreach ($social as $it) {
		$net = strtolower($it['network'] ?? '');
		if ($net === 'linkedin' && !empty($it['url'])) {
			$linkedin = esc_url($it['url']);
			break;
		}
	}
}

// (opcional) logo
$logo = get_template_directory_uri() . '/assets/img/logo-footer.svg';

// === Args del menú de la derecha ===
$args_footer = [
	'theme_location' => 'menu-principal',
	'container'      => false, // importante: no queremos <div> envolvente
	'menu_class'     => 'list-unstyled footer-menu d-flex flex-column align-items-lg-end m-0',
	'items_wrap'     => '<ul id="%1$s" class="%2$s">%3$s</ul>',
	'link_class'     => 'enlace-footer d-block h1 fw-light text-uppercase m-0', // WP 5.7+
	'fallback_cb'    => false,
];
?>

<!-- Footer -->

</div><!-- #page -->

<?php if (!is_page('contacto')) : ?>

	<footer class="site-footer py-4 px-5 bg-primary">
		<div class="container">
			<div class="row align-items-stretch gy-4">
				<!-- Col izquierda: logo + nombre -->
				<div class="col-12 col-lg-4">
					<div class="d-flex flex-column justify-content-between h-100">
						<div class="d-flex align-items-center gap-3 mb-4 footer-brand">
							<?= syp_render_inline_logo([
								'class' => 'brand-logo',
								'title' => get_bloginfo('name'),
							]); ?>
						</div>

						<!-- Social + email -->
						<div class="d-flex align-items-center gap-3 mb-3">
							<?php if ($linkedin) : ?>
								<a class="social-pill d-inline-flex align-items-center gap-2" href="<?= $linkedin; ?>" target="_blank" rel="noopener">
									<!-- icono LinkedIn (SVG) -->
									<span class="social-icon d-inline-flex justify-content-center align-items-center rounded-2">
										<svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
											<path d="M4.98 3.5A2.5 2.5 0 1 0 5 8.5 2.5 2.5 0 0 0 4.98 3.5zM3 9h4v12H3zM9 9h3.8v1.7h.05A4.17 4.17 0 0 1 16.5 9c3 0 3.5 2 3.5 4.6V21h-4v-6.1c0-1.5 0-3.4-2.1-3.4s-2.4 1.6-2.4 3.3V21H7V9z" />
										</svg>
									</span>
									<span class="fw-medium">Linkedin</span>
								</a>
							<?php endif; ?>
						</div>

						<?php if ($email) : ?>
							<div class="">
								<a class="footer-email" href="mailto:<?= antispambot($email); ?>"><?= antispambot($email); ?></a>
							</div>
						<?php endif; ?>
					</div>
				</div>
				<!-- Col centro: dirección -->
				<div class="col-12 col-lg-4 align-content-end">
					<?php if ($address) : ?>
						<p class="footer-address m-0 h5 fw-light"><?= nl2br(esc_html($address)); ?></p>
					<?php endif; ?>
				</div>

				<!-- Col derecha: menú grande alineado a la derecha -->
				<div class="col-12 col-lg-4">
					<?php if (has_nav_menu('menu-principal')) : ?>

						<?php wp_nav_menu($args_footer); ?>
					<?php endif; ?>
				</div>

				<div class="col-12 pt-4">
					<div class="footer-legal small">
						© <?= date('Y'); ?> <?= esc_html($name ?: get_bloginfo('name')); ?>. All rights reserved.
					</div>
				</div>
			</div>
		</div>
	</footer>
<?php endif; ?>


<?php wp_footer(); ?>

</div> <!-- cierre de data-barba="container" -->
</div> <!-- cierre de data-barba="container" -->
<!-- <div class="barba-transition-overlay"></div> -->

</body>


<!-- <script>
	(function() {
		const header = document.getElementById('siteHeader');
		if (!header) return;

		const THRESHOLD = 10;

		function updateHeader() {
			if (window.scrollY > THRESHOLD) header.classList.add('is-scrolled');
			else header.classList.remove('is-scrolled');
		}

		// scroll
		updateHeader();
		window.addEventListener('scroll', updateHeader, {
			passive: true
		});

		// si usas Barba, re-evaluar tras navegar
		document.addEventListener('barba:after', updateHeader);

		// mantener fondo negro cuando el offcanvas/collapse está abierto
		const nav = document.getElementById('navbarNav');
		if (nav) {
			nav.addEventListener('shown.bs.collapse', () => header.classList.add('is-open'));
			nav.addEventListener('hidden.bs.collapse', () => header.classList.remove('is-open'));
		}

		// accesibilidad: respeta reduce motion
		const mq = window.matchMedia('(prefers-reduced-motion: reduce)');
		if (mq.matches) header.style.transition = 'none';
	})();
</script> -->

</html>