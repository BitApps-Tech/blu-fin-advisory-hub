import { Link } from "@tanstack/react-router";
import { Logo } from "./Logo";
import { SocialLinks } from "./SocialLinks";
import { CONTACT } from "../lib/contact";
import { WHAT_WE_DO } from "../lib/what-we-do";

export function SiteFooter() {
  return (
    <footer className="bg-navy text-navy-foreground">
      <div className="container-editorial grid gap-12 py-16 md:grid-cols-4">
        <div className="md:col-span-1">
          <Link to="/" className="inline-block">
            <Logo variant="light" className="h-24 w-auto md:h-28" />
          </Link>
          <p className="-mt-8 max-w-xs text-sm leading-relaxed text-white/70 md:-mt-10">
            A Private Limited Company licensed to operate as a Securities Investment Advisor
            under the Ethiopian Capital Market Authority (ECMA).
          </p>
        </div>

        <div>
          <div className="eyebrow text-white/60">What We Do</div>
          <ul className="mt-4 space-y-2 text-sm text-white/85">
            {WHAT_WE_DO.map((s) => (
              <li key={s.to}>
                <Link to={s.to} className="hover:text-white">{s.short}</Link>
              </li>
            ))}
          </ul>
        </div>

        <div>
          <div className="eyebrow text-white/60">Firm</div>
          <ul className="mt-4 space-y-2 text-sm text-white/85">
            <li><Link to="/about" className="hover:text-white">About Us</Link></li>
            <li><Link to="/insights" className="hover:text-white">News & Articles</Link></li>
            <li><Link to="/careers" className="hover:text-white">Careers</Link></li>
            <li><Link to="/track-record" className="hover:text-white">Track Record</Link></li>
            <li><Link to="/contact" className="hover:text-white">Contact Us</Link></li>
          </ul>
        </div>

        <div>
          <div className="eyebrow text-white/60">Office</div>
          <address className="mt-4 space-y-2 text-sm not-italic text-white/85">
            {CONTACT.addressLines.map((line) => (
              <div key={line}>{line}</div>
            ))}
            <div>
              <a href={CONTACT.phoneHref} className="hover:text-white">
                {CONTACT.phoneDisplay}
              </a>
            </div>
            <div>
              <a href={`mailto:${CONTACT.email}`} className="hover:text-white">
                {CONTACT.email}
              </a>
            </div>
          </address>

          <div className="eyebrow mt-8 text-white/60">Social</div>
          <SocialLinks className="mt-4 text-white" iconClassName="h-4 w-4" />
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
