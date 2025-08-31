<?php
$heading = !empty($attributes['heading']) ? wp_kses_post($attributes['heading']) : '';
$intro   = !empty($attributes['intro'])   ? wp_kses_post($attributes['intro'])   : '';
$items   = !empty($attributes['items']) && is_array($attributes['items']) ? $attributes['items'] : [];

if (!empty($items)) {
	foreach ($items as &$it) {
		$it = wp_parse_args($it, ['title' => '', 'desc' => '', 'iconUrl' => '', 'variant' => 'light']);
	}
	unset($it);
}

ob_start(); ?>
<section class="syp-pills container">
	<div class="row">
		<div class="syp-pills__lead">
			<?php if ($heading): ?><h2 class="syp-pills__title"><?php echo $heading; ?></h2><?php endif; ?>
			<?php if ($intro):   ?><p class="syp-pills__intro"><?php echo $intro; ?></p><?php endif; ?>
		</div>

		<?php if (!empty($items)): ?>
			<div class="syp-pills__list">
				<?php foreach ($items as $i => $it):
					$title   = $it['title']   ? wp_kses_post($it['title']) : '';
					$desc    = $it['desc']    ? wp_kses_post($it['desc'])  : '';
					$iconUrl = $it['iconUrl'] ? esc_url($it['iconUrl'])    : '';
					$variant = in_array($it['variant'], ['light', 'solid'], true) ? $it['variant'] : 'light';
				?>
					<article class="syp-pill syp-pill--<?php echo esc_attr($variant); ?>">
						<div class="syp-pill__left">
							<?php if ($iconUrl): ?>
								<span class="syp-pill__icon" aria-hidden="true"><img src="<?php echo $iconUrl; ?>" alt="" loading="lazy" decoding="async"></span>
							<?php endif; ?>
							<?php if ($title): ?><h3 class="syp-pill__title fw-light"><?php echo $title; ?></h3><?php endif; ?>
						</div>

						<?php if ($desc): ?>
							<div class="syp-pill__desc">
								<p><?php echo $desc; ?></p>
							</div>
						<?php endif; ?>
					</article>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
	</div>
</section>
<?php echo ob_get_clean();
