import { createFileRoute, Link } from "@tanstack/react-router";
import { ArrowUpRight, Landmark, Building2, Handshake } from "lucide-react";
import { COMPANY } from "../lib/company";
import { WHAT_WE_DO } from "../lib/what-we-do";
import photoBanner from "../assets/photo-team-milestone.png";
import photoEcma from "../assets/photo-ecma-license.png";
import photoSigning from "../assets/photo-signing.png";
import photoCeremony from "../assets/photo-ecma-ceremony.png";

export const Route = createFileRoute("/")({
  head: () => ({
    meta: [
      { title: "BluFin Capital Advisory — Securities Investment Advisor" },
      { name: "description", content: COMPANY.mission },
      { property: "og:title", content: "BluFin Capital Advisory — Securities Investment Advisor" },
      { property: "og:description", content: COMPANY.mission },
      { property: "og:url", content: "/" },
    ],
    links: [{ rel: "canonical", href: "/" }],
  }),
  component: Home,
});

const PRACTICE_ICONS = [Landmark, Building2, Handshake] as const;

function Home() {
  return (
    <>
      <section className="hairline-b bg-background">
        <div className="container-editorial grid gap-12 py-20 md:grid-cols-12 md:py-32">
          <div className="md:col-span-8">
            <div className="eyebrow flex items-center gap-3">
              <span className="inline-block h-px w-8 bg-slate-warm" />
              Licensed Securities Investment Advisor · ECMA
            </div>
            <h1 className="mt-8 font-serif text-5xl leading-[1.05] text-navy md:text-7xl">
              Ethical, insightful, and compliant advisory for Ethiopia's capital market.
            </h1>
            <p className="mt-8 max-w-2xl text-lg leading-relaxed text-muted-foreground">
              {COMPANY.mission}
            </p>
            <div className="mt-10 flex flex-wrap gap-3">
              <Link to="/contact" className="inline-flex items-center gap-2 bg-navy px-6 py-3.5 text-xs font-medium uppercase tracking-widest text-navy-foreground transition hover:bg-navy/90">
                Request Consultation <ArrowUpRight className="h-4 w-4" />
              </Link>
              <Link to="/about" className="inline-flex items-center gap-2 border border-navy px-6 py-3.5 text-xs font-medium uppercase tracking-widest text-navy transition hover:bg-navy hover:text-navy-foreground">
                About BluFin
              </Link>
            </div>
          </div>
          <div className="hairline-l hidden md:col-span-4 md:block md:pl-10">
            <div className="eyebrow">At a glance</div>
            <dl className="mt-6 space-y-6">
              <div>
                <dt className="text-xs uppercase tracking-widest text-slate-warm">Legal form</dt>
                <dd className="mt-1 font-serif text-xl text-navy">{COMPANY.legalForm}</dd>
              </div>
              <div>
                <dt className="text-xs uppercase tracking-widest text-slate-warm">License</dt>
                <dd className="mt-1 font-serif text-xl text-navy">Securities Investment Advisory</dd>
              </div>
              <div>
                <dt className="text-xs uppercase tracking-widest text-slate-warm">Headquarters</dt>
                <dd className="mt-1 font-serif text-xl text-navy">{COMPANY.registeredAddress}</dd>
              </div>
            </dl>
          </div>
        </div>
      </section>

      <section className="hairline-b relative overflow-hidden bg-navy">
        <img
          src={photoBanner}
          alt="BluFin partners and stakeholders marking a completed mandate"
          className="h-[52vh] w-full object-cover object-[center_30%] md:h-[68vh]"
        />
        <div className="pointer-events-none absolute inset-0 bg-gradient-to-t from-navy/80 via-navy/15 to-transparent" />
        <div className="absolute inset-x-0 bottom-0">
          <div className="container-editorial py-8 md:py-12">
            <div className="eyebrow text-white/70">Our vision</div>
            <p className="mt-3 max-w-3xl font-serif text-2xl leading-snug text-white md:text-3xl">
              {COMPANY.vision}
            </p>
          </div>
        </div>
      </section>

      <section className="bg-panel hairline-b">
        <div className="container-editorial grid gap-10 py-16 md:grid-cols-12 md:py-20">
          <div className="md:col-span-4">
            <div className="eyebrow">Founding philosophy</div>
            <h2 className="mt-4 font-serif text-3xl text-navy">
              Independent advice for a once-in-a-generation market.
            </h2>
          </div>
          <div className="md:col-span-8">
            <p className="text-base leading-relaxed text-muted-foreground">{COMPANY.ownership}</p>
            <p className="mt-6 text-base leading-relaxed text-muted-foreground">{COMPANY.foundingPhilosophy}</p>
          </div>
        </div>
      </section>

      <section className="hairline-b bg-background">
        <div className="container-editorial grid gap-16 py-24 md:grid-cols-12">
          <div className="md:col-span-4">
            <div className="eyebrow">Core values</div>
            <h2 className="mt-6 font-serif text-4xl text-navy">
              Non-negotiable beliefs that guide every engagement.
            </h2>
            <div className="mt-10 overflow-hidden">
              <img
                src={photoEcma}
                alt="BluFin representatives at the Ethiopian Capital Market Authority"
                className="aspect-[3/4] w-full object-cover object-top"
              />
              <p className="mt-3 text-xs uppercase tracking-widest text-slate-warm">
                ECMA · Addis Ababa
              </p>
            </div>
          </div>
          <div className="grid gap-10 md:col-span-8 md:grid-cols-2">
            {COMPANY.values.map((v) => (
              <div key={v.title}>
                <h3 className="font-serif text-xl text-navy">{v.title}</h3>
                <p className="mt-3 text-sm leading-relaxed text-muted-foreground">{v.body}</p>
              </div>
            ))}
          </div>
        </div>
      </section>

      <section className="bg-background">
        <div className="container-editorial py-24">
          <div className="flex items-end justify-between gap-6">
            <div>
              <div className="eyebrow">What we do</div>
              <h2 className="mt-4 font-serif text-4xl text-navy">Three practices. One fiduciary standard.</h2>
            </div>
            <Link to="/what-we-do" className="hidden text-xs uppercase tracking-widest text-navy hover:underline md:inline-flex">All services →</Link>
          </div>

          <div className="hairline-t mt-12 grid grid-cols-1 md:grid-cols-3">
            {WHAT_WE_DO.map((s, i) => {
              const Icon = PRACTICE_ICONS[i];
              return (
                <div key={s.to} className={`hairline-b p-8 md:p-10 ${i < WHAT_WE_DO.length - 1 ? "md:hairline-r" : ""} group transition hover:bg-panel`}>
                  <div className="flex items-center justify-between">
                    <Icon className="h-8 w-8 text-navy" strokeWidth={1.25} />
                    <span className="font-serif text-sm text-slate-warm">0{i + 1}</span>
                  </div>
                  <h3 className="mt-8 font-serif text-2xl text-navy">{s.short}</h3>
                  <p className="mt-3 text-sm leading-relaxed text-muted-foreground">{s.summary}</p>
                  <Link to={s.to} className="mt-6 inline-flex items-center gap-1 text-xs uppercase tracking-widest text-navy">
                    Learn more <ArrowUpRight className="h-3.5 w-3.5 transition group-hover:translate-x-0.5 group-hover:-translate-y-0.5" />
                  </Link>
                </div>
              );
            })}
          </div>
        </div>
      </section>

      <section className="hairline-t hairline-b bg-panel">
        <div className="container-editorial grid gap-0 py-0 md:grid-cols-2">
          <div className="flex flex-col justify-center py-16 pr-0 md:py-24 md:pr-12">
            <div className="eyebrow">The firm</div>
            <h2 className="mt-6 font-serif text-4xl text-navy">
              Agile, client-focused, and aligned with our founding principles.
            </h2>
            <p className="mt-6 max-w-md text-sm leading-relaxed text-muted-foreground">
              {COMPANY.status}
            </p>
            <Link to="/about" className="mt-8 inline-flex items-center gap-2 text-xs uppercase tracking-widest text-navy hover:underline">
              Read our company profile <ArrowUpRight className="h-3.5 w-3.5" />
            </Link>
          </div>
          <div className="grid grid-cols-2 gap-px bg-hairline">
            <img
              src={photoSigning}
              alt="BluFin partners presenting a signed advisory mandate"
              className="aspect-[3/4] h-full w-full object-cover object-center"
            />
            <img
              src={photoCeremony}
              alt="Official ceremony with Ethiopian Capital Market Authority representatives"
              className="aspect-[3/4] h-full w-full object-cover object-center"
            />
          </div>
        </div>
      </section>

      <section className="bg-navy text-navy-foreground">
        <div className="container-editorial grid gap-10 py-20 md:grid-cols-12 md:py-28">
          <div className="md:col-span-8">
            <div className="eyebrow text-white/60">Engage BluFin</div>
            <h2 className="mt-6 font-serif text-4xl leading-tight md:text-5xl">
              Empowering informed financial decisions for Ethiopia's capital market.
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
