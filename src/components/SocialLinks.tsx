import { Linkedin, Youtube, Facebook, Instagram } from "lucide-react";
import { SOCIAL } from "../lib/contact";
import { cn } from "../lib/utils";

function XIcon({ className }: { className?: string }) {
  return (
    <svg viewBox="0 0 24 24" fill="currentColor" className={className} aria-hidden>
      <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-4.714-6.231-5.401 6.231H2.744l7.227-8.26L1.61 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z" />
    </svg>
  );
}

const SOCIAL_ICONS = {
  LinkedIn: Linkedin,
  YouTube: Youtube,
  Facebook: Facebook,
  Instagram: Instagram,
  X: XIcon,
} as const;

type SocialLinksProps = {
  className?: string;
  iconClassName?: string;
};

export function SocialLinks({ className, iconClassName }: SocialLinksProps) {
  return (
    <div className={cn("flex flex-wrap items-center gap-3", className)}>
      {SOCIAL.map((item) => {
        const Icon = SOCIAL_ICONS[item.name];
        return (
          <a
            key={item.name}
            href={item.href}
            target="_blank"
            rel="noopener noreferrer"
            aria-label={`BluFin Capital Advisory on ${item.name}`}
            className="flex h-8 w-8 items-center justify-center rounded-full opacity-70 transition-all duration-300 hover:bg-white/15 hover:opacity-100"
          >
            <Icon className={cn("h-4 w-4", iconClassName)} />
          </a>
        );
      })}
    </div>
  );
}
