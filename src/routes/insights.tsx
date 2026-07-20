import { createFileRoute } from "@tanstack/react-router";
import { useEffect, useMemo, useState } from "react";
import { articleStore, type Article, ensureSeeded } from "../lib/mock-store";
import { AtAGlance } from "../components/AtAGlance";
import { MidContactBanner } from "../components/MidContactBanner";
import { useI18n } from "../i18n";
import photoFeatured from "../assets/photo-signing.png";
import photoCard1 from "../assets/photo-ecma-ceremony.png";
import photoCard2 from "../assets/photo-team-certificate.png";
import photoCard3 from "../assets/photo-ecma-trio.png";
import photoCard4 from "../assets/photo-signing-alt.png";

const ARTICLE_IMAGES = [photoCard1, photoCard2, photoCard3, photoCard4];

export const Route = createFileRoute("/insights")({
  validateSearch: (search: Record<string, unknown>) => ({
    article: typeof search.article === "string" ? search.article : undefined,
  }),
  head: () => ({
    meta: [
      { title: "Insights & Newsroom — BluFin Capital Advisory" },
      {
        name: "description",
        content:
          "Sector research, market commentaries, regulatory analysis, and firm announcements from BluFin Capital Advisory.",
      },
      { property: "og:title", content: "Insights & Newsroom — BluFin Capital Advisory" },
      { property: "og:description", content: "Research, commentary, and announcements." },
      { property: "og:url", content: "/insights" },
    ],
    links: [{ rel: "canonical", href: "/insights" }],
  }),
  component: Insights,
});

const CATEGORIES = [
  "All",
  "Market Commentary",
  "Sector Research",
  "Announcement",
  "Regulatory",
] as const;

function Insights() {
  const { t } = useI18n();
  const { article: articleId } = Route.useSearch();
  const [articles, setArticles] = useState<Article[]>([]);
  const [cat, setCat] = useState<string>("All");
  const [selected, setSelected] = useState<Article | null>(null);

  useEffect(() => {
    ensureSeeded();
    setArticles(articleStore.list());
  }, []);

  useEffect(() => {
    if (!articleId || articles.length === 0) return;
    const match = articles.find((a) => a.id === articleId);
    if (match) setSelected(match);
  }, [articleId, articles]);

  const filtered = useMemo(
    () => (cat === "All" ? articles : articles.filter((a) => a.category === cat)),
    [articles, cat],
  );
  const featured = filtered[0];
  const rest = filtered.slice(1);

  return (
    <>
      <section className="hairline-b bg-background">
        <div className="container-editorial grid gap-12 py-20 md:grid-cols-12 md:py-24">
          <div className="md:col-span-8">
            <div className="eyebrow">{t.insights.eyebrow}</div>
            <h1 className="mt-6 max-w-3xl font-serif text-5xl text-navy md:text-6xl">
              {t.insights.headline}
            </h1>
          </div>
          <div className="hidden md:col-span-4 md:block">
            <AtAGlance practice="Market Research & Commentary" />
          </div>
        </div>
      </section>

      <section className="hairline-b bg-background">
        <div className="container-editorial flex flex-wrap gap-6 py-5">
          {CATEGORIES.map((c) => (
            <button
              key={c}
              onClick={() => setCat(c)}
              className={`text-xs uppercase tracking-widest transition ${cat === c ? "text-navy underline underline-offset-8" : "text-slate-warm hover:text-navy"}`}
            >
              {c === "All" ? t.insights.all : c}
            </button>
          ))}
        </div>
      </section>

      <MidContactBanner />

      <section className="bg-background">
        <div className="container-editorial py-16">
          {featured && (
            <article
              onClick={() => setSelected(featured)}
              className="hairline-b group grid cursor-pointer gap-10 pb-16 md:grid-cols-12"
            >
              <div className="md:col-span-4">
                <img
                  src={photoFeatured}
                  alt=""
                  className="aspect-[4/5] w-full object-cover object-center transition group-hover:opacity-90"
                />
              </div>
              <div className="flex flex-col justify-center md:col-span-8">
                <span className="eyebrow">{featured.category}</span>
                <h2 className="mt-4 font-serif text-4xl leading-tight text-navy transition group-hover:opacity-80 md:text-5xl">
                  {featured.title}
                </h2>
                <p className="mt-6 max-w-2xl text-lg leading-relaxed text-muted-foreground">
                  {featured.excerpt}
                </p>
                <div className="mt-6 text-xs uppercase tracking-widest text-slate-warm">
                  {new Date(featured.publishedAt).toLocaleDateString(undefined, {
                    year: "numeric",
                    month: "long",
                    day: "numeric",
                  })}{" "}
                  · {featured.readMinutes} {t.insights.minRead}
                </div>
              </div>
            </article>
          )}

          {rest.length > 0 && (
            <div className="mt-16 grid grid-cols-1 gap-x-10 gap-y-14 md:grid-cols-2 lg:grid-cols-3">
              {rest.map((a, i) => (
                <article key={a.id} onClick={() => setSelected(a)} className="group cursor-pointer">
                  <img
                    src={ARTICLE_IMAGES[i % ARTICLE_IMAGES.length]}
                    alt=""
                    className="aspect-[4/3] w-full object-cover object-center transition group-hover:opacity-90"
                  />
                  <div className="mt-5">
                    <span className="eyebrow">{a.category}</span>
                    <h3 className="mt-2 font-serif text-2xl text-navy transition group-hover:opacity-80">
                      {a.title}
                    </h3>
                    <p className="mt-3 text-sm leading-relaxed text-muted-foreground">
                      {a.excerpt}
                    </p>
                    <div className="mt-4 text-xs uppercase tracking-widest text-slate-warm">
                      {new Date(a.publishedAt).toLocaleDateString(undefined, {
                        year: "numeric",
                        month: "short",
                        day: "numeric",
                      })}{" "}
                      · {a.readMinutes} {t.insights.minRead}
                    </div>
                  </div>
                </article>
              ))}
            </div>
          )}

          {filtered.length === 0 && (
            <div className="py-24 text-center text-slate-warm">{t.insights.empty}</div>
          )}
        </div>
      </section>

      {selected && (
        <div
          className="fixed inset-0 z-50 flex items-start justify-center overflow-y-auto bg-black/50 p-4 md:p-10"
          onClick={() => setSelected(null)}
        >
          <div
            className="w-full max-w-3xl bg-background p-8 md:p-12"
            onClick={(e) => e.stopPropagation()}
          >
            <button
              onClick={() => setSelected(null)}
              className="text-xs uppercase tracking-widest text-slate-warm hover:text-navy"
            >
              ← {t.common.close}
            </button>
            <span className="eyebrow mt-6 block">{selected.category}</span>
            <h2 className="mt-3 font-serif text-4xl text-navy">{selected.title}</h2>
            <div className="mt-4 text-xs uppercase tracking-widest text-slate-warm">
              {new Date(selected.publishedAt).toLocaleDateString(undefined, {
                year: "numeric",
                month: "long",
                day: "numeric",
              })}{" "}
              · {selected.readMinutes} min read
            </div>
            <div className="hairline-t mt-8 pt-8 text-base leading-relaxed text-foreground/85">
              <p className="mb-4">{selected.excerpt}</p>
              <p>{selected.body}</p>
            </div>
          </div>
        </div>
      )}
    </>
  );
}
