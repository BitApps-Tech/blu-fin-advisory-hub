import { createFileRoute, Link } from "@tanstack/react-router";
import { useMemo } from "react";
import { useI18n } from "../../i18n";
import { TeamSection, type TeamMember } from "../../components/TeamSection";
import { MidContactBanner } from "../../components/MidContactBanner";
import { APPOINTED_IDS, BOARD_IDS } from "../../lib/team";

export const Route = createFileRoute("/about/team")({
  head: () => ({
    meta: [
      { title: "Our Team — BluFin Capital Advisory" },
      {
        name: "description",
        content: "Board of Directors and Appointed Representatives at BluFin Capital Advisory.",
      },
      { property: "og:title", content: "Our Team — BluFin Capital Advisory" },
      { property: "og:url", content: "/about/team" },
    ],
    links: [{ rel: "canonical", href: "/about/team" }],
  }),
  component: AboutTeam,
});

function AboutTeam() {
  const { t } = useI18n();
  const team = t.home.team as TeamMember[];

  const byId = useMemo(() => Object.fromEntries(team.map((m) => [m.id, m])), [team]);

  const boardMembers = useMemo(
    () => BOARD_IDS.map((id) => byId[id]).filter(Boolean) as TeamMember[],
    [byId],
  );

  const appointedMembers = useMemo(
    () => APPOINTED_IDS.map((id) => byId[id]).filter(Boolean) as TeamMember[],
    [byId],
  );

  return (
    <>
      <section className="hairline-b bg-background">
        <div className="container-editorial py-20 md:py-28">
          <div className="eyebrow">{t.about.eyebrow}</div>
          <h1 className="mt-4 font-serif text-5xl text-navy md:text-6xl">
            {t.about.teamPageTitle}
          </h1>
          <p className="mt-6 max-w-2xl text-lg leading-relaxed text-muted-foreground">
            {t.home.teamIntro}
          </p>

          <div className="mt-10 flex flex-wrap items-center justify-center gap-8 md:justify-start">
            <Link
              to="/about"
              activeOptions={{ exact: true }}
              className="nav-link text-sm font-medium text-foreground/80 hover:text-navy"
              activeProps={{ className: "nav-link is-active text-sm font-medium text-navy" }}
            >
              {t.about.companyProfileTab}
            </Link>
            <Link
              to="/about/team"
              className="nav-link text-sm font-medium text-foreground/80 hover:text-navy"
              activeProps={{ className: "nav-link is-active text-sm font-medium text-navy" }}
            >
              {t.about.teamTab}
            </Link>
          </div>
        </div>
      </section>

      <TeamSection
        title={t.about.boardTitle}
        intro={t.about.boardIntro}
        members={boardMembers}
        sectionClassName="bg-background hairline-b"
      />

      <MidContactBanner />

      <TeamSection
        title={t.about.appointedTitle}
        intro={t.about.appointedIntro}
        members={appointedMembers}
        sectionClassName="bg-background hairline-b"
      />
    </>
  );
}
