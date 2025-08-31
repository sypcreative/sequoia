<?php
$heading   = !empty($attributes['heading'])   ? wp_kses_post($attributes['heading'])   : '';
$intro     = !empty($attributes['intro'])     ? wp_kses_post($attributes['intro'])     : '';
$ctaText   = !empty($attributes['ctaText'])   ? wp_kses_post($attributes['ctaText'])   : '';
$ctaUrl    = !empty($attributes['ctaUrl'])    ? esc_url($attributes['ctaUrl'])         : '';
$ctaTarget = !empty($attributes['ctaTarget']) ? $attributes['ctaTarget']               : '';
$items     = !empty($attributes['items']) && is_array($attributes['items']) ? $attributes['items'] : [];
$theme     = !empty($attributes['theme'])     ? sanitize_html_class($attributes['theme']) : 'light';
?>
<section class="syp-why syp-why--theme-<?php echo esc_attr($theme); ?> py-5">
	<div class="container">

		<div class="syp-why__lead">
			<?php if ($heading): ?><h2 class="syp-why__title"><?php echo $heading; ?></h2><?php endif; ?>
			<?php if ($intro):   ?><p className="syp-why__intro"><?php echo $intro; ?></p><?php endif; ?>
			<?php if ($ctaText && $ctaUrl): ?>
				<p class="syp-why__cta-wrap">
					<a class="syp-why__btn" href="<?php echo $ctaUrl; ?>" <?php echo $ctaTarget ? ' target="_blank" rel="noopener"' : ''; ?>>
						<?php echo $ctaText; ?>
					</a>
				</p>
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
							<span class="syp-why__summary-text h3 fw-light mb-0"><?php echo $title; ?></span>
							<?php if ($iconUrl): ?><img class="syp-why__summary-icon" src="<?php echo $iconUrl; ?>" alt="" loading="lazy" decoding="async" /><?php endif; ?>
						</summary>
						<?php if ($text): ?><div class="syp-why__content">
								<p><?php echo $text; ?></p>
							</div><?php endif; ?>
					</details>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>

	</div>
</section>