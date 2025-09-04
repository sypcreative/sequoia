// Inicializador de Timeline (exportable)
window.initTimelineSlider = function (selector = "[data-timeline]") {
  if (typeof Swiper === "undefined") {
    console.warn("Swiper no está cargado");
    return;
  }

  document.querySelectorAll(selector).forEach((instance) => {
    const swiperEl = instance.querySelector(".syp-timeline__swiper");
    if (!swiperEl) return;

    const swiper = new Swiper(swiperEl, {
      slidesPerView: "auto",
      spaceBetween: 48,
      freeMode: false,
      mousewheel: { forceToAxis: true },
      keyboard: { enabled: true },
      grabCursor: true,
      navigation: {
        prevEl: instance.querySelector(".syp-timeline__nav--prev"),
        nextEl: instance.querySelector(".syp-timeline__nav--next"),
      },
      breakpoints: {
        0: { spaceBetween: 24 },
        640: { spaceBetween: 32 },
        1024: { spaceBetween: 48 },
      },
    });

    // Mantener la línea por debajo de los puntos
    const track = instance.querySelector(".syp-timeline__track");
    const updateTrackZ = () => {
      if (track) track.style.zIndex = 1;
    };
    swiper.on("resize", updateTrackZ);
    updateTrackZ();
  });
};
