<?php

/**
 * Template name: Contacto
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package sequoia
 */

$block_id    = 'block-contact-' . $block['id'];
$align_class = isset($block['align']) ? 'align' . $block['align'] : '';

// $title   = get_field('block_contact_title');
// $email   = get_field('block_contact_email');
// $phone   = get_field('block_contact_phone');
// $copy    = get_field('block_contact_main_text');
// $image   = get_field('block_contact_img');
// $address = get_field('block_contact_address');
// $social  = get_field('block_contact_rrss');
get_header();
?>

<main id="primary" class="site-main">

	<section id="<?php echo esc_attr($block_id); ?>" class="block-contact position-relative vh-100 d-flex flex-column align-items-center justify-content-between <?php echo esc_attr($align_class); ?>">
		<div class="row h-100 px-lg-5 px-2">
			<div class="col-lg-7 col-12 d-flex flex-column justify-content-center align-items-center position-relative">
				<?php if ($copy) : ?>
					<div class="position-relative">
						<h2 class="text-center text-lg-start jumbo-contact fw-bold lh-1 mb-4 "><?php echo nl2br(esc_html($copy)); ?></h2>

						<?php if ($email) : ?>
							<a href="mailto:<?php echo antispambot($email); ?>"
								class="text-primary century fs-6 position-absolute badge rounded-pill px-4 py-2 bg-pink text-decoration-none magnetic-badge">
								<?php echo esc_html($email); ?>
							</a>
						<?php endif; ?>

						<?php if ($phone) : ?>
							<a href="tel:<?php echo preg_replace('/\s+/', '', $phone); ?>"
								class="text-primary century fs-6 position-absolute badge rounded-pill px-4 py-2 bg-pink text-decoration-none magnetic-badge">
								<?php echo esc_html($phone); ?>
							</a>
						<?php endif; ?>
					</div>
				<?php endif; ?>
			</div>

			<div class="col-lg-5 position-relative justify-content-center align-items-center d-lg-flex d-none">
				<?php if ($image) : ?>
					<img style="max-width: 500px;" class="img-fluid block-contact__image" src="<?php echo esc_url($image['sizes']['large'] ?? $image['url']); ?>" alt="<?php echo esc_attr($image['alt']); ?>">
				<?php endif; ?>
			</div>
		</div>

		<div class="w-100 d-flex justify-content-between align-items-center position-absolute bottom-0 start-0 px-5 py-4  ">
			<?php if ($address) : ?>
				<div class="d-flex align-items-center justify-content-center">
					<address class="fw-medium text-pink m-0"><?php echo nl2br(esc_html($address)); ?></address>
				</div>
			<?php endif; ?>

			<?php if ($social) : ?>
				<div class="d-flex align-items-center justify-content-center">
					<?php foreach ($social as $item) :
						$link = $item['block_contact_rrss_link'];
						$icon = $item['block_contact_rrss_icon'];
						if (! $link || ! $icon) {
							continue;
						}
					?>
						<a href="<?php echo esc_url($link); ?>" target="_blank" rel="noopener" class="d-inline-block bg-pink p-2 rounded-circle d-flex align-items-center justify-content-center ms-2" style="width: 32px; height: 32px;">
							<img style="width: 18px;height: 18px;" src="<?php echo esc_url($icon['sizes']['thumbnail'] ?? $icon['url']); ?>" alt="<?php echo esc_attr($icon['alt']); ?>">
						</a>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
		</div>
	</section>
	<?php

	// Contenido de la página
	the_content();
	?>

</main><!-- #main -->

<?php
get_sidebar();
get_footer();
