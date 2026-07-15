type LogoProps = {
  variant?: "dark" | "light";
  showWordmark?: boolean;
  className?: string;
};

export function Logo({ variant = "dark", showWordmark = true, className }: LogoProps) {
  const navy = variant === "dark" ? "#1f3e72" : "#ffffff";
  const grey = variant === "dark" ? "#8a8886" : "#c9c8c6";
  const wordmarkColor = variant === "dark" ? "text-navy" : "text-white";
  const subColor = variant === "dark" ? "text-slate-warm" : "text-white/70";

  return (
    <div className={`inline-flex items-center gap-3 ${className ?? ""}`}>
      <svg viewBox="0 0 60 60" width="36" height="36" aria-hidden="true">
        {/* Shark fin */}
        <path
          d="M8 44 C 18 22, 28 10, 36 6 C 30 20, 26 32, 24 44 Z"
          fill={navy}
        />
        {/* Three rising bars */}
        <rect x="16" y="34" width="7" height="16" fill={grey} />
        <rect x="25" y="28" width="7" height="22" fill={grey} />
        <rect x="34" y="20" width="7" height="30" fill={navy} />
      </svg>
      {showWordmark && (
        <div className="leading-none">
          <div className={`font-serif text-xl font-semibold tracking-tight ${wordmarkColor}`}>
            BluFin
          </div>
          <div className={`mt-0.5 text-[10px] uppercase tracking-[0.22em] ${subColor}`}>
            Capital Advisory
          </div>
        </div>
      )}
    </div>
  );
}
