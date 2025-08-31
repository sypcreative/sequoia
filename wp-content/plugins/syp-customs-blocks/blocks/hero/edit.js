(function (wp) {
  const { InspectorControls, MediaUpload, MediaUploadCheck, useBlockProps } =
    wp.blockEditor || wp.editor;
  const {
    PanelBody,
    TextControl,
    TextareaControl,
    ToggleControl,
    SelectControl,
    Button,
  } = wp.components;

  wp.blocks.registerBlockType("syp/hero", {
    edit: (props) => {
      const { attributes, setAttributes } = props;
      const {
        title,
        subtitle,
        imageId,
        imageUrl,
        mobileImageId,
        mobileImageUrl,
        overlay,
        layout = "split",
        textPos = "right",
        theme = "light",
      } = attributes;

      const blockProps = useBlockProps({
        className: "syp-hero syp-hero--is-editor",
        style: { minHeight: "360px" }, // altura mínima visible al insertar
      });

      const onSelectImage = (m) =>
        setAttributes({ imageId: m?.id || 0, imageUrl: m?.url || "" });
      const onSelectMobile = (m) =>
        setAttributes({
          mobileImageId: m?.id || 0,
          mobileImageUrl: m?.url || "",
        });

      return wp.element.createElement(
        "div",
        blockProps,

        // ====== SIDEBAR (Inspector) — aquí van SIEMPRE los campos ======
        wp.element.createElement(
          InspectorControls,
          null,
          wp.element.createElement(
            PanelBody,
            { title: "Contenido", initialOpen: true },
            wp.element.createElement(TextControl, {
              label: "Título",
              value: title || "",
              onChange: (v) => setAttributes({ title: v }),
            }),
            wp.element.createElement(TextareaControl, {
              label: "Subtítulo",
              value: subtitle || "",
              onChange: (v) => setAttributes({ subtitle: v }),
            })
          ),
          wp.element.createElement(
            PanelBody,
            { title: "Imágenes", initialOpen: true },
            wp.element.createElement("div", null, "Imagen (desktop)"),
            wp.element.createElement(
              MediaUploadCheck,
              null,
              wp.element.createElement(MediaUpload, {
                onSelect: onSelectImage,
                allowedTypes: ["image"],
                value: imageId,
                render: ({ open }) =>
                  wp.element.createElement(
                    Button,
                    { onClick: open, variant: "secondary" },
                    imageId ? "Cambiar" : "Seleccionar"
                  ),
              })
            ),
            wp.element.createElement(
              "div",
              { style: { marginTop: 12 } },
              "Imagen (mobile)"
            ),
            wp.element.createElement(
              MediaUploadCheck,
              null,
              wp.element.createElement(MediaUpload, {
                onSelect: onSelectMobile,
                allowedTypes: ["image"],
                value: mobileImageId,
                render: ({ open }) =>
                  wp.element.createElement(
                    Button,
                    { onClick: open, variant: "secondary" },
                    mobileImageId ? "Cambiar" : "Seleccionar"
                  ),
              })
            )
          ),
          wp.element.createElement(
            PanelBody,
            { title: "Overlay", initialOpen: true },
            wp.element.createElement(ToggleControl, {
              label: "Mostrar overlay",
              checked: !!overlay,
              onChange: (v) => setAttributes({ overlay: !!v }),
            })
          ),
          wp.element.createElement(
            PanelBody,
            { title: "Layout", initialOpen: true },
            wp.element.createElement(SelectControl, {
              label: "Ancho de imagen",
              value: layout,
              options: [
                { label: "50% (Split)", value: "split" },
                { label: "100% (Full)", value: "full" },
              ],
              onChange: (v) => setAttributes({ layout: v }),
            }),
            wp.element.createElement(SelectControl, {
              label: "Posición del texto",
              value: textPos,
              options: [
                { label: "Derecha", value: "right" },
                { label: "Izquierda", value: "left" },
              ],
              onChange: (v) => setAttributes({ textPos: v }),
            }),
            wp.element.createElement(SelectControl, {
              label: "Tema del texto",
              value: theme,
              options: [
                { label: "Claro (blanco)", value: "light" },
                { label: "Oscuro (negro)", value: "dark" },
              ],
              onChange: (v) => setAttributes({ theme: v }),
            })
          )
        ),

        // ====== PREVIEW (no interactiva): no roba el click ======
        wp.element.createElement(
          "div",
          { className: "syp-hero__preview", style: { pointerEvents: "none" } },
          // usa la misma estructura visual que en el front (simplificada)
          layout === "split"
            ? wp.element.createElement(
                "div",
                { className: "syp-hero__grid" },
                textPos === "left" &&
                  wp.element.createElement(
                    "div",
                    { className: "syp-hero__col syp-hero__col--content" },
                    wp.element.createElement(
                      "div",
                      {
                        className: `syp-hero__content-wrap syp-hero--theme-${theme}`,
                      },
                      wp.element.createElement(
                        "h1",
                        { className: "syp-hero__title" },
                        title || "Título…"
                      ),
                      wp.element.createElement(
                        "p",
                        { className: "syp-hero__subtitle" },
                        subtitle || "Subtítulo…"
                      )
                    )
                  ),
                wp.element.createElement(
                  "div",
                  { className: "syp-hero__col syp-hero__col--media" },
                  imageUrl &&
                    wp.element.createElement("img", {
                      className: "syp-hero__image",
                      src: imageUrl,
                      alt: "",
                    }),
                  overlay &&
                    wp.element.createElement("span", {
                      className: "syp-hero__overlay",
                      "aria-hidden": true,
                    })
                ),
                textPos === "right" &&
                  wp.element.createElement(
                    "div",
                    { className: "syp-hero__col syp-hero__col--content" },
                    wp.element.createElement(
                      "div",
                      {
                        className: `syp-hero__content-wrap syp-hero--theme-${theme}`,
                      },
                      wp.element.createElement(
                        "h1",
                        { className: "syp-hero__title" },
                        title || "Título…"
                      ),
                      wp.element.createElement(
                        "p",
                        { className: "syp-hero__subtitle" },
                        subtitle || "Subtítulo…"
                      )
                    )
                  )
              )
            : wp.element.createElement(
                "div",
                { className: "syp-hero__grid syp-hero--full" },
                wp.element.createElement(
                  "div",
                  { className: "syp-hero__col syp-hero__col--media" },
                  imageUrl &&
                    wp.element.createElement("img", {
                      className: "syp-hero__image",
                      src: imageUrl,
                      alt: "",
                    }),
                  overlay &&
                    wp.element.createElement("span", {
                      className: "syp-hero__overlay",
                      "aria-hidden": true,
                    })
                ),
                wp.element.createElement(
                  "div",
                  { className: "syp-hero__col syp-hero__col--overlay-content" },
                  wp.element.createElement(
                    "div",
                    {
                      className: `syp-hero__content-wrap syp-hero--theme-${theme}`,
                    },
                    wp.element.createElement(
                      "h1",
                      { className: "syp-hero__title" },
                      title || "Título…"
                    ),
                    wp.element.createElement(
                      "p",
                      { className: "syp-hero__subtitle" },
                      subtitle || "Subtítulo…"
                    )
                  )
                )
              )
        )
      );
    },
    save: () => null,
  });
})(window.wp);
