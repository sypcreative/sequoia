// Swiper timeline: centra SIEMPRE cada slide en viewport
(function () {
  function init(instance) {
    const swiperEl = instance.querySelector(".syp-timeline__swiper");
    if (!swiperEl || typeof Swiper === "undefined") return null;

    const prev = instance.querySelector(".syp-timeline__nav--prev");
    const next = instance.querySelector(".syp-timeline__nav--next");

    const swiper = new Swiper(swiperEl, {
      slidesPerView: "auto",
      centeredSlides: true,
      centeredSlidesBounds: true,
      spaceBetween: 48,
      // mousewheel: { forceToAxis: true },
      // keyboard: { enabled: true },
      // grabCursor: true,
      watchSlidesProgress: true,
      navigation: { prevEl: prev, nextEl: next },
      breakpoints: {
        0: { spaceBetween: 24 },
        640: { spaceBetween: 32 },
        1024: { spaceBetween: 48 },
      },
    });

    // offsets = (ancho visible del carrusel - ancho del slide)/2
    function applyOffsets() {
      // Como el carrusel mide 100vw, Swiper ya conoce su ancho exacto:
      const viewport = swiper.width; // ancho visible en px
      const first = swiper.slides[0];
      const last = swiper.slides[swiper.slides.length - 1];
      const firstW = first ? first.getBoundingClientRect().width : 500;
      const lastW = last ? last.getBoundingClientRect().width : 500;

      swiper.params.slidesOffsetBefore = Math.max(0, (viewport - firstW) / 2);
      swiper.params.slidesOffsetAfter = Math.max(0, (viewport - lastW) / 2);
      swiper.update();
    }

    applyOffsets();
    swiper.on("resize", applyOffsets);
    swiper.on("breakpoint", applyOffsets);
    swiper.on("slideChange", () => requestAnimationFrame(applyOffsets));

    // En extremos, fuerza el índice exacto por si hay desviaciones
    swiper.on("reachBeginning", () => swiper.slideTo(0, 300));
    swiper.on("reachEnd", () => swiper.slideTo(swiper.slides.length - 1, 300));

    return swiper;
  }

  function initAll() {
    document.querySelectorAll("[data-timeline]").forEach(init);
  }

  // Compat por si lo llamas desde otro script
  window.initTimelineSlider = (sel = "[data-timeline]") =>
    Array.from(document.querySelectorAll(sel)).map(init).filter(Boolean);

  if (document.readyState === "loading")
    document.addEventListener("DOMContentLoaded", initAll);
  else initAll();
})();
