(function (wp) {
  const { registerBlockType } = wp.blocks;
  const { useBlockProps, InspectorControls, BlockControls } = wp.blockEditor;
  const {
    PanelBody,
    ToggleControl,
    SelectControl,
    RangeControl,
    ToolbarGroup,
    ToolbarButton,
  } = wp.components;
  const { Fragment, createElement: el } = wp.element;
  const ServerSideRender = wp.serverSideRender;

  registerBlockType("syp/blog-card", {
    edit({ attributes, setAttributes }) {
      const {
        dateRange = "all",
        bg = "primary",
        excerptLength = 24,
        showArrow = true,
      } = attributes;

      const blockProps = useBlockProps({ className: "syp-blog-cards editor" });

      return el(
        Fragment,
        null,

        // Toolbar: toggle flecha
        el(
          BlockControls,
          null,
          el(
            ToolbarGroup,
            null,
            el(ToolbarButton, {
              icon: showArrow ? "visibility" : "hidden",
              label: showArrow ? "Ocultar flecha" : "Mostrar flecha",
              onClick: () => setAttributes({ showArrow: !showArrow }),
            })
          )
        ),

        // Inspector
        el(
          InspectorControls,
          null,
          el(
            PanelBody,
            { title: "Ajustes" },
            el(SelectControl, {
              label: "Mostrar",
              value: dateRange,
              options: [
                { label: "Todos los posts", value: "all" },
                { label: "Solo del último año", value: "last_year" },
              ],
              onChange: (v) => setAttributes({ dateRange: v }),
            }),
            el(SelectControl, {
              label: "Color de fondo (Bootstrap)",
              value: bg,
              options: [
                { label: "primary", value: "primary" },
                { label: "secondary", value: "secondary" },
                { label: "success", value: "success" },
                { label: "danger", value: "danger" },
                { label: "warning", value: "warning" },
                { label: "info", value: "info" },
                { label: "light", value: "light" },
                { label: "dark", value: "dark" },
              ],
              onChange: (v) => setAttributes({ bg: v }),
            }),
            el(RangeControl, {
              label: "Longitud del extracto (palabras)",
              min: 0,
              max: 80,
              value: excerptLength,
              onChange: (v) => setAttributes({ excerptLength: v }),
            }),
            el(ToggleControl, {
              label: "Mostrar flecha",
              checked: !!showArrow,
              onChange: (val) => setAttributes({ showArrow: !!val }),
            })
          )
        ),

        // Vista previa SSR
        el(
          "div",
          blockProps,
          el(ServerSideRender, {
            block: "syp/blog-card",
            attributes,
          })
        )
      );
    },

    save() {
      return null; // SSR
    },
  });
})(window.wp);
