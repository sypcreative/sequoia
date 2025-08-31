(function (wp) {
  const { registerBlockType } = wp.blocks;
  const {
    useBlockProps,
    RichText,
    MediaUpload,
    MediaUploadCheck,
    BlockControls,
    URLInputButton,
  } = wp.blockEditor;
  const {
    ToolbarGroup,
    ToolbarButton,
    Button,
    TextareaControl,
    TextControl,
    ToggleControl,
  } = wp.components;
  const { Fragment, createElement: el } = wp.element;

  registerBlockType("syp/why-sequoia", {
    edit({ attributes, setAttributes }) {
      const {
        heading = "",
        intro = "",
        ctaText = "",
        ctaUrl = "",
        ctaTarget = "",
        items = [],
        theme = "light",
      } = attributes;
      const blockProps = useBlockProps({ className: "syp-why editor" });

      const addItem = () =>
        setAttributes({
          items: [...items, { title: "", text: "", iconUrl: "" }],
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
              label: "Add item",
              onClick: addItem,
            }),
            el(ToolbarButton, {
              icon: theme === "light" ? "visibility" : "hidden",
              label: "Change theme",
              onClick: () =>
                setAttributes({ theme: theme === "light" ? "dark" : "light" }),
            })
          )
        ),

        el(
          "section",
          blockProps,
          el(
            "div",
            { className: "container" },

            // Intro block
            el(
              "div",
              { className: "syp-why__lead" },
              el(RichText, {
                tagName: "h2",
                className: "syp-why__title",
                placeholder: "Type your title here",
                value: heading,
                onChange: (v) => setAttributes({ heading: v }),
                allowedFormats: [],
              }),
              el(
                "div",
                { className: "syp-why__cta" },
                el(TextControl, {
                  className: "syp-why__cta-text",
                  label: "Button Text",
                  placeholder: "View our services",
                  value: ctaText,
                  onChange: (v) => setAttributes({ ctaText: v }),
                }),
                el(URLInputButton, {
                  url: ctaUrl,
                  onChange: (url) => setAttributes({ ctaUrl: url }),
                  label: "URL del botón",
                }),
                el(ToggleControl, {
                  label: "Open in new tab",
                  checked: ctaTarget === "_blank",
                  onChange: (val) =>
                    setAttributes({ ctaTarget: val ? "_blank" : "" }),
                })
              )
            ),

            // Accordion list
            el(
              "div",
              { className: "syp-why__list" },
              items.map((it, i) =>
                el(
                  "div",
                  { key: i, className: "syp-why__item" },
                  el(
                    "div",
                    { className: "syp-why__item-row" },
                    el(TextControl, {
                      className: "syp-why__item-title-input",
                      placeholder: "Item title",
                      value: it.title,
                      onChange: (v) => updateItem(i, "title", v),
                    }),
                    el(
                      "div",
                      {
                        style: {
                          display: "flex",
                          gap: 8,
                          alignItems: "center",
                        },
                      },
                      el(
                        MediaUploadCheck,
                        null,
                        el(MediaUpload, {
                          onSelect: (m) => updateItem(i, "iconUrl", m.url),
                          allowedTypes: ["image"],
                          render: ({ open }) =>
                            el(
                              Button,
                              { variant: "secondary", onClick: open },
                              it.iconUrl ? "Change icon" : "icon"
                            ),
                        })
                      ),
                      it.iconUrl &&
                        el("img", {
                          className: "syp-why__item-icon",
                          src: it.iconUrl,
                          alt: "",
                        })
                    )
                  ),
                  el(TextareaControl, {
                    className: "syp-why__item-body-input",
                    placeholder: "Type your text here",
                    value: it.text,
                    onChange: (v) => updateItem(i, "text", v),
                  }),
                  el(
                    "div",
                    { style: { display: "flex", gap: 8 } },
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
                      "Remove"
                    )
                  )
                )
              ),
              el(
                Button,
                { className: "syp-add", variant: "primary", onClick: addItem },
                "+ Add item"
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
