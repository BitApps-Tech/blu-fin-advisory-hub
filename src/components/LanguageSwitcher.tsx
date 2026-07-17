import { useEffect, useRef, useState } from "react";
import { ChevronDown, Languages } from "lucide-react";
import { useI18n } from "../i18n";

export function LanguageSwitcher({ className = "" }: { className?: string }) {
  const { locale, setLocale, locales } = useI18n();
  const [open, setOpen] = useState(false);
  const ref = useRef<HTMLDivElement>(null);
  const current = locales.find((l) => l.code === locale) ?? locales[0];

  useEffect(() => {
    function onDocClick(e: MouseEvent) {
      if (!ref.current?.contains(e.target as Node)) setOpen(false);
    }
    document.addEventListener("mousedown", onDocClick);
    return () => document.removeEventListener("mousedown", onDocClick);
  }, []);

  return (
    <div className={`relative ${className}`} ref={ref}>
      <button
        type="button"
        onClick={() => setOpen((v) => !v)}
        className="inline-flex items-center gap-1.5 text-[11px] font-medium uppercase tracking-wider text-foreground/80 transition hover:text-navy"
        aria-expanded={open}
        aria-label="Language"
      >
        <Languages className="h-3.5 w-3.5" />
        <span>{current.native}</span>
        <ChevronDown className={`h-3 w-3 transition ${open ? "rotate-180" : ""}`} />
      </button>
      {open && (
        <div className="absolute right-0 top-full z-50 mt-2 min-w-[10rem] border border-hairline bg-background py-1 shadow-sm">
          {locales.map((l) => (
            <button
              key={l.code}
              type="button"
              onClick={() => {
                setLocale(l.code);
                setOpen(false);
              }}
              className={`block w-full px-3 py-2 text-left text-[12px] transition hover:bg-panel ${
                l.code === locale ? "font-medium text-navy" : "text-foreground/80"
              }`}
            >
              {l.native}
              <span className="ml-2 text-[10px] uppercase tracking-wider text-slate-warm">{l.label}</span>
            </button>
          ))}
        </div>
      )}
    </div>
  );
}
