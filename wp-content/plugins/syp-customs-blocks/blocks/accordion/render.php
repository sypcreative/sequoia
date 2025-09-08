<?php
$heading   = !empty($attributes['heading'])   ? wp_kses_post($attributes['heading'])   : '';
$ctaText   = !empty($attributes['ctaText'])   ? wp_kses_post($attributes['ctaText'])   : '';
$ctaUrl    = !empty($attributes['ctaUrl'])    ? esc_url($attributes['ctaUrl'])         : '';
$ctaTarget = !empty($attributes['ctaTarget']) ? $attributes['ctaTarget']               : '';
$items     = !empty($attributes['items']) && is_array($attributes['items']) ? $attributes['items'] : [];
$theme     = !empty($attributes['theme'])     ? sanitize_html_class($attributes['theme']) : 'light';
?>
<section class="syp-why syp-why--theme-<?php echo esc_attr($theme); ?> py-5 container">
	<div class="row">

		<div class="syp-why__lead col-12 d-flex flex-column flex-md-row justify-content-between">
			<?php if ($heading): ?><h2 class="syp-why__title" data-anim="text-animated"><?php echo $heading; ?></h2><?php endif; ?>
			<?php if ($ctaText && $ctaUrl): ?>
				<div class="btn-wrapper d-none d-md-inline-flex align-items-center">
					<a class="syp-why_btn btn btn-green text-white" href="<?php echo $ctaUrl; ?>" <?php echo $ctaTarget ? ' target="_blank" rel="noopener"' : ''; ?>>
						<?php echo $ctaText; ?>
					</a>
					<span class="btn btn-green text-white btn-arrow">→</span>
				</div>
			<?php endif; ?>
		</div>

		<?php if (!empty($items)): ?>
			<div class="syp-why__accordion">
				<?php foreach ($items as $i => $it):
					$title   = $it['title']   ? wp_kses_post($it['title'])   : '';
					$text    = $it['text']    ? wp_kses_post($it['text'])    : '';
					$iconUrl = $it['iconUrl'] ? esc_url($it['iconUrl'])      : '';
					$id      = 'syp-why-item-' . intval($i);
				?>
					<details class="syp-why__panel">
						<summary class="syp-why__summary" id="<?php echo esc_attr($id); ?>">
							<span class="syp-why__summary-text h3 fw-light mb-0" data-anim="text-animated"><?php echo $title; ?></span>
							<?php if ($iconUrl): ?><img class="syp-why__summary-icon" src="<?php echo $iconUrl; ?>" alt="" loading="lazy" decoding="async" /><?php endif; ?>
						</summary>
						<?php if ($text): ?><div class="syp-why__content">
								<p><?php echo $text; ?></p>
							</div><?php endif; ?>
					</details>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>

		<?php if ($ctaText && $ctaUrl): ?>
			<div class="btn-wrapper d-inline-flex d-md-none align-items-center pt-5">
				<a class="syp-why_btn btn btn-green text-white" href="<?php echo $ctaUrl; ?>" <?php echo $ctaTarget ? ' target="_blank" rel="noopener"' : ''; ?>>
					<?php echo $ctaText; ?>
				</a>
				<span class="btn btn-green text-white btn-arrow">→</span>
			</div>
		<?php endif; ?>
	</div>
</section>