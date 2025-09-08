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
		<div class="col-lg-10" data-anim="text-animated-group">
			<?php foreach ($paragraphs as $p) :
				if (! $p) {
					continue;
				} ?>
				<p class="h-md-1 h-3 fw-light pb-4"><?php echo wp_kses_post($p); ?></p>
			<?php endforeach; ?>

			<?php if ($buttonText && $buttonUrl) : ?>
				<div class="wp-block-buttons mt-3">
					<div class="btn-wrapper d-inline-flex align-items-center">
						<a class="btn btn-green text-white" href="<?php echo $buttonUrl; ?>">
							<?php echo $buttonText; ?>
						</a>
						<span class="btn btn-green text-white btn-arrow">→</span>
					</div>
				</div>
			<?php endif; ?>
		</div>
	</div>
</section>