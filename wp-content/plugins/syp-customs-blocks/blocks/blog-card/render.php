<?php
// Render directo (sin returns/funciones). Pinta posts en Bootstrap row/cols.
$atts = isset($attributes) && is_array($attributes) ? $attributes : [];
$atts = wp_parse_args($atts, [
	'postsPerPage'  => -1,
	'order'         => 'desc',
	'orderby'       => 'date',
	'categoriesCsv' => '',
	'excerptLength' => 24,
	'showArrow'     => true,
]);

$args = [
	'post_type'           => 'post',
	'post_status'         => 'publish',
	'ignore_sticky_posts' => 1,
	'posts_per_page'      => (int) $atts['postsPerPage'],
	'orderby'             => in_array($atts['orderby'], ['date', 'title', 'modified', 'rand'], true) ? $atts['orderby'] : 'date',
	'order'               => (strtolower($atts['order']) === 'asc') ? 'ASC' : 'DESC',
];
if (!empty($atts['categoriesCsv'])) {
	$ids = array_filter(array_map('intval', explode(',', $atts['categoriesCsv'])));
	if ($ids) $args['category__in'] = $ids;
}
$q = new WP_Query($args);

// Wrapper con clases Bootstrap
$wrapper_attributes = get_block_wrapper_attributes([
	'class' => 'syp-blog-cards container py-5',
]);
?>
<!-- syp/blog-card DIRECT render (Bootstrap) -->
<section <?php echo $wrapper_attributes; ?>>
	<div class="row g-4 pt-5">
		<?php if ($q->have_posts()): ?>
			<?php while ($q->have_posts()): $q->the_post();
				$title   = get_the_title() ?: __('(Sin título)', 'syp');
				$link    = get_permalink();
				$raw     = has_excerpt() ? get_the_excerpt() : wp_strip_all_tags(get_the_content(null, false));
				$excerpt = ((int)$atts['excerptLength'] > 0) ? wp_trim_words($raw, (int)$atts['excerptLength'], '…') : '';
			?>
				<div class="col-12 col-md-6">
					<article class="syp-blog-card bg-green text-white p-4 rounded position-relative h-100">
						<h3 class="syp-blog-card__title h5 mb-2">
							<a class="stretched-link text-white text-decoration-none" href="<?php echo esc_url($link); ?>">
								<?php echo esc_html($title); ?>
							</a>
						</h3>

						<?php if ($excerpt): ?>
							<div class="syp-blog-card__excerpt small opacity-75">
								<?php echo esc_html($excerpt); ?>
							</div>
						<?php endif; ?>

						<?php if (!empty($atts['showArrow'])): ?>
							<span class="syp-blog-card__arrow position-absolute bottom-0 end-0 mb-3 me-3" aria-hidden="true">
								<svg class="syp-blog-card__arrow-svg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="20" height="20" role="img" focusable="false">
									<path d="M13.172 12l-4.95-4.95 1.414-1.414L16 12l-6.364 6.364-1.414-1.414z" fill="currentColor" />
								</svg>
							</span>
						<?php endif; ?>
					</article>
				</div>
			<?php endwhile;
			wp_reset_postdata(); ?>
		<?php else: ?>
			<div class="col-12">
				<p class="mb-0"><?php esc_html_e('No hay entradas todavía.', 'syp'); ?></p>
			</div>
		<?php endif; ?>
	</div>
</section>