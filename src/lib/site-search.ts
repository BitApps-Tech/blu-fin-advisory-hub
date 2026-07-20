import type { Dictionary } from "../i18n";
import type { Article } from "./mock-store";
import { getPractices } from "./what-we-do";

export type SearchHit = {
  id: string;
  title: string;
  excerpt: string;
  to: string;
  search?: Record<string, string>;
  kind: "article" | "page" | "practice" | "team";
};

function normalize(value: string) {
  return value.toLowerCase().replace(/\s+/g, " ").trim();
}

function matches(haystack: string, needle: string) {
  return normalize(haystack).includes(needle);
}

export function searchSite(query: string, t: Dictionary, articles: Article[]): SearchHit[] {
  const q = normalize(query);
  if (!q) return [];

  const hits: SearchHit[] = [];

  for (const article of articles) {
    const blob = `${article.title} ${article.excerpt} ${article.body} ${article.category}`;
    if (matches(blob, q)) {
      hits.push({
        id: `article-${article.id}`,
        title: article.title,
        excerpt: article.excerpt,
        to: "/insights",
        search: { article: article.id },
        kind: "article",
      });
    }
  }

  for (const practice of getPractices(t)) {
    const blob = `${practice.label} ${practice.short} ${practice.summary} ${practice.tagline} ${practice.points.join(" ")}`;
    if (matches(blob, q)) {
      hits.push({
        id: `practice-${practice.key}`,
        title: practice.label,
        excerpt: practice.summary,
        to: practice.to,
        kind: "practice",
      });
    }
  }

  for (const member of t.home.team) {
    const blob = `${member.name} ${member.title} ${member.bio}`;
    if (matches(blob, q)) {
      hits.push({
        id: `team-${member.id}`,
        title: member.name,
        excerpt: `${member.title} — ${member.bio}`,
        to: `/about/team/${member.id}`,
        kind: "team",
      });
    }
  }

  const pages: SearchHit[] = [
    {
      id: "page-home",
      title: t.nav.home,
      excerpt: t.home.headline,
      to: "/",
      kind: "page",
    },
    {
      id: "page-about",
      title: t.about.companyProfileTab,
      excerpt: t.about.headline,
      to: "/about",
      kind: "page",
    },
    {
      id: "page-board",
      title: t.about.boardTitle,
      excerpt: t.about.boardIntro,
      to: "/about/team/board",
      kind: "page",
    },
    {
      id: "page-appointed",
      title: t.about.appointedTitle,
      excerpt: t.about.appointedIntro,
      to: "/about/team/appointed",
      kind: "page",
    },
    {
      id: "page-what-we-do",
      title: t.nav.whatWeDo,
      excerpt: t.whatWeDo.headline,
      to: "/what-we-do",
      kind: "page",
    },
    {
      id: "page-insights",
      title: t.nav.mediaCenter,
      excerpt: t.insights.headline,
      to: "/insights",
      kind: "page",
    },
    {
      id: "page-careers",
      title: t.nav.careers,
      excerpt: t.careers.headline,
      to: "/careers",
      kind: "page",
    },
    {
      id: "page-contact",
      title: t.nav.contact,
      excerpt: t.contact.headline,
      to: "/contact",
      kind: "page",
    },
    {
      id: "page-track-record",
      title: t.footer.trackRecord,
      excerpt: t.trackRecord.headline,
      to: "/track-record",
      kind: "page",
    },
  ];

  for (const page of pages) {
    const blob = `${page.title} ${page.excerpt}`;
    if (matches(blob, q)) hits.push(page);
  }

  return hits;
}
