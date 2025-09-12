(function (wp) {
  const { registerBlockType } = wp.blocks;
  const {
    useBlockProps,
    RichText,
    MediaUpload,
    MediaUploadCheck,
    BlockControls,
  } = wp.blockEditor;
  const { ToolbarGroup, ToolbarButton, Button, TextControl } = wp.components;
  const { Fragment, createElement: el } = wp.element;

  registerBlockType("syp/timeline", {
    edit({ attributes, setAttributes }) {
      const { items = [], introduction = "" } = attributes;
      const blockProps = useBlockProps({ className: "syp-timeline editor" });

      const addItem = () =>
        setAttributes({
          items: [
            ...items,
            { year: "", iconUrl: "", title: "", text: "", shortTitle: "" },
          ],
        });

      const updateItem = (i, k, v) =>
        setAttributes({
          items: items.map((it, idx) => (idx === i ? { ...it, [k]: v } : it)),
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
              label: "Añadir hito",
              onClick: addItem,
            })
          )
        ),

        el(
          "section",
          blockProps,

          // INTRODUCCIÓN (fuera del swiper/lista)
          el(RichText, {
            tagName: "p",
            className: "syp-timeline__intro",
            placeholder:
              "Industry experts founded Sequoia with a bold vision: to redefine the way used mobile devices move through the global supply chain. Here's how we got here:",
            value: introduction,
            onChange: (v) => setAttributes({ introduction: v }),
          }),

          // LISTA DE ITEMS
          el(
            "div",
            { className: "syp-timeline__list" },
            items.map((it, i) =>
              el(
                "article",
                { key: i, className: "syp-timeline__item" },

                // Año
                el(TextControl, {
                  label: "Año",
                  placeholder: "2020",
                  value: it.year,
                  onChange: (v) => updateItem(i, "year", v),
                }),

                // Icono
                el(
                  "div",
                  { className: "syp-timeline__icon-upload" },
                  it.iconUrl
                    ? el("img", {
                        src: it.iconUrl,
                        alt: "",
                        style: { maxWidth: "56px", height: "auto" },
                      })
                    : el(
                        "div",
                        { className: "syp-timeline__icon-ph" },
                        "Icono"
                      ),
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
                  )
                ),

                // Título
                el(RichText, {
                  tagName: "h3",
                  className: "syp-timeline__title",
                  placeholder: "Título…",
                  value: it.title,
                  onChange: (v) => updateItem(i, "title", v),
                  allowedFormats: [],
                }),

                // Texto
                el(RichText, {
                  tagName: "p",
                  className: "syp-timeline__text",
                  placeholder: "Descripción…",
                  value: it.text,
                  onChange: (v) => updateItem(i, "text", v),
                }),

                // Título abreviado
                el(TextControl, {
                  label: "Título abreviado",
                  placeholder: "The beginning",
                  value: it.shortTitle,
                  onChange: (v) => updateItem(i, "shortTitle", v),
                }),

                // Tools
                el(
                  "div",
                  { className: "syp-timeline__tools" },
                  el(
                    Button,
                    { variant: "tertiary", onClick: () => moveItem(i, i - 1) },
                    "↑"
                  ),
                  el(
                    Button,
                    { variant: "tertiary", onClick: () => moveItem(i, i + 1) },
                    "↓"
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
              "+ Añadir hito"
            )
          )
        )
      );
    },
    save() {
      return null; // SSR en render.php
    },
  });
})(window.wp);
