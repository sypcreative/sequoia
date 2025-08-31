import barba from "@barba/core";
import gsap from "gsap";
import lenis from "./initLenis.js";

/* ---------------------------------------------
   Funciones reutilizables
--------------------------------------------- */
function runSharedScripts() {
  initFooterResize();
  animateLinesText();
  initDraggableImages();
  initQuoteImagesAnimation();
  initAnimatedBanner();
  initMagneticBadges();
  // initProductQuantityControls();

  const dragArea = document.querySelector(".content-drag-area");
  const spacer = document.querySelector(".block-ventajas__spacer");
  if (dragArea && spacer) {
    const updateHeight = () => {
      spacer.style.height = `${dragArea.offsetHeight}px`;
    };
    updateHeight();
    window.addEventListener("resize", updateHeight);
  }
}

/* ---------------------------------------------
   Loader inicial
--------------------------------------------- */
function isFirstVisitFromOutside() {
  const referrer = document.referrer;
  const sameOrigin = referrer.includes(window.location.hostname);
  const hasVisited = sessionStorage.getItem("hasVisited");
  return !hasVisited && (!referrer || !sameOrigin);
}

/* ---------------------------------------------
   Barba.js Transiciones
--------------------------------------------- */
function initBarba() {
  const overlay = document.querySelector(".barba-transition-overlay");

  barba.init({
    transitions: [
      {
        name: "curtain",
        sync: true,

        leave() {
          return gsap.to(overlay, {
            y: "0%",
            duration: 0.7,
            ease: "power2.inOut",
          });
        },

        enter({ next }) {
          gsap.set(next.container, { opacity: 0 });
          return gsap.to(next.container, { opacity: 1, duration: 0.2 });
        },

        afterEnter() {
          reinitWooAddToCart();
          initMiniCartDrawer();
          gsap.to(overlay, {
            y: "-100%",
            duration: 0.7,
            ease: "power2.inOut",
          });
        },
      },
    ],
  });

  barba.hooks.afterEnter(() => {
    lenis.scrollTo(0, { immediate: true });

    jQuery("body").trigger("wc_init");
    document.body.dispatchEvent(new Event("wc-init"));

    reinitWooAddToCart();
    initMiniCartDrawer();

    gsap.to(overlay, {
      y: "-100%",
      duration: 0.7,
      ease: "power2.inOut",
    });
  });
  barba.hooks.after(() => {
    runSharedScripts();
  });
}

/* ---------------------------------------------
   Iniciar todo
--------------------------------------------- */
document.addEventListener("DOMContentLoaded", () => {
  initBarba();
});
