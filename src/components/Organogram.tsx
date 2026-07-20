import { useState } from "react";
import { Link } from "@tanstack/react-router";
import { ArrowUpRight } from "lucide-react";
import { cn } from "../lib/utils";
import { ORG_NODES, type OrgNodeId } from "../lib/organogram";
import { useI18n } from "../i18n";

/** Parent chain for hover highlighting (node → … → board). */
const ORG_PARENT: Partial<Record<OrgNodeId, OrgNodeId>> = {
  audit: "board",
  complianceEthics: "board",
  remuneration: "board",
  ceo: "board",
  cco: "board",
  cro: "ceo",
  rm: "ceo",
  ccs: "ceo",
  investment: "cro",
  transaction: "cro",
  infoSystem: "ccs",
  hr: "ccs",
  legal: "ccs",
  accounting: "ccs",
};

function pathToRoot(id: OrgNodeId): Set<OrgNodeId> {
  const path = new Set<OrgNodeId>([id]);
  let current: OrgNodeId | undefined = id;
  while (current && ORG_PARENT[current]) {
    current = ORG_PARENT[current];
    if (current) path.add(current);
  }
  return path;
}

function OrgBox({
  id,
  active,
  hovered,
  dimmed,
  onSelect,
  onHover,
  className,
  dashed,
  compact,
}: {
  id: OrgNodeId;
  active: boolean;
  hovered: boolean;
  dimmed: boolean;
  onSelect: (id: OrgNodeId) => void;
  onHover: (id: OrgNodeId | null) => void;
  className?: string;
  dashed?: boolean;
  compact?: boolean;
}) {
  const node = ORG_NODES[id];
  const lit = active || hovered;

  return (
    <button
      type="button"
      onClick={() => onSelect(id)}
      onMouseEnter={() => onHover(id)}
      onMouseLeave={() => onHover(null)}
      onFocus={() => onHover(id)}
      onBlur={() => onHover(null)}
      aria-pressed={active}
      className={cn(
        "group/org relative w-full border bg-background text-left outline-none transition-all duration-300 ease-out",
        compact ? "px-3 py-2.5" : "px-4 py-3",
        dashed ? "border-dashed" : "border-solid",
        lit
          ? "z-10 -translate-y-0.5 border-navy bg-navy text-navy-foreground shadow-[0_14px_28px_-18px_oklch(0.34_0.09_262_/_0.65)]"
          : dashed
            ? "border-navy/45 hover:border-navy hover:bg-panel"
            : "border-hairline hover:border-navy/60 hover:bg-panel hover:-translate-y-0.5 hover:shadow-[0_10px_24px_-18px_oklch(0.34_0.09_262_/_0.45)]",
        dimmed && !lit && "opacity-35",
        !dimmed && !lit && "opacity-100",
        className,
      )}
    >
      {/* Hover accent bar */}
      <span
        className={cn(
          "pointer-events-none absolute inset-x-0 top-0 h-0.5 origin-left scale-x-0 bg-[#1F3E72] transition-transform duration-300",
          lit ? "scale-x-100 bg-white/80" : "group-hover/org:scale-x-100",
        )}
        aria-hidden
      />

      {!compact && (
        <span
          className={cn(
            "block text-[9px] font-semibold uppercase tracking-[0.14em] transition-colors duration-300",
            lit ? "text-white/70" : "text-slate-warm group-hover/org:text-navy/70",
          )}
        >
          {node.subtitle}
        </span>
      )}
      <span
        className={cn(
          "block font-serif leading-snug transition-colors duration-300",
          compact ? "text-sm" : "mt-1 text-base md:text-lg",
          lit ? "text-white" : "text-navy",
        )}
      >
        {node.label}
      </span>
    </button>
  );
}

function HRail({ className, lit }: { className?: string; lit?: boolean }) {
  return (
    <div
      className={cn(
        "h-px transition-colors duration-300",
        lit ? "bg-navy" : "bg-hairline",
        className,
      )}
      aria-hidden
    />
  );
}

function VRail({ className, lit }: { className?: string; lit?: boolean }) {
  return (
    <div
      className={cn(
        "w-px transition-colors duration-300",
        lit ? "bg-navy" : "bg-hairline",
        className,
      )}
      aria-hidden
    />
  );
}

export function Organogram() {
  const { t } = useI18n();
  const [activeId, setActiveId] = useState<OrgNodeId>("board");
  const [hoveredId, setHoveredId] = useState<OrgNodeId | null>(null);
  const active = ORG_NODES[activeId];
  const focusId = hoveredId ?? activeId;
  const highlight = pathToRoot(focusId);
  const dimming = hoveredId !== null;

  const isLit = (id: OrgNodeId) => highlight.has(id);
  const boxProps = (id: OrgNodeId) => ({
    id,
    active: activeId === id,
    hovered: hoveredId === id || (hoveredId !== null && highlight.has(id) && hoveredId !== id),
    dimmed: dimming && !highlight.has(id),
    onSelect: setActiveId,
    onHover: setHoveredId,
  });

  return (
    <div className="mt-12">
      <p className="mb-8 max-w-2xl text-sm text-muted-foreground">{t.about.organogramHint}</p>

      <div className="overflow-x-auto pb-2">
        <div className="mx-auto min-w-[58rem] px-2">
          <div className="flex justify-center">
            <OrgBox {...boxProps("board")} className="max-w-[18rem]" />
          </div>

          <div className="mx-auto flex h-8 justify-center">
            <VRail className="h-full" lit={isLit("board") && (isLit("ceo") || isLit("cco") || isLit("audit"))} />
          </div>

          <div className="relative grid grid-cols-[14rem_1fr_14rem] gap-4">
            <HRail
              className="absolute left-[7rem] right-[7rem] top-0"
              lit={dimming && (isLit("ceo") || isLit("cco") || isLit("audit"))}
            />
            <div className="absolute left-[7rem] top-0 h-6">
              <VRail className="h-full" lit={isLit("audit") || isLit("complianceEthics") || isLit("remuneration")} />
            </div>
            <div className="absolute left-1/2 top-0 h-6 -translate-x-1/2">
              <VRail className="h-full" lit={isLit("ceo")} />
            </div>
            <div className="absolute right-[7rem] top-0 h-6">
              <VRail className="h-full" lit={isLit("cco")} />
            </div>

            <div className="flex flex-col items-center gap-2 pt-6">
              <OrgBox {...boxProps("audit")} compact className="max-w-none" />
              <OrgBox {...boxProps("complianceEthics")} compact className="max-w-none" />
              <OrgBox {...boxProps("remuneration")} compact className="max-w-none" />
            </div>

            <div className="flex flex-col items-center pt-6">
              <OrgBox {...boxProps("ceo")} className="max-w-[16rem]" />

              <p
                className={cn(
                  "mt-2 max-w-[16rem] text-center text-[9px] uppercase tracking-[0.12em] transition-colors duration-300",
                  hoveredId === "cco" || hoveredId === "ceo" ? "text-navy" : "text-slate-warm",
                )}
              >
                {t.about.organogramAdminLine}
              </p>

              <div className="flex h-8 justify-center">
                <VRail className="h-full" lit={isLit("ceo") && (isLit("cro") || isLit("rm") || isLit("ccs"))} />
              </div>

              <div className="relative grid w-full grid-cols-3 gap-3">
                <HRail
                  className="absolute left-[16%] right-[16%] top-0"
                  lit={isLit("cro") || isLit("rm") || isLit("ccs")}
                />
                <div className="absolute left-[16%] top-0 h-5">
                  <VRail className="h-full" lit={isLit("cro")} />
                </div>
                <div className="absolute left-1/2 top-0 h-5 -translate-x-1/2">
                  <VRail className="h-full" lit={isLit("rm")} />
                </div>
                <div className="absolute right-[16%] top-0 h-5">
                  <VRail className="h-full" lit={isLit("ccs")} />
                </div>

                <div className="flex flex-col items-center gap-2 pt-5">
                  <OrgBox {...boxProps("cro")} compact className="max-w-none" />
                  <div className="flex h-5 justify-center">
                    <VRail className="h-full" lit={isLit("investment") || isLit("transaction")} />
                  </div>
                  <div className="grid w-full gap-2">
                    <OrgBox {...boxProps("investment")} compact className="max-w-none" />
                    <OrgBox {...boxProps("transaction")} compact className="max-w-none" />
                  </div>
                </div>

                <div className="flex flex-col items-center pt-5">
                  <OrgBox {...boxProps("rm")} compact className="max-w-none" />
                </div>

                <div className="flex flex-col items-center gap-2 pt-5">
                  <OrgBox {...boxProps("ccs")} compact className="max-w-none" />
                  <div className="flex h-5 justify-center">
                    <VRail
                      className="h-full"
                      lit={isLit("infoSystem") || isLit("hr") || isLit("legal") || isLit("accounting")}
                    />
                  </div>
                  <div className="grid w-full gap-2">
                    <OrgBox {...boxProps("infoSystem")} compact className="max-w-none" />
                    <OrgBox {...boxProps("hr")} compact className="max-w-none" />
                    <OrgBox {...boxProps("legal")} compact className="max-w-none" />
                    <OrgBox {...boxProps("accounting")} compact className="max-w-none" />
                  </div>
                </div>
              </div>
            </div>

            <div className="flex flex-col items-center pt-6">
              <OrgBox {...boxProps("cco")} dashed className="max-w-none" />
              <p
                className={cn(
                  "mt-3 max-w-[13rem] text-center text-[9px] uppercase tracking-[0.12em] transition-colors duration-300",
                  hoveredId === "cco" ? "text-navy" : "text-slate-warm",
                )}
              >
                {t.about.organogramIndependence}
              </p>
            </div>
          </div>
        </div>
      </div>

      {/* Hover preview strip */}
      {hoveredId && hoveredId !== activeId && (
        <div className="mt-8 border border-hairline bg-panel px-5 py-4 transition-all duration-300 animate-in fade-in-0 slide-in-from-bottom-1">
          <p className="text-[10px] font-semibold uppercase tracking-[0.16em] text-slate-warm">
            {ORG_NODES[hoveredId].subtitle}
          </p>
          <p className="mt-1 font-serif text-xl text-navy">{ORG_NODES[hoveredId].label}</p>
          <p className="mt-2 line-clamp-2 text-sm text-muted-foreground">
            {ORG_NODES[hoveredId].description}
          </p>
        </div>
      )}

      <div
        key={active.id}
        className="mt-12 animate-in fade-in-0 slide-in-from-bottom-2 border-t border-hairline duration-500 fill-mode-both"
      >
        <div className="grid gap-8 py-10 md:grid-cols-12">
          <div className="md:col-span-4">
            <div className="eyebrow">{active.subtitle}</div>
            <h3 className="mt-3 font-serif text-3xl text-navy">{active.label}</h3>
            {active.teamTo?.to === "/about/team/$memberId" ? (
              <Link
                to="/about/team/$memberId"
                params={active.teamTo.params}
                className="mt-6 inline-flex items-center gap-2 text-xs font-semibold uppercase tracking-[0.16em] text-navy transition-opacity hover:opacity-70"
              >
                {t.about.organogramViewPeople} <ArrowUpRight className="h-3.5 w-3.5" />
              </Link>
            ) : active.teamTo ? (
              <Link
                to={active.teamTo.to}
                className="mt-6 inline-flex items-center gap-2 text-xs font-semibold uppercase tracking-[0.16em] text-navy transition-opacity hover:opacity-70"
              >
                {t.about.organogramViewPeople} <ArrowUpRight className="h-3.5 w-3.5" />
              </Link>
            ) : null}
          </div>
          <div className="md:col-span-8">
            <p className="text-base leading-relaxed text-muted-foreground">{active.description}</p>
            {active.note && (
              <p className="mt-4 border-l-2 border-navy/30 pl-4 text-sm leading-relaxed text-navy/90">
                {active.note}
              </p>
            )}
            <ul className="mt-6 space-y-3 border-t border-hairline pt-6">
              {active.responsibilities.map((item) => (
                <li key={item} className="flex gap-3 text-sm leading-relaxed text-foreground/85">
                  <span className="mt-2 h-1 w-1 shrink-0 rounded-full bg-navy" aria-hidden />
                  {item}
                </li>
              ))}
            </ul>
          </div>
        </div>
      </div>
    </div>
  );
}
