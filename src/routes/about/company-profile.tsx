import { createFileRoute } from "@tanstack/react-router";
import { useI18n } from "../../i18n";
import { COMPANY } from "../../lib/company";
import { CONTACT } from "../../lib/contact";
import { BOARD_COMPOSITION, CORE_SERVICES, STRUCTURE_INTRO } from "../../lib/organogram";
import { MidContactBanner } from "../../components/MidContactBanner";
import { AboutPageNav } from "../../components/AboutPageNav";
import { pageLinks, pageOgUrl } from "../../lib/seo";

export const Route = createFileRoute("/about/company-profile")({
  head: () => ({
    meta: [
      { title: "Company Profile — BluFin Capital Advisory" },
      {
        name: "description",
        content: "Legal details, ownership, services, and organizational structure of BluFin Capital Advisory PLC.",
      },
      { property: "og:title", content: "Company Profile — BluFin Capital Advisory" },
      pageOgUrl("/about/company-profile"),
    ],
    links: pageLinks("/about/company-profile"),
  }),
  component: CompanyProfile,
});

function CompanyProfile() {
  const { t } = useI18n();
  const c = t.company;
  const labels = t.about.legalLabels;

  const legalDetails = [
    { label: labels.legalName, value: COMPANY.legalName },
    { label: labels.tradingName, value: COMPANY.tradingName },
    { label: labels.legalForm, value: c.legalForm },
    { label: labels.regNo, value: COMPANY.commercialRegistrationNo },
    { label: labels.tin, value: COMPANY.tin },
    { label: labels.registeredAddress, value: c.registeredAddress },
    { label: labels.businessLicense, value: c.businessLicense },
    { label: labels.office, value: CONTACT.addressShort },
  ];

  return (
    <>
      <section className="hairline-b bg-background">
        <div className="container-editorial py-20 md:py-28">
          <div className="eyebrow">{t.about.eyebrow}</div>
          <h1 className="mt-4 font-serif text-5xl text-navy md:text-6xl">
            {t.about.companyProfileTab}
          </h1>
          <p className="mt-6 max-w-2xl text-lg leading-relaxed text-muted-foreground">
            {t.about.companyProfileIntro}
          </p>
          <AboutPageNav />
        </div>
      </section>

      <section className="hairline-b bg-panel">
        <div className="container-editorial py-20 md:py-24">
          <div className="eyebrow">{t.about.servicesEyebrow}</div>
          <h2 className="mt-4 max-w-2xl font-serif text-3xl text-navy md:text-4xl">
            {t.about.servicesTitle}
          </h2>
          <ol className="mt-12 hairline-t">
            {CORE_SERVICES.map((service, i) => (
              <li
                key={service}
                className="hairline-b grid gap-2 py-6 md:grid-cols-[4rem_1fr] md:items-baseline md:gap-8"
              >
                <span className="font-serif text-2xl text-navy/40">
                  {String(i + 1).padStart(2, "0")}
                </span>
                <span className="text-lg text-navy md:text-xl">{service}</span>
              </li>
            ))}
          </ol>
        </div>
      </section>

      <section className="hairline-b bg-background">
        <div className="container-editorial py-20 md:py-24">
          <div className="eyebrow">{t.about.legalEyebrow}</div>
          <h2 className="mt-4 max-w-2xl font-serif text-3xl text-navy md:text-4xl">
            {t.about.legalTitle}
          </h2>
          <dl className="mt-12 grid gap-0 hairline-t md:grid-cols-2">
            {legalDetails.map((row) => (
              <div
                key={row.label}
                className="hairline-b grid gap-1 py-5 md:grid-cols-2 md:gap-6 md:pr-8"
              >
                <dt className="text-xs uppercase tracking-widest text-slate-warm">{row.label}</dt>
                <dd className="text-sm text-navy md:text-base">{row.value}</dd>
              </div>
            ))}
          </dl>
        </div>
      </section>

      <MidContactBanner />

      <section className="hairline-b bg-background">
        <div className="container-editorial grid gap-16 py-24 md:grid-cols-12">
          <div className="md:col-span-4">
            <div className="eyebrow">{t.about.ownershipEyebrow}</div>
            <h2 className="mt-6 font-serif text-4xl text-navy">{t.about.ownershipTitle}</h2>
          </div>
          <div className="space-y-6 md:col-span-8">
            <p className="text-base leading-relaxed text-muted-foreground">{c.ownership}</p>
            <p className="text-base leading-relaxed text-muted-foreground">
              {c.foundingPhilosophy}
            </p>
          </div>
        </div>
      </section>

      <section className="bg-panel">
        <div className="container-editorial grid gap-12 py-20 md:grid-cols-12 md:py-24">
          <div className="md:col-span-4">
            <div className="eyebrow">{t.about.structureEyebrow}</div>
            <h2 className="mt-4 font-serif text-3xl text-navy md:text-4xl">
              {t.about.structureTitle}
            </h2>
          </div>
          <div className="space-y-8 md:col-span-8">
            <p className="text-base leading-relaxed text-muted-foreground">{STRUCTURE_INTRO}</p>
            <div>
              <h3 className="text-xs font-semibold uppercase tracking-[0.18em] text-navy">
                {t.about.boardCompositionTitle}
              </h3>
              <p className="mt-4 border-t border-hairline pt-4 text-sm leading-relaxed text-foreground/85">
                {BOARD_COMPOSITION}
              </p>
            </div>
          </div>
        </div>
      </section>
    </>
  );
}
