<?php
$title     = isset($attributes['title']) ? wp_kses_post($attributes['title']) : '';
$subtitle  = isset($attributes['subtitle']) ? wp_kses_post($attributes['subtitle']) : '';
$imageUrl  = !empty($attributes['imageUrl']) ? esc_url($attributes['imageUrl']) : '';
$mobileUrl = !empty($attributes['mobileImageUrl']) ? esc_url($attributes['mobileImageUrl']) : '';
$overlay   = !empty($attributes['overlay']);

$layout  = !empty($attributes['layout']) ? $attributes['layout'] : 'split';   // split o full
$textPos = !empty($attributes['textPos']) ? $attributes['textPos'] : 'right'; // right o left
$theme   = !empty($attributes['theme']) ? $attributes['theme'] : 'light';    // light o dark

$classes = ['syp-hero', 'syp-hero--' . $layout, 'syp-hero--text-' . $textPos, 'syp-hero--theme-' . $theme];
?>
<section class="container-full <?php echo esc_attr(implode(' ', $classes)); ?>">
	<div class="syp-hero__grid row vh-100">
		<?php if ($layout === 'split' && $textPos === 'left'): ?>
			<!-- Texto primero -->
			<div class="syp-hero__col syp-hero__col--content">
				<div class="syp-hero__content-wrap">
					<?php if ($title): ?><h1 class="syp-hero__title"><?php echo $title; ?></h1><?php endif; ?>
					<?php if ($subtitle): ?><p class="syp-hero__subtitle"><?php echo $subtitle; ?></p><?php endif; ?>
				</div>
			</div>
		<?php endif; ?>

		<!-- Imagen -->
		<div class="syp-hero__col syp-hero__col--media col-6 h-100" aria-hidden="true">
			<?php if ($imageUrl): ?>
				<picture class="syp-hero__media">
					<?php if ($mobileUrl): ?>
						<source media="(max-width: 767px)" srcset="<?php echo $mobileUrl; ?>"><?php endif; ?>
					<img class="syp-hero__image" src="<?php echo $imageUrl; ?>" alt="" loading="lazy" decoding="async" />
				</picture>
			<?php endif; ?>
			<?php if ($overlay): ?><span class="syp-hero__overlay" aria-hidden="true"></span><?php endif; ?>
		</div>

		<?php if ($layout === 'split' && $textPos === 'right'): ?>
			<!-- Texto después -->
			<div class="syp-hero__col syp-hero__col--content col-6 d-flex align-items-end">
				<div class="syp-hero__content-wrap pb-5 ms-auto">
					<?php if ($title): ?><h1 class="syp-hero__title text-uppercase jumbo"><?php echo $title; ?></h1><?php endif; ?>
					<?php if ($subtitle): ?><p class="syp-hero__subtitle"><?php echo $subtitle; ?></p><?php endif; ?>
				</div>
			</div>
		<?php endif; ?>

		<?php if ($layout === 'full'): ?>
			<!-- Texto sobre la imagen -->
			<div class="syp-hero__col syp-hero__col--overlay-content">
				<div class="syp-hero__content-wrap">
					<?php if ($title): ?><h1 class="syp-hero__title"><?php echo $title; ?></h1><?php endif; ?>
					<?php if ($subtitle): ?><p class="syp-hero__subtitle"><?php echo $subtitle; ?></p><?php endif; ?>
				</div>
			</div>
		<?php endif; ?>
	</div>
</section>