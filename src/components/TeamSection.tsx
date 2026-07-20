import { Link } from "@tanstack/react-router";
import { useState } from "react";
import { Linkedin } from "lucide-react";
import { useI18n } from "../i18n";
import { memberInitials, TEAM_PROFILES } from "../lib/team";
import { getTeamDetail } from "../lib/team-details";
import { Reveal } from "./Reveal";
import { cn } from "../lib/utils";

export type TeamMember = {
  id: string;
  name: string;
  title: string;
  bio: string;
};

function XIcon({ className }: { className?: string }) {
  return (
    <svg viewBox="0 0 24 24" fill="currentColor" className={className} aria-hidden>
      <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-4.714-6.231-5.401 6.231H2.744l7.227-8.26L1.61 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z" />
    </svg>
  );
}

type TeamSectionProps = {
  title?: string;
  intro?: string;
  members?: TeamMember[];
  hideHeading?: boolean;
  hideIntro?: boolean;
  sectionClassName?: string;
};

export function TeamSection({
  title,
  intro,
  members,
  hideHeading,
  hideIntro,
  sectionClassName = "hairline-b bg-background",
}: TeamSectionProps) {
  const { t } = useI18n();
  const teamMembers = members ?? (t.home.team as TeamMember[]);
  const [openId, setOpenId] = useState<string | null>(null);

  const gridCols =
    teamMembers.length >= 5
      ? "lg:grid-cols-5"
      : teamMembers.length === 3
        ? "lg:grid-cols-3"
        : "lg:grid-cols-4";

  return (
    <section className={sectionClassName}>
      <div className="container-editorial py-20 md:py-24">
        {!hideHeading && (
          <Reveal>
            <h2 className="text-center text-sm font-semibold uppercase tracking-[0.2em] text-navy md:text-base">
              {title ?? t.home.teamTitle}
            </h2>
            {!hideIntro && (
              <p className="mx-auto mt-4 max-w-2xl text-center text-sm leading-relaxed text-muted-foreground md:text-base">
                {intro ?? t.home.teamIntro}
              </p>
            )}
          </Reveal>
        )}

        <div className={cn("mt-12 grid grid-cols-1 gap-8 sm:grid-cols-2 lg:gap-6", gridCols)}>
          {teamMembers.map((member, i) => {
            const profile = TEAM_PROFILES[member.id] ?? {
              linkedin: "https://www.linkedin.com/",
              x: "https://x.com/",
            };
            const detail = getTeamDetail(member.id);
            const hoverBio = detail?.hoverBio ?? member.bio;
            const isOpen = openId === member.id;
            const hasAvatar = Boolean(profile.avatar);

            return (
              <Reveal key={member.id} delayMs={i * 90}>
                <article
                  className="group flex flex-col items-stretch"
                  onMouseLeave={() => setOpenId(null)}
                >
                  <div
                    className="relative flex aspect-[3/4] w-full cursor-pointer items-center justify-center overflow-hidden bg-panel"
                    onClick={() => setOpenId(isOpen ? null : member.id)}
                    onKeyDown={(e) => {
                      if (e.key === "Enter" || e.key === " ") {
                        e.preventDefault();
                        setOpenId(isOpen ? null : member.id);
                      }
                    }}
                    role="button"
                    tabIndex={0}
                    aria-expanded={isOpen}
                    aria-label={`${member.name} — ${member.title}`}
                  >
                    {hasAvatar ? (
                      <img
                        src={profile.avatar}
                        alt=""
                        className="h-[85%] w-auto object-contain object-bottom transition-transform duration-300 ease-out group-hover:scale-[1.02]"
                      />
                    ) : (
                      <div className="flex h-full w-full items-center justify-center bg-gradient-to-b from-panel to-white">
                        <span className="font-serif text-5xl tracking-wide text-navy/35 md:text-6xl">
                          {memberInitials(member.name)}
                        </span>
                      </div>
                    )}

                    <div
                      className={cn(
                        "absolute inset-x-0 bottom-0 flex h-[55%] flex-col justify-end bg-gradient-to-t from-[#1F3E72]/75 via-[#1F3E72]/45 to-transparent px-4 pb-4 pt-10 text-white transition-opacity duration-200 ease-out md:px-5 md:pb-5",
                        "pointer-events-none opacity-0",
                        "group-hover:pointer-events-auto group-hover:opacity-100",
                        isOpen && "pointer-events-auto opacity-100",
                      )}
                    >
                      <div className="min-h-0 overflow-hidden">
                        <h3 className="font-serif text-lg leading-snug text-white md:text-xl">
                          {member.title}
                        </h3>
                        <div className="mt-2 h-px w-10 bg-white/70" />
                        <p className="mt-2 text-xs leading-relaxed text-white/95 line-clamp-4 md:text-sm">
                          {hoverBio}
                        </p>
                      </div>
                      <Link
                        to="/about/team/$memberId"
                        params={{ memberId: member.id }}
                        className="mt-3 inline-flex w-fit text-[11px] font-semibold uppercase tracking-[0.16em] text-white transition-opacity hover:opacity-80"
                        onClick={(e) => e.stopPropagation()}
                      >
                        {t.home.teamMore}
                      </Link>
                    </div>
                  </div>

                  <div className="bg-navy px-3 py-3 text-center">
                    <h3 className="text-sm font-semibold tracking-wide text-navy-foreground">
                      {member.name}
                    </h3>
                  </div>
                  <p className="mt-3 text-center text-[11px] font-medium uppercase tracking-[0.14em] text-foreground/80">
                    {member.title}
                  </p>
                  <div className="mt-3 flex items-center justify-center gap-3">
                    <a
                      href={profile.linkedin}
                      target="_blank"
                      rel="noopener noreferrer"
                      aria-label={`${member.name} LinkedIn`}
                      className="flex h-8 w-8 items-center justify-center rounded-full text-navy/60 transition-all duration-300 hover:bg-navy hover:text-navy-foreground"
                    >
                      <Linkedin className="h-4 w-4" />
                    </a>
                    <a
                      href={profile.x}
                      target="_blank"
                      rel="noopener noreferrer"
                      aria-label={`${member.name} X`}
                      className="flex h-8 w-8 items-center justify-center rounded-full text-navy/60 transition-all duration-300 hover:bg-navy hover:text-navy-foreground"
                    >
                      <XIcon className="h-3.5 w-3.5" />
                    </a>
                  </div>
                </article>
              </Reveal>
            );
          })}
        </div>
      </div>
    </section>
  );
}
