// lenis.js
import Lenis from '@studio-freight/lenis';

const lenis = new Lenis({
  lerp: 0.1,
  smooth: true,
});

function raf(time) {
  lenis.raf(time);
  requestAnimationFrame(raf);
}

requestAnimationFrame(raf);

export default lenis;
