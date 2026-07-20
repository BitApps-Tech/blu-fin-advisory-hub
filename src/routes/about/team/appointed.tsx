import { createFileRoute } from "@tanstack/react-router";
import { useMemo } from "react";
import { useI18n } from "../../../i18n";
import { TeamSection, type TeamMember } from "../../../components/TeamSection";
import { AboutPageNav } from "../../../components/AboutPageNav";
import { MidContactBanner } from "../../../components/MidContactBanner";
import { APPOINTED_IDS } from "../../../lib/team";

export const Route = createFileRoute("/about/team/appointed")({
  head: () => ({
    meta: [
      { title: "Appointed Representatives — BluFin Capital Advisory" },
      {
        name: "description",
        content:
          "BluFin's appointed representatives — CEO, Chief Compliance Officer, and Chief Research Officer.",
      },
      { property: "og:title", content: "Appointed Representatives — BluFin Capital Advisory" },
      { property: "og:url", content: "/about/team/appointed" },
    ],
    links: [{ rel: "canonical", href: "/about/team/appointed" }],
  }),
  component: AppointedPage,
});

function AppointedPage() {
  const { t } = useI18n();
  const team = t.home.team as TeamMember[];

  const members = useMemo(() => {
    const byId = Object.fromEntries(team.map((m) => [m.id, m]));
    return APPOINTED_IDS.map((id) => byId[id]).filter(Boolean) as TeamMember[];
  }, [team]);

  return (
    <>
      <section className="hairline-b bg-background">
        <div className="container-editorial py-20 md:py-28">
          <div className="eyebrow">{t.about.eyebrow}</div>
          <h1 className="mt-4 font-serif text-5xl text-navy md:text-6xl">
            {t.about.appointedTitle}
          </h1>
          <p className="mt-6 max-w-2xl text-lg leading-relaxed text-muted-foreground">
            {t.about.appointedIntro}
          </p>
          <AboutPageNav />
        </div>
      </section>

      <TeamSection
        hideHeading
        hideIntro
        members={members}
        sectionClassName="bg-background hairline-b"
      />

      <MidContactBanner />
    </>
  );
}
