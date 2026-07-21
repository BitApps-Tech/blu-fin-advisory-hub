import { createFileRoute, Link } from "@tanstack/react-router";
import { useEffect, useMemo, useState } from "react";
import { ArrowUpRight } from "lucide-react";
import { z } from "zod";
import { articleStore, ensureSeeded, type Article } from "../lib/mock-store";
import { searchSite, type SearchHit } from "../lib/site-search";
import { useI18n } from "../i18n";
import { pageLinks, pageOgUrl } from "../lib/seo";

const searchSchema = z.object({
  q: z.string().optional().catch(""),
});

export const Route = createFileRoute("/search")({
  validateSearch: (search) => searchSchema.parse(search),
  head: () => ({
    meta: [
      { title: "Search — BluFin Capital Advisory" },
      { name: "robots", content: "noindex, follow" },
      pageOgUrl("/search"),
    ],
    links: pageLinks("/search"),
  }),
  component: SearchPage,
});

function SearchPage() {
  const { t } = useI18n();
  const { q = "" } = Route.useSearch();
  const query = q.trim();
  const [articles, setArticles] = useState<Article[]>([]);

  useEffect(() => {
    ensureSeeded();
    setArticles(articleStore.list());
  }, []);

  const hits = useMemo(() => searchSite(query, t, articles), [query, t, articles]);

  function kindLabel(kind: SearchHit["kind"]) {
    switch (kind) {
      case "article":
        return t.insights.kindArticle;
      case "practice":
        return t.insights.kindPractice;
      case "team":
        return t.insights.kindTeam;
      default:
        return t.insights.kindPage;
    }
  }

  return (
    <>
      <section className="hairline-b bg-background">
        <div className="container-editorial py-16 md:py-24">
          <div className="eyebrow">{t.nav.search}</div>
          <h1 className="mt-4 font-serif text-4xl text-navy md:text-5xl">
            {query ? (
              <>
                {t.insights.resultsFor} <span className="italic text-slate-warm">“{query}”</span>
              </>
            ) : (
              t.nav.search
            )}
          </h1>
          {query && (
            <p className="mt-4 text-sm text-muted-foreground">
              {hits.length} {t.insights.resultsCount}
              <Link
                to="/search"
                search={{ q: undefined }}
                className="ml-4 text-navy underline-offset-4 hover:underline"
              >
                {t.insights.clearSearch}
              </Link>
            </p>
          )}
        </div>
      </section>

      <section className="bg-background">
        <div className="container-editorial py-12 md:py-16">
          {!query && (
            <p className="text-muted-foreground">
              {t.nav.searchPlaceholder.replace("…", "")} — {t.nav.mediaCenter}, {t.nav.whatWeDo},{" "}
              {t.about.teamTab}, {t.nav.about}.
            </p>
          )}

          {query && hits.length === 0 && (
            <div className="py-16 text-center text-slate-warm">{t.insights.noResults}</div>
          )}

          {hits.length > 0 && (
            <ul className="hairline-t divide-y divide-hairline">
              {hits.map((hit) => (
                <li key={hit.id}>
                  <Link
                    to={hit.to}
                    search={hit.search}
                    className="group flex items-start justify-between gap-6 py-6 transition-colors hover:bg-panel md:px-4"
                  >
                    <div>
                      <span className="text-[10px] font-semibold uppercase tracking-[0.16em] text-slate-warm">
                        {kindLabel(hit.kind)}
                      </span>
                      <h2 className="mt-2 font-serif text-2xl text-navy transition-opacity group-hover:opacity-80">
                        {hit.title}
                      </h2>
                      <p className="mt-2 max-w-3xl text-sm leading-relaxed text-muted-foreground">
                        {hit.excerpt}
                      </p>
                    </div>
                    <ArrowUpRight className="mt-2 h-4 w-4 shrink-0 text-navy transition-transform group-hover:translate-x-0.5 group-hover:-translate-y-0.5" />
                  </Link>
                </li>
              ))}
            </ul>
          )}
        </div>
      </section>
    </>
  );
}
