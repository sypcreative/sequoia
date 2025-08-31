<?php
$breadcrumbs = '<a href="' . home_url() . '" class="text-white text-decoration-none">Home</a>';
if (is_singular()) {
	$post_type     = get_post_type();
	$post_type_obj = get_post_type_object($post_type);


	if ($post_type === 'proyectos') {
		$breadcrumbs .= ' <span class="text-white"> / </span> <a href=" https://pre.sequoia.com/proyectos/ " class="text-white text-decoration-none">' . $post_type_obj->labels->name . '</a>';
	}
	elseif ($post_type === 'productos') {
		$breadcrumbs .= ' <span class="text-white"> / </span><span class="text-white">' . $post_type_obj->labels->name . '</span>';
	}


	$ancestors = get_post_ancestors(get_the_ID());
	if ($ancestors) {
		$ancestors = array_reverse($ancestors);
		foreach ($ancestors as $ancestor) {
			$breadcrumbs .= ' <span class="text-white"> / </span>  <a href="' . get_permalink($ancestor) . '" class="text-white text-decoration-none">' . get_the_title($ancestor) . '</a>';
		}
	}
	$breadcrumbs .= ' <span class="text-white"> / </span> <span class="text-white">' . get_the_title() . '</span>';
}
?>
<!-- Hero Cabecera -->
<section class="section py-hero">
		<?php if (!is_front_page()): ?>
			<div class="container-fluid">
				<div class="row justify-content-center">
					<div class="col-8 position-relative">
						<nav class="text-decoration-none cor-primary  position-absolute px-md-3 legend"
							 style="top: 120px; left: 0;">
							<?= $breadcrumbs ?>
						</nav>
					</div>
				</div>
			</div>
		<?php endif; ?>
</section>
