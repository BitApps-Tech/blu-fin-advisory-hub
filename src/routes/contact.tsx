import { createFileRoute } from "@tanstack/react-router";
import { useState } from "react";
import { z } from "zod";
import { toast } from "sonner";
import { leadStore, uid } from "../lib/mock-store";
import { CONTACT } from "../lib/contact";
import { SocialLinks } from "../components/SocialLinks";
import { useI18n } from "../i18n";
import { pageLinks, pageOgUrl } from "../lib/seo";

export const Route = createFileRoute("/contact")({
  head: () => ({
    meta: [
      { title: "Contact — BluFin Capital Advisory" },
      { name: "description", content: "Request a confidential consultation with a senior partner at BluFin Capital Advisory." },
      { property: "og:title", content: "Contact — BluFin Capital Advisory" },
      pageOgUrl("/contact"),
    ],
    links: pageLinks("/contact"),
  }),
  component: Contact,
});

const schema = z.object({
  name: z.string().trim().min(2).max(100),
  email: z.string().trim().email().max(255),
  company: z.string().trim().min(1).max(120),
  capitalNeeds: z.string().min(1),
  sector: z.string().min(1),
  service: z.string().min(1),
  message: z.string().trim().min(10).max(2000),
});

const CAPITAL = ["Under $1M", "$1M – $10M", "$10M – $50M", "$50M – $250M", "$250M+"];
const SECTORS = ["Retail", "Logistics", "Tech", "Manufacturing", "Financial Services", "Energy", "Other"];
const SERVICES = ["Corporate Finance", "Listing Solutions", "Transaction Advisory", "Private Equity"];

function Contact() {
  const { t } = useI18n();
  const [form, setForm] = useState({ name: "", email: "", company: "", capitalNeeds: "", sector: "", service: "", message: "" });
  const [errors, setErrors] = useState<Record<string, string>>({});
  const [submitting, setSubmitting] = useState(false);

  function set<K extends keyof typeof form>(k: K, v: string) {
    setForm((f) => ({ ...f, [k]: v }));
  }

  function submit(e: React.FormEvent) {
    e.preventDefault();
    const parsed = schema.safeParse(form);
    if (!parsed.success) {
      const errs: Record<string, string> = {};
      parsed.error.issues.forEach((i) => (errs[i.path[0] as string] = i.message));
      setErrors(errs);
      return;
    }
    setErrors({});
    setSubmitting(true);
    leadStore.add({
      id: uid(),
      ...parsed.data,
      submittedAt: new Date().toISOString(),
      status: "new",
    });
    setTimeout(() => {
      setSubmitting(false);
      toast.success(t.contact.success);
      setForm({ name: "", email: "", company: "", capitalNeeds: "", sector: "", service: "", message: "" });
    }, 500);
  }

  return (
    <>
      <section className="hairline-b bg-background">
        <div className="container-editorial grid gap-16 py-20 md:grid-cols-12 md:py-24">
          <div className="md:col-span-5">
            <div className="eyebrow">{t.contact.eyebrow}</div>
            <h1 className="mt-6 font-serif text-5xl leading-tight text-navy md:text-6xl">{t.contact.headline}</h1>
            <p className="mt-6 text-lg text-muted-foreground">{t.contact.intro}</p>

            <dl className="hairline-t mt-12 space-y-6 pt-10">
              <div>
                <dt className="eyebrow">{t.contact.office}</dt>
                <dd className="mt-2 space-y-1 text-base text-navy">
                  {CONTACT.addressLines.map((line) => (
                    <div key={line}>{line}</div>
                  ))}
                </dd>
              </div>
              <div>
                <dt className="eyebrow">{t.contact.advisoryDesk}</dt>
                <dd className="mt-2 text-base text-navy">
                  <a href={`mailto:${CONTACT.email}`} className="hover:underline">{CONTACT.email}</a>
                </dd>
              </div>
              <div>
                <dt className="eyebrow">{t.contact.telephone}</dt>
                <dd className="mt-2 text-base text-navy">
                  <a href={CONTACT.phoneHref} className="hover:underline">{CONTACT.phoneDisplay}</a>
                </dd>
              </div>
              <div>
                <dt className="eyebrow">{t.contact.social}</dt>
                <dd className="mt-3 text-navy">
                  <SocialLinks iconClassName="h-5 w-5" />
                </dd>
              </div>
            </dl>
          </div>

          <form onSubmit={submit} className="hairline-l md:col-span-7 md:pl-14">
            <div className="grid gap-6">
              <Field label={t.contact.fullName} error={errors.name}>
                <input className={inp} value={form.name} onChange={(e) => set("name", e.target.value)} />
              </Field>
              <div className="grid gap-6 md:grid-cols-2">
                <Field label={t.contact.email} error={errors.email}>
                  <input type="email" className={inp} value={form.email} onChange={(e) => set("email", e.target.value)} />
                </Field>
                <Field label={t.contact.company} error={errors.company}>
                  <input className={inp} value={form.company} onChange={(e) => set("company", e.target.value)} />
                </Field>
              </div>
              <div className="grid gap-6 md:grid-cols-3">
                <Field label={t.contact.capitalNeeds} error={errors.capitalNeeds}>
                  <select className={inp} value={form.capitalNeeds} onChange={(e) => set("capitalNeeds", e.target.value)}>
                    <option value="">{t.contact.select}</option>
                    {CAPITAL.map((c) => <option key={c}>{c}</option>)}
                  </select>
                </Field>
                <Field label={t.contact.sector} error={errors.sector}>
                  <select className={inp} value={form.sector} onChange={(e) => set("sector", e.target.value)}>
                    <option value="">{t.contact.select}</option>
                    {SECTORS.map((c) => <option key={c}>{c}</option>)}
                  </select>
                </Field>
                <Field label={t.contact.service} error={errors.service}>
                  <select className={inp} value={form.service} onChange={(e) => set("service", e.target.value)}>
                    <option value="">{t.contact.select}</option>
                    {SERVICES.map((c) => <option key={c}>{c}</option>)}
                  </select>
                </Field>
              </div>
              <Field label={t.contact.message} error={errors.message}>
                <textarea rows={5} className={inp} value={form.message} onChange={(e) => set("message", e.target.value)} />
              </Field>

              <button
                type="submit"
                disabled={submitting}
                className="mt-2 inline-flex items-center justify-center bg-navy px-6 py-4 text-xs uppercase tracking-widest text-navy-foreground transition hover:bg-navy/90 disabled:opacity-50"
              >
                {submitting ? t.common.sending : t.common.submitInquiry}
              </button>

              <p className="text-xs text-slate-warm">{t.contact.consent}</p>
            </div>
          </form>
        </div>
      </section>
    </>
  );
}

const inp = "w-full border border-hairline bg-background py-3 px-3 text-sm outline-none transition focus:border-navy";

function Field({ label, error, children }: { label: string; error?: string; children: React.ReactNode }) {
  return (
    <label className="block">
      <span className="eyebrow mb-2 block">{label}</span>
      {children}
      {error && <span className="mt-1 block text-xs text-destructive">{error}</span>}
    </label>
  );
}
