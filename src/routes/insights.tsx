import { createFileRoute } from "@tanstack/react-router";
import { useEffect, useMemo, useState } from "react";
import { articleStore, type Article, ensureSeeded } from "../lib/mock-store";

export const Route = createFileRoute("/insights")({
  head: () => ({
    meta: [
      { title: "Insights & Newsroom — BluFin Capital Advisory" },
      { name: "description", content: "Sector research, market commentaries, regulatory analysis, and firm announcements from BluFin Capital Advisory." },
      { property: "og:title", content: "Insights & Newsroom — BluFin Capital Advisory" },
      { property: "og:description", content: "Research, commentary, and announcements." },
      { property: "og:url", content: "/insights" },
    ],
    links: [{ rel: "canonical", href: "/insights" }],
  }),
  component: Insights,
});

const CATEGORIES = ["All", "Market Commentary", "Sector Research", "Announcement", "Regulatory"] as const;

function Insights() {
  const [articles, setArticles] = useState<Article[]>([]);
  const [cat, setCat] = useState<string>("All");
  const [selected, setSelected] = useState<Article | null>(null);

  useEffect(() => {
    ensureSeeded();
    setArticles(articleStore.list());
  }, []);

  const filtered = useMemo(
    () => (cat === "All" ? articles : articles.filter((a) => a.category === cat)),
    [articles, cat]
  );
  const featured = filtered[0];
  const rest = filtered.slice(1);

  return (
    <>
      <section className="hairline-b bg-background">
        <div className="container-editorial py-20 md:py-24">
          <div className="eyebrow">Insights & Newsroom</div>
          <h1 className="mt-6 max-w-3xl font-serif text-5xl text-navy md:text-6xl">
            Research, commentary and announcements.
          </h1>
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
              {c}
            </button>
          ))}
        </div>
      </section>

      <section className="bg-background">
        <div className="container-editorial py-16">
          {featured && (
            <article onClick={() => setSelected(featured)} className="hairline-b group grid cursor-pointer gap-10 pb-16 md:grid-cols-12">
              <div className="md:col-span-4">
                <div className="aspect-[4/5] bg-navy" />
              </div>
              <div className="flex flex-col justify-center md:col-span-8">
                <span className="eyebrow">{featured.category}</span>
                <h2 className="mt-4 font-serif text-4xl leading-tight text-navy transition group-hover:opacity-80 md:text-5xl">
                  {featured.title}
                </h2>
                <p className="mt-6 max-w-2xl text-lg leading-relaxed text-muted-foreground">{featured.excerpt}</p>
                <div className="mt-6 text-xs uppercase tracking-widest text-slate-warm">
                  {new Date(featured.publishedAt).toLocaleDateString(undefined, { year: "numeric", month: "long", day: "numeric" })} · {featured.readMinutes} min read
                </div>
              </div>
            </article>
          )}

          {rest.length > 0 && (
            <div className="mt-16 grid grid-cols-1 gap-x-10 gap-y-14 md:grid-cols-2 lg:grid-cols-3">
              {rest.map((a) => (
                <article key={a.id} onClick={() => setSelected(a)} className="group cursor-pointer">
                  <div className="aspect-[4/3] bg-panel" />
                  <div className="mt-5">
                    <span className="eyebrow">{a.category}</span>
                    <h3 className="mt-2 font-serif text-2xl text-navy transition group-hover:opacity-80">{a.title}</h3>
                    <p className="mt-3 text-sm leading-relaxed text-muted-foreground">{a.excerpt}</p>
                    <div className="mt-4 text-xs uppercase tracking-widest text-slate-warm">
                      {new Date(a.publishedAt).toLocaleDateString(undefined, { year: "numeric", month: "short", day: "numeric" })} · {a.readMinutes} min
                    </div>
                  </div>
                </article>
              ))}
            </div>
          )}

          {filtered.length === 0 && (
            <div className="py-24 text-center text-slate-warm">No articles in this category yet.</div>
          )}
        </div>
      </section>

      {selected && (
        <div className="fixed inset-0 z-50 flex items-start justify-center overflow-y-auto bg-black/50 p-4 md:p-10" onClick={() => setSelected(null)}>
          <div className="w-full max-w-3xl bg-background p-8 md:p-12" onClick={(e) => e.stopPropagation()}>
            <button onClick={() => setSelected(null)} className="text-xs uppercase tracking-widest text-slate-warm hover:text-navy">← Close</button>
            <span className="eyebrow mt-6 block">{selected.category}</span>
            <h2 className="mt-3 font-serif text-4xl text-navy">{selected.title}</h2>
            <div className="mt-4 text-xs uppercase tracking-widest text-slate-warm">
              {new Date(selected.publishedAt).toLocaleDateString(undefined, { year: "numeric", month: "long", day: "numeric" })} · {selected.readMinutes} min read
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
