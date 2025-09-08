// anim-text.js
import gsap from "gsap";
import SplitType from "split-type";

// Split de líneas + máscara por línea
function splitLinesWithMasks(el) {
  //   document
  //     .getElementsByClassName(".navbar-toggle")
  //     .onClick(console.log("holaa"));
  if (el.__split) el.__split.revert();
  const split = new SplitType(el, { types: "lines" });
  el.__split = split;

  split.lines.forEach((line) => {
    const mask = document.createElement("span");
    mask.className = "line-mask";
    mask.style.display = "block";
    mask.style.overflow = "hidden";
    while (line.firstChild) mask.appendChild(line.firstChild);
    line.appendChild(mask);
  });

  return el.querySelectorAll(".line-mask");
}

// Animación “single”
function animateLines(
  el,
  { duration = 0.6, ease = "power3.out", lineDelay = 0.3 } = {}
) {
  const masks = splitLinesWithMasks(el);
  gsap.fromTo(
    masks,
    { yPercent: 100, opacity: 0.2 },
    { yPercent: 0, opacity: 1, duration, ease, stagger: lineDelay }
  );
}

// Grupo de párrafos con SOLAPE entre ellos
function animateParagraphGroup(
  container,
  {
    duration = 0.6,
    ease = "power3.out",
    lineDelay = 0.3, // retraso entre líneas dentro de cada párrafo
    paragraphOverlap = 0.25, // segundos que se solapa el siguiente párrafo antes de que acabe el anterior
  } = {}
) {
  const paragraphs = Array.from(container.querySelectorAll("p, h1"));
  if (!paragraphs.length) return null;

  const masksByParagraph = paragraphs.map((p) => splitLinesWithMasks(p));
  const tl = gsap.timeline();

  masksByParagraph.forEach((masks, i) => {
    const position = i === 0 ? 0 : `>-=${paragraphOverlap}`;
    tl.fromTo(
      masks,
      { yPercent: 100, opacity: 0.2 },
      { yPercent: 0, opacity: 1, duration, ease, stagger: lineDelay },
      position
    );
  });

  return tl;
}

export function initTextAnimations(root = document) {
  // Elementos sueltos
  const singles = root.querySelectorAll(
    '[data-anim="text-animated"]:not([data-anim-ready])'
  );
  singles.forEach((el) => {
    el.dataset.animReady = "true";
    const io = new IntersectionObserver(
      (entries, obs) => {
        entries.forEach((entry) => {
          if (entry.isIntersecting && !el.dataset.animDone) {
            animateLines(el);
            el.dataset.animDone = "true";
            obs.unobserve(el);
          }
        });
      },
      { threshold: 0.2 }
    );
    io.observe(el);
  });

  // Grupos de párrafos encadenados con solape
  const groups = root.querySelectorAll(
    '[data-anim="text-animated-group"]:not([data-anim-ready])'
  );
  groups.forEach((box) => {
    box.dataset.animReady = "true";
    const io = new IntersectionObserver(
      (entries, obs) => {
        entries.forEach((entry) => {
          if (entry.isIntersecting && !box.dataset.animDone) {
            animateParagraphGroup(box, {
              // ajusta estos si quieres:
              // duration: 0.6,
              // lineDelay: 0.28,
              // paragraphOverlap: 0.25,
            });
            box.dataset.animDone = "true";
            obs.unobserve(box);
          }
        });
      },
      { threshold: 0.1 }
    );
    io.observe(box);
  });
}

export function observeAndInit() {
  initTextAnimations(document);
}
