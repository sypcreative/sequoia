(function (wp) {
  const { registerBlockType } = wp.blocks;
  const {
    useBlockProps,
    RichText,
    MediaUpload,
    MediaUploadCheck,
    BlockControls,
  } = wp.blockEditor;
  const { ToolbarGroup, ToolbarButton, Button, TextControl, TextareaControl } =
    wp.components;
  const { Fragment, createElement: el } = wp.element;

  registerBlockType("syp/team-grid", {
    edit({ attributes, setAttributes }) {
      const { heading = "", intro = "", people = [] } = attributes;
      const blockProps = useBlockProps({ className: "syp-team editor" });

      const addPerson = () =>
        setAttributes({
          people: [
            ...people,
            { name: "", role: "", email: "", imageUrl: "", description: "" },
          ],
        });

      const updatePerson = (i, k, v) =>
        setAttributes({
          people: people.map((p, idx) => (idx === i ? { ...p, [k]: v } : p)),
        });

      const removePerson = (i) =>
        setAttributes({ people: people.filter((_, idx) => idx !== i) });

      const movePerson = (from, to) => {
        if (to < 0 || to >= people.length) return;
        const next = [...people];
        const [m] = next.splice(from, 1);
        next.splice(to, 0, m);
        setAttributes({ people: next });
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
              label: "Añadir miembro",
              onClick: addPerson,
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
              { className: "syp-team__lead" },
              el(RichText, {
                tagName: "h2",
                className: "syp-team__title",
                placeholder: "The team at Sequoia GRP…",
                value: heading,
                onChange: (v) => setAttributes({ heading: v }),
                allowedFormats: [],
              }),
              el(RichText, {
                tagName: "p",
                className: "syp-team__intro",
                placeholder: "Intro…",
                value: intro,
                onChange: (v) => setAttributes({ intro: v }),
              })
            ),

            el(
              "div",
              { className: "syp-team__grid" },
              people.map((p, i) =>
                el(
                  "article",
                  { key: i, className: "syp-person" },

                  el(
                    "div",
                    { className: "syp-person__media" },
                    p.imageUrl
                      ? el("img", { src: p.imageUrl, alt: "" })
                      : el("div", { className: "syp-person__ph" }, "No image"),
                    el(
                      "div",
                      { className: "syp-person__upload" },
                      el(
                        MediaUploadCheck,
                        null,
                        el(MediaUpload, {
                          onSelect: (m) => updatePerson(i, "imageUrl", m.url),
                          allowedTypes: ["image"],
                          render: ({ open }) =>
                            el(
                              Button,
                              { variant: "secondary", onClick: open },
                              p.imageUrl ? "Cambiar foto" : "Subir foto"
                            ),
                        })
                      )
                    )
                  ),

                  el(
                    "div",
                    { className: "syp-person__meta" },
                    el(TextControl, {
                      className: "syp-person__name-input",
                      placeholder: "Nombre…",
                      value: p.name,
                      onChange: (v) => updatePerson(i, "name", v),
                    }),
                    el(TextControl, {
                      className: "syp-person__role-input",
                      placeholder: "Cargo…",
                      value: p.role,
                      onChange: (v) => updatePerson(i, "role", v),
                    }),
                    el(TextControl, {
                      className: "syp-person__email-input",
                      placeholder: "email@empresa.com",
                      value: p.email,
                      onChange: (v) => updatePerson(i, "email", v),
                    }),
                    el(TextareaControl, {
                      className: "syp-person__description-input",
                      placeholder: "Descripción / bio corta…",
                      value: p.description,
                      onChange: (v) => updatePerson(i, "description", v),
                      rows: 3,
                    })
                  ),

                  el(
                    "div",
                    { className: "syp-person__tools" },
                    el(
                      Button,
                      {
                        variant: "tertiary",
                        onClick: () => movePerson(i, i - 1),
                      },
                      "←"
                    ),
                    el(
                      Button,
                      {
                        variant: "tertiary",
                        onClick: () => movePerson(i, i + 1),
                      },
                      "→"
                    ),
                    el(
                      Button,
                      {
                        variant: "secondary",
                        isDestructive: true,
                        onClick: () => removePerson(i),
                      },
                      "Eliminar"
                    )
                  )
                )
              ),
              el(
                Button,
                {
                  className: "syp-add",
                  variant: "primary",
                  onClick: addPerson,
                },
                "+ Añadir miembro"
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
