import { createFileRoute, Link } from "@tanstack/react-router";
import { ArrowUpRight } from "lucide-react";
import { COMPANY } from "../lib/company";
import { CONTACT } from "../lib/contact";

export const Route = createFileRoute("/careers")({
  head: () => ({
    meta: [
      { title: "Careers — BluFin Capital Advisory" },
      { name: "description", content: "Explore career opportunities at BluFin Capital Advisory — integrity, objectivity, and professional excellence." },
      { property: "og:title", content: "Careers — BluFin Capital Advisory" },
      { property: "og:url", content: "/careers" },
    ],
    links: [{ rel: "canonical", href: "/careers" }],
  }),
  component: Careers,
});

function Careers() {
  return (
    <>
      <section className="hairline-b bg-background">
        <div className="container-editorial py-20 md:py-28">
          <div className="eyebrow">Careers</div>
          <h1 className="mt-6 max-w-4xl font-serif text-5xl text-navy md:text-6xl">
            Join a firm built on integrity, objectivity, and professional excellence.
          </h1>
          <p className="mt-8 max-w-2xl text-lg text-muted-foreground">
            Our core values guide the actions and behaviours of every employee. We look for
            professionals who share our commitment to ethical, insightful, and compliant advisory.
          </p>
        </div>
      </section>

      <section className="hairline-b bg-panel">
        <div className="container-editorial py-20">
          <div className="eyebrow">How we work</div>
          <div className="mt-10 grid gap-8 md:grid-cols-2">
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
        <div className="container-editorial grid gap-16 py-24 md:grid-cols-12">
          <div className="md:col-span-5">
            <div className="eyebrow">Openings</div>
            <h2 className="mt-6 font-serif text-3xl text-navy">
              We review expressions of interest on a rolling basis.
            </h2>
          </div>
          <div className="md:col-span-7">
            <div className="hairline-t border-hairline py-10">
              <p className="text-base leading-relaxed text-muted-foreground">
                There are no formal openings listed at this time. If your experience aligns with
                our investment and transaction advisory practice, send a confidential introduction
                to{" "}
                <a href={`mailto:${CONTACT.email}`} className="text-navy underline-offset-4 hover:underline">
                  {CONTACT.email}
                </a>
                .
              </p>
              <Link
                to="/contact"
                className="mt-8 inline-flex items-center gap-2 bg-navy px-6 py-3.5 text-xs font-medium uppercase tracking-widest text-navy-foreground transition hover:bg-navy/90"
              >
                Get in Touch <ArrowUpRight className="h-4 w-4" />
              </Link>
            </div>
          </div>
        </div>
      </section>
    </>
  );
}
