(function (wp) {
  const { registerBlockType } = wp.blocks;
  const { useBlockProps, RichText, URLInputButton } = wp.blockEditor;
  const { Button, TextControl } = wp.components;
  const el = wp.element.createElement;
  const Fragment = wp.element.Fragment;

  registerBlockType("syp/title-text-button", {
    edit({ attributes, setAttributes }) {
      const { title, paragraphs = [], buttonText, buttonUrl } = attributes;

      const blockProps = useBlockProps({
        className: "syp-title-text-button", // en editor usaremos el wrapper Gutenberg .wp-block-syp-title-text-button
      });

      const addParagraph = () =>
        setAttributes({ paragraphs: [...paragraphs, ""] });
      const updateParagraph = (i, v) => {
        const next = [...paragraphs];
        next[i] = v;
        setAttributes({ paragraphs: next });
      };
      const removeParagraph = (i) => {
        const next = [...paragraphs];
        next.splice(i, 1);
        setAttributes({ paragraphs: next });
      };

      return el(
        "section",
        blockProps,
        el(
          "div",
          { className: "row" },
          el(
            "div",
            { className: "col-lg-10" },

            // Título
            el(RichText, {
              tagName: "h2",
              className: "mb-3",
              placeholder: "Add your title here",
              value: title,
              onChange: (val) => setAttributes({ title: val }),
              allowedFormats: ["core/bold", "core/italic", "core/link"],
            }),

            // Repetidor
            el(
              Fragment,
              null,
              paragraphs.map((p, i) =>
                el(
                  "div",
                  { key: i, className: "syp-repeater-item" },
                  el(RichText, {
                    tagName: "p",
                    placeholder: "Add your text here",
                    value: p,
                    className: "mb-0",
                    onChange: (val) => updateParagraph(i, val),
                    allowedFormats: ["core/bold", "core/italic", "core/link"],
                  }),
                  el(
                    Button,
                    {
                      className: "syp-repeater-remove",
                      isDestructive: true,
                      variant: "secondary",
                      onClick: () => removeParagraph(i),
                      "aria-label": "Delete Paragraph",
                    },
                    "−"
                  )
                )
              ),
              el(
                Button,
                {
                  className: "syp-add",
                  variant: "primary",
                  onClick: addParagraph,
                },
                "+ Add Paragraph"
              )
            ),

            // CTA
            el(
              "div",
              { className: "syp-cta" },
              el(TextControl, {
                label: "Button Text",
                placeholder: "View out product portfolio",
                value: buttonText,
                onChange: (val) => setAttributes({ buttonText: val }),
              }),
              el(URLInputButton, {
                url: buttonUrl,
                onChange: (url) => setAttributes({ buttonUrl: url }),
              })
            )
          )
        )
      );
    },
    save() {
      return null;
    }, // dinámico
  });
})(window.wp);
