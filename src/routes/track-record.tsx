import { createFileRoute } from "@tanstack/react-router";
import { useMemo, useState, useEffect } from "react";
import { Search } from "lucide-react";
import { txnStore, type Transaction, ensureSeeded } from "../lib/mock-store";
import { AtAGlance } from "../components/AtAGlance";
import { MidContactBanner } from "../components/MidContactBanner";
import { useI18n } from "../i18n";
import photoTeamCert from "../assets/photo-team-certificate.png";
import { pageLinks, pageOgUrl } from "../lib/seo";

export const Route = createFileRoute("/track-record")({
  head: () => ({
    meta: [
      { title: "Track Record — BluFin Capital Advisory" },
      {
        name: "description",
        content:
          "A curated ledger of BluFin's advisory transactions across corporate finance, listings, M&A, and private equity.",
      },
      { property: "og:title", content: "Track Record — BluFin Capital Advisory" },
      { property: "og:description", content: "Interactive ledger of completed advisory mandates." },
      pageOgUrl("/track-record"),
    ],
    links: pageLinks("/track-record"),
  }),
  component: TrackRecord,
});

const SECTORS = [
  "All",
  "Retail",
  "Logistics",
  "Tech",
  "Manufacturing",
  "Financial Services",
  "Energy",
] as const;
const SCALES = ["All", "Under $5M", "$5M–$25M", "$25M–$100M", "$100M+"] as const;

function TrackRecord() {
  const { t } = useI18n();
  const [txns, setTxns] = useState<Transaction[]>([]);
  const [sector, setSector] = useState<string>("All");
  const [scale, setScale] = useState<string>("All");
  const [q, setQ] = useState("");

  useEffect(() => {
    ensureSeeded();
    setTxns(txnStore.list());
  }, []);

  const filtered = useMemo(() => {
    return txns.filter((t) => {
      if (sector !== "All" && t.sector !== sector) return false;
      if (scale !== "All" && t.scale !== scale) return false;
      if (q && !`${t.client} ${t.summary} ${t.service}`.toLowerCase().includes(q.toLowerCase()))
        return false;
      return true;
    });
  }, [txns, sector, scale, q]);

  return (
    <>
      <section className="hairline-b bg-background">
        <div className="container-editorial grid gap-12 py-20 md:grid-cols-12 md:py-24">
          <div className="md:col-span-8">
            <div className="eyebrow">{t.trackRecord.eyebrow}</div>
            <h1 className="mt-6 max-w-3xl font-serif text-5xl text-navy md:text-6xl">
              {t.trackRecord.headline}
            </h1>
            <p className="mt-6 max-w-2xl text-lg text-muted-foreground">{t.trackRecord.intro}</p>
          </div>
          <div className="hidden md:col-span-4 md:block">
            <AtAGlance practice="Transaction Advisory" />
          </div>
        </div>
      </section>

      <section className="hairline-b bg-background">
        <img
          src={photoTeamCert}
          alt="BluFin team celebrating a completed capital markets mandate"
          className="h-[36vh] w-full object-cover object-center md:h-[44vh]"
        />
      </section>

      <MidContactBanner />

      {/* Filters */}
      <section className="hairline-b bg-panel">
        <div className="container-editorial grid gap-4 py-6 md:grid-cols-12 md:items-center">
          <div className="relative md:col-span-5">
            <Search className="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-warm" />
            <input
              value={q}
              onChange={(e) => setQ(e.target.value)}
              placeholder={t.trackRecord.search}
              className="w-full border border-hairline bg-background py-3 pl-10 pr-3 text-sm outline-none transition focus:border-navy"
            />
          </div>
          <div className="md:col-span-3">
            <label className="eyebrow mb-1 block">{t.trackRecord.industry}</label>
            <select
              value={sector}
              onChange={(e) => setSector(e.target.value)}
              className="w-full border border-hairline bg-background py-3 px-3 text-sm outline-none focus:border-navy"
            >
              {SECTORS.map((s) => (
                <option key={s}>{s}</option>
              ))}
            </select>
          </div>
          <div className="md:col-span-3">
            <label className="eyebrow mb-1 block">{t.trackRecord.dealScale}</label>
            <select
              value={scale}
              onChange={(e) => setScale(e.target.value)}
              className="w-full border border-hairline bg-background py-3 px-3 text-sm outline-none focus:border-navy"
            >
              {SCALES.map((s) => (
                <option key={s}>{s}</option>
              ))}
            </select>
          </div>
          <div className="md:col-span-1 md:text-right">
            <span className="text-xs text-slate-warm">
              {filtered.length} {t.trackRecord.deals}
            </span>
          </div>
        </div>
      </section>

      {/* Grid */}
      <section className="bg-background">
        <div className="container-editorial py-16">
          {filtered.length === 0 ? (
            <div className="py-24 text-center text-slate-warm">{t.trackRecord.empty}</div>
          ) : (
            <div className="hairline-t grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3">
              {filtered.map((t, i) => (
                <article
                  key={t.id}
                  className={`hairline-b p-8 transition hover:bg-panel ${i % 3 !== 2 ? "lg:hairline-r" : ""} ${i % 2 === 0 ? "md:hairline-r lg:hairline-r" : ""}`}
                >
                  <div className="flex items-center justify-between">
                    <div className="flex h-12 w-12 items-center justify-center border border-hairline bg-panel font-serif text-lg text-navy">
                      {t.client.slice(0, 1)}
                    </div>
                    <span className="text-xs uppercase tracking-widest text-slate-warm">
                      {t.year}
                    </span>
                  </div>
                  <h3 className="mt-6 font-serif text-xl text-navy">{t.client}</h3>
                  <div className="mt-2 flex flex-wrap gap-2">
                    <span className="border border-hairline px-2 py-1 text-[10px] uppercase tracking-widest text-slate-warm">
                      {t.sector}
                    </span>
                    <span className="border border-hairline px-2 py-1 text-[10px] uppercase tracking-widest text-slate-warm">
                      {t.scale}
                    </span>
                  </div>
                  <p className="mt-5 text-sm leading-relaxed text-foreground/80">{t.summary}</p>
                  <div className="hairline-t mt-6 flex items-center justify-between pt-4">
                    <span className="text-xs text-slate-warm">{t.service}</span>
                    <span className="text-xs font-medium text-navy">{t.milestone}</span>
                  </div>
                </article>
              ))}
            </div>
          )}
        </div>
      </section>
    </>
  );
}
