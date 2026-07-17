import { useI18n } from "../i18n";

type Props = {
  practice?: string;
  className?: string;
};

export function AtAGlance({ practice, className = "" }: Props) {
  const { t } = useI18n();
  const items = [
    { label: t.home.glanceLicense, value: t.company.businessLicense },
    { label: t.home.glanceLegal, value: t.company.legalForm },
    { label: t.home.glanceHq, value: t.company.registeredAddress },
    { label: "Practice", value: practice ?? t.whatWeDo.eyebrow },
  ];
  return (
    <aside className={`hairline-l md:pl-10 ${className}`}>
      <div className="eyebrow">{t.common.atAGlance}</div>
      <dl className="mt-6 space-y-6">
        {items.map((i) => (
          <div key={i.label}>
            <dt className="text-xs uppercase tracking-widest text-slate-warm">{i.label}</dt>
            <dd className="mt-1 font-serif text-xl text-navy">{i.value}</dd>
          </div>
        ))}
      </dl>
    </aside>
  );
}
