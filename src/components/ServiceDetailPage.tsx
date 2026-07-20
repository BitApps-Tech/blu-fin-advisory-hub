import { Link } from "@tanstack/react-router";
import { ArrowUpRight } from "lucide-react";
import { useI18n } from "../i18n";
import { getPractices, type PracticeKey } from "../lib/what-we-do";
import { MidContactBanner } from "./MidContactBanner";

export function ServiceDetailPage({ practiceKey }: { practiceKey: PracticeKey }) {
  const { t } = useI18n();
  const practices = getPractices(t);
  const service = practices.find((p) => p.key === practiceKey)!;
  const others = practices.filter((s) => s.key !== practiceKey);

  return (
    <>
      <section className="hairline-b bg-background">
        <div className="container-editorial grid gap-12 py-20 md:grid-cols-12 md:py-28">
          <div className="md:col-span-8">
            <div className="eyebrow">{service.eyebrow}</div>
            <h1 className="mt-6 max-w-4xl font-serif text-5xl text-navy md:text-6xl">
              {service.title}
            </h1>
            <p className="mt-4 italic text-slate-warm">{service.tagline}</p>
            <p className="mt-8 max-w-2xl text-lg text-muted-foreground">{service.summary}</p>
          </div>
        </div>
      </section>

      <MidContactBanner />

      <section className="bg-background">
        <div className="container-editorial grid gap-16 py-20 md:grid-cols-12">
          <div className="md:col-span-7">
            <div className="eyebrow">{t.whatWeDo.scope}</div>
            <ul className="mt-8 space-y-4">
              {service.points.map((p) => (
                <li key={p} className="flex items-start gap-3 text-base text-foreground/85">
                  <span className="mt-2.5 h-px w-5 shrink-0 bg-navy" />
                  {p}
                </li>
              ))}
            </ul>
            <Link
              to="/contact"
              className="mt-12 inline-flex items-center gap-2 bg-navy px-6 py-3.5 text-xs font-medium uppercase tracking-widest text-navy-foreground transition hover:bg-navy/90"
            >
              {t.common.requestConsultation} <ArrowUpRight className="h-4 w-4" />
            </Link>
          </div>

          <div className="md:col-span-5">
            <div className="eyebrow">{t.whatWeDo.related}</div>
            <ul className="mt-6 space-y-4">
              {others.map((s) => (
                <li key={s.to}>
                  <Link
                    to={s.to}
                    className="group flex items-center justify-between border-b border-hairline py-4 text-navy"
                  >
                    <span className="font-serif text-xl">{s.short}</span>
                    <ArrowUpRight className="h-4 w-4 transition group-hover:translate-x-0.5 group-hover:-translate-y-0.5" />
                  </Link>
                </li>
              ))}
            </ul>
          </div>
        </div>
      </section>
    </>
  );
}
