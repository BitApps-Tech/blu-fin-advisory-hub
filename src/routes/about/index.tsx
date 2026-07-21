import { createFileRoute, Link } from "@tanstack/react-router";
import { ArrowUpRight } from "lucide-react";
import { useI18n } from "../../i18n";
import { COMPANY } from "../../lib/company";
import photoEcma from "../../assets/photo-ecma-license.png";
import photoTeam from "../../assets/photo-team-milestone.png";
import { MidContactBanner } from "../../components/MidContactBanner";
import { AboutPageNav } from "../../components/AboutPageNav";
import { pageLinks, pageOgUrl } from "../../lib/seo";

export const Route = createFileRoute("/about/")({
  head: () => ({
    meta: [
      { title: "About Us — BluFin Capital Advisory" },
      { name: "description", content: COMPANY.mission },
      { property: "og:title", content: "About Us — BluFin Capital Advisory" },
      pageOgUrl("/about"),
    ],
    links: pageLinks("/about"),
  }),
  component: About,
});

function About() {
  const { t } = useI18n();
  const c = t.company;

  const hubs = [
    {
      to: "/about/company-profile" as const,
      label: t.about.companyProfileTab,
      body: t.about.hubCompanyBody,
    },
    {
      to: "/about/governance" as const,
      label: t.about.governanceTab,
      body: t.about.hubGovernanceBody,
    },
    {
      to: "/about/team/board" as const,
      label: t.about.teamTab,
      body: t.about.hubTeamBody,
    },
  ];

  return (
    <>
      <section className="hairline-b bg-background">
        <div className="container-editorial py-20 md:py-28">
          <div className="eyebrow">{t.about.eyebrow}</div>
          <h1 className="mt-6 max-w-4xl font-serif text-5xl text-navy md:text-6xl">
            {t.about.headline}
          </h1>
          <p className="mt-8 max-w-3xl text-lg leading-relaxed text-muted-foreground">{c.status}</p>
          <AboutPageNav />
        </div>
      </section>

      <section className="hairline-b bg-background">
        <div className="container-editorial grid gap-0 md:grid-cols-2">
          <img src={photoTeam} alt="" className="aspect-[4/3] w-full object-cover object-center" />
          <img src={photoEcma} alt="" className="aspect-[4/3] w-full object-cover object-center" />
        </div>
      </section>

      <section className="hairline-b bg-background">
        <div className="container-editorial grid gap-12 py-20 md:grid-cols-12 md:py-24">
          <div className="md:col-span-4">
            <div className="eyebrow">{t.about.overviewEyebrow}</div>
            <h2 className="mt-4 font-serif text-3xl text-navy md:text-4xl">{t.about.overviewTitle}</h2>
          </div>
          <p className="text-base leading-relaxed text-muted-foreground md:col-span-8 md:pt-10">
            {t.about.overviewBody}
          </p>
        </div>
      </section>

      <MidContactBanner />

      <section className="hairline-b bg-background">
        <div className="container-editorial grid gap-12 py-24 md:grid-cols-2">
          <div>
            <div className="eyebrow">{t.about.vision}</div>
            <h2 className="mt-6 font-serif text-3xl text-navy md:text-4xl">{c.vision}</h2>
            <p className="mt-6 text-sm leading-relaxed text-muted-foreground">
              {c.visionElaboration}
            </p>
          </div>
          <div>
            <div className="eyebrow">{t.about.mission}</div>
            <h2 className="mt-6 font-serif text-3xl text-navy md:text-4xl">{c.mission}</h2>
            <p className="mt-6 text-sm leading-relaxed text-muted-foreground">
              {c.missionElaboration}
            </p>
          </div>
        </div>
      </section>

      <section className="hairline-b bg-panel">
        <div className="container-editorial py-20 md:py-24">
          <div className="eyebrow">{t.about.exploreEyebrow}</div>
          <h2 className="mt-4 max-w-2xl font-serif text-3xl text-navy md:text-4xl">
            {t.about.exploreTitle}
          </h2>
          <div className="mt-12 hairline-t">
            {hubs.map((hub) => (
              <Link
                key={hub.to}
                to={hub.to}
                className="group hairline-b flex items-start justify-between gap-6 py-8 transition-colors hover:bg-background/60 md:px-2"
              >
                <div>
                  <h3 className="font-serif text-2xl text-navy md:text-3xl">{hub.label}</h3>
                  <p className="mt-3 max-w-2xl text-sm leading-relaxed text-muted-foreground">
                    {hub.body}
                  </p>
                </div>
                <ArrowUpRight className="mt-2 h-4 w-4 shrink-0 text-navy transition-transform group-hover:translate-x-0.5 group-hover:-translate-y-0.5" />
              </Link>
            ))}
          </div>
        </div>
      </section>

      <section className="bg-background">
        <div className="container-editorial py-24">
          <div className="eyebrow">{t.about.valuesEyebrow}</div>
          <h2 className="mt-4 max-w-3xl font-serif text-4xl text-navy">{t.about.valuesTitle}</h2>
          <div className="hairline-t mt-12 grid grid-cols-1 md:grid-cols-2">
            {c.values.map((v, i) => (
              <div
                key={v.title}
                className={`hairline-b p-8 md:p-10 ${i % 2 === 0 ? "md:hairline-r" : ""}`}
              >
                <h3 className="font-serif text-2xl text-navy">{v.title}</h3>
                <p className="mt-4 text-sm leading-relaxed text-muted-foreground">{v.body}</p>
              </div>
            ))}
          </div>
          <Link
            to="/contact"
            className="mt-12 inline-flex items-center gap-2 text-xs uppercase tracking-widest text-navy hover:underline"
          >
            {t.common.speakWithPartner} <ArrowUpRight className="h-3.5 w-3.5" />
          </Link>
        </div>
      </section>
    </>
  );
}
