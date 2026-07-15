import { Link } from "@tanstack/react-router";
import { Logo } from "./Logo";

export function SiteFooter() {
  return (
    <footer className="bg-navy text-navy-foreground">
      <div className="container-editorial grid gap-12 py-16 md:grid-cols-4">
        <div className="md:col-span-1">
          <Logo variant="light" />
          <p className="mt-6 max-w-xs text-sm leading-relaxed text-white/70">
            A licensed Securities Investment Advisor regulated under the Ethiopian
            Capital Market Authority (ECMA).
          </p>
        </div>

        <div>
          <div className="eyebrow text-white/60">Advisory</div>
          <ul className="mt-4 space-y-2 text-sm text-white/85">
            <li><Link to="/services" className="hover:text-white">Corporate Finance</Link></li>
            <li><Link to="/services" className="hover:text-white">Listing Solutions</Link></li>
            <li><Link to="/services" className="hover:text-white">Transaction Advisory</Link></li>
            <li><Link to="/services" className="hover:text-white">Private Equity</Link></li>
          </ul>
        </div>

        <div>
          <div className="eyebrow text-white/60">Firm</div>
          <ul className="mt-4 space-y-2 text-sm text-white/85">
            <li><Link to="/track-record" className="hover:text-white">Track Record</Link></li>
            <li><Link to="/insights" className="hover:text-white">Insights</Link></li>
            <li><Link to="/contact" className="hover:text-white">Contact</Link></li>
            <li><Link to="/portal" className="hover:text-white">Portal Login</Link></li>
          </ul>
        </div>

        <div>
          <div className="eyebrow text-white/60">Office</div>
          <address className="mt-4 space-y-2 text-sm not-italic text-white/85">
            <div>Bole Road, Addis Ababa</div>
            <div>Ethiopia</div>
            <div>+251 11 000 0000</div>
            <div>advisory@blufincapital.et</div>
          </address>
        </div>
      </div>

      <div className="border-t border-white/10">
        <div className="container-editorial flex flex-col items-start justify-between gap-3 py-6 text-xs text-white/60 md:flex-row md:items-center">
          <div>© {new Date().getFullYear()} BluFin Capital Advisory PLC. All rights reserved.</div>
          <div>Licensed by the Ethiopian Capital Market Authority · License #ECMA-SIA-2024</div>
        </div>
      </div>
    </footer>
  );
}
