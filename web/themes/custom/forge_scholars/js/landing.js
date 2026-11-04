(function (Drupal, once) {
  Drupal.behaviors.forgeNav = {
    attach(context) {
      once("forge-nav", "[data-forge-nav]", context).forEach((nav) => {
        const toggle = nav.querySelector("[data-nav-toggle]");
        const panel = nav.querySelector("[data-nav-panel]");
        if (!toggle || !panel) return;
        toggle.addEventListener("click", () => {
          const open = panel.classList.toggle("is-open");
          toggle.setAttribute("aria-expanded", open ? "true" : "false");
          const label = toggle.querySelector(".fs-sr");
          if (label) label.textContent = open ? "Close menu" : "Open menu";
        });
        panel.querySelectorAll("a").forEach((link) => {
          link.addEventListener("click", () => {
            panel.classList.remove("is-open");
            toggle.setAttribute("aria-expanded", "false");
          });
        });
      });
    },
  };

  Drupal.behaviors.forgeTracks = {
    attach(context) {
      once("forge-tracks", "[data-forge-tracks]", context).forEach((root) => {
        const slides = Array.from(root.querySelectorAll("[data-slide]"));
        const dots = Array.from(root.querySelectorAll("[data-dot]"));
        const title = root.querySelector("[data-track-title]");
        const amount = root.querySelector("[data-track-amount]");
        const summary = root.querySelector("[data-track-summary]");
        const count = root.querySelector("[data-track-count]");
        let index = 0;
        let timer = 0;
        const reduce = window.matchMedia("(prefers-reduced-motion: reduce)").matches;

        const show = (next) => {
          index = (next + slides.length) % slides.length;
          slides.forEach((slide, i) => {
            const on = i === index;
            slide.classList.toggle("is-active", on);
            slide.setAttribute("aria-hidden", on ? "false" : "true");
          });
          dots.forEach((dot, i) => {
            if (i === index) {
              dot.setAttribute("aria-current", "true");
            }
            else {
              dot.removeAttribute("aria-current");
            }
          });
          const slide = slides[index];
          if (title) title.textContent = slide.dataset.name || "";
          if (amount) amount.textContent = slide.dataset.award || "";
          if (summary) summary.textContent = slide.dataset.summary || "";
          if (count) count.textContent = "0" + (index + 1) + " / 0" + slides.length;
        };

        const play = () => {
          window.clearInterval(timer);
          if (reduce) return;
          timer = window.setInterval(() => show(index + 1), 7000);
        };

        root.querySelectorAll("[data-prev]").forEach((button) => {
          button.addEventListener("click", () => {
            show(index - 1);
            play();
          });
        });
        root.querySelectorAll("[data-next]").forEach((button) => {
          button.addEventListener("click", () => {
            show(index + 1);
            play();
          });
        });
        dots.forEach((dot) => {
          dot.addEventListener("click", () => {
            show(Number(dot.dataset.dot));
            play();
          });
        });
        root.addEventListener("mouseenter", () => window.clearInterval(timer));
        root.addEventListener("mouseleave", play);
        root.addEventListener("focusin", () => window.clearInterval(timer));
        root.addEventListener("focusout", play);
        root.addEventListener("keydown", (event) => {
          if (event.key === "ArrowRight") {
            event.preventDefault();
            show(index + 1);
            play();
          }
          if (event.key === "ArrowLeft") {
            event.preventDefault();
            show(index - 1);
            play();
          }
        });
        show(0);
        play();
      });
    },
  };
})(Drupal, once);
