import avatar0 from "../assets/avatar-team-0.png";
import avatar2 from "../assets/avatar-team-2.png";
import avatarAbraham from "../assets/avatar-team-abraham.png";
import avatarBizuayehu from "../assets/avatar-team-bizuayehu.png";
import avatarGuang from "../assets/avatar-team-guang.png";
import avatarYohannes from "../assets/avatar-team-yohannes.png";
import avatarDaniel from "../assets/avatar-team-daniel.png";
import avatarKindie from "../assets/avatar-team-kindie.png";

type TeamProfile = {
  avatar?: string;
  linkedin: string;
  x: string;
};

/** ECMA/LR/224/26 — Board of Directors (document order) */
export const BOARD_IDS = ["abraham", "guang", "yohannes", "daniel", "kindie"] as const;

/** ECMA/LR/224/26 — Appointed Representatives (document order) */
export const APPOINTED_IDS = ["yitbarek", "bizuayehu", "abebe"] as const;

/**
 * Stable avatar + social mapping keyed by `home.team[].id`.
 * Members without an avatar render initials on the team page.
 */
export const TEAM_PROFILES: Record<string, TeamProfile> = {
  yitbarek: {
    avatar: avatar0,
    linkedin: "https://www.linkedin.com/",
    x: "https://x.com/",
  },
  bizuayehu: {
    avatar: avatarBizuayehu,
    linkedin: "https://www.linkedin.com/",
    x: "https://x.com/",
  },
  abebe: {
    avatar: avatar2,
    linkedin: "https://www.linkedin.com/",
    x: "https://x.com/",
  },
  abraham: {
    avatar: avatarAbraham,
    linkedin: "https://www.linkedin.com/",
    x: "https://x.com/",
  },
  guang: {
    avatar: avatarGuang,
    linkedin: "https://www.linkedin.com/",
    x: "https://x.com/",
  },
  yohannes: {
    avatar: avatarYohannes,
    linkedin: "https://www.linkedin.com/",
    x: "https://x.com/",
  },
  daniel: {
    avatar: avatarDaniel,
    linkedin: "https://www.linkedin.com/",
    x: "https://x.com/",
  },
  kindie: {
    avatar: avatarKindie,
    linkedin: "https://www.linkedin.com/",
    x: "https://x.com/",
  },
};

export function memberInitials(name: string): string {
  const parts = name
    .replace(/^(Dr\.|Mr\.|Ms\.|Mrs\.)\s+/i, "")
    .split(/\s+/)
    .filter(Boolean);
  if (parts.length === 0) return "?";
  if (parts.length === 1) return parts[0].slice(0, 2).toUpperCase();
  return `${parts[0][0]}${parts[parts.length - 1][0]}`.toUpperCase();
}
