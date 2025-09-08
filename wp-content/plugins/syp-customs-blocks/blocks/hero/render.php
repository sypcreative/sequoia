<?php
$title     = isset($attributes['title']) ? wp_kses_post($attributes['title']) : '';
$subtitle  = isset($attributes['subtitle']) ? wp_kses_post($attributes['subtitle']) : '';
$imageUrl  = !empty($attributes['imageUrl']) ? esc_url($attributes['imageUrl']) : '';
$overlay   = !empty($attributes['overlay']);

$layout  = !empty($attributes['layout']) ? $attributes['layout'] : 'split';   // split o full
$textPos = !empty($attributes['textPos']) ? $attributes['textPos'] : 'right'; // right o left
$theme   = !empty($attributes['theme']) ? $attributes['theme'] : 'light';     // light o dark

$classes = ['syp-hero', 'syp-hero--' . $layout, 'syp-hero--text-' . $textPos, 'syp-hero--theme-' . $theme];

/*
 * Columnas: móvil full (col-12), desktop split (col-lg-6).
 * En 'full' la media va a col-12 siempre.
 */
$mediaCol   = ($layout === 'full') ? 'col-12' : 'col-12 col-lg-6';
$contentCol = ($layout === 'split') ? 'col-12 col-lg-5 offset-lg-1' : 'col-12';
?>

<section class="container-full <?php echo esc_attr(implode(' ', $classes)); ?>">
	<div class="syp-hero__grid row g-0 position-relative"><!-- g-0 para quitar gutters -->
		<?php if ($layout === 'split' && $textPos === 'left'): ?>
			<div class="syp-hero__col syp-hero__col--content col-12 col-lg-5 offset-lg-1 d-flex align-items-end">
				<div class="syp-hero__content-wrap">
					<?php if ($title): ?><h1 class="syp-hero__title text-uppercase"><?php echo $title; ?></h1><?php endif; ?>
					<?php if ($subtitle): ?><p class="syp-hero__subtitle"><?php echo $subtitle; ?></p><?php endif; ?>
				</div>
			</div>
		<?php endif; ?>

		<!-- Imagen -->
		<div class="syp-hero__col syp-hero__col--media <?php echo esc_attr($mediaCol); ?> h-100" aria-hidden="true">
			<?php if ($imageUrl): ?>
				<picture class="syp-hero__media">
					<img class="syp-hero__image" src="<?php echo $imageUrl; ?>" alt="" loading="lazy" decoding="async" />
				</picture>
			<?php endif; ?>
			<?php if ($overlay): ?><span class="syp-hero__overlay" aria-hidden="true"></span><?php endif; ?>
		</div>

		<?php if ($layout === 'split' && $textPos === 'right'): ?>
			<div class="syp-hero__col syp-hero__col--content <?php echo esc_attr($contentCol); ?> d-flex align-items-end">
				<div class="syp-hero__content-wrap pb-5" data-anim="text-animated-group">
					<?php if ($title): ?><h1 class="syp-hero__title text-uppercase jumbo"><?php echo $title; ?></h1><?php endif; ?>
					<?php if ($subtitle): ?><p class="syp-hero__subtitle"><?php echo $subtitle; ?></p><?php endif; ?>
				</div>
			</div>
		<?php endif; ?>

		<?php if ($layout === 'full'): ?>
			<!-- Texto sobre la imagen -->
			<div class="syp-hero__col syp-hero__col--overlay-content position-absolute top-0 start-0 h-100 col-8 col-md-4">
				<div class="syp-hero__content-wrap h-100 d-flex flex-column justify-content-end pb-5 ps-5">
					<?php if ($subtitle): ?><p class="syp-hero__subtitle pb-5"><?php echo $subtitle; ?></p><?php endif; ?>
					<?php if ($title): ?><h1 class="syp-hero__title pb-5 mb-5"><?php echo $title; ?></h1><?php endif; ?>
				</div>
			</div>
		<?php endif; ?>
	</div>
</section>