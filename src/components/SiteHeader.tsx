import { Link, useNavigate, useRouterState } from "@tanstack/react-router";
import { useEffect, useRef, useState } from "react";
import {
  Menu,
  X,
  Mail,
  ChevronDown,
  Search,
  Linkedin,
  Instagram,
  Youtube,
  Facebook,
} from "lucide-react";
import { Logo } from "./Logo";
import { LanguageSwitcher } from "./LanguageSwitcher";
import { CONTACT, SOCIAL } from "../lib/contact";
import { useI18n } from "../i18n";
import { getPractices } from "../lib/what-we-do";
import { cn } from "../lib/utils";

type NavChild = { to: string; label: string };
type NavItem = {
  to: string;
  label: string;
  exact?: boolean;
  children?: readonly NavChild[];
};

function XIcon({ className }: { className?: string }) {
  return (
    <svg viewBox="0 0 24 24" fill="currentColor" className={className} aria-hidden>
      <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-4.714-6.231-5.401 6.231H2.744l7.227-8.26L1.61 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z" />
    </svg>
  );
}

const UTILITY_SOCIAL = [
  { name: "LinkedIn" as const, Icon: Linkedin },
  { name: "YouTube" as const, Icon: Youtube },
  { name: "Facebook" as const, Icon: Facebook },
  { name: "Instagram" as const, Icon: Instagram },
  { name: "X" as const, Icon: XIcon },
];

export function SiteHeader() {
  const { t } = useI18n();
  const navigate = useNavigate();
  const practices = getPractices(t);
  const [open, setOpen] = useState(false);
  const [openMenu, setOpenMenu] = useState<string | null>(null);
  const [scrolled, setScrolled] = useState(false);
  const [searchOpen, setSearchOpen] = useState(false);
  const [query, setQuery] = useState("");
  const dropdownRefs = useRef<Record<string, HTMLDivElement | null>>({});
  const searchRef = useRef<HTMLDivElement>(null);
  const searchInputRef = useRef<HTMLInputElement>(null);
  const searchParams = useRouterState({ select: (s) => s.location.search });
  const pathname = useRouterState({ select: (s) => s.location.pathname });

  useEffect(() => {
    if (
      pathname === "/search" &&
      typeof searchParams === "object" &&
      searchParams &&
      "q" in searchParams
    ) {
      const q = (searchParams as { q?: string }).q;
      if (typeof q === "string") setQuery(q);
    }
  }, [pathname, searchParams]);

  const nav: NavItem[] = [
    { to: "/", label: t.nav.home, exact: true },
    {
      to: "/about",
      label: t.nav.about,
      children: [
        { to: "/about", label: t.about.companyProfileTab },
        { to: "/about/team", label: t.about.teamTab },
      ],
    },
    {
      to: "/what-we-do",
      label: t.nav.whatWeDo,
      children: practices.map((s) => ({ to: s.to, label: s.label })),
    },
    { to: "/insights", label: t.nav.news },
    { to: "/careers", label: t.nav.careers },
    { to: "/contact", label: t.nav.contact },
  ];

  useEffect(() => {
    function onScroll() {
      setScrolled(window.scrollY > 12);
    }
    onScroll();
    window.addEventListener("scroll", onScroll, { passive: true });
    return () => window.removeEventListener("scroll", onScroll);
  }, []);

  useEffect(() => {
    function onDocClick(e: MouseEvent) {
      const target = e.target as Node;
      const inside = Object.values(dropdownRefs.current).some((el) => el?.contains(target));
      if (!inside) setOpenMenu(null);
      if (searchRef.current && !searchRef.current.contains(target)) setSearchOpen(false);
    }
    document.addEventListener("mousedown", onDocClick);
    return () => document.removeEventListener("mousedown", onDocClick);
  }, []);

  useEffect(() => {
    setOpen(false);
    setOpenMenu(null);
    setSearchOpen(false);
  }, [pathname]);

  useEffect(() => {
    if (searchOpen) searchInputRef.current?.focus();
  }, [searchOpen]);

  function isItemActive(item: NavItem) {
    if (item.exact) return pathname === item.to;
    if (item.to === "/about") return pathname === "/about" || pathname.startsWith("/about/");
    return pathname === item.to || pathname.startsWith(`${item.to}/`);
  }

  function submitSearch(e: React.FormEvent) {
    e.preventDefault();
    const next = query.trim();
    setSearchOpen(false);
    void navigate({
      to: "/search",
      search: next ? { q: next } : {},
    });
  }

  return (
    <header
      className={cn(
        "sticky top-0 z-40 bg-background/90 backdrop-blur transition-shadow duration-300",
        scrolled && "shadow-[0_8px_24px_-18px_oklch(0.34_0.09_262_/_0.45)]",
      )}
    >
      {/* Thin utility bar */}
      <div className="bg-[#1F3E72] text-white">
        <div className="container-editorial flex h-9 items-center justify-between gap-4">
          <div className="flex min-w-0 items-center gap-2.5 text-[10px] font-semibold uppercase tracking-[0.14em] text-white/85">
            <Link to="/insights" className="shrink-0 transition-colors hover:text-white">
              {t.nav.mediaCenter}
            </Link>
            <span className="text-white/35" aria-hidden>
              |
            </span>
            <Link to="/contact" className="shrink-0 transition-colors hover:text-white">
              {t.nav.contact}
            </Link>
            <span className="text-white/35" aria-hidden>
              |
            </span>
            <a
              href={`mailto:${CONTACT.email}`}
              className="inline-flex min-w-0 items-center gap-1.5 transition-colors hover:text-white"
            >
              <Mail className="h-3 w-3 shrink-0" />
              <span className="truncate">
                <span className="sm:hidden">{t.nav.emailUs}</span>
                <span className="hidden sm:inline">{CONTACT.email}</span>
              </span>
            </a>
          </div>

          <div className="flex shrink-0 items-center gap-3">
            <div className="relative" ref={searchRef}>
              {searchOpen ? (
                <form onSubmit={submitSearch} className="flex items-center">
                  <input
                    ref={searchInputRef}
                    value={query}
                    onChange={(e) => setQuery(e.target.value)}
                    placeholder={t.nav.searchPlaceholder}
                    aria-label={t.nav.search}
                    className="h-7 w-36 border border-white/25 bg-white px-2 text-[11px] text-[#1F3E72] outline-none placeholder:text-[#1F3E72]/50 focus:border-white sm:w-48"
                  />
                  <button
                    type="submit"
                    aria-label={t.nav.search}
                    className="ml-1 flex h-7 w-7 items-center justify-center text-white/85 transition-colors hover:text-white"
                  >
                    <Search className="h-3.5 w-3.5" />
                  </button>
                </form>
              ) : (
                <button
                  type="button"
                  aria-label={t.nav.search}
                  onClick={() => setSearchOpen(true)}
                  className="flex h-7 w-7 items-center justify-center text-white/85 transition-colors hover:text-white"
                >
                  <Search className="h-3.5 w-3.5" />
                </button>
              )}
            </div>

            <div className="flex items-center gap-1.5">
              {UTILITY_SOCIAL.map(({ name, Icon }) => {
                const href = SOCIAL.find((s) => s.name === name)?.href ?? "#";
                return (
                  <a
                    key={name}
                    href={href}
                    target="_blank"
                    rel="noopener noreferrer"
                    aria-label={name}
                    className="flex h-7 w-7 items-center justify-center text-white/85 transition-colors hover:text-white"
                  >
                    <Icon className="h-3.5 w-3.5" />
                  </a>
                );
              })}
            </div>
          </div>
        </div>
      </div>

      <div className="hairline-b">
        <div
          className={cn(
            "container-editorial flex items-center justify-between gap-6 transition-[height] duration-300",
            scrolled ? "h-28" : "h-40",
          )}
        >
          <Link to="/" className="shrink-0 transition-opacity hover:opacity-80">
            <Logo
              className={cn(
                "transition-[height] duration-300",
                scrolled ? "h-16 md:h-20" : "h-24 md:h-32",
              )}
            />
          </Link>

          <nav className="hidden items-center gap-7 xl:flex">
            {nav.map((item) =>
              item.children ? (
                <div
                  key={item.to}
                  className="relative"
                  ref={(el) => {
                    dropdownRefs.current[item.to] = el;
                  }}
                >
                  <button
                    type="button"
                    onClick={() => setOpenMenu((v) => (v === item.to ? null : item.to))}
                    className={cn(
                      "nav-link inline-flex items-center gap-1 text-[12px] font-medium uppercase tracking-wider",
                      isItemActive(item)
                        ? "is-active text-navy"
                        : "text-foreground/80 hover:text-navy",
                    )}
                    aria-expanded={openMenu === item.to}
                  >
                    {item.label}
                    <ChevronDown
                      className={cn(
                        "h-3.5 w-3.5 transition-transform duration-300",
                        openMenu === item.to && "rotate-180",
                      )}
                    />
                  </button>
                  <div
                    className={cn(
                      "absolute left-0 top-full z-50 mt-3 min-w-[17rem] origin-top border border-hairline bg-background py-2 shadow-sm transition-all duration-300",
                      openMenu === item.to
                        ? "pointer-events-auto visible translate-y-0 opacity-100"
                        : "pointer-events-none invisible -translate-y-1 opacity-0",
                    )}
                    aria-hidden={openMenu !== item.to}
                  >
                    {item.to === "/what-we-do" && (
                      <Link
                        to={item.to}
                        className="block px-4 py-2.5 text-[12px] font-medium text-foreground/80 transition-colors hover:bg-panel hover:text-navy"
                        onClick={() => setOpenMenu(null)}
                      >
                        {t.nav.overview}
                      </Link>
                    )}
                    {item.children.map((child) => (
                      <Link
                        key={child.to}
                        to={child.to}
                        className="block px-4 py-2.5 text-[12px] font-medium text-foreground/80 transition-colors hover:bg-panel hover:text-navy"
                        activeProps={{ className: "bg-panel text-navy" }}
                        activeOptions={{ exact: true }}
                        onClick={() => setOpenMenu(null)}
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
                  className="nav-link text-[12px] font-medium uppercase tracking-wider text-foreground/80 hover:text-navy"
                  activeProps={{ className: "nav-link is-active text-navy" }}
                  activeOptions={{ exact: Boolean(item.exact) }}
                >
                  {item.label}
                </Link>
              ),
            )}
          </nav>

          <div className="hidden items-center gap-5 xl:flex">
            <LanguageSwitcher />
          </div>

          <div className="flex items-center gap-3 xl:hidden">
            <LanguageSwitcher />
            <button
              aria-label={t.nav.menu}
              className="rounded-sm p-1 transition-colors hover:bg-panel"
              onClick={() => setOpen((v) => !v)}
            >
              {open ? <X className="h-6 w-6" /> : <Menu className="h-6 w-6" />}
            </button>
          </div>
        </div>
      </div>

      <div
        className={cn(
          "hairline-t overflow-hidden transition-all duration-300 xl:hidden",
          open ? "max-h-[40rem] opacity-100" : "pointer-events-none max-h-0 opacity-0",
        )}
        aria-hidden={!open}
      >
        <div className="container-editorial flex flex-col py-4">
          {nav.map((item) =>
            item.children ? (
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
                      className="block text-sm text-foreground/75 transition-colors hover:text-navy"
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
                className="border-b border-hairline py-3 text-sm font-medium uppercase tracking-wider text-foreground/80 transition-colors hover:text-navy"
                activeProps={{ className: "text-navy" }}
              >
                {item.label}
              </Link>
            ),
          )}
        </div>
      </div>
    </header>
  );
}
