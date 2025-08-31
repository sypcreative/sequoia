(function (wp) {
  const { registerBlockType } = wp.blocks;
  const {
    useBlockProps,
    RichText,
    MediaUpload,
    MediaUploadCheck,
    BlockControls,
  } = wp.blockEditor;
  const {
    ToolbarGroup,
    ToolbarButton,
    Button,
    TextControl,
    TextareaControl,
    SelectControl,
  } = wp.components;
  const { Fragment, createElement: el } = wp.element;

  registerBlockType("syp/products-pills", {
    edit({ attributes, setAttributes }) {
      const { heading = "", intro = "", items = [] } = attributes;
      const blockProps = useBlockProps({ className: "syp-pills editor" });

      // helpers
      const addItem = () =>
        setAttributes({
          items: [
            ...items,
            { title: "", desc: "", iconUrl: "", variant: "light" },
          ],
        });
      const updateItem = (i, key, val) =>
        setAttributes({
          items: items.map((it, idx) =>
            idx === i ? { ...it, [key]: val } : it
          ),
        });
      const removeItem = (i) =>
        setAttributes({ items: items.filter((_, idx) => idx !== i) });
      const moveItem = (from, to) => {
        if (to < 0 || to >= items.length) return;
        const next = [...items];
        const [m] = next.splice(from, 1);
        next.splice(to, 0, m);
        setAttributes({ items: next });
      };

      return el(
        Fragment,
        null,
        el(
          BlockControls,
          null,
          el(
            ToolbarGroup,
            null,
            el(ToolbarButton, {
              icon: "plus-alt2",
              label: "Añadir fila",
              onClick: addItem,
            })
          )
        ),

        el(
          "section",
          blockProps,
          el(
            "div",
            { className: "container" },

            el(
              "div",
              { className: "syp-pills__lead" },
              el(RichText, {
                tagName: "h2",
                className: "syp-pills__title",
                placeholder: "Products we manage",
                value: heading,
                onChange: (v) => setAttributes({ heading: v }),
                allowedFormats: [],
              }),
              el(RichText, {
                tagName: "p",
                className: "syp-pills__intro",
                placeholder: "Texto introductorio…",
                value: intro,
                onChange: (v) => setAttributes({ intro: v }),
              })
            ),

            el(
              "div",
              { className: "syp-pills__list" },
              items.map((it, i) =>
                el(
                  "div",
                  {
                    key: i,
                    className: `syp-pill syp-pill--${it.variant || "light"}`,
                  },
                  // columna izquierda: icono + título
                  el(
                    "div",
                    { className: "syp-pill__left" },
                    el(
                      "div",
                      { className: "syp-pill__icon" },
                      it.iconUrl
                        ? el("img", { src: it.iconUrl, alt: "" })
                        : el("div", { className: "syp-pill__icon-ph" }, "◻︎")
                    ),
                    el(TextControl, {
                      className: "syp-pill__title-input",
                      placeholder: "Título…",
                      value: it.title,
                      onChange: (v) => updateItem(i, "title", v),
                    })
                  ),
                  // derecha: descripción
                  el(TextareaControl, {
                    className: "syp-pill__desc-input",
                    placeholder: "Descripción…",
                    value: it.desc,
                    onChange: (v) => updateItem(i, "desc", v),
                  }),
                  // controles
                  el(
                    "div",
                    { className: "syp-pill__tools" },
                    el(SelectControl, {
                      label: "Estilo",
                      value: it.variant || "light",
                      options: [
                        { label: "Light Green", value: "light" },
                        { label: "Solid Green", value: "solid" },
                      ],
                      onChange: (v) => updateItem(i, "variant", v),
                    }),
                    el(
                      MediaUploadCheck,
                      null,
                      el(MediaUpload, {
                        onSelect: (m) => updateItem(i, "iconUrl", m.url),
                        allowedTypes: ["image", "image/svg+xml"],
                        render: ({ open }) =>
                          el(
                            Button,
                            { variant: "secondary", onClick: open },
                            it.iconUrl ? "Cambiar icono" : "Subir icono"
                          ),
                      })
                    ),
                    el(
                      "div",
                      { className: "syp-pill__arrows" },
                      el(
                        Button,
                        {
                          variant: "tertiary",
                          onClick: () => moveItem(i, i - 1),
                        },
                        "↑"
                      ),
                      el(
                        Button,
                        {
                          variant: "tertiary",
                          onClick: () => moveItem(i, i + 1),
                        },
                        "↓"
                      )
                    ),
                    el(
                      Button,
                      {
                        variant: "secondary",
                        isDestructive: true,
                        onClick: () => removeItem(i),
                      },
                      "Eliminar"
                    )
                  )
                )
              ),
              el(
                Button,
                { className: "syp-add", variant: "primary", onClick: addItem },
                "+ Añadir fila"
              )
            )
          )
        )
      );
    },
    save() {
      return null;
    },
  });
})(window.wp);
