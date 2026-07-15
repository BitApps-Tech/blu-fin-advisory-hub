import { createFileRoute } from "@tanstack/react-router";
import { useState } from "react";
import { z } from "zod";
import { toast } from "sonner";
import { leadStore, uid } from "../lib/mock-store";

export const Route = createFileRoute("/contact")({
  head: () => ({
    meta: [
      { title: "Contact — BluFin Capital Advisory" },
      { name: "description", content: "Request a confidential consultation with a senior partner at BluFin Capital Advisory." },
      { property: "og:title", content: "Contact — BluFin Capital Advisory" },
      { property: "og:description", content: "Request a confidential consultation." },
      { property: "og:url", content: "/contact" },
    ],
    links: [{ rel: "canonical", href: "/contact" }],
  }),
  component: Contact,
});

const schema = z.object({
  name: z.string().trim().min(2, "Name is required").max(100),
  email: z.string().trim().email("Valid email required").max(255),
  company: z.string().trim().min(1, "Company is required").max(120),
  capitalNeeds: z.string().min(1, "Please select"),
  sector: z.string().min(1, "Please select"),
  service: z.string().min(1, "Please select"),
  message: z.string().trim().min(10, "Please provide a brief description").max(2000),
});

const CAPITAL = ["Under $1M", "$1M – $10M", "$10M – $50M", "$50M – $250M", "$250M+"];
const SECTORS = ["Retail", "Logistics", "Tech", "Manufacturing", "Financial Services", "Energy", "Other"];
const SERVICES = ["Corporate Finance", "Listing Solutions", "Transaction Advisory", "Private Equity"];

function Contact() {
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
      toast.success("Your inquiry has been received. A senior partner will be in touch.");
      setForm({ name: "", email: "", company: "", capitalNeeds: "", sector: "", service: "", message: "" });
    }, 500);
  }

  return (
    <>
      <section className="hairline-b bg-background">
        <div className="container-editorial grid gap-16 py-20 md:grid-cols-12 md:py-24">
          <div className="md:col-span-5">
            <div className="eyebrow">Contact</div>
            <h1 className="mt-6 font-serif text-5xl leading-tight text-navy md:text-6xl">
              Confidential consultation with our senior team.
            </h1>
            <p className="mt-6 text-lg text-muted-foreground">
              Submissions are reviewed personally by a partner. Response typically within one business day.
            </p>

            <dl className="hairline-t mt-12 space-y-6 pt-10">
              <div>
                <dt className="eyebrow">Office</dt>
                <dd className="mt-2 text-base text-navy">Bole Road, Addis Ababa, Ethiopia</dd>
              </div>
              <div>
                <dt className="eyebrow">Advisory desk</dt>
                <dd className="mt-2 text-base text-navy">advisory@blufincapital.et</dd>
              </div>
              <div>
                <dt className="eyebrow">Telephone</dt>
                <dd className="mt-2 text-base text-navy">+251 11 000 0000</dd>
              </div>
            </dl>
          </div>

          <form onSubmit={submit} className="hairline-l md:col-span-7 md:pl-14">
            <div className="grid gap-6">
              <Field label="Full name" error={errors.name}>
                <input className={inp} value={form.name} onChange={(e) => set("name", e.target.value)} />
              </Field>
              <div className="grid gap-6 md:grid-cols-2">
                <Field label="Email" error={errors.email}>
                  <input type="email" className={inp} value={form.email} onChange={(e) => set("email", e.target.value)} />
                </Field>
                <Field label="Company" error={errors.company}>
                  <input className={inp} value={form.company} onChange={(e) => set("company", e.target.value)} />
                </Field>
              </div>
              <div className="grid gap-6 md:grid-cols-3">
                <Field label="Estimated capital needs" error={errors.capitalNeeds}>
                  <select className={inp} value={form.capitalNeeds} onChange={(e) => set("capitalNeeds", e.target.value)}>
                    <option value="">Select…</option>
                    {CAPITAL.map((c) => <option key={c}>{c}</option>)}
                  </select>
                </Field>
                <Field label="Sector" error={errors.sector}>
                  <select className={inp} value={form.sector} onChange={(e) => set("sector", e.target.value)}>
                    <option value="">Select…</option>
                    {SECTORS.map((c) => <option key={c}>{c}</option>)}
                  </select>
                </Field>
                <Field label="Service requested" error={errors.service}>
                  <select className={inp} value={form.service} onChange={(e) => set("service", e.target.value)}>
                    <option value="">Select…</option>
                    {SERVICES.map((c) => <option key={c}>{c}</option>)}
                  </select>
                </Field>
              </div>
              <Field label="How can we help?" error={errors.message}>
                <textarea rows={5} className={inp} value={form.message} onChange={(e) => set("message", e.target.value)} />
              </Field>

              <button
                type="submit"
                disabled={submitting}
                className="mt-2 inline-flex items-center justify-center bg-navy px-6 py-4 text-xs uppercase tracking-widest text-navy-foreground transition hover:bg-navy/90 disabled:opacity-50"
              >
                {submitting ? "Sending…" : "Submit Inquiry"}
              </button>

              <p className="text-xs text-slate-warm">
                By submitting, you consent to BluFin storing your inquiry to respond. Information is treated as strictly confidential.
              </p>
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
