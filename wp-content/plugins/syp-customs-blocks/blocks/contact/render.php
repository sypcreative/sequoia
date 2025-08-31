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
$site_linkedin = get_option('syp_company_linkedin', '');

$company  = $useSite ? $site_company  : (!empty($contact['company'])  ? wp_kses_post($contact['company']) : '');
$address  = $useSite ? $site_address  : (!empty($contact['address'])  ? wp_kses_post($contact['address']) : '');
$email    = $useSite ? $site_email    : (!empty($contact['email'])    ? sanitize_email($contact['email']) : '');
$linkedin = $useSite ? $site_linkedin : (!empty($contact['linkedin']) ? esc_url($contact['linkedin']) : '');

ob_start(); ?>
<section class="syp-contact container vh-100 d-flex align-items-center">
	<div class="syp-contact__grid row px-5">
		<div class="syp-contact__lead col-5">
			<?php if ($heading): ?><h2 class="syp-contact__title h1 fw-light"><?php echo $heading; ?></h2><?php endif; ?>
			<?php if ($intro):   ?><p class="syp-contact__intro"><?php echo $intro; ?></p><?php endif; ?>
			<?php if ($ctaText && $ctaUrl): ?>
				<p class="syp-contact__cta-wrap pt-4">
					<a class="syp-contact__btn w-100 text-center" href="<?php echo $ctaUrl; ?>" <?php echo $ctaTarget ? ' target="_blank" rel="noopener"' : ''; ?>>
						<?php echo $ctaText; ?>
					</a>
				</p>
			<?php endif; ?>
		</div>

		<aside class="syp-contact__card offset-2 col-5">
			<div class="syp-contact__card-inner">
				<?php if ($company): ?>
					<p class="syp-contact__row syp-contact__company">
						<span class="syp-contact__ico" aria-hidden="true">✉️</span>
						<span><?php echo $company; ?></span>
					</p>
				<?php endif; ?>

				<?php if ($address): ?>
					<p class="syp-contact__row syp-contact__addr">
						<span class="syp-contact__ico" aria-hidden="true">✉️</span>
						<span><?php echo nl2br($address); ?></span>
					</p>
				<?php endif; ?>

				<?php if ($email): ?>
					<p class="syp-contact__row syp-contact__mail">
						<span class="syp-contact__ico" aria-hidden="true">✉️</span>
						<a href="mailto:<?php echo esc_attr($email); ?>"><?php echo esc_html($email); ?></a>
					</p>
				<?php endif; ?>

				<?php if ($linkedin): ?>
					<p class="syp-contact__row syp-contact__ln">
						<span class="syp-contact__ico" aria-hidden="true">in</span>
						<a href="<?php echo $linkedin; ?>" target="_blank" rel="noopener">Linkedin</a>
					</p>
				<?php endif; ?>
			</div>
		</aside>
	</div>
</section>
<?php echo ob_get_clean();
