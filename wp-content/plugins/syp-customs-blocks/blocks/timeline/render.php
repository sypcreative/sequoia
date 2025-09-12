<?php
if (! defined('ABSPATH')) {
	exit;
}

$intro = isset($attributes['introduction']) ? $attributes['introduction'] : '';
$items = (isset($attributes['items']) && is_array($attributes['items'])) ? $attributes['items'] : [];
$uid   = 'syp-timeline-' . wp_unique_id();

$wrapper_attrs = get_block_wrapper_attributes([
	'class' => 'syp-timeline',
	'id'    => $uid
]);

// Swiper vía CDN
wp_enqueue_style('swiper', 'https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css', [], '10.0.0');
wp_enqueue_script('swiper', 'https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js', [], '10.0.0', true);
?>
<section <?php echo $wrapper_attrs; ?> data-timeline>
	<?php if (!empty($intro)) : ?>
		<div class="syp-timeline__intro container">
			<h3 class="pb-4" data-anim="text-animated"><?php echo $intro ?></h3>
		</div>
	<?php endif; ?>
	<!-- línea base -->
	<div class=" syp-timeline__track" aria-hidden="true">
	</div>

	<!-- slider -->
	<div class="swiper syp-timeline__swiper">
		<div class="swiper-wrapper">
			<?php foreach ($items as $i => $it) :
				$year       = isset($it['year'])       ? sanitize_text_field($it['year']) : '';
				$iconUrl    = isset($it['iconUrl'])    ? esc_url($it['iconUrl']) : '';
				$title      = isset($it['title'])      ? wp_kses_post($it['title']) : '';
				$text       = isset($it['text'])       ? wp_kses_post($it['text']) : '';
				$shortTitle = isset($it['shortTitle']) ? sanitize_text_field($it['shortTitle']) : '';
			?>
				<article class="swiper-slide syp-timeline__item d-flex flex-column" data-index="<?php echo (int)$i; ?>">
					<div class="syp-timeline__year text-black text-center" aria-hidden="true"><?php echo esc_html($year); ?></div>

					<div class="syp-timeline__card d-flex justify-content-center">
						<div class="syp-timeline__head pb-4">
							<h3 class="syp-timeline__title pb-4"><?php echo $title; ?></h3>
							<p class="syp-timeline__text"><?php echo $text; ?></p>
						</div>
						<?php if ($iconUrl): ?>
							<div class="syp-timeline__icon mt-auto ms-auto"><img src="<?php echo $iconUrl; ?>" alt="" loading="lazy"></div>
						<?php endif; ?>
					</div>

					<?php if ($shortTitle): ?>
						<div class="syp-timeline__label text-center pt-4"><span><?php echo esc_html($shortTitle); ?></span></div>
					<?php endif; ?>

					<!-- Punto siempre sobre la línea -->
					<div class="syp-timeline__dot" aria-hidden="true"></div>
				</article>
			<?php endforeach; ?>
		</div>
	</div>

	<!-- flechas -->
	<div class="syp-timeline__navs" aria-label="Navigation">
		<div class="syp-timeline__nav syp-timeline__nav--prev" aria-label="Previous">←</div>
		<div class="syp-timeline__nav syp-timeline__nav--next" aria-label="Next">→</div>
	</div>
</section>