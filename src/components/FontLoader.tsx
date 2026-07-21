import { useEffect } from "react";
import { useI18n } from "../i18n";

const CRITICAL_FONTS =
  "https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@500;600;700&family=Inter:wght@400;500;600&display=swap";

const ETHIOPIC_FONTS =
  "https://fonts.googleapis.com/css2?family=Noto+Sans+Ethiopic:wght@400;500;600;700&display=swap";

const ETHIOPIC_LOCALES = new Set(["am", "ti"]);

function injectStylesheet(href: string, id: string) {
  if (document.getElementById(id)) return;
  const link = document.createElement("link");
  link.id = id;
  link.rel = "stylesheet";
  link.href = href;
  link.media = "print";
  link.onload = () => {
    link.media = "all";
  };
  document.head.appendChild(link);
  window.setTimeout(() => {
    if (link.media !== "all") link.media = "all";
  }, 2000);
}

/**
 * Loads fonts after first paint so FCP/LCP are not blocked by webfonts.
 * Ethiopic face is loaded only when Amharic/Tigrinya is active.
 */
export function FontLoader() {
  const { locale } = useI18n();

  useEffect(() => {
    let cancelled = false;
    const load = () => {
      if (cancelled) return;
      injectStylesheet(CRITICAL_FONTS, "blufin-fonts-critical");
    };

    const idleId =
      "requestIdleCallback" in window
        ? window.requestIdleCallback(load, { timeout: 2500 })
        : undefined;
    const timeoutId = window.setTimeout(load, 1200);

    return () => {
      cancelled = true;
      window.clearTimeout(timeoutId);
      if (idleId !== undefined && "cancelIdleCallback" in window) {
        window.cancelIdleCallback(idleId);
      }
    };
  }, []);

  useEffect(() => {
    if (ETHIOPIC_LOCALES.has(locale)) {
      injectStylesheet(ETHIOPIC_FONTS, "blufin-fonts-ethiopic");
    }
  }, [locale]);

  return null;
}
