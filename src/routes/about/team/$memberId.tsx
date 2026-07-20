import { createFileRoute, Link, notFound } from "@tanstack/react-router";
import { ArrowLeft } from "lucide-react";
import { useI18n } from "../../../i18n";
import { AboutPageNav } from "../../../components/AboutPageNav";
import { MidContactBanner } from "../../../components/MidContactBanner";
import { TEAM_PROFILES, memberInitials } from "../../../lib/team";
import { getTeamDetail, teamGroupPath, TEAM_DETAILS } from "../../../lib/team-details";
import type { TeamMember } from "../../../components/TeamSection";

const MEMBER_PAGE_NAMES: Record<string, string> = {
  abraham: "Mr. Abraham Ejigu Shiferaw",
  guang: "Mr. Guang Xue",
  yohannes: "Dr. Yohannes Workeaferahu Elifneh",
  daniel: "Mr. Daniel Yared Hailemariam",
  kindie: "Mr. Kindie Abebe Alemayehu",
  yitbarek: "Dr. Yitbarek Takele Bayiley",
  bizuayehu: "Mr. Bizuayehu Seyoum Tsehaye",
  abebe: "Dr. Abebe Gule Girma",
};

export const Route = createFileRoute("/about/team/$memberId")({
  beforeLoad: ({ params }) => {
    if (!TEAM_DETAILS[params.memberId]) {
      throw notFound();
    }
  },
  head: ({ params }) => {
    const name = MEMBER_PAGE_NAMES[params.memberId] ?? "Team member";
    return {
      meta: [
        { title: `${name} — BluFin Capital Advisory` },
        {
          name: "description",
          content: getTeamDetail(params.memberId)?.hoverBio ?? "",
        },
        { property: "og:title", content: `${name} — BluFin Capital Advisory` },
        { property: "og:url", content: `/about/team/${params.memberId}` },
      ],
      links: [{ rel: "canonical", href: `/about/team/${params.memberId}` }],
    };
  },
  component: TeamMemberPage,
});

function TeamMemberPage() {
  const { t } = useI18n();
  const { memberId } = Route.useParams();
  const detail = getTeamDetail(memberId);
  const member = (t.home.team as TeamMember[]).find((m) => m.id === memberId);
  const profile = TEAM_PROFILES[memberId];
  const backTo = teamGroupPath(memberId);

  if (!detail || !member) {
    return (
      <section className="container-editorial py-24 text-center">
        <p className="text-muted-foreground">{t.home.teamNotFound}</p>
        <Link to="/about/team/board" className="mt-6 inline-block text-navy underline">
          {t.home.teamBack}
        </Link>
      </section>
    );
  }

  return (
    <>
      <section className="hairline-b bg-background">
        <div className="container-editorial py-16 md:py-24">
          <Link
            to={backTo}
            className="inline-flex items-center gap-2 text-xs font-semibold uppercase tracking-[0.16em] text-navy transition-opacity hover:opacity-70"
          >
            <ArrowLeft className="h-3.5 w-3.5" />
            {t.home.teamBack}
          </Link>

          <div className="mt-10 grid gap-10 md:grid-cols-[minmax(0,16rem)_1fr] md:gap-14">
            <div>
              <div className="flex aspect-[3/4] w-full items-center justify-center overflow-hidden bg-panel">
                {profile?.avatar ? (
                  <img
                    src={profile.avatar}
                    alt={member.name}
                    className="h-[90%] w-auto object-contain object-bottom"
                  />
                ) : (
                  <span className="font-serif text-5xl text-navy/35">
                    {memberInitials(member.name)}
                  </span>
                )}
              </div>
              <div className="bg-navy px-3 py-3 text-center">
                <p className="text-sm font-semibold tracking-wide text-navy-foreground">
                  {member.name}
                </p>
              </div>
              <p className="mt-3 text-center text-[11px] font-medium uppercase tracking-[0.14em] text-foreground/80">
                {member.title}
              </p>
            </div>

            <div>
              <div className="eyebrow">{t.home.teamProfile}</div>
              <h1 className="mt-3 font-serif text-3xl text-navy md:text-4xl">{member.name}</h1>
              <p className="mt-2 text-sm font-medium uppercase tracking-[0.14em] text-slate-warm">
                {member.title}
              </p>
              <p className="mt-6 text-base leading-relaxed text-muted-foreground">{detail.overview}</p>

              <div className="mt-10">
                <h2 className="text-xs font-semibold uppercase tracking-[0.18em] text-navy">
                  {t.home.teamEducation}
                </h2>
                <ul className="mt-4 space-y-2 border-t border-hairline pt-4">
                  {detail.education.map((item) => (
                    <li key={item} className="text-sm leading-relaxed text-foreground/85">
                      {item}
                    </li>
                  ))}
                </ul>
              </div>

              <div className="mt-10">
                <h2 className="text-xs font-semibold uppercase tracking-[0.18em] text-navy">
                  {t.home.teamExperience}
                </h2>
                <ul className="mt-4 list-disc space-y-2 border-t border-hairline pt-4 pl-5">
                  {detail.experience.map((item) => (
                    <li key={item} className="text-sm leading-relaxed text-foreground/85">
                      {item}
                    </li>
                  ))}
                </ul>
              </div>

              {detail.sections?.map((section) => (
                <div key={section.title} className="mt-10">
                  <h2 className="text-xs font-semibold uppercase tracking-[0.18em] text-navy">
                    {section.title}
                  </h2>
                  <p className="mt-4 border-t border-hairline pt-4 text-sm leading-relaxed text-foreground/85">
                    {section.body}
                  </p>
                </div>
              ))}
            </div>
          </div>

          <AboutPageNav />
        </div>
      </section>

      <MidContactBanner />
    </>
  );
}
