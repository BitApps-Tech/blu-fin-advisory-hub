import darkLogo from "../assets/blufin-logo.png";
import { cn } from "../lib/utils";

type LogoProps = {
  variant?: "dark" | "light";
  showWordmark?: boolean;
  className?: string;
};

export function Logo({ variant = "dark", className }: LogoProps) {
  return (
    <img
      src={darkLogo}
      alt="BluFin Capital Advisory"
      className={cn(
        "h-24 w-auto object-contain md:h-32",
        variant === "light" && "brightness-0 invert",
        className,
      )}
    />
  );
}