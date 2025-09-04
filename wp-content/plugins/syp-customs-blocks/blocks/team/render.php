<?php
$heading = !empty($attributes['heading']) ? wp_kses_post($attributes['heading']) : '';
$intro   = !empty($attributes['intro'])   ? wp_kses_post($attributes['intro'])   : '';
$people  = !empty($attributes['people']) && is_array($attributes['people']) ? $attributes['people'] : [];

if ($people) {
	foreach ($people as &$p) {
		$p = wp_parse_args($p, ['name' => '', 'role' => '', 'email' => '', 'imageUrl' => '']);
	}
	unset($p);
}

ob_start(); ?>
<section class="syp-team">
	<div class="container">
		<div class="syp-team__lead">
			<?php if ($heading): ?><h2 class="syp-team__title"><?php echo $heading; ?></h2><?php endif; ?>
			<?php if ($intro):   ?><p class="syp-team__intro"><?php echo $intro; ?></p><?php endif; ?>
		</div>

		<?php if ($people): ?>
			<div class="syp-team__grid">
				<?php foreach ($people as $i => $p):
					$name = $p['name']   ? wp_kses_post($p['name'])   : '';
					$role = $p['role']   ? wp_kses_post($p['role'])   : '';
					$mail = $p['email']  ? sanitize_email($p['email']) : '';
					$img  = $p['imageUrl'] ? esc_url($p['imageUrl'])  : '';
				?>
					<article class="syp-person">
						<?php if ($img): ?>
							<div class="syp-person__media">
								<img src="<?php echo $img; ?>" alt="" loading="lazy" decoding="async" />
							</div>
						<?php endif; ?>

						<footer class="syp-person__footer px-4">
							<div class="syp-person__name fw-medium"><?php echo $name; ?></div>
							<div class="syp-person__role text-uppercase"><?php echo $role; ?></div>
							<?php if ($mail): ?>
								<a class="syp-person__email text-decoration-none" href="mailto:<?php echo esc_attr($mail); ?>">
									<?php echo esc_html($mail); ?>
								</a>
							<?php endif; ?>
						</footer>
					</article>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
	</div>
</section>
<?php echo ob_get_clean();
