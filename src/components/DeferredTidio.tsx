import { useEffect } from "react";

const TIDIO_SRC = "https://code.tidio.co/vxue7ykeev7yoeznacaxqponmi6cdh9f.js";
const SCRIPT_ID = "tidio-chat-script";

/** Load Tidio after the page is interactive so it does not compete with LCP. */
export function DeferredTidio() {
  useEffect(() => {
    if (typeof window === "undefined") return;
    if (document.getElementById(SCRIPT_ID)) return;

    let loaded = false;
    const load = () => {
      if (loaded) return;
      loaded = true;
      cleanup();
      const s = document.createElement("script");
      s.id = SCRIPT_ID;
      s.src = TIDIO_SRC;
      s.async = true;
      document.body.appendChild(s);
    };

    const onInteract = () => load();
    const cleanup = () => {
      window.removeEventListener("pointerdown", onInteract);
      window.removeEventListener("keydown", onInteract);
      window.removeEventListener("scroll", onInteract);
    };

    window.addEventListener("pointerdown", onInteract, { once: true, passive: true });
    window.addEventListener("keydown", onInteract, { once: true });
    window.addEventListener("scroll", onInteract, { once: true, passive: true });

    const idleId =
      "requestIdleCallback" in window
        ? window.requestIdleCallback(() => load(), { timeout: 5000 })
        : undefined;
    const timeoutId = window.setTimeout(load, 4500);

    return () => {
      cleanup();
      window.clearTimeout(timeoutId);
      if (idleId !== undefined && "cancelIdleCallback" in window) {
        window.cancelIdleCallback(idleId);
      }
    };
  }, []);

  return null;
}
