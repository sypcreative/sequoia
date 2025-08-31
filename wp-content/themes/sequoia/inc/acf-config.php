<?php

/** @noinspection PhpUnused */

/**
 * Esta función crea categorías para los bloques de Gutenberg y los coloca en primera posición
 */
function sequoia_blocks_category($categories): array
{

	$custom_block = array(
		[
			'slug'  => 'sequoia-generales',
			'title' => __('sequoia | Generales', 'sequoia'),
		],
		[
			'slug'  => 'sequoia-home',
			'title' => __('sequoia | Home', 'sequoia'),
		],
		[
			'slug'  => 'sequoia-blog',
			'title' => __('sequoia | Blog', 'sequoia'),
		],
		[
			'slug'  => 'sequoia-about',
			'title' => __('sequoia | About', 'sequoia'),
		],
		[
			'slug'  => 'sequoia-contact',
			'title' => __('sequoia | Contact', 'sequoia'),
		],
		[
			'slug'  => 'sequoia-single-productos',
			'title' => __('sequoia | Single Productos', 'sequoia'),
		],
	);

	$categories_sorted = array();
	foreach ($custom_block as $category) {
		$categories_sorted[] = $category;
	}
	foreach ($categories as $category) {
		$categories_sorted[] = $category;
	}
	return $categories_sorted;
}

add_action('block_categories_all', 'sequoia_blocks_category', 10, 2);
//https://stackoverflow.com/questions/65886937/show-preview-image-for-custom-gutenberg-blocks
function sequoia_blocks(): void
{

	if (function_exists('acf_register_block')) {
		/**
		 * Block Banner Contacto
		 */
		acf_register_block(
			array(
				'name'            => 'block-banner',
				'title'           => __('Block Banner', 'sequoia'),
				'post_types' => array('page'),
				'description'     => __('Bloque que contiene un banner horizontal con un titulo,  una descripción y un botón de contacto', 'sequoia'),
				'render_template' => 'template-parts/blocks/1-generales/block-banner.php',
				'category'        => 'sequoia-generales',
				'mode'            => 'edit',
				'icon'            => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-bookmarks" viewBox="0 0 16 16"><path d="M2 4a2 2 0 0 1 2-2h6a2 2 0 0 1 2 2v11.5a.5.5 0 0 1-.777.416L7 13.101l-4.223 2.815A.5.5 0 0 1 2 15.5V4zm2-1a1 1 0 0 0-1 1v10.566l3.723-2.482a.5.5 0 0 1 .554 0L11 14.566V4a1 1 0 0 0-1-1H4z"/><path d="M4.268 1H12a1 1 0 0 1 1 1v11.768l.223.148A.5.5 0 0 0 14 13.5V2a2 2 0 0 0-2-2H6a2 2 0 0 0-1.732 1z"/> </svg>',
				'keywords'        => array('categorias', 'sequoia'),
				'render_callback' => 'render_preview',
				'example'         => array(
					'attributes' => array(
						'mode' => 'preview', // Important!
						'data' => array(
							'image' => '<img src="' . get_template_directory_uri() . '/assets/dist/img/blocks/block-banner-contacto.png' . '" style="display: block; margin: 0 auto;  max-width:100%;">'
						),
					),
				),
			)
		);

		/**
		 * Block Animated Banner
		 */
		acf_register_block(
			array(
				'name'            => 'block-animated-banner',
				'title'           => __('Block Animated Banner', 'sequoia'),
				'post_types' => array('page'),
				'description'     => __('Bloque un banner con texto e imagen animado de forma infinita', 'sequoia'),
				'render_template' => 'template-parts/blocks/1-generales/block-animated-banner.php',
				'category'        => 'sequoia-generales',
				'mode'            => 'edit',
				'icon'            => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-bookmarks" viewBox="0 0 16 16"><path d="M2 4a2 2 0 0 1 2-2h6a2 2 0 0 1 2 2v11.5a.5.5 0 0 1-.777.416L7 13.101l-4.223 2.815A.5.5 0 0 1 2 15.5V4zm2-1a1 1 0 0 0-1 1v10.566l3.723-2.482a.5.5 0 0 1 .554 0L11 14.566V4a1 1 0 0 0-1-1H4z"/><path d="M4.268 1H12a1 1 0 0 1 1 1v11.768l.223.148A.5.5 0 0 0 14 13.5V2a2 2 0 0 0-2-2H6a2 2 0 0 0-1.732 1z"/> </svg>',
				'keywords'        => array('categorias', 'sequoia'),
				'render_callback' => 'render_preview',
				'example'         => array(
					'attributes' => array(
						'mode' => 'preview', // Important!
						'data' => array(
							'image' => '<img src="' . get_template_directory_uri() . '/assets/dist/img/blocks/block-banner-contacto.png' . '" style="display: block; margin: 0 auto;  max-width:100%;">'
						),
					),
				),
			)
		);

		/**
		 * Block Home Hero
		 */
		acf_register_block(
			array(
				'name'            => 'block-home-hero',
				'title'           => __('Block Home Hero', 'sequoia'),
				'post_types' => array('page'),
				'description'     => __('Bloque que contiene un banner horizontal con un titulo,  una descripción y un botón de contacto', 'sequoia'),
				'render_template' => 'template-parts/blocks/2-home/block-home-hero.php',
				'category'        => 'sequoia-home',
				'mode'            => 'edit',
				'icon'            => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-bookmarks" viewBox="0 0 16 16"><path d="M2 4a2 2 0 0 1 2-2h6a2 2 0 0 1 2 2v11.5a.5.5 0 0 1-.777.416L7 13.101l-4.223 2.815A.5.5 0 0 1 2 15.5V4zm2-1a1 1 0 0 0-1 1v10.566l3.723-2.482a.5.5 0 0 1 .554 0L11 14.566V4a1 1 0 0 0-1-1H4z"/><path d="M4.268 1H12a1 1 0 0 1 1 1v11.768l.223.148A.5.5 0 0 0 14 13.5V2a2 2 0 0 0-2-2H6a2 2 0 0 0-1.732 1z"/> </svg>',
				'keywords'        => array('categorias', 'sequoia'),
				'render_callback' => 'render_preview',
				'example'         => array(
					'attributes' => array(
						'mode' => 'preview', // Important!
						'data' => array(
							'image' => '<img src="' . get_template_directory_uri() . '/assets/dist/img/blocks/block-banner-contacto.png' . '" style="display: block; margin: 0 auto;  max-width:100%;">'
						),
					),
				),
			)
		);


		/**
		 * Block Title Gallery
		 */
		acf_register_block(
			array(
				'name'            => 'block-title-gallery',
				'title'           => __('Block Title Gallery', 'sequoia'),
				'post_types' => array('page'),
				'description'     => __('Bloque que un título y subtítulo además de una galería de imágenes.', 'sequoia'),
				'render_template' => 'template-parts/blocks/2-home/block-title-gallery.php',
				'category'        => 'sequoia-home',
				'mode'            => 'edit',
				'icon'            => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-bookmarks" viewBox="0 0 16 16"><path d="M2 4a2 2 0 0 1 2-2h6a2 2 0 0 1 2 2v11.5a.5.5 0 0 1-.777.416L7 13.101l-4.223 2.815A.5.5 0 0 1 2 15.5V4zm2-1a1 1 0 0 0-1 1v10.566l3.723-2.482a.5.5 0 0 1 .554 0L11 14.566V4a1 1 0 0 0-1-1H4z"/><path d="M4.268 1H12a1 1 0 0 1 1 1v11.768l.223.148A.5.5 0 0 0 14 13.5V2a2 2 0 0 0-2-2H6a2 2 0 0 0-1.732 1z"/> </svg>',
				'keywords'        => array('categorias', 'sequoia'),
				'render_callback' => 'render_preview',
				'example'         => array(
					'attributes' => array(
						'mode' => 'preview', // Important!
						'data' => array(
							'image' => '<img src="' . get_template_directory_uri() . '/assets/dist/img/blocks/block-banner-contacto.png' . '" style="display: block; margin: 0 auto;  max-width:100%;">'
						),
					),
				),
			)
		);

		/**
		 * Block Home Blog
		 */
		acf_register_block(
			array(
				'name'            => 'block-home-blog',
				'title'           => __('Block home Blog', 'sequoia'),
				'post_types' => array('page'),
				'description'     => __('Bloque que un título y subtítulo además de varios posts recientes del blog.', 'sequoia'),
				'render_template' => 'template-parts/blocks/2-home/block-home-blog.php',
				'category'        => 'sequoia-home',
				'mode'            => 'edit',
				'icon'            => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-bookmarks" viewBox="0 0 16 16"><path d="M2 4a2 2 0 0 1 2-2h6a2 2 0 0 1 2 2v11.5a.5.5 0 0 1-.777.416L7 13.101l-4.223 2.815A.5.5 0 0 1 2 15.5V4zm2-1a1 1 0 0 0-1 1v10.566l3.723-2.482a.5.5 0 0 1 .554 0L11 14.566V4a1 1 0 0 0-1-1H4z"/><path d="M4.268 1H12a1 1 0 0 1 1 1v11.768l.223.148A.5.5 0 0 0 14 13.5V2a2 2 0 0 0-2-2H6a2 2 0 0 0-1.732 1z"/> </svg>',
				'keywords'        => array('categorias', 'sequoia'),
				'render_callback' => 'render_preview',
				'example'         => array(
					'attributes' => array(
						'mode' => 'preview', // Important!
						'data' => array(
							'image' => '<img src="' . get_template_directory_uri() . '/assets/dist/img/blocks/block-banner-contacto.png' . '" style="display: block; margin: 0 auto;  max-width:100%;">'
						),
					),
				),
			)
		);

		/**
		 * Block Blog List
		 */
		acf_register_block(
			array(
				'name'            => 'block-blog-list',
				'title'           => __('Block Blog List', 'sequoia'),
				'post_types' => array('page'),
				'description'     => __('Bloque que lista las entradas del blog de sequoia y las permite filtrar por página y categorías.', 'sequoia'),
				'render_template' => 'template-parts/blocks/3-blog/block-blog-list.php',
				'category'        => 'sequoia-blog',
				'mode'            => 'edit',
				'icon'            => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-bookmarks" viewBox="0 0 16 16"><path d="M2 4a2 2 0 0 1 2-2h6a2 2 0 0 1 2 2v11.5a.5.5 0 0 1-.777.416L7 13.101l-4.223 2.815A.5.5 0 0 1 2 15.5V4zm2-1a1 1 0 0 0-1 1v10.566l3.723-2.482a.5.5 0 0 1 .554 0L11 14.566V4a1 1 0 0 0-1-1H4z"/><path d="M4.268 1H12a1 1 0 0 1 1 1v11.768l.223.148A.5.5 0 0 0 14 13.5V2a2 2 0 0 0-2-2H6a2 2 0 0 0-1.732 1z"/> </svg>',
				'keywords'        => array('categorias', 'sequoia'),
				'render_callback' => 'render_preview',
				'example'         => array(
					'attributes' => array(
						'mode' => 'preview', // Important!
						'data' => array(
							'image' => '<img src="' . get_template_directory_uri() . '/assets/dist/img/blocks/block-banner-contacto.png' . '" style="display: block; margin: 0 auto;  max-width:100%;">'
						),
					),
				),
			)
		);

		/**
		 * Block Meet us
		 */
		acf_register_block(
			array(
				'name'            => 'block-meet-us',
				'title'           => __('Block meet us', 'sequoia'),
				'post_types' => array('page'),
				'description'     => __('Bloque con un titular y una descripción breve', 'sequoia'),
				'render_template' => 'template-parts/blocks/2-home/block-meet-us.php',
				'category'        => 'sequoia-generales',
				'mode'            => 'edit',
				'icon'            => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-bookmarks" viewBox="0 0 16 16"><path d="M2 4a2 2 0 0 1 2-2h6a2 2 0 0 1 2 2v11.5a.5.5 0 0 1-.777.416L7 13.101l-4.223 2.815A.5.5 0 0 1 2 15.5V4zm2-1a1 1 0 0 0-1 1v10.566l3.723-2.482a.5.5 0 0 1 .554 0L11 14.566V4a1 1 0 0 0-1-1H4z"/><path d="M4.268 1H12a1 1 0 0 1 1 1v11.768l.223.148A.5.5 0 0 0 14 13.5V2a2 2 0 0 0-2-2H6a2 2 0 0 0-1.732 1z"/> </svg>',
				'keywords'        => array('categorias', 'sequoia'),
				'render_callback' => 'render_preview',
				'example'         => array(
					'attributes' => array(
						'mode' => 'preview', // Important!
						'data' => array(
							'image' => '<img src="' . get_template_directory_uri() . '/assets/dist/img/blocks/block-banner-contacto.png' . '" style="display: block; margin: 0 auto;  max-width:100%;">'
						),
					),
				),
			)
		);

		/**
		 * Block About cards
		 */
		acf_register_block(
			array(
				'name'            => 'block-about-cards',
				'title'           => __('Block About Cards', 'sequoia'),
				'post_types' => array('page'),
				'description'     => __('Bloque con titulo, subtitulo y dos cards informativas', 'sequoia'),
				'render_template' => 'template-parts/blocks/4-about/block-about-cards.php',
				'category'        => 'sequoia-about',
				'mode'            => 'edit',
				'icon'            => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-bookmarks" viewBox="0 0 16 16"><path d="M2 4a2 2 0 0 1 2-2h6a2 2 0 0 1 2 2v11.5a.5.5 0 0 1-.777.416L7 13.101l-4.223 2.815A.5.5 0 0 1 2 15.5V4zm2-1a1 1 0 0 0-1 1v10.566l3.723-2.482a.5.5 0 0 1 .554 0L11 14.566V4a1 1 0 0 0-1-1H4z"/><path d="M4.268 1H12a1 1 0 0 1 1 1v11.768l.223.148A.5.5 0 0 0 14 13.5V2a2 2 0 0 0-2-2H6a2 2 0 0 0-1.732 1z"/> </svg>',
				'keywords'        => array('categorias', 'sequoia'),
				'render_callback' => 'render_preview',
				'example'         => array(
					'attributes' => array(
						'mode' => 'preview', // Important!
						'data' => array(
							'image' => '<img src="' . get_template_directory_uri() . '/assets/dist/img/blocks/block-banner-contacto.png' . '" style="display: block; margin: 0 auto;  max-width:100%;">'
						),
					),
				),
			)
		);

		
		/**
		 * Block Contact
		 */
		acf_register_block(
			array(
				'name'            => 'block-contact',
				'title'           => __('Block Contact', 'sequoia'),
				'post_types' => array('page'),
				'description'     => __('Bloque de contacto, con un mensaje una imagen y un footer custom', 'sequoia'),
				'render_template' => 'template-parts/blocks/5-contact/block-contact.php',
				'category'        => 'sequoia-contact',
				'mode'            => 'edit',
				'icon'            => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-bookmarks" viewBox="0 0 16 16"><path d="M2 4a2 2 0 0 1 2-2h6a2 2 0 0 1 2 2v11.5a.5.5 0 0 1-.777.416L7 13.101l-4.223 2.815A.5.5 0 0 1 2 15.5V4zm2-1a1 1 0 0 0-1 1v10.566l3.723-2.482a.5.5 0 0 1 .554 0L11 14.566V4a1 1 0 0 0-1-1H4z"/><path d="M4.268 1H12a1 1 0 0 1 1 1v11.768l.223.148A.5.5 0 0 0 14 13.5V2a2 2 0 0 0-2-2H6a2 2 0 0 0-1.732 1z"/> </svg>',
				'keywords'        => array('categorias', 'sequoia'),
				'render_callback' => 'render_preview',
				'example'         => array(
					'attributes' => array(
						'mode' => 'preview', // Important!
						'data' => array(
							'image' => '<img src="' . get_template_directory_uri() . '/assets/dist/img/blocks/block-banner-contacto.png' . '" style="display: block; margin: 0 auto;  max-width:100%;">'
						),
					),
				),
			)
		);

		/**
		 * Block Quienes Somos
		 */
		acf_register_block(
			array(
				'name'            => 'block-quienes-somos',
				'title'           => __('Block Quienes Somos', 'sequoia'),
				'post_types' => array('page'),
				'description'     => __('Bloque de quienes somos, información sobre la marca acompañado por un título, subtítulo, introducción e imagenes + texto.', 'sequoia'),
				'render_template' => 'template-parts/blocks/4-about/block-quienes-somos.php',
				'category'        => 'sequoia-about',
				'mode'            => 'edit',
				'icon'            => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-bookmarks" viewBox="0 0 16 16"><path d="M2 4a2 2 0 0 1 2-2h6a2 2 0 0 1 2 2v11.5a.5.5 0 0 1-.777.416L7 13.101l-4.223 2.815A.5.5 0 0 1 2 15.5V4zm2-1a1 1 0 0 0-1 1v10.566l3.723-2.482a.5.5 0 0 1 .554 0L11 14.566V4a1 1 0 0 0-1-1H4z"/><path d="M4.268 1H12a1 1 0 0 1 1 1v11.768l.223.148A.5.5 0 0 0 14 13.5V2a2 2 0 0 0-2-2H6a2 2 0 0 0-1.732 1z"/> </svg>',
				'keywords'        => array('categorias', 'sequoia'),
				'render_callback' => 'render_preview',
				'example'         => array(
					'attributes' => array(
						'mode' => 'preview', // Important!
						'data' => array(
							'image' => '<img src="' . get_template_directory_uri() . '/assets/dist/img/blocks/block-banner-contacto.png' . '" style="display: block; margin: 0 auto;  max-width:100%;">'
						),
					),
				),
			)
		);

		/**
		 * Block Ventajas
		 */
		acf_register_block(
			array(
				'name'            => 'block-ventajas',
				'title'           => __('Block Ventajas', 'sequoia'),
				'post_types' => array('page'),
				'description'     => __('Bloque de ventajas de sequoia, consiste en distintas cards, con información sobre el método de elaboración... que se pueden arrastrar y mover por la pantalla.', 'sequoia'),
				'render_template' => 'template-parts/blocks/4-about/block-ventajas.php',
				'category'        => 'sequoia-about',
				'mode'            => 'edit',
				'icon'            => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-bookmarks" viewBox="0 0 16 16"><path d="M2 4a2 2 0 0 1 2-2h6a2 2 0 0 1 2 2v11.5a.5.5 0 0 1-.777.416L7 13.101l-4.223 2.815A.5.5 0 0 1 2 15.5V4zm2-1a1 1 0 0 0-1 1v10.566l3.723-2.482a.5.5 0 0 1 .554 0L11 14.566V4a1 1 0 0 0-1-1H4z"/><path d="M4.268 1H12a1 1 0 0 1 1 1v11.768l.223.148A.5.5 0 0 0 14 13.5V2a2 2 0 0 0-2-2H6a2 2 0 0 0-1.732 1z"/> </svg>',
				'keywords'        => array('categorias', 'sequoia'),
				'render_callback' => 'render_preview',
				'example'         => array(
					'attributes' => array(
						'mode' => 'preview', // Important!
						'data' => array(
							'image' => '<img src="' . get_template_directory_uri() . '/assets/dist/img/blocks/block-banner-contacto.png' . '" style="display: block; margin: 0 auto;  max-width:100%;">'
						),
					),
				),
			)
		);

		/**
		 * Block Quote
		 */
		acf_register_block(
			array(
				'name'            => 'block-quote',
				'title'           => __('Block Quote', 'sequoia'),
				'post_types' => array('page'),
				'description'     => __('Bloque contiene una frase rodeada de imágenes con animación de aparición.', 'sequoia'),
				'render_template' => 'template-parts/blocks/4-about/block-quote.php',
				'category'        => 'sequoia-about',
				'mode'            => 'edit',
				'icon'            => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-bookmarks" viewBox="0 0 16 16"><path d="M2 4a2 2 0 0 1 2-2h6a2 2 0 0 1 2 2v11.5a.5.5 0 0 1-.777.416L7 13.101l-4.223 2.815A.5.5 0 0 1 2 15.5V4zm2-1a1 1 0 0 0-1 1v10.566l3.723-2.482a.5.5 0 0 1 .554 0L11 14.566V4a1 1 0 0 0-1-1H4z"/><path d="M4.268 1H12a1 1 0 0 1 1 1v11.768l.223.148A.5.5 0 0 0 14 13.5V2a2 2 0 0 0-2-2H6a2 2 0 0 0-1.732 1z"/> </svg>',
				'keywords'        => array('categorias', 'sequoia'),
				'render_callback' => 'render_preview',
				'example'         => array(
					'attributes' => array(
						'mode' => 'preview', // Important!
						'data' => array(
							'image' => '<img src="' . get_template_directory_uri() . '/assets/dist/img/blocks/block-banner-contacto.png' . '" style="display: block; margin: 0 auto;  max-width:100%;">'
						),
					),
				),
			)
		);

		/**
		 * Block Hero Nosotros
		 */
		acf_register_block(
			array(
				'name'            => 'block-hero-nosotros',
				'title'           => __('Block Hero Nosotros', 'sequoia'),
				'post_types' => array('page'),
				'description'     => __('Bloque que contiene un título y subtítulo además de 3 o más "pills" con texto.', 'sequoia'),
				'render_template' => 'template-parts/blocks/4-about/block-hero-nosotros.php',
				'category'        => 'sequoia-about',
				'mode'            => 'edit',
				'icon'            => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-bookmarks" viewBox="0 0 16 16"><path d="M2 4a2 2 0 0 1 2-2h6a2 2 0 0 1 2 2v11.5a.5.5 0 0 1-.777.416L7 13.101l-4.223 2.815A.5.5 0 0 1 2 15.5V4zm2-1a1 1 0 0 0-1 1v10.566l3.723-2.482a.5.5 0 0 1 .554 0L11 14.566V4a1 1 0 0 0-1-1H4z"/><path d="M4.268 1H12a1 1 0 0 1 1 1v11.768l.223.148A.5.5 0 0 0 14 13.5V2a2 2 0 0 0-2-2H6a2 2 0 0 0-1.732 1z"/> </svg>',
				'keywords'        => array('categorias', 'sequoia'),
				'render_callback' => 'render_preview',
				'example'         => array(
					'attributes' => array(
						'mode' => 'preview', // Important!
						'data' => array(
							'image' => '<img src="' . get_template_directory_uri() . '/assets/dist/img/blocks/block-banner-contacto.png' . '" style="display: block; margin: 0 auto;  max-width:100%;">'
						),
					),
				),
			)
		);
	}
}

add_action('acf/init', 'sequoia_blocks');


/**
 * Poder ver la preview de los acf
 * @link https://support.advancedcustomfields.com/forums/topic/custom-fields-on-post-preview/
 */
function fix_acf_field_post_id_on_preview($post_id, $original_post_id)
{
	// Don't do anything to options
	if (is_string($post_id) && str_contains($post_id, 'option')) {
		return $post_id;
	}
	// Don't do anything to blocks
	if (is_string($original_post_id) && str_contains($original_post_id, 'block')) {
		return $post_id;
	}

	// This should only affect on post meta fields
	if (is_preview()) {
		return get_the_ID();
	}

	return $post_id;
}

add_filter('acf/validate_post_id', __NAMESPACE__ . '\fix_acf_field_post_id_on_preview', 10, 2);


// ********** Ruta del archivo JSON Guardar campos ACF (¡esto funciona bien!)) **********

function my_acf_json_save_point(): string
{

	// update path HAVING ISSUES!!!
	// return
	return plugin_dir_path(__FILE__) . 'acf-json';
}

add_filter('acf/settings/save_json', 'my_acf_json_save_point');

// ********** Ruta del archivo JSON Cargar campos ACF (¿esto no funciona?) **********
/**
 * @param $paths
 */
function my_acf_json_load_point($paths)
{
	// Remove original path
	unset($paths[0]); // Append our new path
	$paths[] = plugin_dir_path(__FILE__) . 'acf-json';

	return $paths;
}

add_filter('acf/settings/load_json', 'my_acf_json_load_point');

/**
 * Callback block render,
 * return preview image
 * @link //https://stackoverflow.com/questions/65886937/show-preview-image-for-custom-gutenberg-blocks
 * @link https://www.grbav.com/acf-custom-block-shows-preview-image-in-gutenberg/
 * @param $block
 */
function render_preview($block): void
{
	/**
	 * Back-end preview
	 */

	/**
	 * Back-end preview
	 */
	if (!empty($block['data']['image'])) {
		echo $block['data']['image'];
	} else {
		if ($block) :
			$template = $block['render_template'];
			$template = str_replace('.php', '', $template);
			get_template_part('/' . $template);
		endif;
	}
}
