import { Link, useRouterState } from "@tanstack/react-router";
import { useEffect, useRef, useState } from "react";
import { Menu, X, Mail, ChevronDown } from "lucide-react";
import { Logo } from "./Logo";
import { LanguageSwitcher } from "./LanguageSwitcher";
import { CONTACT } from "../lib/contact";
import { useI18n } from "../i18n";
import { getPractices } from "../lib/what-we-do";

export function SiteHeader() {
  const { t } = useI18n();
  const practices = getPractices(t);
  const [open, setOpen] = useState(false);
  const [whatOpen, setWhatOpen] = useState(false);
  const dropdownRef = useRef<HTMLDivElement>(null);
  const pathname = useRouterState({ select: (s) => s.location.pathname });
  const whatActive = pathname.startsWith("/what-we-do");

  const nav = [
    { to: "/", label: t.nav.home, exact: true },
    { to: "/about", label: t.nav.about },
    {
      to: "/what-we-do",
      label: t.nav.whatWeDo,
      children: practices.map((s) => ({ to: s.to, label: s.label })),
    },
    { to: "/insights", label: t.nav.news },
    { to: "/careers", label: t.nav.careers },
    { to: "/contact", label: t.nav.contact },
  ] as const;

  useEffect(() => {
    function onDocClick(e: MouseEvent) {
      if (!dropdownRef.current?.contains(e.target as Node)) setWhatOpen(false);
    }
    document.addEventListener("mousedown", onDocClick);
    return () => document.removeEventListener("mousedown", onDocClick);
  }, []);

  useEffect(() => {
    setOpen(false);
    setWhatOpen(false);
  }, [pathname]);

  return (
    <header className="hairline-b sticky top-0 z-40 bg-background/90 backdrop-blur">
      <div className="container-editorial flex h-40 items-center justify-between gap-6">
        <Link to="/" className="shrink-0">
          <Logo />
        </Link>

        <nav className="hidden items-center gap-7 xl:flex">
          {nav.map((item) =>
            "children" in item && item.children ? (
              <div key={item.to} className="relative" ref={dropdownRef}>
                <button
                  type="button"
                  onClick={() => setWhatOpen((v) => !v)}
                  className={`inline-flex items-center gap-1 text-[12px] font-medium uppercase tracking-wider transition-colors hover:text-navy ${whatActive ? "text-navy" : "text-foreground/80"}`}
                  aria-expanded={whatOpen}
                >
                  {item.label}
                  <ChevronDown className={`h-3.5 w-3.5 transition ${whatOpen ? "rotate-180" : ""}`} />
                </button>
                {whatOpen && (
                  <div className="absolute left-0 top-full z-50 mt-3 min-w-[17rem] border border-hairline bg-background py-2 shadow-sm">
                    <Link
                      to={item.to}
                      className="block px-4 py-2.5 text-[12px] font-medium text-foreground/80 transition hover:bg-panel hover:text-navy"
                      onClick={() => setWhatOpen(false)}
                    >
                      {t.nav.overview}
                    </Link>
                    {item.children.map((child) => (
                      <Link
                        key={child.to}
                        to={child.to}
                        className="block px-4 py-2.5 text-[12px] font-medium text-foreground/80 transition hover:bg-panel hover:text-navy"
                        activeProps={{ className: "bg-panel text-navy" }}
                        onClick={() => setWhatOpen(false)}
                      >
                        {child.label}
                      </Link>
                    ))}
                  </div>
                )}
              </div>
            ) : (
              <Link
                key={item.to}
                to={item.to}
                className="text-[12px] font-medium uppercase tracking-wider text-foreground/80 transition-colors hover:text-navy"
                activeProps={{ className: "text-navy" }}
                activeOptions={{ exact: "exact" in item ? Boolean(item.exact) : false }}
              >
                {item.label}
              </Link>
            ),
          )}
        </nav>

        <div className="hidden items-center gap-5 xl:flex">
          <LanguageSwitcher />
          <a href={`mailto:${CONTACT.email}`} className="group flex items-center gap-3">
            <span className="flex h-10 w-10 items-center justify-center rounded-full border border-navy/20 text-navy">
              <Mail className="h-4 w-4" />
            </span>
            <span className="leading-tight">
              <span className="block text-[10px] font-medium uppercase tracking-widest text-slate-warm">{t.nav.emailUs}</span>
              <span className="block text-[11px] font-medium uppercase tracking-wide text-navy group-hover:underline">
                {CONTACT.email}
              </span>
            </span>
          </a>
        </div>

        <div className="flex items-center gap-3 xl:hidden">
          <LanguageSwitcher />
          <button
            aria-label={t.nav.menu}
            onClick={() => setOpen((v) => !v)}
          >
            {open ? <X className="h-6 w-6" /> : <Menu className="h-6 w-6" />}
          </button>
        </div>
      </div>

      {open && (
        <div className="hairline-t xl:hidden">
          <div className="container-editorial flex flex-col py-4">
            {nav.map((item) =>
              "children" in item && item.children ? (
                <div key={item.to} className="border-b border-hairline py-3">
                  <Link
                    to={item.to}
                    onClick={() => setOpen(false)}
                    className="text-sm font-medium uppercase tracking-wider text-navy"
                  >
                    {item.label}
                  </Link>
                  <div className="mt-2 space-y-2 pl-3">
                    {item.children.map((child) => (
                      <Link
                        key={child.to}
                        to={child.to}
                        onClick={() => setOpen(false)}
                        className="block text-sm text-foreground/75"
                      >
                        {child.label}
                      </Link>
                    ))}
                  </div>
                </div>
              ) : (
                <Link
                  key={item.to}
                  to={item.to}
                  onClick={() => setOpen(false)}
                  className="border-b border-hairline py-3 text-sm font-medium uppercase tracking-wider text-foreground/80"
                  activeProps={{ className: "text-navy" }}
                >
                  {item.label}
                </Link>
              ),
            )}
            <div className="mt-4 space-y-3 text-sm">
              <a href={`mailto:${CONTACT.email}`} className="flex items-center gap-2 text-navy">
                <Mail className="h-4 w-4" /> {CONTACT.email}
              </a>
            </div>
          </div>
        </div>
      )}
    </header>
  );
}
