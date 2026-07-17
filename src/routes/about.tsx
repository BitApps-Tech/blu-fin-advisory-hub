import { createFileRoute, Link } from "@tanstack/react-router";
import { ArrowUpRight } from "lucide-react";
import { COMPANY } from "../lib/company";
import { CONTACT } from "../lib/contact";
import photoEcma from "../assets/photo-ecma-license.png";
import photoTeam from "../assets/photo-team.png";

export const Route = createFileRoute("/about")({
  head: () => ({
    meta: [
      { title: "About Us — BluFin Capital Advisory" },
      {
        name: "description",
        content: COMPANY.mission,
      },
      { property: "og:title", content: "About Us — BluFin Capital Advisory" },
      { property: "og:url", content: "/about" },
    ],
    links: [{ rel: "canonical", href: "/about" }],
  }),
  component: About,
});

const LEGAL_DETAILS = [
  { label: "Legal Name", value: COMPANY.legalName },
  { label: "Trading Name", value: COMPANY.tradingName },
  { label: "Legal Form", value: COMPANY.legalForm },
  { label: "Commercial Registration No.", value: COMPANY.commercialRegistrationNo },
  { label: "Tax Identification Number (TIN)", value: COMPANY.tin },
  { label: "Registered Address", value: COMPANY.registeredAddress },
  { label: "Business License", value: COMPANY.businessLicense },
  { label: "Office", value: CONTACT.addressShort },
] as const;

function About() {
  return (
    <>
      <section className="hairline-b bg-background">
        <div className="container-editorial py-20 md:py-28">
          <div className="eyebrow">About Us</div>
          <h1 className="mt-6 max-w-4xl font-serif text-5xl text-navy md:text-6xl">
            Ethiopia's capital market, advised with integrity and objectivity.
          </h1>
          <p className="mt-8 max-w-3xl text-lg leading-relaxed text-muted-foreground">
            {COMPANY.status}
          </p>
        </div>
      </section>

      <section className="hairline-b bg-background">
        <div className="container-editorial grid gap-0 md:grid-cols-2">
          <img
            src={photoTeam}
            alt="BluFin advisory team"
            className="aspect-[4/3] w-full object-cover object-center"
          />
          <img
            src={photoEcma}
            alt="BluFin at the Ethiopian Capital Market Authority"
            className="aspect-[4/3] w-full object-cover object-center"
          />
        </div>
      </section>

      <section className="hairline-b bg-panel">
        <div className="container-editorial py-20 md:py-24">
          <div className="eyebrow">Corporate & legal details</div>
          <h2 className="mt-4 max-w-2xl font-serif text-3xl text-navy md:text-4xl">
            Formal company profile and legal status
          </h2>
          <dl className="mt-12 grid gap-0 hairline-t md:grid-cols-2">
            {LEGAL_DETAILS.map((row) => (
              <div key={row.label} className="hairline-b grid gap-1 py-5 md:grid-cols-2 md:gap-6 md:pr-8">
                <dt className="text-xs uppercase tracking-widest text-slate-warm">{row.label}</dt>
                <dd className="text-sm text-navy md:text-base">{row.value}</dd>
              </div>
            ))}
          </dl>
        </div>
      </section>

      <section className="hairline-b bg-background">
        <div className="container-editorial grid gap-16 py-24 md:grid-cols-12">
          <div className="md:col-span-4">
            <div className="eyebrow">Ownership</div>
            <h2 className="mt-6 font-serif text-4xl text-navy">
              Founded by Ethiopian finance professionals.
            </h2>
          </div>
          <div className="space-y-6 md:col-span-8">
            <p className="text-base leading-relaxed text-muted-foreground">{COMPANY.ownership}</p>
            <p className="text-base leading-relaxed text-muted-foreground">{COMPANY.foundingPhilosophy}</p>
          </div>
        </div>
      </section>

      <section className="hairline-b bg-background">
        <div className="container-editorial grid gap-12 py-24 md:grid-cols-2">
          <div>
            <div className="eyebrow">Vision</div>
            <h2 className="mt-6 font-serif text-3xl text-navy md:text-4xl">{COMPANY.vision}</h2>
            <p className="mt-6 text-sm leading-relaxed text-muted-foreground">{COMPANY.visionElaboration}</p>
          </div>
          <div>
            <div className="eyebrow">Mission</div>
            <h2 className="mt-6 font-serif text-3xl text-navy md:text-4xl">{COMPANY.mission}</h2>
            <p className="mt-6 text-sm leading-relaxed text-muted-foreground">{COMPANY.missionElaboration}</p>
          </div>
        </div>
      </section>

      <section className="bg-background">
        <div className="container-editorial py-24">
          <div className="eyebrow">Core values</div>
          <h2 className="mt-4 max-w-3xl font-serif text-4xl text-navy">
            Guiding principles embedded in our culture.
          </h2>
          <div className="hairline-t mt-12 grid grid-cols-1 md:grid-cols-2">
            {COMPANY.values.map((v, i) => (
              <div
                key={v.title}
                className={`hairline-b p-8 md:p-10 ${i % 2 === 0 ? "md:hairline-r" : ""}`}
              >
                <h3 className="font-serif text-2xl text-navy">{v.title}</h3>
                <p className="mt-4 text-sm leading-relaxed text-muted-foreground">{v.body}</p>
              </div>
            ))}
          </div>
          <Link
            to="/contact"
            className="mt-12 inline-flex items-center gap-2 text-xs uppercase tracking-widest text-navy hover:underline"
          >
            Speak with a partner <ArrowUpRight className="h-3.5 w-3.5" />
          </Link>
        </div>
      </section>
    </>
  );
}
