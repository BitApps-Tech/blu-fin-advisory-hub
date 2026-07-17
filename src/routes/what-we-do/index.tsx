import { createFileRoute, Link } from "@tanstack/react-router";
import { ArrowUpRight } from "lucide-react";
import { WHAT_WE_DO } from "../../lib/what-we-do";
import photoTeam from "../../assets/photo-team.png";

export const Route = createFileRoute("/what-we-do/")({
  head: () => ({
    meta: [
      { title: "What We Do — BluFin Capital Advisory" },
      { name: "description", content: "Listing & capital markets, corporate finance & advisory, and M&A and deals from BluFin Capital Advisory." },
      { property: "og:title", content: "What We Do — BluFin Capital Advisory" },
      { property: "og:url", content: "/what-we-do" },
    ],
    links: [{ rel: "canonical", href: "/what-we-do" }],
  }),
  component: WhatWeDo,
});

function WhatWeDo() {
  return (
    <>
      <section className="hairline-b bg-background">
        <div className="container-editorial py-20 md:py-28">
          <div className="eyebrow">What We Do</div>
          <h1 className="mt-6 max-w-4xl font-serif text-5xl text-navy md:text-6xl">
            Three practices, engineered for the transactions that define institutions.
          </h1>
          <p className="mt-8 max-w-2xl text-lg text-muted-foreground">
            Every engagement is led by a senior partner. Every mandate carries the full
            weight of our firm's institutional discipline and regulatory expertise.
          </p>
        </div>
      </section>

      <section className="hairline-b bg-background">
        <img
          src={photoTeam}
          alt="BluFin advisory team"
          className="h-[36vh] w-full object-cover object-center md:h-[44vh]"
        />
      </section>

      <section className="bg-background">
        <div className="container-editorial hairline-t">
          {WHAT_WE_DO.map((s, i) => (
            <Link
              key={s.to}
              to={s.to}
              className={`group grid gap-6 py-12 transition hover:bg-panel md:grid-cols-12 md:gap-10 md:px-4 ${i < WHAT_WE_DO.length - 1 ? "hairline-b" : ""}`}
            >
              <div className="md:col-span-1">
                <span className="font-serif text-lg text-slate-warm">0{i + 1}</span>
              </div>
              <div className="md:col-span-5">
                <h2 className="font-serif text-3xl text-navy">{s.title}</h2>
                <p className="mt-2 italic text-slate-warm">{s.tagline}</p>
              </div>
              <div className="flex items-end justify-between gap-4 md:col-span-6">
                <p className="max-w-md text-sm leading-relaxed text-muted-foreground">{s.summary}</p>
                <ArrowUpRight className="h-5 w-5 shrink-0 text-navy transition group-hover:translate-x-0.5 group-hover:-translate-y-0.5" />
              </div>
            </Link>
          ))}
        </div>
      </section>
    </>
  );
}
