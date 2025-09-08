<?php
$heading   = !empty($attributes['heading'])   ? wp_kses_post($attributes['heading']) : '';
$intro     = !empty($attributes['intro'])     ? wp_kses_post($attributes['intro'])   : '';
$ctaText   = !empty($attributes['ctaText'])   ? wp_kses_post($attributes['ctaText']) : '';
$ctaUrl    = !empty($attributes['ctaUrl'])    ? esc_url($attributes['ctaUrl'])       : '';
$ctaTarget = !empty($attributes['ctaTarget']) ? $attributes['ctaTarget']             : '';

$useSite   = isset($attributes['useSiteOptions']) ? (bool)$attributes['useSiteOptions'] : true;
$contact   = isset($attributes['contact']) && is_array($attributes['contact']) ? $attributes['contact'] : [];

// Datos desde opciones de sitio:
$site_company  = get_option('syp_company_name',     '');
$site_address  = get_option('syp_company_address',  '');
$site_email    = get_option('syp_company_email',    '');

$company  = $useSite ? $site_company  : (!empty($contact['company'])  ? wp_kses_post($contact['company']) : '');
$address  = $useSite ? $site_address  : (!empty($contact['address'])  ? wp_kses_post($contact['address']) : '');
$email    = $useSite ? $site_email    : (!empty($contact['email'])    ? sanitize_email($contact['email']) : '');

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
ob_start(); ?>
<section class="syp-contact container vh-100 d-flex align-items-center pt-md-0 pt-5">
	<div class="syp-contact__grid row px-md-5 px-0 pt-md-0 pt-5">
		<div class="syp-contact__lead col-12 col-md-5">
			<?php if ($heading): ?><h2 class="syp-contact__title h-md-1 h3 fw-light"><?php echo $heading; ?></h2><?php endif; ?>
			<?php if ($intro):   ?><p class="syp-contact__intro"><?php echo $intro; ?></p><?php endif; ?>
			<?php if ($ctaText && $ctaUrl): ?>
				<p class="syp-contact__cta-wrap pt-4">
					<a class="syp-contact__btn w-100 text-center" href="<?php echo $ctaUrl; ?>" <?php echo $ctaTarget ? ' target="_blank" rel="noopener"' : ''; ?>>
						<?php echo $ctaText; ?>
					</a>
				</p>
			<?php endif; ?>
		</div>

		<aside class="syp-contact__card offset-md-2 col-12 col-md-5 pb-5 pb-md-0">
			<div class="syp-contact__card-inner">
				<?php if ($company): ?>
					<p class="syp-contact__row syp-contact__company">
						<span class="syp-contact__ico" aria-hidden="true">
							<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-briefcase" viewBox="0 0 16 16">
								<path d="M6.5 1A1.5 1.5 0 0 0 5 2.5V3H1.5A1.5 1.5 0 0 0 0 4.5v8A1.5 1.5 0 0 0 1.5 14h13a1.5 1.5 0 0 0 1.5-1.5v-8A1.5 1.5 0 0 0 14.5 3H11v-.5A1.5 1.5 0 0 0 9.5 1zm0 1h3a.5.5 0 0 1 .5.5V3H6v-.5a.5.5 0 0 1 .5-.5m1.886 6.914L15 7.151V12.5a.5.5 0 0 1-.5.5h-13a.5.5 0 0 1-.5-.5V7.15l6.614 1.764a1.5 1.5 0 0 0 .772 0M1.5 4h13a.5.5 0 0 1 .5.5v1.616L8.129 7.948a.5.5 0 0 1-.258 0L1 6.116V4.5a.5.5 0 0 1 .5-.5" />
							</svg>
						</span>
						<span><?php echo $company; ?></span>
					</p>
				<?php endif; ?>

				<?php if ($address): ?>
					<p class="syp-contact__row syp-contact__addr">
						<span class="syp-contact__ico" aria-hidden="true">
							<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-geo-alt" viewBox="0 0 16 16">
								<path d="M12.166 8.94c-.524 1.062-1.234 2.12-1.96 3.07A32 32 0 0 1 8 14.58a32 32 0 0 1-2.206-2.57c-.726-.95-1.436-2.008-1.96-3.07C3.304 7.867 3 6.862 3 6a5 5 0 0 1 10 0c0 .862-.305 1.867-.834 2.94M8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10" />
								<path d="M8 8a2 2 0 1 1 0-4 2 2 0 0 1 0 4m0 1a3 3 0 1 0 0-6 3 3 0 0 0 0 6" />
							</svg>
						</span>
						<span><?php echo nl2br($address); ?></span>
					</p>
				<?php endif; ?>

				<?php if ($email): ?>
					<p class="syp-contact__row syp-contact__mail">
						<span class="syp-contact__ico" aria-hidden="true">
							<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-envelope" viewBox="0 0 16 16">
								<path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2zm2-1a1 1 0 0 0-1 1v.217l7 4.2 7-4.2V4a1 1 0 0 0-1-1zm13 2.383-4.708 2.825L15 11.105zm-.034 6.876-5.64-3.471L8 9.583l-1.326-.795-5.64 3.47A1 1 0 0 0 2 13h12a1 1 0 0 0 .966-.741M1 11.105l4.708-2.897L1 5.383z" />
							</svg>
						</span>
						<a href="mailto:<?php echo esc_attr($email); ?>"><?php echo esc_html($email); ?></a>
					</p>
				<?php endif; ?>

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
		</aside>
	</div>
</section>
<?php echo ob_get_clean();
