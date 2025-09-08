<?php
$heading = !empty($attributes['heading']) ? wp_kses_post($attributes['heading']) : '';
$cards   = !empty($attributes['cards']) && is_array($attributes['cards']) ? $attributes['cards'] : [];

if (!empty($cards)) {
	foreach ($cards as &$c) {
		$c = wp_parse_args($c, ['title' => '', 'variant' => 'light', 'items' => []]);
		if (!empty($c['items']) && is_array($c['items'])) {
			foreach ($c['items'] as &$it) {
				$it = wp_parse_args($it, ['text' => '']);
			}
			unset($it);
		} else {
			$c['items'] = [];
		}
	}
	unset($c);
}
?>
<section class="syp-cards pt-5 container">
	<div class="row pt-5">
		<div class="col-12 col-md-8">
			<?php if ($heading): ?><h2 class="syp-cards__title fw-light h3 h-md-2"><?php echo $heading; ?></h2><?php endif; ?>
		</div>
		<div class="col-12">
			<div class="row">
				<?php if (!empty($cards)): ?>
					<div class="col-12">
						<div class="row">
							<?php foreach ($cards as $i => $card):
								$title = $card['title'] ? wp_kses_post($card['title']) : '';
								$variant = in_array($card['variant'], ['light', 'solid'], true) ? $card['variant'] : 'light'; ?>
								<div class="col-12 col-md-4 mb-3">
									<article class="syp-card d-flex flex-column justify-content-between syp-card--<?php echo esc_attr($variant); ?>">
										<?php if ($title): ?><h3 class="syp-card__title h1 fw-medium"><?php echo $title; ?></h3><?php endif; ?>

										<?php if (!empty($card['items'])): ?>
											<ul class="syp-card__ul" role="list">
												<?php foreach ($card['items'] as $it):
													$text = $it['text'] ? wp_kses_post($it['text']) : '';
													if (!$text) continue; ?>
													<li class="syp-card__li">
														<span class="syp-card__text"><?php echo $text; ?></span>
													</li>
												<?php endforeach; ?>
											</ul>
										<?php endif; ?>
									</article>
								</div>
							<?php endforeach; ?>
						</div>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
</section>