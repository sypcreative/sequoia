<?php

/**
 * Plugin Name: SYP Custom Blocks
 * Description: Colección de bloques nativos de Paula (server-side, BEM + SCSS).
 * Author: Paula Sanz
 */

add_action('init', function () {
	$base = __DIR__ . '/blocks';
	if (!is_dir($base)) return;

	// Registra todos los bloques que tengan block.json
	foreach (glob($base . '/*/block.json') as $metadata) {
		register_block_type(dirname($metadata));
	}
});

add_filter('block_categories_all', function ($categories, $post) {
	return array_merge(
		$categories,
		[
			[
				'slug'  => 'sequoia-home',
				'title' => __('Sequoia | Home', 'syp'), // título visible en el editor
				'icon'  => null,
			],
		]
	);
}, 10, 2);

// === SYP: opciones de contacto (sitio) ===
add_action('admin_menu', function () {
	add_menu_page(
		__('General information', 'syp'),
		__('Contacto del sitio', 'syp'),
		'manage_options',
		'syp-contact',
		'syp_contact_options_page',
		'dashicons-email-alt',
		25
	);
});

function syp_contact_options_page()
{ ?>
	<div class="wrap">
		<h1><?php echo esc_html__('Contacto del sitio', 'syp'); ?></h1>
		<form method="post" action="options.php">
			<?php settings_fields('syp_contact_group');
			do_settings_sections('syp-contact');
			submit_button(); ?>
		</form>
	</div>
<?php }

add_action('admin_init', function () {
	register_setting('syp_contact_group', 'syp_company_name',    ['type' => 'string', 'sanitize_callback' => 'wp_kses_post']);
	register_setting('syp_contact_group', 'syp_company_address', ['type' => 'string', 'sanitize_callback' => 'wp_kses_post']);
	register_setting('syp_contact_group', 'syp_company_email',   ['type' => 'string', 'sanitize_callback' => 'sanitize_email']);
	register_setting('syp_contact_group', 'syp_social_links',    ['type' => 'array', 'sanitize_callback' => 'syp_sanitize_social_links']);

	add_settings_section('syp_contact_section', __('Datos de contacto', 'syp'), '__return_false', 'syp-contact');

	add_settings_field('syp_company_name', __('Company Name', 'syp'), function () {
		echo '<input type="text" class="regular-text" name="syp_company_name" value="' . esc_attr(get_option('syp_company_name', '')) . '">';
	}, 'syp-contact', 'syp_contact_section');

	add_settings_field('syp_company_address', __('Address', 'syp'), function () {
		echo '<textarea name="syp_company_address" class="large-text" rows="3">' . esc_textarea(get_option('syp_company_address', '')) . '</textarea>';
	}, 'syp-contact', 'syp_contact_section');

	add_settings_field('syp_company_email', __('Email', 'syp'), function () {
		echo '<input type="email" class="regular-text" name="syp_company_email" value="' . esc_attr(get_option('syp_company_email', '')) . '">';
	}, 'syp-contact', 'syp_contact_section');

	add_settings_field('syp_social_links', __('Social media', 'syp'), 'syp_social_links_field', 'syp-contact', 'syp_contact_section');
});

// Sanitizar el repetidor
function syp_sanitize_social_links($val)
{
	$out = [];
	if (is_array($val) && isset($val['label'], $val['url'])) {
		$N = max(count((array)$val['label']), count((array)$val['url']));
		for ($i = 0; $i < $N; $i++) {
			$label = isset($val['label'][$i]) ? wp_strip_all_tags($val['label'][$i]) : '';
			$url   = isset($val['url'][$i])   ? esc_url_raw($val['url'][$i])         : '';
			if ($label || $url) $out[] = ['label' => $label, 'url' => $url];
		}
	}
	return $out;
}

// Campo repetidor (con JS para añadir/eliminar filas)
function syp_social_links_field()
{
	$links = get_option('syp_social_links', []);
?>
	<table class="widefat striped" id="syp-socials">
		<thead>
			<tr>
				<th><?php _e('Social Media Name', 'syp'); ?></th>
				<th><?php _e('URL', 'syp'); ?></th>
				<th></th>
			</tr>
		</thead>
		<tbody>
			<?php if (empty($links)) $links = [['label' => 'LinkedIn', 'url' => '']]; ?>
			<?php foreach ($links as $i => $row): ?>
				<tr>
					<td><input type="text" name="syp_social_links[label][]" value="<?php echo esc_attr($row['label'] ?? ''); ?>" class="regular-text"></td>
					<td><input type="url" name="syp_social_links[url][]" value="<?php echo esc_attr($row['url']   ?? ''); ?>" class="regular-text"></td>
					<td><button type="button" class="button link-delete">✕</button></td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
	<p><button type="button" class="button button-secondary" id="syp-add-social"><?php _e('Añadir red', 'syp'); ?></button></p>
	<script>
		(function() {
			const table = document.getElementById('syp-socials').querySelector('tbody');
			document.getElementById('syp-add-social').addEventListener('click', () => {
				const tr = document.createElement('tr');
				tr.innerHTML = `
          <td><input type="text" name="syp_social_links[label][]" value="" class="regular-text"></td>
          <td><input type="url"  name="syp_social_links[url][]"   value="" class="regular-text"></td>
          <td><button type="button" class="button link-delete">✕</button></td>`;
				table.appendChild(tr);
			});
			table.addEventListener('click', (e) => {
				if (e.target.classList.contains('link-delete')) {
					e.target.closest('tr').remove();
				}
			});
		})();
	</script>
<?php
}
