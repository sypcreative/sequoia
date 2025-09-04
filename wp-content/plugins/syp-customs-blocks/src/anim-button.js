import gsap from "gsap";

export function initBtnSwap(root = document) {
  root.querySelectorAll(".btn-wrapper").forEach((wrap) => {
    const main = wrap.querySelector("a.btn"); // botón de texto
    const arrow = wrap.querySelector(".btn-arrow"); // cuadrado con flecha
    if (!main || !arrow) return;

    // Prepara una TL reversible
    let tl;

    const build = () => {
      // Medidas y posiciones actuales en pantalla
      const mRect = main.getBoundingClientRect();
      const aRect = arrow.getBoundingClientRect();

      // Distancias necesarias para "cruzarse" y quedar en orden inverso
      const moveMainRight = arrow.offsetWidth; // texto → derecha
      const moveArrowLeft = main.offsetWidth - 0.5; // flecha → izquierda
      console.log("moveArrowLeft", moveArrowLeft);
      // TL: ambas animaciones en paralelo
      tl = gsap.timeline({
        paused: true,
        defaults: { duration: 0.35, ease: "power3.out" },
      });
      tl.to(main, { x: moveMainRight }, 0);
      tl.to(arrow, { x: -moveArrowLeft }, 0);
    };

    // (Re)construye la TL cuando haga falta (primer hover o si cambian tamaños)
    const ensure = () => {
      if (!tl) build();
    };

    // Hover sobre TODO el wrapper (si prefieres solo el <a>, cambia el listener)
    wrap.addEventListener("mouseenter", () => {
      ensure();
      tl.play();
    });
    wrap.addEventListener("mouseleave", () => {
      if (tl) tl.reverse();
    });

    // Por si cambian medidas (responsive, fuentes), reseteamos la TL
    window.addEventListener("resize", () => {
      tl = null;
      gsap.set([main, arrow], { x: 0 });
    });
  });
}
