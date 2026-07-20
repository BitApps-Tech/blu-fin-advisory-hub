import { useState } from "react";
import { toast } from "sonner";
import { leadStore, uid } from "../lib/mock-store";
import { useI18n } from "../i18n";
import { cn } from "../lib/utils";

const SERVICES = [
  "Corporate Finance",
  "Listing Solutions",
  "Transaction Advisory",
  "Private Equity",
] as const;

export function MidContactBanner() {
  const { t } = useI18n();
  const [service, setService] = useState<(typeof SERVICES)[number]>("Corporate Finance");
  const [company, setCompany] = useState("");
  const [email, setEmail] = useState("");
  const [submitting, setSubmitting] = useState(false);

  function submit(e: React.FormEvent) {
    e.preventDefault();
    if (!company.trim()) return;
    if (!email.trim() || !email.includes("@")) return;

    setSubmitting(true);
    const leadId = uid();

    leadStore.add({
      id: leadId,
      name: "Website visitor",
      email: email.trim(),
      company: company.trim(),
      capitalNeeds: "Not specified",
      sector: "Other",
      service,
      message: `Quick inquiry via on-page banner (leadId: ${leadId}).`,
      submittedAt: new Date().toISOString(),
      status: "new",
    });

    setTimeout(() => {
      setSubmitting(false);
      toast.success(t.contact.success);
      setCompany("");
      setEmail("");
      setService("Corporate Finance");
    }, 350);
  }

  const inputClassName = cn(
    "h-10 w-full rounded-md border border-white/25 bg-white/95 px-3 text-sm text-[#1F3E72] outline-none",
    "placeholder:text-[#1F3E72]/60 focus:ring-2 focus:ring-white/35",
  );

  return (
    <section className="bg-[#1F3E72] text-white" aria-label="Contact banner">
      <div className="container-editorial grid gap-10 py-16 md:grid-cols-12 md:items-center">
        <div className="md:col-span-4">
          <div className="eyebrow text-white/70">CONTACT US</div>
          <h2 className="mt-4 font-serif text-3xl md:text-4xl">{t.contact.headline}</h2>
          <p className="mt-4 text-sm leading-relaxed text-white/75">{t.contact.intro}</p>
        </div>

        <form onSubmit={submit} className="md:col-span-8">
          <div className="grid gap-4 md:grid-cols-2">
            <label className="block">
              <span className="sr-only">{t.contact.service}</span>
              <select
                className={inputClassName}
                value={service}
                onChange={(e) => setService(e.target.value as (typeof SERVICES)[number])}
              >
                {SERVICES.map((s) => (
                  <option key={s} value={s}>
                    {t.contact.service}: {s}
                  </option>
                ))}
              </select>
            </label>

            <label className="block">
              <span className="sr-only">{t.contact.company}</span>
              <input
                className={inputClassName}
                value={company}
                onChange={(e) => setCompany(e.target.value)}
                placeholder={t.contact.company}
                autoComplete="organization"
              />
            </label>
          </div>

          <div className="mt-4 flex flex-col gap-4 md:flex-row md:items-center">
            <label className="flex-1">
              <span className="sr-only">{t.contact.email}</span>
              <input
                type="email"
                className={inputClassName}
                value={email}
                onChange={(e) => setEmail(e.target.value)}
                placeholder={t.contact.email}
                autoComplete="email"
              />
            </label>

            <button
              type="submit"
              disabled={submitting}
              className={cn(
                "h-10 rounded-md px-5 text-xs uppercase tracking-widest transition",
                "bg-[#8A8886] text-[#1F3E72] hover:bg-[#807E7A] disabled:opacity-50 disabled:cursor-not-allowed",
              )}
            >
              {submitting ? t.common.sending : t.common.submitInquiry}
            </button>
          </div>
        </form>
      </div>
    </section>
  );
}
