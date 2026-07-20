import { createFileRoute } from "@tanstack/react-router";
import { useI18n } from "../../i18n";
import { BOARD_COMMITTEES, GOVERNANCE_INTRO } from "../../lib/organogram";
import { MidContactBanner } from "../../components/MidContactBanner";
import { AboutPageNav } from "../../components/AboutPageNav";
import { Organogram } from "../../components/Organogram";

export const Route = createFileRoute("/about/governance")({
  head: () => ({
    meta: [
      { title: "Governance & Organogram — BluFin Capital Advisory" },
      {
        name: "description",
        content:
          "BluFin Capital Advisory governance structure, interactive organogram, and board committees.",
      },
      { property: "og:title", content: "Governance & Organogram — BluFin Capital Advisory" },
      { property: "og:url", content: "/about/governance" },
    ],
    links: [{ rel: "canonical", href: "/about/governance" }],
  }),
  component: GovernancePage,
});

function GovernancePage() {
  const { t } = useI18n();

  return (
    <>
      <section className="hairline-b bg-background">
        <div className="container-editorial py-20 md:py-28">
          <div className="eyebrow">{t.about.eyebrow}</div>
          <h1 className="mt-4 font-serif text-5xl text-navy md:text-6xl">
            {t.about.governanceTab}
          </h1>
          <p className="mt-6 max-w-3xl text-lg leading-relaxed text-muted-foreground">
            {GOVERNANCE_INTRO}
          </p>
          <AboutPageNav />
        </div>
      </section>

      <section className="hairline-b bg-background">
        <div className="container-editorial py-20 md:py-24">
          <div className="eyebrow">{t.about.organogramEyebrow}</div>
          <h2 className="mt-4 max-w-3xl font-serif text-3xl text-navy md:text-4xl">
            {t.about.organogramTitle}
          </h2>
          <Organogram />
        </div>
      </section>

      <MidContactBanner />

      <section className="bg-panel">
        <div className="container-editorial py-20 md:py-24">
          <div className="eyebrow">{t.about.committeesEyebrow}</div>
          <h2 className="mt-4 max-w-3xl font-serif text-3xl text-navy md:text-4xl">
            {t.about.committeesTitle}
          </h2>
          <div className="mt-12 hairline-t">
            {BOARD_COMMITTEES.map((committee) => (
              <article key={committee.id} className="hairline-b py-10">
                <h3 className="font-serif text-2xl text-navy md:text-3xl">{committee.title}</h3>
                <div className="mt-6 grid gap-8 md:grid-cols-12">
                  <div className="md:col-span-4">
                    <h4 className="text-[10px] font-semibold uppercase tracking-[0.16em] text-slate-warm">
                      {t.about.committeeComposition}
                    </h4>
                    <p className="mt-3 text-sm leading-relaxed text-muted-foreground">
                      {committee.composition}
                    </p>
                  </div>
                  <div className="md:col-span-8">
                    <h4 className="text-[10px] font-semibold uppercase tracking-[0.16em] text-slate-warm">
                      {t.about.committeeResponsibilities}
                    </h4>
                    <ul className="mt-3 space-y-2">
                      {committee.responsibilities.map((item) => (
                        <li
                          key={item}
                          className="flex gap-3 text-sm leading-relaxed text-foreground/85"
                        >
                          <span className="mt-2 h-1 w-1 shrink-0 rounded-full bg-navy" aria-hidden />
                          {item}
                        </li>
                      ))}
                    </ul>
                  </div>
                </div>
              </article>
            ))}
          </div>
        </div>
      </section>
    </>
  );
}
