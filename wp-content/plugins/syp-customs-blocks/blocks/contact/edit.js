(function (wp) {
  const { registerBlockType } = wp.blocks;
  const { useBlockProps, RichText, BlockControls, URLInputButton } =
    wp.blockEditor;
  const {
    ToolbarGroup,
    ToolbarButton,
    TextControl,
    TextareaControl,
    ToggleControl,
  } = wp.components;
  const { Fragment, createElement: el } = wp.element;

  registerBlockType("syp/contact-cta", {
    edit({ attributes, setAttributes }) {
      const {
        heading = "",
        intro = "",
        ctaText = "",
        ctaUrl = "",
        ctaTarget = "",
        useSiteOptions = true,
        contact = { company: "", address: "", email: "", linkedin: "" },
      } = attributes;

      const blockProps = useBlockProps({ className: "syp-contact editor" });

      const setC = (k, v) => setAttributes({ contact: { ...contact, [k]: v } });

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
              icon: "admin-generic",
              label: "Use general information: ON/OFF",
              isPressed: useSiteOptions,
              onClick: () => setAttributes({ useSiteOptions: !useSiteOptions }),
            })
          )
        ),

        el(
          "section",
          blockProps,
          el(
            "div",
            { className: "container grid" },

            // Columna izquierda: lead + CTA
            el(
              "div",
              { className: "syp-contact__lead" },
              el(RichText, {
                tagName: "h2",
                className: "syp-contact__title",
                placeholder: "Let’s Build a Smarter Supply Chain Together",
                value: heading,
                onChange: (v) => setAttributes({ heading: v }),
                allowedFormats: [],
              }),
              el(RichText, {
                tagName: "p",
                className: "syp-contact__intro",
                placeholder: "Whether you’re looking to buy, sell...",
                value: intro,
                onChange: (v) => setAttributes({ intro: v }),
              }),
              el(
                "div",
                { className: "syp-contact__cta" },
                el(TextControl, {
                  label: "Button text",
                  value: ctaText,
                  onChange: (v) => setAttributes({ ctaText: v }),
                  placeholder: "Speak with our Team",
                }),
                el(URLInputButton, {
                  url: ctaUrl,
                  onChange: (u) => setAttributes({ ctaUrl: u }),
                  label: "URL del botón",
                }),
                el(ToggleControl, {
                  label: "Open in new tab",
                  checked: ctaTarget === "_blank",
                  onChange: (b) =>
                    setAttributes({ ctaTarget: b ? "_blank" : "" }),
                })
              )
            ),

            // Columna derecha: tarjeta
            el(
              "aside",
              { className: "syp-contact__card syp-card" },
              el(
                "div",
                { className: "syp-card__inner" },

                el(ToggleControl, {
                  label: "Use general information",
                  checked: useSiteOptions,
                  onChange: (v) => setAttributes({ useSiteOptions: v }),
                }),

                el(TextControl, {
                  label: "Company",
                  value: contact.company,
                  onChange: (v) => setC("company", v),
                  help: useSiteOptions
                    ? "Se ignorará en el front (se usarán las opciones del sitio)"
                    : "",
                }),
                el(TextareaControl, {
                  label: "Address",
                  value: contact.address,
                  onChange: (v) => setC("address", v),
                }),
                el(TextControl, {
                  label: "Email",
                  type: "email",
                  value: contact.email,
                  onChange: (v) => setC("email", v),
                }),
                el(TextControl, {
                  label: "LinkedIn (URL)",
                  type: "url",
                  value: contact.linkedin,
                  onChange: (v) => setC("linkedin", v),
                })
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
