import { Link } from "@tanstack/react-router";
import { useI18n } from "../i18n";

const TABS = [
  { to: "/about", labelKey: "aboutTab" as const, exact: true },
  { to: "/about/company-profile", labelKey: "companyProfileTab" as const, exact: true },
  { to: "/about/governance", labelKey: "governanceTab" as const, exact: true },
  { to: "/about/team/board", labelKey: "boardTitle" as const, exact: false },
  { to: "/about/team/appointed", labelKey: "appointedTitle" as const, exact: false },
] as const;

export function AboutPageNav() {
  const { t } = useI18n();

  return (
    <div className="mt-10 flex flex-wrap items-center justify-center gap-6 md:justify-start md:gap-8">
      {TABS.map((tab) => (
        <Link
          key={tab.to}
          to={tab.to}
          activeOptions={{ exact: tab.exact }}
          className="nav-link text-sm font-medium text-foreground/80 hover:text-navy"
          activeProps={{ className: "nav-link is-active text-sm font-medium text-navy" }}
        >
          {t.about[tab.labelKey]}
        </Link>
      ))}
    </div>
  );
}
