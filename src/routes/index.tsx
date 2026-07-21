import { createFileRoute, Link } from "@tanstack/react-router";
import { useEffect, useMemo, useState } from "react";
import { ArrowUpRight, Landmark, Building2, Handshake } from "lucide-react";
import { useI18n } from "../i18n";
import { getPractices } from "../lib/what-we-do";
import { COMPANY } from "../lib/company";
import { pageLinks, pageOgUrl } from "../lib/seo";
import { AwardsSection } from "../components/AwardsSection";
import { MidContactBanner } from "../components/MidContactBanner";
import { Reveal } from "../components/Reveal";
import photoBanner from "../assets/photo-team-milestone.png";
import photoEcma from "../assets/photo-ecma-license.png";
import photoSigning from "../assets/photo-signing.png";
import photoCeremony from "../assets/photo-ecma-ceremony.png";
import photoTeamCert from "../assets/photo-team-certificate.png";

export const Route = createFileRoute("/")({
  head: () => ({
    meta: [
      { title: "BluFin Capital Advisory — Securities Investment Advisor" },
      { name: "description", content: COMPANY.mission },
      { property: "og:title", content: "BluFin Capital Advisory — Securities Investment Advisor" },
      pageOgUrl("/"),
    ],
    links: pageLinks("/"),
  }),
  component: Home,
});

const PRACTICE_ICONS = [Landmark, Building2, Handshake] as const;

function Home() {
  const { t } = useI18n();
  const practices = getPractices(t);
  const c = t.company;

  const heroSlides = useMemo(
    () => [
      { src: photoBanner, effect: "hero-banner-anim-fade", alt: "BluFin milestone" },
      { src: photoEcma, effect: "hero-banner-anim-slide-left", alt: "ECMA license" },
      { src: photoSigning, effect: "hero-banner-anim-slide-up", alt: "Signing ceremony" },
      { src: photoCeremony, effect: "hero-banner-anim-zoom", alt: "ECMA ceremony" },
      { src: photoTeamCert, effect: "hero-banner-anim-fade-soft", alt: "Team certificate" },
    ],
    [],
  );

  const [heroIndex, setHeroIndex] = useState(0);

  useEffect(() => {
    const reducedMotion = window.matchMedia?.("(prefers-reduced-motion: reduce)")?.matches ?? false;
    if (reducedMotion || heroSlides.length <= 1) return;

    const id = window.setInterval(() => {
      setHeroIndex((v) => (v + 1) % heroSlides.length);
    }, 5500);

    return () => window.clearInterval(id);
  }, [heroSlides.length]);

  return (
    <>
      <section className="hairline-b bg-background">
        <div className="container-editorial grid gap-12 py-20 md:grid-cols-12 md:py-32">
          <Reveal className="md:col-span-8">
            <div className="eyebrow flex items-center gap-3">
              <span className="inline-block h-px w-8 bg-slate-warm" />
              {t.home.eyebrow}
            </div>
            <h1 className="mt-8 font-serif text-5xl leading-[1.05] text-navy md:text-7xl">
              {t.home.headline}
            </h1>
            <p className="mt-8 max-w-2xl text-lg leading-relaxed text-muted-foreground">
              {c.mission}
            </p>
            <div className="mt-10 flex flex-wrap gap-3">
              <Link
                to="/contact"
                className="btn-primary-motion inline-flex items-center gap-2 bg-navy px-6 py-3.5 text-xs font-medium uppercase tracking-widest text-navy-foreground hover:bg-navy/90"
              >
                {t.home.ctaPrimary}{" "}
                <ArrowUpRight className="h-4 w-4 transition-transform duration-300 group-hover:translate-x-0.5 group-hover:-translate-y-0.5" />
              </Link>
              <Link
                to="/about"
                className="btn-primary-motion inline-flex items-center gap-2 border border-navy px-6 py-3.5 text-xs font-medium uppercase tracking-widest text-navy hover:bg-navy hover:text-navy-foreground"
              >
                {t.home.ctaSecondary}
              </Link>
            </div>
          </Reveal>
          <Reveal className="hairline-l hidden md:col-span-4 md:block md:pl-10" delayMs={120}>
            <div className="eyebrow">{t.common.atAGlance}</div>
            <dl className="mt-6 space-y-6">
              <div>
                <dt className="text-xs uppercase tracking-widest text-slate-warm">
                  {t.home.glanceLegal}
                </dt>
                <dd className="mt-1 font-serif text-xl text-navy">{c.legalForm}</dd>
              </div>
              <div>
                <dt className="text-xs uppercase tracking-widest text-slate-warm">
                  {t.home.glanceLicense}
                </dt>
                <dd className="mt-1 font-serif text-xl text-navy">{c.businessLicense}</dd>
              </div>
              <div>
                <dt className="text-xs uppercase tracking-widest text-slate-warm">
                  {t.home.glanceHq}
                </dt>
                <dd className="mt-1 font-serif text-xl text-navy">{c.registeredAddress}</dd>
              </div>
            </dl>
          </Reveal>
        </div>
      </section>

      {/* Sticky banner: image stays in the background while page content scrolls over it */}
      <div className="relative isolate">
        <section className="sticky top-0 z-0 h-[100svh] overflow-hidden bg-navy">
          <img
            key={heroIndex}
            src={heroSlides[heroIndex]?.src}
            alt={heroSlides[heroIndex]?.alt ?? ""}
            className={`absolute inset-0 h-full w-full object-cover object-[center_30%] ${
              heroSlides[heroIndex]?.effect ?? ""
            }`}
          />
          <div className="pointer-events-none absolute inset-0 bg-gradient-to-t from-navy/80 via-navy/20 to-navy/10" />
          <div className="absolute inset-x-0 bottom-0">
            <Reveal className="container-editorial py-8 md:py-12">
              <div className="eyebrow text-white/70">{t.home.visionLabel}</div>
              <p className="mt-3 max-w-3xl font-serif text-2xl leading-snug text-white md:text-3xl">
                {c.vision}
              </p>
            </Reveal>
          </div>
        </section>

        <div className="relative z-10 -mt-[100svh]">
          {/* Transparent viewport so the sticky banner is fully visible first */}
          <div className="pointer-events-none h-[100svh]" aria-hidden />

          <section className="bg-panel hairline-b">
            <Reveal className="container-editorial grid gap-10 py-16 md:grid-cols-12 md:py-20">
              <div className="md:col-span-4">
                <div className="eyebrow">{t.home.foundingEyebrow}</div>
                <h2 className="mt-4 font-serif text-3xl text-navy">{t.home.foundingTitle}</h2>
              </div>
              <div className="md:col-span-8">
                <p className="text-base leading-relaxed text-muted-foreground">{c.ownership}</p>
                <p className="mt-6 text-base leading-relaxed text-muted-foreground">
                  {c.foundingPhilosophy}
                </p>
              </div>
            </Reveal>
          </section>

          <section className="hairline-b bg-background">
            <div className="container-editorial grid gap-16 py-24 md:grid-cols-12">
              <Reveal className="md:col-span-4">
                <div className="eyebrow">{t.home.valuesEyebrow}</div>
                <h2 className="mt-6 font-serif text-4xl text-navy">{t.home.valuesTitle}</h2>
                <div className="media-zoom mt-10">
                  <img
                    src={photoEcma}
                    alt=""
                    className="aspect-[3/4] w-full object-cover object-top"
                  />
                </div>
              </Reveal>
              <div className="grid gap-10 md:col-span-8 md:grid-cols-2">
                {c.values.map((v, i) => (
                  <Reveal key={v.title} delayMs={i * 80}>
                    <div className="group border-l-2 border-transparent pl-0 transition-all duration-300 hover:border-navy hover:pl-4">
                      <h3 className="font-serif text-xl text-navy">{v.title}</h3>
                      <p className="mt-3 text-sm leading-relaxed text-muted-foreground">{v.body}</p>
                    </div>
                  </Reveal>
                ))}
              </div>
            </div>
          </section>

          <section className="bg-background">
            <div className="container-editorial py-24">
              <Reveal className="flex items-end justify-between gap-6">
                <div>
                  <div className="eyebrow">{t.home.practicesEyebrow}</div>
                  <h2 className="mt-4 font-serif text-4xl text-navy">{t.home.practicesTitle}</h2>
                </div>
                <Link
                  to="/what-we-do"
                  className="link-more hidden text-xs uppercase tracking-widest text-navy md:inline-flex"
                >
                  {t.home.allServices}
                </Link>
              </Reveal>

              <div className="hairline-t mt-12 grid grid-cols-1 md:grid-cols-3">
                {practices.map((s, i) => {
                  const Icon = PRACTICE_ICONS[i];
                  return (
                    <Reveal
                      key={s.to}
                      delayMs={i * 100}
                      className={`hairline-b group relative p-8 transition-colors duration-300 hover:bg-panel md:p-10 ${i < practices.length - 1 ? "md:hairline-r" : ""}`}
                    >
                      <span className="absolute inset-y-0 left-0 w-0 bg-navy transition-all duration-300 group-hover:w-1" />
                      <div className="flex items-center justify-between">
                        <Icon
                          className="h-8 w-8 text-navy transition-transform duration-300 group-hover:scale-110"
                          strokeWidth={1.25}
                        />
                        <span className="font-serif text-sm text-slate-warm">0{i + 1}</span>
                      </div>
                      <h3 className="mt-8 font-serif text-2xl text-navy">{s.short}</h3>
                      <p className="mt-3 text-sm leading-relaxed text-muted-foreground">
                        {s.summary}
                      </p>
                      <Link
                        to={s.to}
                        className="link-more mt-6 inline-flex items-center gap-1 text-xs uppercase tracking-widest text-navy"
                      >
                        {s.short}
                        <ArrowUpRight className="h-3.5 w-3.5 transition-transform duration-300 group-hover:translate-x-0.5 group-hover:-translate-y-0.5" />
                      </Link>
                    </Reveal>
                  );
                })}
              </div>
            </div>
          </section>

          <MidContactBanner />

          <AwardsSection />

          <section className="hairline-t hairline-b bg-panel">
            <div className="container-editorial grid gap-0 py-0 md:grid-cols-2">
              <Reveal className="flex flex-col justify-center py-16 pr-0 md:py-24 md:pr-12">
                <div className="eyebrow">{t.home.firmEyebrow}</div>
                <h2 className="mt-6 font-serif text-4xl text-navy">{t.home.firmTitle}</h2>
                <p className="mt-6 max-w-md text-sm leading-relaxed text-muted-foreground">
                  {c.status}
                </p>
                <Link
                  to="/about"
                  className="link-more mt-8 inline-flex items-center gap-2 text-xs uppercase tracking-widest text-navy"
                >
                  {t.home.firmCta}{" "}
                  <ArrowUpRight className="h-3.5 w-3.5 transition-transform duration-300 group-hover:translate-x-0.5" />
                </Link>
              </Reveal>
              <div className="grid grid-cols-2 gap-px bg-hairline">
                <div className="media-zoom">
                  <img
                    src={photoSigning}
                    alt=""
                    className="aspect-[3/4] h-full w-full object-cover object-center"
                  />
                </div>
                <div className="media-zoom">
                  <img
                    src={photoCeremony}
                    alt=""
                    className="aspect-[3/4] h-full w-full object-cover object-center"
                  />
                </div>
              </div>
            </div>
          </section>

          <section className="bg-navy text-navy-foreground">
            <Reveal className="container-editorial grid gap-10 py-20 md:grid-cols-12 md:py-28">
              <div className="md:col-span-8">
                <div className="eyebrow text-white/60">{t.home.engageEyebrow}</div>
                <h2 className="mt-6 font-serif text-4xl leading-tight md:text-5xl">
                  {t.home.engageTitle}
                </h2>
              </div>
              <div className="flex items-end md:col-span-4">
                <Link
                  to="/contact"
                  className="btn-primary-motion group inline-flex w-full items-center justify-between border border-white/30 px-6 py-4 text-xs uppercase tracking-widest hover:bg-white hover:text-navy"
                >
                  {t.common.bookConsultation}{" "}
                  <ArrowUpRight className="h-4 w-4 transition-transform duration-300 group-hover:translate-x-0.5 group-hover:-translate-y-0.5" />
                </Link>
              </div>
            </Reveal>
          </section>
        </div>
      </div>
    </>
  );
}
