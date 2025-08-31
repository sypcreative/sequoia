<?php
$currentSiteID = get_current_blog_id();
?>
<script>
    // Variable de control para el gtm.
    let controlGTMFirtTime = 0
    datalayerPage()

    /**
     * Sets the dataLayer variables for the current page based on the URL slug and predefined mappings.
     * Also logs the dataLayer if the user is logged in.
     *
     * @return {void}
     */
    function datalayerPage() {
        let dataLayerData;
        let isGroup = true;
        let gtmPaginas = {}
        let gtmPaginasGroup = {
            'other': {
                'pageCategory': 'other',
                'pageSection': 'other',
                'pageType': 'other'
            },
            '404': {
                'pageCategory': 'error',
                'pageSection': '404',
                'pageType': '404'
            },
            'home': {
                'pageCategory': 'home',
                'pageSection': 'home',
                'pageType': 'home'
            },
            'estrategia': {
                'pageCategory': 'servicios',
                'pageSection': 'estrategia',
                'pageType': 'estrategia'
            },
            'creacion': {
                'pageCategory': 'servicios',
                'pageSection': 'creacion',
                'pageType': 'creacion'
            },
            'activacion': {
                'pageCategory': 'servicios',
                'pageSection': 'activacion',
                'pageType': 'activacion'
            },
            'inversores': {
                'pageCategory': 'corporativo',
                'pageSection': 'inversores',
                'pageType': 'inversores'
            },
            'noticias': {
                'pageCategory': 'corporativo',
                'pageSection': 'blog',
                'pageType': 'blog'
            },
            'post': {
                'pageCategory': 'corporativo',
                'pageSection': 'blog',
                'pageType': 'post'
            },
            'quienes-somos': {
                'pageCategory': 'corporativo',
                'pageSection': 'quienes somos',
                'pageType': 'quienes somos'
            },
            'contacto': {
                'pageCategory': 'corporativo',
                'pageSection': 'contacto',
                'pageType': 'contacto'
            },
            'politica-privacidad': {
                'pageCategory': 'corporativo',
                'pageSection': 'legal',
                'pageType': 'politica privacidad'
            },
            'politica-de-cookies': {
                'pageCategory': 'corporativo',
                'pageSection': 'legal',
                'pageType': 'politica cookies'
            },
            'aviso-legal': {
                'pageCategory': 'corporativo',
                'pageSection': 'legal',
                'pageType': 'aviso legal'
            }
        };
        let gtmPaginasOne = {
            'other': {
                'pageCategory': 'other',
                'pageSection': 'other',
                'pageType': 'other'
            },
            '404': {
                'pageCategory': 'error',
                'pageSection': '404',
                'pageType': '404'
            },
            'home': {
                'pageCategory': 'home',
                'pageSection': 'home',
                'pageType': 'home'
            },
            'casos-de-exito': {
                'pageCategory': 'corporativo',
                'pageSection': 'casos de éxito',
                'pageType': 'casos de éxito'
            },
            'blog': {
                'pageCategory': 'corporativo',
                'pageSection': 'blog',
                'pageType': 'blog'
            },
            'post': {
                'pageCategory': 'corporativo',
                'pageSection': 'blog',
                'pageType': 'post'
            },
            'quienes-somos': {
                'pageCategory': 'corporativo',
                'pageSection': 'quienes somos',
                'pageType': 'quienes somos'
            },
            'contacto': {
                'pageCategory': 'corporativo',
                'pageSection': 'contacto',
                'pageType': 'contacto'
            },
            'politica-privacidad': {
                'pageCategory': 'corporativo',
                'pageSection': 'legal',
                'pageType': 'politica privacidad'
            },
            'politica-de-cookies': {
                'pageCategory': 'corporativo',
                'pageSection': 'legal',
                'pageType': 'politica cookies'
            },
            'aviso-legal': {
                'pageCategory': 'corporativo',
                'pageSection': 'legal',
                'pageType': 'aviso legal'
            },
            'politica-de-seguridad-de-la-informacion': {
                'pageCategory': 'corporativo',
                'pageSection': 'legal',
                'pageType': 'politica de seguridad de la informacion'
            }
        };
		<?php
		if ($currentSiteID == 2) {
			echo 'isGroup = false;';
		}
		?>
        if (isGroup) {
            gtmPaginas = gtmPaginasGroup
        } else {
            gtmPaginas = gtmPaginasOne
        }
        var urlSegments = window.location.pathname.split('/');
        var slugActual = urlSegments[1] || ''; // Ajusta el índice según la estructura de tu URL

        // Comprobando si es la página de inicio
        if (window.location.pathname === '/' || window.location.pathname === '') {
            slugActual = 'home'
        }
        // Si tiene blog en la URL y luego Categoria = Post
        if (urlSegments[1] == 'blog' && urlSegments[2] == 'category') {
            slugActual = 'post'
        }
        // Si tiene blog en la URL y después más contenido = Post
        else if (urlSegments[1] == 'blog' && urlSegments[2]) {
            slugActual = 'post'
        }
        if (controlGTMFirtTime >= 1) {
            dataLayerData = {
                'event': 'virtual_page',
                'path': window.location.pathname,
                'pageCategory': '',
                'pageSection': '',
                'pageType': ''
            };
        } else {
            dataLayerData = {
                'path': window.location.pathname,
                'pageCategory': '',
                'pageSection': '',
                'pageType': ''
            };
            controlGTMFirtTime++;
        }
        // Comprobar si el slug actual está en el array
        if (gtmPaginas.hasOwnProperty(slugActual)) {
            dataLayerData.pageCategory = gtmPaginas[slugActual].pageCategory;
            dataLayerData.pageSection = gtmPaginas[slugActual].pageSection;
            dataLayerData.pageType = gtmPaginas[slugActual].pageType;
        } else {
            dataLayerData.pageCategory = gtmPaginas['404'].pageCategory;
            dataLayerData.pageSection = gtmPaginas['404'].pageSection;
            dataLayerData.pageType = gtmPaginas['404'].pageType;
        }
        window.dataLayer = window.dataLayer || [];
        window.dataLayer.push(dataLayerData);
    }
</script>
