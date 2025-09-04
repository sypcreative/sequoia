// tiltCards.js
// Uso: import { initCardsTilt } from './tiltCards';
//      initCardsTilt({ selector: '.syp-card', gsap });  // si no pasas gsap, usa window.gsap

export function initCardsTilt({
  selector = ".syp-card",
  gsap: GSAP = typeof window !== "undefined" ? window.gsap : undefined,
  maxRX = 2, // rotación X máxima (grados)
  maxRY = 2, // rotación Y máxima (grados)
  zLift = 8, // elevación Z en hover (px)
  perspective = 500, // perspectiva del contenedor (px)
  respectReduceMotion = true,
  disableOnCoarsePointer = true,
} = {}) {
  if (!GSAP) {
    console.warn(
      "[initCardsTilt] GSAP no está disponible. Pásalo en options.gsap o carga window.gsap."
    );
    return { destroy() {} };
  }

  // Accesibilidad / rendimiento
  if (
    respectReduceMotion &&
    matchMedia?.("(prefers-reduced-motion: reduce)").matches
  ) {
    return { destroy() {} };
  }
  if (disableOnCoarsePointer && matchMedia?.("(pointer: coarse)").matches) {
    return { destroy() {} };
  }

  const cards = Array.from(document.querySelectorAll(selector));
  if (!cards.length) return { destroy() {} };

  const cleanups = [];

  cards.forEach((card) => {
    // Asegura perspectiva en el contenedor más cercano (la col de Bootstrap suele ir bien)
    const holder =
      card.closest('[class*="col-"]') || card.parentElement || card;
    const prevPerspective = holder.style.perspective;
    if (!holder.style.perspective)
      holder.style.perspective = `${perspective}px`;

    GSAP.set(card, { transformStyle: "preserve-3d", willChange: "transform" });

    // Un pelín de profundidad en hijos (opcional)
    const title = card.querySelector(".syp-card__title");
    const texts = card.querySelectorAll(".syp-card__text");
    if (title) GSAP.set(title, { z: 30 });
    texts.forEach((t) => GSAP.set(t, { z: 12 }));

    const rX = GSAP.quickTo(card, "rotationX", {
      duration: 0.3,
      ease: "power2",
    });
    const rY = GSAP.quickTo(card, "rotationY", {
      duration: 0.3,
      ease: "power2",
    });
    const z = GSAP.quickTo(card, "z", { duration: 0.3, ease: "power2" });

    function onEnter() {
      z(zLift);
      card.style.transition = "box-shadow .2s ease";
      card.style.boxShadow = "0 12px 30px rgba(0,0,0,.05)";
    }

    function onMove(e) {
      const rect = card.getBoundingClientRect();
      const x = e.clientX - rect.left;
      const y = e.clientY - rect.top;

      const rx = GSAP.utils.mapRange(0, rect.height, maxRX, -maxRX, y);
      const ry = GSAP.utils.mapRange(0, rect.width, -maxRY, maxRY, x);

      rX(rx);
      rY(ry);
    }

    function onLeave() {
      z(0);
      rX(0);
      rY(0);
      card.style.boxShadow = "";
    }

    card.addEventListener("pointerenter", onEnter);
    card.addEventListener("pointermove", onMove);
    card.addEventListener("pointerleave", onLeave);

    cleanups.push(() => {
      card.removeEventListener("pointerenter", onEnter);
      card.removeEventListener("pointermove", onMove);
      card.removeEventListener("pointerleave", onLeave);
    });

    // cleanup de perspectiva
    cleanups.push(() => {
      holder.style.perspective = prevPerspective;
    });
  });

  return {
    destroy() {
      cleanups.forEach((fn) => fn());
    },
  };
}
