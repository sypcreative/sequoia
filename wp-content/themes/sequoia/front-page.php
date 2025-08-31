<?php

/**
 * Front Page
 */

// $hero = get_field('hero_general_selector');

get_header(); ?>

<main id="primary" class="site-main">
	<?php
	// if (!true) {
	// } else {
	// 	echo '<div class="py-5"></div>';
	// }
	// Contenido de la página
	the_content();
	?>
</main>
<?php
get_sidebar();
get_footer();
