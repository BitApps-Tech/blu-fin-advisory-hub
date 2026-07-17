import { Award, ShieldCheck } from "lucide-react";
import { useI18n } from "../i18n";
import { Reveal } from "./Reveal";

export function AwardsSection() {
  const { t } = useI18n();
  const a = t.home.awards;

  return (
    <section className="hairline-b bg-background">
      <div className="container-editorial py-20 md:py-24">
        <Reveal>
          <div className="eyebrow text-center">{a.eyebrow}</div>
          <h2 className="mt-4 text-center font-serif text-4xl text-navy md:text-5xl">
            {a.title}
          </h2>
          <p className="mx-auto mt-6 max-w-2xl text-center text-base leading-relaxed text-muted-foreground">
            {a.intro}
          </p>
        </Reveal>

        <Reveal delayMs={120}>
          <div className="hover-lift mx-auto mt-14 max-w-3xl border border-hairline bg-panel transition-colors duration-300 hover:border-navy/25">
            <div className="flex flex-col gap-8 p-8 md:flex-row md:items-start md:gap-10 md:p-12">
              <div className="flex h-20 w-20 shrink-0 items-center justify-center bg-navy text-navy-foreground transition-transform duration-300 hover:scale-105">
                <Award className="h-9 w-9" strokeWidth={1.25} aria-hidden />
              </div>
              <div className="min-w-0 flex-1">
                <div className="flex items-center gap-2 text-xs uppercase tracking-widest text-slate-warm">
                  <ShieldCheck className="h-3.5 w-3.5 text-navy" />
                  {a.issuer}
                </div>
                <h3 className="mt-3 font-serif text-2xl text-navy md:text-3xl">{a.licenseTitle}</h3>
                <p className="mt-4 text-sm leading-relaxed text-muted-foreground">{a.licenseBody}</p>
                <dl className="mt-6 grid gap-4 sm:grid-cols-2">
                  <div>
                    <dt className="text-[10px] uppercase tracking-widest text-slate-warm">{a.refLabel}</dt>
                    <dd className="mt-1 text-sm font-medium text-navy">{a.refValue}</dd>
                  </div>
                  <div>
                    <dt className="text-[10px] uppercase tracking-widest text-slate-warm">{a.dateLabel}</dt>
                    <dd className="mt-1 text-sm font-medium text-navy">{a.dateValue}</dd>
                  </div>
                </dl>
              </div>
            </div>
          </div>
        </Reveal>
      </div>
    </section>
  );
}
