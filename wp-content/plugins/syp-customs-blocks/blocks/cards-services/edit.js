(function (wp) {
  const { registerBlockType } = wp.blocks;
  const { useBlockProps, RichText, BlockControls } = wp.blockEditor;
  const {
    ToolbarGroup,
    ToolbarButton,
    Button,
    TextControl,
    TextareaControl,
    SelectControl,
  } = wp.components;
  const { Fragment, createElement: el } = wp.element;

  registerBlockType("syp/cards-list", {
    edit({ attributes, setAttributes }) {
      const { heading = "", cards = [] } = attributes;
      const blockProps = useBlockProps({ className: "syp-cards editor" });

      // helpers cards
      const addCard = () =>
        setAttributes({
          cards: [...cards, { title: "", variant: "light", items: [] }],
        });
      const updateCard = (i, key, val) =>
        setAttributes({
          cards: cards.map((c, idx) => (idx === i ? { ...c, [key]: val } : c)),
        });
      const removeCard = (i) =>
        setAttributes({ cards: cards.filter((_, idx) => idx !== i) });
      const moveCard = (from, to) => {
        if (to < 0 || to >= cards.length) return;
        const next = [...cards];
        const [m] = next.splice(from, 1);
        next.splice(to, 0, m);
        setAttributes({ cards: next });
      };

      // helpers items
      const addItem = (ci) =>
        updateCard(ci, "items", [...(cards[ci].items || []), { text: "" }]);
      const updateItem = (ci, ii, val) =>
        updateCard(
          ci,
          "items",
          cards[ci].items.map((it, j) => (j === ii ? { text: val } : it))
        );
      const removeItem = (ci, ii) =>
        updateCard(
          ci,
          "items",
          cards[ci].items.filter((_, j) => j !== ii)
        );
      const moveItem = (ci, from, to) => {
        const list = [...(cards[ci].items || [])];
        if (to < 0 || to >= list.length) return;
        const [m] = list.splice(from, 1);
        list.splice(to, 0, m);
        updateCard(ci, "items", list);
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
              label: "Añadir card",
              onClick: addCard,
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
              className: "syp-cards__title",
              placeholder:
                "Offering Quality Services with Unmatched Marketing Strategies",
              value: heading,
              onChange: (v) => setAttributes({ heading: v }),
              allowedFormats: [],
            }),

            el(
              "div",
              { className: "syp-cards__grid" },
              cards.map((card, i) =>
                el(
                  "div",
                  {
                    key: i,
                    className: `syp-card syp-card--${card.variant || "light"}`,
                  },

                  el(
                    "div",
                    { className: "syp-card__head" },
                    el(TextControl, {
                      className: "syp-card__title-input",
                      placeholder: "Card title…",
                      value: card.title,
                      onChange: (v) => updateCard(i, "title", v),
                    }),
                    el(SelectControl, {
                      className: "syp-card__variant",
                      label: "Estilo",
                      value: card.variant || "light",
                      options: [
                        { label: "Light Green", value: "light" },
                        { label: "Dark Green", value: "solid" },
                      ],
                      onChange: (v) => updateCard(i, "variant", v),
                    })
                  ),

                  el(
                    "div",
                    { className: "syp-card__list" },
                    (card.items || []).map((it, j) =>
                      el(
                        "div",
                        { key: j, className: "syp-card__item" },
                        el(
                          "span",
                          {
                            className: "syp-card__bullet",
                            "aria-hidden": true,
                          },
                          "•"
                        ),
                        el(TextareaControl, {
                          className: "syp-card__text-input",
                          placeholder: "Item text…",
                          value: it.text,
                          onChange: (v) => updateItem(i, j, v),
                        }),
                        el(
                          "div",
                          { className: "syp-card__item-actions" },
                          el(
                            Button,
                            {
                              variant: "tertiary",
                              onClick: () => moveItem(i, j, j - 1),
                            },
                            "↑"
                          ),
                          el(
                            Button,
                            {
                              variant: "tertiary",
                              onClick: () => moveItem(i, j, j + 1),
                            },
                            "↓"
                          ),
                          el(
                            Button,
                            {
                              variant: "secondary",
                              isDestructive: true,
                              onClick: () => removeItem(i, j),
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
                        variant: "secondary",
                        onClick: () => addItem(i),
                      },
                      "+ Añadir ítem"
                    )
                  ),

                  el(
                    "div",
                    { className: "syp-card__foot" },
                    el(
                      Button,
                      {
                        variant: "tertiary",
                        onClick: () => moveCard(i, i - 1),
                      },
                      "←"
                    ),
                    el(
                      Button,
                      {
                        variant: "tertiary",
                        onClick: () => moveCard(i, i + 1),
                      },
                      "→"
                    ),
                    el(
                      Button,
                      {
                        variant: "secondary",
                        isDestructive: true,
                        onClick: () => removeCard(i),
                      },
                      "Eliminar card"
                    )
                  )
                )
              ),
              el(
                Button,
                {
                  className: "syp-add syp-add--card",
                  variant: "primary",
                  onClick: addCard,
                },
                "+ Añadir card"
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
