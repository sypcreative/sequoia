<?php

/**
 * Plugin Name: SYP Custom Blocks
 * Description: Colección de bloques nativos de Paula (server-side, BEM + SCSS).
 * Author: Paula Sanz
 */

if (!defined('ABSPATH')) exit;

// Helpers ruta/URL del plugin
define('SYP_CB_PATH', plugin_dir_path(__FILE__));
define('SYP_CB_URL',  plugin_dir_url(__FILE__));

/**
 * Registrar TODOS los bloques que tengan block.json en /blocks/*
 */
add_action('init', function () {
	$base = SYP_CB_PATH . 'blocks';
	if (!is_dir($base)) return;

	foreach (glob($base . '/*/block.json') as $metadata) {
		register_block_type(dirname($metadata));
	}
});

/**
 * Categorías personalizadas de bloques
 */
add_filter('block_categories_all', function ($categories) {
	$custom = [
		['slug' => 'sequoia-home',     'title' => __('01 · Sequoia — Home',     'syp')],
		['slug' => 'sequoia-services', 'title' => __('02 · Sequoia — Services', 'syp')],
		['slug' => 'sequoia-about',    'title' => __('03 · Sequoia — About',    'syp')],
		['slug' => 'sequoia-contact',  'title' => __('04 · Sequoia — Contact',  'syp')],
	];
	return array_merge($custom, $categories);
}, 10, 1);

/**
 * Encolar el bundle compilado (front). Usa lo que emite @wordpress/scripts en assets/build/
 */
add_action('wp_enqueue_scripts', function () {
	if (is_admin()) return;

	$build_dir   = SYP_CB_PATH . 'assets/build';
	$asset_file  = $build_dir . '/index.asset.php';
	$script_file = $build_dir . '/index.js';

	if (file_exists($asset_file) && file_exists($script_file)) {
		$asset = include $asset_file;

		wp_enqueue_script(
			'syp-blocks-front',
			SYP_CB_URL . 'assets/build/index.js',
			$asset['dependencies'] ?? [],
			$asset['version'] ?? filemtime($script_file),
			true
		);
	}

	// CSS global generado por el bundler (si importaste SCSS en tu entry)
	$style_file = $build_dir . '/index.css';
	if (file_exists($style_file)) {
		wp_enqueue_style(
			'syp-blocks-front',
			SYP_CB_URL . 'assets/build/index.css',
			[],
			filemtime($style_file)
		);
	}
}, 20);

/**
 * Página de opciones (General information)
 */
add_action('admin_menu', function () {
	add_menu_page(
		__('General information', 'syp'),
		__('General information', 'syp'),
		'manage_options',
		'syp-contact',
		'syp_contact_options_page',
		'dashicons-email-alt',
		25
	);
});

/**
 * Cargar el Media Uploader SOLO en nuestra página de opciones
 */

add_action('admin_enqueue_scripts', function ($hook) {
	if ($hook !== 'toplevel_page_syp-contact') return;

	wp_enqueue_media();

	wp_enqueue_script(
		'syp-admin-logo',
		SYP_CB_URL . 'assets/admin/admin-logo.js',
		['jquery'],
		filemtime(SYP_CB_PATH . 'assets/admin/admin-logo.js'),
		true
	);

	// Pasar strings traducibles al JS
	wp_localize_script('syp-admin-logo', 'SYP_LOGO_I18N', [
		'title'  => __('Select or Upload Logo', 'syp'),
		'button' => __('Use this logo', 'syp'),
	]);
});


/**
 * Render de la página de opciones
 */
function syp_contact_options_page()
{ ?>
	<div class="wrap">
		<h1><?php echo esc_html__('General information', 'syp'); ?></h1>
		<form method="post" action="options.php">
			<?php
			settings_fields('syp_contact_group');
			do_settings_sections('syp-contact');
			submit_button();
			?>
		</form>
	</div>
	<?php }

/**
 * Ajustes / Campos de la página de opciones
 */
add_action('admin_init', function () {
	// === Opciones ===

	// Guardamos ID (integer) en vez de URL
	register_setting('syp_contact_group', 'syp_company_logo_id', [
		'type'              => 'integer',
		'sanitize_callback' => 'absint',
	]);

	register_setting('syp_contact_group', 'syp_company_name',    ['type' => 'string', 'sanitize_callback' => 'wp_kses_post']);
	register_setting('syp_contact_group', 'syp_company_address', ['type' => 'string', 'sanitize_callback' => 'wp_kses_post']);
	register_setting('syp_contact_group', 'syp_company_email',   ['type' => 'string', 'sanitize_callback' => 'sanitize_email']);
	register_setting('syp_contact_group', 'syp_social_links',    ['type' => 'array',  'sanitize_callback' => 'syp_sanitize_social_links']);

	// === Sección ===
	add_settings_section('syp_contact_section', __('Contact info', 'syp'), '__return_false', 'syp-contact');

	// === Campo: Logo (ID) ===
	add_settings_field('syp_company_logo_id', __('Company Logo', 'syp'), function () {
		$logo_id  = (int) get_option('syp_company_logo_id', 0);
		$logo_url = $logo_id ? wp_get_attachment_url($logo_id) : '';
	?>
		<div class="syp-logo-upload">
			<img id="syp-company-logo-preview" src="<?php echo esc_url($logo_url); ?>" style="max-height:80px;<?php echo $logo_url ? '' : 'display:none;'; ?>">
			<input type="hidden" id="syp-company-logo" name="syp_company_logo_id" value="<?php echo esc_attr($logo_id); ?>">
			<button type="button" class="button" id="syp-company-logo-upload"><?php _e('Select Logo', 'syp'); ?></button>
			<button type="button" class="button" id="syp-company-logo-remove" <?php echo $logo_url ? '' : 'style="display:none;"'; ?>><?php _e('Remove', 'syp'); ?></button>

			<p class="description"><?php _e('Upload a single SVG or image. SVG recommended for color control.', 'syp'); ?></p>
		</div>

	<?php
	}, 'syp-contact', 'syp_contact_section');

	// === Campos extra ===
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

/**
 * Sanitizar el repetidor de redes sociales
 */
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

/**
 * Campo repetidor (con pequeño JS inline para añadir/eliminar filas)
 */
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
			const tbody = document.getElementById('syp-socials').querySelector('tbody');
			document.getElementById('syp-add-social').addEventListener('click', () => {
				const tr = document.createElement('tr');
				tr.innerHTML = `
				<td><input type="text" name="syp_social_links[label][]" value="" class="regular-text"></td>
				<td><input type="url"  name="syp_social_links[url][]"   value="" class="regular-text"></td>
				<td><button type="button" class="button link-delete">✕</button></td>`;
				tbody.appendChild(tr);
			});
			tbody.addEventListener('click', (e) => {
				if (e.target.classList.contains('link-delete')) {
					e.target.closest('tr').remove();
				}
			});
		})();
	</script>
<?php
}

/**
 * Renderizar el logo inline si es SVG (permite cambiar color con CSS),
 * o <img> normal como fallback.
 */
function syp_render_inline_logo($args = [])
{
	$args = wp_parse_args($args, [
		'class' => 'brand-logo',
		'color' => '', // ej: 'var(--navbar-brand-color)'
		'title' => get_bloginfo('name'),
	]);

	$logo_id = (int) get_option('syp_company_logo_id', 0);
	if (!$logo_id) return '';

	$path = get_attached_file($logo_id);
	$url  = wp_get_attachment_url($logo_id);

	// Si no es SVG: fallback <img>
	if (!$path || !preg_match('/\.svg$/i', $path)) {
		return sprintf(
			'<img class="%s" src="%s" alt="%s" />',
			esc_attr($args['class']),
			esc_url($url),
			esc_attr($args['title'])
		);
	}

	$svg = @file_get_contents($path);
	if (!$svg) return '';

	// Limpieza básica y accesibilidad
	$svg = preg_replace('/<\?xml.*?\?>|<!DOCTYPE.*?>/si', '', $svg);
	$svg = preg_replace('/\sstyle="[^"]*"/i', '', $svg);
	$svg = preg_replace('/\s(fill|stroke)="[^"]*"/i', '', $svg);
	$svg = preg_replace('/<svg\b/i', '<svg focusable="false" role="img" aria-label="' . esc_attr($args['title']) . '"', $svg, 1);

	$style = $args['color'] ? 'style="color:' . esc_attr($args['color']) . '"' : '';

	return '<span class="' . esc_attr($args['class']) . '" ' . $style . '>' . $svg . '</span>';
}
