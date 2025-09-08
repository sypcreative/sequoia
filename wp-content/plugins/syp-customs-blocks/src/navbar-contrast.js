// navbar-contrast.js
(function () {
  const header = document.getElementById("siteHeader");
  if (!header) return;

  const collapse = document.getElementById("navbarNav");
  let ticking = false;

  function applyState(scrolled) {
    header.classList.toggle("is-solid", scrolled);
    header.toggleAttribute("data-scrolled", scrolled);
  }

  function update() {
    const scrolled = window.scrollY > 8;
    applyState(scrolled);
  }

  function onScroll() {
    if (ticking) return;
    ticking = true;
    requestAnimationFrame(() => {
      update();
      ticking = false;
    });
  }

  // Scroll + carga + resize
  window.addEventListener("scroll", onScroll, { passive: true });
  window.addEventListener("load", update);
  window.addEventListener("resize", update);

  // Si usas Bootstrap 5: solid cuando se abre el menú colapsable
  if (collapse) {
    collapse.addEventListener("show.bs.collapse", () => applyState(true));
    collapse.addEventListener("hidden.bs.collapse", update);
  }
})();
