import gsap from "gsap";
import { initBtnSwap } from "./anim-button";
import { observeAndInit } from "./anim-text";
import "./index.scss";
import "./swiper";
import { initCardsTilt } from "./tilt-cards";
import "./navbar-contrast";

function start() {
  observeAndInit(); // tus animaciones de texto
  initBtnSwap();
  const tilt = initCardsTilt({
    selector: ".syp-card",
    gsap, // ← descomenta si lo importas como módulo
  });
  window.initTimelineSlider();
}

if (!document.body.classList.contains("wp-admin")) {
  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", start);
  } else {
    start();
  }
}
