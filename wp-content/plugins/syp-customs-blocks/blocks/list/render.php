<?php
$heading = !empty($attributes['heading']) ? wp_kses_post($attributes['heading']) : '';
$items   = !empty($attributes['items']) && is_array($attributes['items']) ? $attributes['items'] : [];

if (!empty($items)) {
	foreach ($items as &$it) {
		$it = wp_parse_args($it, ['text' => '']);
	}
	unset($it);
}
?>
<section class="syp-list container">
	<?php if ($heading): ?><h2 class="syp-list__title"><?php echo $heading; ?></h2><?php endif; ?>

	<?php if (!empty($items)): ?>
		<ul class="syp-list__ul px-4" role="list">
			<?php foreach ($items as $i => $it):
				$text = $it['text'] ? wp_kses_post($it['text']) : '';
				if (!$text) continue; ?>
				<li class="syp-list__li">
					<span class="syp-list__icon" aria-hidden="true">
						<?php /* check minimal inline */ ?>
						<svg width="26" height="26" viewBox="0 0 24 24" fill="none" aria-hidden="true">
							<path d="M20 6L9 17l-5-5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
						</svg>
					</span>
					<span class="syp-list__text"><?php echo $text; ?></span>
				</li>
			<?php endforeach; ?>
		</ul>
	<?php endif; ?>
</section>