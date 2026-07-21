/** Production site origin used for absolute canonical / Open Graph URLs. */
export const SITE_URL = "https://blufincapitaladvisory.com";

/** Build an absolute URL from a path (e.g. `/about` → `https://…/about`). */
export function absoluteUrl(path = "/"): string {
  if (/^https?:\/\//i.test(path)) return path;
  const normalized = path.startsWith("/") ? path : `/${path}`;
  return `${SITE_URL}${normalized === "/" ? "/" : normalized}`;
}

/** Standard page head bits for absolute canonical + og:url. */
export function pageLinks(path: string) {
  return [{ rel: "canonical" as const, href: absoluteUrl(path) }];
}

export function pageOgUrl(path: string) {
  return { property: "og:url" as const, content: absoluteUrl(path) };
}
