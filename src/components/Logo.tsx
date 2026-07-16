import darkLogo from "../assets/blufin-logo.png.asset.json";
import lightLogo from "../assets/blufin-logo-white.png.asset.json";

type LogoProps = {
  variant?: "dark" | "light";
  showWordmark?: boolean;
  className?: string;
};

export function Logo({ variant = "dark", className }: LogoProps) {
  const src = variant === "dark" ? darkLogo.url : lightLogo.url;
  return (
    <img
      src={src}
      alt="BluFin Capital Advisory"
      className={`h-24 w-auto object-contain md:h-32 ${className ?? ""}`}
    />
  );
}
