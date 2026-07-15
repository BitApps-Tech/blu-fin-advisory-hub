import { createFileRoute, Link } from "@tanstack/react-router";
import { Building2, Landmark, Handshake, LineChart, ArrowUpRight } from "lucide-react";

export const Route = createFileRoute("/services")({
  head: () => ({
    meta: [
      { title: "Advisory Services — BluFin Capital Advisory" },
      { name: "description", content: "Corporate Finance, Listing Solutions, Transaction Advisory, and Private Equity — the four core advisory practices of BluFin Capital Advisory PLC." },
      { property: "og:title", content: "Advisory Services — BluFin Capital Advisory" },
      { property: "og:description", content: "Four institutional advisory practices, one fiduciary standard." },
      { property: "og:url", content: "/services" },
    ],
    links: [{ rel: "canonical", href: "/services" }],
  }),
  component: Services,
});

const SERVICES = [
  {
    icon: Building2,
    n: "01",
    title: "Corporate Finance",
    tagline: "Capital structure, raised with intention.",
    points: [
      "Growth capital & structured equity raises",
      "Debt structuring, refinancing, and syndication",
      "Balance sheet & capital allocation advisory",
      "Fairness opinions and independent valuations",
    ],
  },
  {
    icon: Landmark,
    n: "02",
    title: "Listing Solutions",
    tagline: "The definitive path to public markets.",
    points: [
      "ESX listing readiness diagnostics",
      "Prospectus and offering documentation",
      "Governance, disclosure & investor relations design",
      "Post-listing capital markets support",
    ],
  },
  {
    icon: Handshake,
    n: "03",
    title: "Transaction Advisory",
    tagline: "Discreet execution of complex deals.",
    points: [
      "Sell-side & buy-side M&A execution",
      "Joint venture and strategic partnership structuring",
      "Cross-border transaction advisory",
      "Post-merger integration support",
    ],
  },
  {
    icon: LineChart,
    n: "04",
    title: "Private Equity",
    tagline: "Institutional capital, deployed with rigor.",
    points: [
      "Growth equity structuring & syndication",
      "Portfolio company value creation",
      "Exit planning and secondary transactions",
      "Limited partner advisory & fund formation",
    ],
  },
] as const;

function Services() {
  return (
    <>
      <section className="hairline-b bg-background">
        <div className="container-editorial py-20 md:py-28">
          <div className="eyebrow">Advisory Services</div>
          <h1 className="mt-6 max-w-4xl font-serif text-5xl text-navy md:text-6xl">
            Four practices, engineered for the transactions that define institutions.
          </h1>
          <p className="mt-8 max-w-2xl text-lg text-muted-foreground">
            Every engagement is led by a senior partner. Every mandate carries the full
            weight of our firm's institutional discipline, sector depth, and regulatory expertise.
          </p>
        </div>
      </section>

      <section className="bg-background">
        <div className="container-editorial hairline-b hairline-t grid grid-cols-1 lg:grid-cols-2">
          {SERVICES.map((s, i) => (
            <div key={s.title} className={`p-10 md:p-14 ${i % 2 === 0 ? "lg:hairline-r" : ""} ${i < SERVICES.length - 2 ? "hairline-b" : ""} transition hover:bg-panel`}>
              <div className="flex items-start justify-between">
                <s.icon className="h-10 w-10 text-navy" strokeWidth={1.25} />
                <span className="font-serif text-lg text-slate-warm">{s.n}</span>
              </div>
              <h2 className="mt-10 font-serif text-3xl text-navy">{s.title}</h2>
              <p className="mt-3 italic text-slate-warm">{s.tagline}</p>
              <ul className="mt-8 space-y-3">
                {s.points.map((p) => (
                  <li key={p} className="flex items-start gap-3 text-sm text-foreground/85">
                    <span className="mt-2 h-px w-4 shrink-0 bg-navy" />
                    {p}
                  </li>
                ))}
              </ul>
            </div>
          ))}
        </div>
      </section>

      <section className="bg-navy text-navy-foreground">
        <div className="container-editorial flex flex-wrap items-center justify-between gap-6 py-16">
          <h2 className="max-w-xl font-serif text-3xl md:text-4xl">Discuss your mandate with a senior partner.</h2>
          <Link to="/contact" className="inline-flex items-center gap-2 border border-white/30 px-6 py-3.5 text-xs uppercase tracking-widest transition hover:bg-white hover:text-navy">
            Request Consultation <ArrowUpRight className="h-4 w-4" />
          </Link>
        </div>
      </section>
    </>
  );
}
