# 🌲 Sequoia Group – Rediseño Web

### Proyecto de rediseño y desarrollo web realizado por **Paula Sanz (SYP Creative)**

---

## 🧭 Descripción general

Este proyecto consiste en el **rediseño completo del sitio web de Sequoia Group**, con el objetivo de ofrecer una experiencia más moderna, coherente con la identidad de marca y totalmente editable desde el panel de administración de WordPress.

Se ha desarrollado un **tema personalizado en PHP**, junto con un **plugin propio** que integra **bloques Gutenberg reutilizables** para la creación flexible de páginas y secciones dinámicas.

---

## ⚙️ Tecnologías utilizadas

- **WordPress (PHP 8+)**
- **HTML5 / SCSS / JavaScript (ES6)**
- **Gutenberg Blocks (React)**
- **ACF Pro** (para campos personalizados)
- **Lenis.js** (scroll suave)
- **GSAP / Framer Motion** (animaciones)
- **Webpack / Vite** (build frontend)
- **Git** (control de versiones)

---

## 🧩 Estructura principal

```plaintext
wp-content/
│
├── themes/
│   └── sequoia-theme/        # Tema principal personalizado
│       ├── template-parts/   # Bloques y componentes de página
│       ├── assets/           # SCSS, JS y fuentes personalizadas
│       ├── functions.php     # Configuración del tema
│       └── style.css
│
├── plugins/
│   └── sequoia-blocks/       # Plugin personalizado de bloques Gutenberg
│       ├── src/              # Bloques React + JSX
│       ├── build/            # Archivos compilados
│       ├── sequoia-blocks.php
│       └── block.json
│
└── uploads/                  # Contenido multimedia subido por el cliente
```

---

## 🚀 Características destacadas

- 🔹 **Diseño totalmente a medida**, adaptable a todos los dispositivos.
- 🔹 **Sistema modular de bloques Gutenberg**, que permite construir páginas con libertad y consistencia visual.
- 🔹 **Integración con ACF** para datos dinámicos y opciones globales del sitio.
- 🔹 **Optimización de rendimiento**, con imágenes en formato WebP y carga diferida.
- 🔹 **Scroll y animaciones suaves** para una experiencia fluida y moderna.
- 🔹 **Panel de administración personalizado** para simplificar la edición de contenido.

---

## 🧠 Desarrollo del plugin personalizado

El plugin **`sequoia-blocks`** añade varios bloques Gutenberg reutilizables, como:

- **Hero Section** (con imagen o vídeo de fondo)
- **Bloques de texto + imagen**
- **Grid de servicios o proyectos**
- **Bloques de testimonios**
- **Sección de contacto dinámica**

Cada bloque incluye soporte para estilos personalizados y campos ACF, lo que permite total control desde el editor.

---

## 🔧 Instalación y configuración

1. Clonar el repositorio o subir los archivos al servidor WordPress.
2. Activar el tema **Sequoia Theme** desde el panel de WordPress.
3. Instalar y activar el plugin **Sequoia Blocks**.
4. Asegurarse de tener **ACF Pro** y **Gutenberg activo**.
5. Configurar las opciones generales del sitio en el panel **Apariencia → Personalizar**.

---

## 📦 Dependencias (frontend)

Instalación de dependencias:

```bash
npm install

```
