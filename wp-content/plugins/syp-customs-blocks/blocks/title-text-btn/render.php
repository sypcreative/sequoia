<?php

/**
 * @var array  $attributes
 * @var string $content (no lo usamos)
 */

// Sanitizar atributos
$paragraphs  = isset($attributes['paragraphs']) && is_array($attributes['paragraphs']) ? $attributes['paragraphs'] : [];
$buttonText  = isset($attributes['buttonText']) ? wp_kses_post($attributes['buttonText']) : '';
$buttonUrl   = isset($attributes['buttonUrl']) ? esc_url($attributes['buttonUrl']) : '';

?>
<section class="syp-text-cta container py-5">
	<div class="row pt-5">
		<div class="col-12">
			<?php foreach ($paragraphs as $p) :
				if (! $p) {
					continue;
				} ?>
				<p class="h4 fw-light pb-4"><?php echo wp_kses_post($p); ?></p>
			<?php endforeach; ?>

			<?php if ($buttonText && $buttonUrl) : ?>
				<div class="wp-block-buttons mt-3">
					<div class="wp-block-button">
						<a class="wp-block-button__link" href="<?php echo $buttonUrl; ?>">
							<?php echo $buttonText; ?>
						</a>
					</div>
				</div>
			<?php endif; ?>
		</div>
	</div>
</section>