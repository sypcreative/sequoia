import { setCategories, getCategories } from '@wordpress/blocks';
import domReady from '@wordpress/dom-ready';

domReady(() => {
  setCategories([
    {
      slug: 'sequoia-home',
      title: 'Sequoia | Home',
      icon: 'admin-home', // Puedes cambiar el icono si quieres
    },
    ...getCategories().filter((category) => category.slug !== 'sequoia-home'),
  ]);
});

import '../template-parts/blocks/hero/src/index.js';