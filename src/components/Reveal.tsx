import { useEffect, useLayoutEffect, useRef, type ReactNode } from "react";
import { cn } from "../lib/utils";

type RevealProps = {
  children: ReactNode;
  className?: string;
  delayMs?: number;
  /** Above-fold content — visible immediately (critical for LCP). */
  priority?: boolean;
};

/** Fade-up on scroll — Absa-style entrance without changing layout or brand colors. */
export function Reveal({ children, className, delayMs = 0, priority = false }: RevealProps) {
  const ref = useRef<HTMLDivElement>(null);

  useLayoutEffect(() => {
    if (priority) return;
    const el = ref.current;
    if (!el) return;

    if (window.matchMedia("(prefers-reduced-motion: reduce)").matches) {
      el.classList.add("is-visible");
      return;
    }

    // Paint immediately if already in the viewport (avoids delaying LCP).
    const rect = el.getBoundingClientRect();
    if (rect.top < window.innerHeight * 0.92 && rect.bottom > 0) {
      el.classList.add("is-visible");
      return;
    }
  }, [priority]);

  useEffect(() => {
    if (priority) return;
    const el = ref.current;
    if (!el || el.classList.contains("is-visible")) return;

    const observer = new IntersectionObserver(
      ([entry]) => {
        if (entry?.isIntersecting) {
          el.classList.add("is-visible");
          observer.unobserve(el);
        }
      },
      { threshold: 0.08, rootMargin: "0px 0px -4% 0px" },
    );

    observer.observe(el);
    return () => observer.disconnect();
  }, [priority]);

  return (
    <div
      ref={ref}
      className={cn("reveal", priority && "is-visible", className)}
      style={delayMs ? { transitionDelay: `${delayMs}ms` } : undefined}
    >
      {children}
    </div>
  );
}
