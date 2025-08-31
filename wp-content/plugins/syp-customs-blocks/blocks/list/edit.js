(function (wp) {
  const { registerBlockType } = wp.blocks;
  const { useBlockProps, RichText, BlockControls } = wp.blockEditor;
  const { ToolbarGroup, ToolbarButton, Button, TextareaControl } =
    wp.components;
  const { Fragment, createElement: el } = wp.element;

  registerBlockType("syp/list", {
    edit({ attributes, setAttributes }) {
      const { heading = "", items = [] } = attributes;
      const blockProps = useBlockProps({ className: "syp-list editor" });

      const addItem = () => setAttributes({ items: [...items, { text: "" }] });
      const updateItem = (i, v) =>
        setAttributes({
          items: items.map((it, idx) => (idx === i ? { text: v } : it)),
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
              label: "Añadir ítem",
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
            el(RichText, {
              tagName: "h2",
              className: "syp-list__title",
              placeholder: "What our team delivers",
              value: heading,
              onChange: (v) => setAttributes({ heading: v }),
              allowedFormats: [],
            }),
            el(
              "div",
              { className: "syp-list__items" },
              items.map((it, i) =>
                el(
                  "div",
                  { key: i, className: "syp-list__item" },
                  // icono solo como preview en editor
                  el(
                    "span",
                    { className: "syp-list__icon", "aria-hidden": true },
                    "✓"
                  ),
                  el(TextareaControl, {
                    className: "syp-list__text-input",
                    placeholder: "Texto del punto…",
                    value: it.text,
                    onChange: (v) => updateItem(i, v),
                  }),
                  el(
                    "div",
                    { className: "syp-list__actions" },
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
                "+ Añadir ítem"
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
