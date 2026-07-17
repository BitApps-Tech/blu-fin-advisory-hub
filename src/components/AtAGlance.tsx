import { COMPANY } from "../lib/company";

type Item = { label: string; value: string };

type Props = {
  practice?: string;
  className?: string;
};

export function AtAGlance({ practice = "Investment & Transaction Advisory", className = "" }: Props) {
  const items: Item[] = [
    { label: "License", value: "Securities Investment Advisory" },
    { label: "Legal form", value: COMPANY.legalForm },
    { label: "Headquarters", value: COMPANY.registeredAddress },
    { label: "Practice Focus", value: practice },
  ];
  return (
    <aside className={`hairline-l md:pl-10 ${className}`}>
      <div className="eyebrow">At a glance</div>
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
