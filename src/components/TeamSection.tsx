import { Linkedin } from "lucide-react";
import { useI18n } from "../i18n";
import { TEAM_PROFILES } from "../lib/team";

function XIcon({ className }: { className?: string }) {
  return (
    <svg viewBox="0 0 24 24" fill="currentColor" className={className} aria-hidden>
      <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-4.714-6.231-5.401 6.231H2.744l7.227-8.26L1.61 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z" />
    </svg>
  );
}

export function TeamSection() {
  const { t } = useI18n();

  return (
    <section className="hairline-b bg-background">
      <div className="container-editorial py-20 md:py-24">
        <h2 className="text-center text-sm font-semibold uppercase tracking-[0.2em] text-navy md:text-base">
          {t.home.teamTitle}
        </h2>
        <p className="mx-auto mt-4 max-w-2xl text-center text-sm leading-relaxed text-muted-foreground md:text-base">
          {t.home.teamIntro}
        </p>

        <div className="mt-12 grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-4 lg:gap-6">
          {t.home.team.map((member, i) => {
            const profile = TEAM_PROFILES[i] ?? TEAM_PROFILES[0];
            return (
              <article key={member.name + member.title} className="flex flex-col items-stretch">
                <div className="flex aspect-[3/4] items-center justify-center overflow-hidden rounded-t-sm bg-white">
                  <img
                    src={profile.avatar}
                    alt=""
                    className="h-[85%] w-auto object-contain object-bottom"
                  />
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
                    className="text-navy/60 transition hover:text-navy"
                  >
                    <Linkedin className="h-4 w-4" />
                  </a>
                  <a
                    href={profile.x}
                    target="_blank"
                    rel="noopener noreferrer"
                    aria-label={`${member.name} X`}
                    className="text-navy/60 transition hover:text-navy"
                  >
                    <XIcon className="h-3.5 w-3.5" />
                  </a>
                </div>
              </article>
            );
          })}
        </div>
      </div>
    </section>
  );
}
