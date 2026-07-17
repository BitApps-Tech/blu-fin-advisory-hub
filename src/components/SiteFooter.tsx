import { Link } from "@tanstack/react-router";
import { Logo } from "./Logo";
import { SocialLinks } from "./SocialLinks";
import { CONTACT } from "../lib/contact";
import { useI18n } from "../i18n";
import { getPractices } from "../lib/what-we-do";

export function SiteFooter() {
  const { t } = useI18n();
  const practices = getPractices(t);

  return (
    <footer className="bg-navy text-navy-foreground">
      <div className="container-editorial grid gap-12 py-16 md:grid-cols-4">
        <div className="md:col-span-1">
          <Link to="/" className="inline-block">
            <Logo variant="light" className="h-24 w-auto md:h-28" />
          </Link>
          <p className="-mt-8 max-w-xs text-sm leading-relaxed text-white/70 md:-mt-10">
            {t.footer.blurb}
          </p>
        </div>

        <div>
          <div className="eyebrow text-white/60">{t.footer.whatWeDo}</div>
          <ul className="mt-4 space-y-2 text-sm text-white/85">
            {practices.map((s) => (
              <li key={s.to}>
                <Link to={s.to} className="hover:text-white">{s.short}</Link>
              </li>
            ))}
          </ul>
        </div>

        <div>
          <div className="eyebrow text-white/60">{t.footer.firm}</div>
          <ul className="mt-4 space-y-2 text-sm text-white/85">
            <li><Link to="/about" className="hover:text-white">{t.footer.about}</Link></li>
            <li><Link to="/insights" className="hover:text-white">{t.footer.news}</Link></li>
            <li><Link to="/careers" className="hover:text-white">{t.footer.careers}</Link></li>
            <li><Link to="/track-record" className="hover:text-white">{t.footer.trackRecord}</Link></li>
            <li><Link to="/contact" className="hover:text-white">{t.footer.contact}</Link></li>
          </ul>
        </div>

        <div>
          <div className="eyebrow text-white/60">{t.footer.office}</div>
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

          <div className="eyebrow mt-8 text-white/60">{t.footer.social}</div>
          <SocialLinks className="mt-4 text-white" iconClassName="h-4 w-4" />
        </div>
      </div>

      <div className="border-t border-white/10">
        <div className="container-editorial flex flex-col items-start justify-between gap-3 py-6 text-xs text-white/60 md:flex-row md:items-center">
          <div>© {new Date().getFullYear()} BluFin Capital Advisory PLC. {t.footer.rights}</div>
          <div>{t.footer.license}</div>
        </div>
      </div>
    </footer>
  );
}
