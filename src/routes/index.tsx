import { createFileRoute, Link } from "@tanstack/react-router";
import { useEffect, useState } from "react";
import { ArrowUpRight, Building2, LineChart, Handshake, Landmark, ShieldCheck } from "lucide-react";

export const Route = createFileRoute("/")({
  head: () => ({
    meta: [
      { title: "BluFin Capital Advisory — Institutional Capital Markets Advisory" },
      { name: "description", content: "ECMA-licensed Securities Investment Advisor. Corporate finance, listing solutions, transaction advisory and private equity for Ethiopia's next generation of issuers." },
      { property: "og:title", content: "BluFin Capital Advisory — Institutional Capital Markets Advisory" },
      { property: "og:description", content: "ECMA-licensed Securities Investment Advisor. Corporate finance, listing solutions, transaction advisory and private equity for Ethiopia's next generation of issuers." },
      { property: "og:url", content: "/" },
    ],
    links: [{ rel: "canonical", href: "/" }],
  }),
  component: Home,
});

function useCounter(target: number, durationMs = 1600) {
  const [n, setN] = useState(0);
  useEffect(() => {
    let raf = 0;
    const start = performance.now();
    const tick = (t: number) => {
      const p = Math.min(1, (t - start) / durationMs);
      const eased = 1 - Math.pow(1 - p, 3);
      setN(Math.round(target * eased));
      if (p < 1) raf = requestAnimationFrame(tick);
    };
    raf = requestAnimationFrame(tick);
    return () => cancelAnimationFrame(raf);
  }, [target, durationMs]);
  return n;
}

function Counter({ value, prefix = "", suffix = "", label }: { value: number; prefix?: string; suffix?: string; label: string }) {
  const n = useCounter(value);
  return (
    <div className="p-8 md:p-10">
      <div className="font-serif text-5xl font-semibold tracking-tight text-navy md:text-6xl">
        {prefix}{n.toLocaleString()}{suffix}
      </div>
      <div className="eyebrow mt-4">{label}</div>
    </div>
  );
}

const SERVICES = [
  { icon: Building2, title: "Corporate Finance", desc: "Capital structure advisory, debt & equity raises, and structured financing for growth-stage and established issuers.", n: "01" },
  { icon: Landmark, title: "Listing Solutions", desc: "End-to-end preparation and execution for public listings on the Ethiopian Securities Exchange.", n: "02" },
  { icon: Handshake, title: "Transaction Advisory", desc: "Buy-side and sell-side M&A, joint venture structuring, and cross-border transaction execution.", n: "03" },
  { icon: LineChart, title: "Private Equity", desc: "Institutional-grade private capital deployment, portfolio structuring and value-creation advisory.", n: "04" },
] as const;

function Home() {
  return (
    <>
      {/* HERO */}
      <section className="hairline-b bg-background">
        <div className="container-editorial grid gap-12 py-20 md:grid-cols-12 md:py-32">
          <div className="md:col-span-8">
            <div className="eyebrow flex items-center gap-3">
              <span className="inline-block h-px w-8 bg-slate-warm" />
              Licensed by the Ethiopian Capital Market Authority
            </div>
            <h1 className="mt-8 font-serif text-5xl leading-[1.05] text-navy md:text-7xl">
              Fiduciary capital markets advisory for Ethiopia's most consequential issuers.
            </h1>
            <p className="mt-8 max-w-2xl text-lg leading-relaxed text-muted-foreground">
              BluFin Capital Advisory PLC is an ECMA-licensed Securities Investment Advisor.
              We partner with founders, boards, and institutional allocators to design and
              execute capital transactions of enduring significance.
            </p>
            <div className="mt-10 flex flex-wrap gap-3">
              <Link to="/contact" className="inline-flex items-center gap-2 bg-navy px-6 py-3.5 text-xs font-medium uppercase tracking-widest text-navy-foreground transition hover:bg-navy/90">
                Request Consultation <ArrowUpRight className="h-4 w-4" />
              </Link>
              <Link to="/track-record" className="inline-flex items-center gap-2 border border-navy px-6 py-3.5 text-xs font-medium uppercase tracking-widest text-navy transition hover:bg-navy hover:text-navy-foreground">
                Review Track Record
              </Link>
            </div>
          </div>
          <div className="hairline-l hidden md:col-span-4 md:block md:pl-10">
            <div className="eyebrow">At a glance</div>
            <dl className="mt-6 space-y-6">
              <div>
                <dt className="text-xs uppercase tracking-widest text-slate-warm">Regulatory</dt>
                <dd className="mt-1 font-serif text-xl text-navy">ECMA-Licensed SIA</dd>
              </div>
              <div>
                <dt className="text-xs uppercase tracking-widest text-slate-warm">Headquarters</dt>
                <dd className="mt-1 font-serif text-xl text-navy">Addis Ababa, Ethiopia</dd>
              </div>
              <div>
                <dt className="text-xs uppercase tracking-widest text-slate-warm">Practice</dt>
                <dd className="mt-1 font-serif text-xl text-navy">Institutional Advisory</dd>
              </div>
            </dl>
          </div>
        </div>
      </section>

      {/* COUNTERS */}
      <section className="bg-panel hairline-b">
        <div className="container-editorial grid grid-cols-1 md:grid-cols-3">
          <div className="hairline-r"><Counter value={4} prefix="ETB " suffix="B+" label="Value of Advised Capital" /></div>
          <div className="hairline-r"><Counter value={38} suffix="+" label="Completed Transactions" /></div>
          <div><Counter value={14} label="Years of Advisory Experience" /></div>
        </div>
      </section>

      {/* PHILOSOPHY */}
      <section className="hairline-b bg-background">
        <div className="container-editorial grid gap-16 py-24 md:grid-cols-12">
          <div className="md:col-span-4">
            <div className="eyebrow">Corporate philosophy</div>
            <h2 className="mt-6 font-serif text-4xl text-navy">Advisory built on discretion, discipline, and a fiduciary standard.</h2>
          </div>
          <div className="grid gap-10 md:col-span-8 md:grid-cols-2">
            <div>
              <ShieldCheck className="h-6 w-6 text-navy" />
              <h3 className="mt-4 font-serif text-xl text-navy">Regulatory alignment</h3>
              <p className="mt-3 text-sm leading-relaxed text-muted-foreground">
                We operate under the full oversight of the Ethiopian Capital Market Authority,
                embedding compliance and disclosure discipline into every mandate.
              </p>
            </div>
            <div>
              <Handshake className="h-6 w-6 text-navy" />
              <h3 className="mt-4 font-serif text-xl text-navy">Client-first mandate</h3>
              <p className="mt-3 text-sm leading-relaxed text-muted-foreground">
                Our senior partners lead every engagement personally. No pooled coverage,
                no rotational teams — only accountable, principal-level attention.
              </p>
            </div>
            <div>
              <LineChart className="h-6 w-6 text-navy" />
              <h3 className="mt-4 font-serif text-xl text-navy">Rigorous execution</h3>
              <p className="mt-3 text-sm leading-relaxed text-muted-foreground">
                From structuring through close, we bring institutional discipline to
                diligence, modeling, negotiation, and post-close integration.
              </p>
            </div>
            <div>
              <Landmark className="h-6 w-6 text-navy" />
              <h3 className="mt-4 font-serif text-xl text-navy">Enduring relationships</h3>
              <p className="mt-3 text-sm leading-relaxed text-muted-foreground">
                We serve a curated roster of issuers and allocators over decades,
                not deal cycles. Trust compounds; we treat it accordingly.
              </p>
            </div>
          </div>
        </div>
      </section>

      {/* SERVICES MATRIX */}
      <section className="bg-background">
        <div className="container-editorial py-24">
          <div className="flex items-end justify-between gap-6">
            <div>
              <div className="eyebrow">Advisory verticals</div>
              <h2 className="mt-4 font-serif text-4xl text-navy">Four practices. One institutional standard.</h2>
            </div>
            <Link to="/services" className="hidden text-xs uppercase tracking-widest text-navy hover:underline md:inline-flex">All services →</Link>
          </div>

          <div className="hairline-t mt-12 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4">
            {SERVICES.map((s, i) => (
              <div key={s.title} className={`hairline-b p-8 md:p-10 ${i < SERVICES.length - 1 ? "lg:hairline-r" : ""} group transition hover:bg-panel`}>
                <div className="flex items-center justify-between">
                  <s.icon className="h-8 w-8 text-navy" strokeWidth={1.25} />
                  <span className="font-serif text-sm text-slate-warm">{s.n}</span>
                </div>
                <h3 className="mt-8 font-serif text-2xl text-navy">{s.title}</h3>
                <p className="mt-3 text-sm leading-relaxed text-muted-foreground">{s.desc}</p>
                <Link to="/services" className="mt-6 inline-flex items-center gap-1 text-xs uppercase tracking-widest text-navy">
                  Learn more <ArrowUpRight className="h-3.5 w-3.5 transition group-hover:translate-x-0.5 group-hover:-translate-y-0.5" />
                </Link>
              </div>
            ))}
          </div>
        </div>
      </section>

      {/* CTA */}
      <section className="bg-navy text-navy-foreground">
        <div className="container-editorial grid gap-10 py-20 md:grid-cols-12 md:py-28">
          <div className="md:col-span-8">
            <div className="eyebrow text-white/60">Engage BluFin</div>
            <h2 className="mt-6 font-serif text-4xl leading-tight md:text-5xl">
              When the transaction matters, the advisor should too.
            </h2>
          </div>
          <div className="flex items-end md:col-span-4">
            <Link to="/contact" className="inline-flex w-full items-center justify-between border border-white/30 px-6 py-4 text-xs uppercase tracking-widest transition hover:bg-white hover:text-navy">
              Book a Consultation <ArrowUpRight className="h-4 w-4" />
            </Link>
          </div>
        </div>
      </section>
    </>
  );
}
