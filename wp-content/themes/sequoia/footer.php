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
		$net = strtolower($it['label'] ?? '');
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

						<div class="d-flex flex-column gap-2">
							<!-- Social + email -->
							<?php if ($email) : ?>
								<div class="">
									<a class="footer-email" href="mailto:<?= antispambot($email); ?>"><?= antispambot($email); ?></a>
								</div>
							<?php endif; ?>
							<div class="d-flex align-items-center gap-3 mb-3">
								<?php if ($linkedin) : ?>
									<a class="social-pill d-inline-flex align-items-center gap-2" href="<?= $linkedin; ?>" target="_blank" rel="noopener">
										<!-- icono LinkedIn (SVG) -->
										<span class="social-icon d-inline-flex justify-content-center align-items-center rounded-2">
											<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-linkedin" viewBox="0 0 16 16">
												<path d="M0 1.146C0 .513.526 0 1.175 0h13.65C15.474 0 16 .513 16 1.146v13.708c0 .633-.526 1.146-1.175 1.146H1.175C.526 16 0 15.487 0 14.854zm4.943 12.248V6.169H2.542v7.225zm-1.2-8.212c.837 0 1.358-.554 1.358-1.248-.015-.709-.52-1.248-1.342-1.248S2.4 3.226 2.4 3.934c0 .694.521 1.248 1.327 1.248zm4.908 8.212V9.359c0-.216.016-.432.08-.586.173-.431.568-.878 1.232-.878.869 0 1.216.662 1.216 1.634v3.865h2.401V9.25c0-2.22-1.184-3.252-2.764-3.252-1.274 0-1.845.7-2.165 1.193v.025h-.016l.016-.025V6.169h-2.4c.03.678 0 7.225 0 7.225z" />
											</svg>
										</span>
										<span class="fw-medium">Linkedin</span>
									</a>
								<?php endif; ?>
							</div>

						</div>
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

</html>