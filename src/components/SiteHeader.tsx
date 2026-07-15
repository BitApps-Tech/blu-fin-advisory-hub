import { Link } from "@tanstack/react-router";
import { useState } from "react";
import { Menu, X, Lock } from "lucide-react";
import { Logo } from "./Logo";

const NAV = [
  { to: "/", label: "Home" },
  { to: "/services", label: "Advisory Services" },
  { to: "/track-record", label: "Track Record" },
  { to: "/insights", label: "Insights" },
  { to: "/contact", label: "Contact" },
] as const;

export function SiteHeader() {
  const [open, setOpen] = useState(false);
  return (
    <header className="hairline-b sticky top-0 z-40 bg-background/90 backdrop-blur">
      <div className="container-editorial flex h-20 items-center justify-between">
        <Link to="/" className="shrink-0">
          <Logo />
        </Link>

        <nav className="hidden items-center gap-8 lg:flex">
          {NAV.map((item) => (
            <Link
              key={item.to}
              to={item.to}
              className="text-[13px] font-medium tracking-wide text-foreground/80 transition-colors hover:text-navy"
              activeProps={{ className: "text-navy" }}
              activeOptions={{ exact: item.to === "/" }}
            >
              {item.label}
            </Link>
          ))}
        </nav>

        <div className="hidden items-center gap-3 lg:flex">
          <Link
            to="/portal"
            className="inline-flex items-center gap-2 border border-navy bg-navy px-5 py-2.5 text-xs font-medium uppercase tracking-widest text-navy-foreground transition hover:bg-navy/90"
          >
            <Lock className="h-3.5 w-3.5" /> Portal Login
          </Link>
        </div>

        <button
          className="lg:hidden"
          aria-label="Menu"
          onClick={() => setOpen((v) => !v)}
        >
          {open ? <X className="h-6 w-6" /> : <Menu className="h-6 w-6" />}
        </button>
      </div>

      {open && (
        <div className="hairline-t lg:hidden">
          <div className="container-editorial flex flex-col py-4">
            {NAV.map((item) => (
              <Link
                key={item.to}
                to={item.to}
                onClick={() => setOpen(false)}
                className="border-b border-hairline py-3 text-sm text-foreground/80"
                activeProps={{ className: "text-navy" }}
              >
                {item.label}
              </Link>
            ))}
            <Link
              to="/portal"
              onClick={() => setOpen(false)}
              className="mt-4 inline-flex items-center justify-center gap-2 bg-navy px-5 py-3 text-xs font-medium uppercase tracking-widest text-navy-foreground"
            >
              <Lock className="h-3.5 w-3.5" /> Portal Login
            </Link>
          </div>
        </div>
      )}
    </header>
  );
}
