import { QueryClient, QueryClientProvider } from "@tanstack/react-query";
import {
  Outlet,
  Link,
  createRootRouteWithContext,
  useRouter,
  useRouterState,
  HeadContent,
  Scripts,
} from "@tanstack/react-router";
import { useEffect, type ReactNode } from "react";

import appCss from "../styles.css?url";
import { reportLovableError } from "../lib/lovable-error-reporting";
import { SiteHeader } from "../components/SiteHeader";
import { SiteFooter } from "../components/SiteFooter";
import { I18nProvider, useI18n } from "../i18n";
import { Toaster } from "sonner";
import { absoluteUrl, SITE_URL } from "../lib/seo";
import { CONTACT } from "../lib/contact";
import { COMPANY } from "../lib/company";

const ORG_JSON_LD = {
  "@context": "https://schema.org",
  "@type": "FinancialService",
  name: COMPANY.tradingName,
  legalName: COMPANY.legalName,
  url: SITE_URL,
  logo: absoluteUrl("/favicon.png"),
  email: CONTACT.email,
  telephone: CONTACT.phoneDisplay,
  address: {
    "@type": "PostalAddress",
    streetAddress: "Kidus Building, 3rd Floor, Kazanchis, Jomo Kenyatta Street",
    addressLocality: "Addis Ababa",
    addressCountry: "ET",
  },
  description: COMPANY.mission,
  areaServed: "Ethiopia",
};

function NotFoundComponent() {
  const { t } = useI18n();
  return (
    <div className="flex min-h-screen items-center justify-center bg-background px-4">
      <div className="max-w-md text-center">
        <div className="eyebrow">{t.errors.notFoundEyebrow}</div>
        <h1 className="mt-4 font-serif text-5xl text-navy">{t.errors.notFoundTitle}</h1>
        <p className="mt-3 text-sm text-muted-foreground">{t.errors.notFoundBody}</p>
        <Link
          to="/"
          className="mt-8 inline-flex items-center bg-navy px-5 py-2.5 text-xs uppercase tracking-widest text-navy-foreground"
        >
          {t.common.returnHome}
        </Link>
      </div>
    </div>
  );
}

function ErrorComponent({ error, reset }: { error: Error; reset: () => void }) {
  const router = useRouter();
  const { t } = useI18n();
  useEffect(() => {
    reportLovableError(error, { boundary: "tanstack_root_error_component" });
  }, [error]);
  return (
    <div className="flex min-h-screen items-center justify-center bg-background px-4">
      <div className="max-w-md text-center">
        <div className="eyebrow">{t.errors.errorEyebrow}</div>
        <h1 className="mt-4 font-serif text-3xl text-navy">{t.errors.errorTitle}</h1>
        <p className="mt-3 text-sm text-muted-foreground">{t.errors.errorBody}</p>
        <div className="mt-6 flex justify-center gap-3">
          <button
            onClick={() => {
              router.invalidate();
              reset();
            }}
            className="bg-navy px-5 py-2.5 text-xs uppercase tracking-widest text-navy-foreground"
          >
            {t.common.tryAgain}
          </button>
          <a
            href="/"
            className="border border-navy px-5 py-2.5 text-xs uppercase tracking-widest text-navy"
          >
            {t.common.goHome}
          </a>
        </div>
      </div>
    </div>
  );
}

export const Route = createRootRouteWithContext<{ queryClient: QueryClient }>()({
  head: () => ({
    meta: [
      { charSet: "utf-8" },
      { name: "viewport", content: "width=device-width, initial-scale=1" },
      { title: "BluFin Capital Advisory — Institutional Capital Markets Advisory" },
      {
        name: "description",
        content:
          "ECMA-licensed Securities Investment Advisor delivering ethical, insightful, and compliant investment and transaction advisory for Ethiopia's capital market.",
      },
      { name: "theme-color", content: "#1F3E72" },
      {
        property: "og:title",
        content: "BluFin Capital Advisory — Institutional Capital Markets Advisory",
      },
      {
        property: "og:description",
        content:
          "ECMA-licensed Securities Investment Advisor delivering ethical, insightful, and compliant investment and transaction advisory for Ethiopia's capital market.",
      },
      { property: "og:site_name", content: "BluFin Capital Advisory" },
      { property: "og:type", content: "website" },
      { property: "og:url", content: SITE_URL },
      { property: "og:locale", content: "en_US" },
      { name: "twitter:card", content: "summary_large_image" },
      {
        name: "twitter:title",
        content: "BluFin Capital Advisory — Institutional Capital Markets Advisory",
      },
      {
        name: "twitter:description",
        content: "ECMA-licensed Securities Investment Advisor.",
      },
      {
        property: "og:image",
        content:
          "https://storage.googleapis.com/gpt-engineer-file-uploads/attachments/og-images/ab6ede28-2a8c-417d-81cb-b4323b147f53",
      },
      {
        name: "twitter:image",
        content:
          "https://storage.googleapis.com/gpt-engineer-file-uploads/attachments/og-images/ab6ede28-2a8c-417d-81cb-b4323b147f53",
      },
    ],
    links: [
      { rel: "stylesheet", href: appCss },
      { rel: "icon", href: "/favicon.png", type: "image/png" },
      { rel: "icon", href: "/favicon.ico", sizes: "any" },
      { rel: "apple-touch-icon", href: "/apple-touch-icon.png" },
      { rel: "preconnect", href: "https://fonts.googleapis.com" },
      { rel: "preconnect", href: "https://fonts.gstatic.com", crossOrigin: "anonymous" },
      {
        rel: "stylesheet",
        href: "https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500;600;700&family=Noto+Sans+Ethiopic:wght@400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap",
      },
    ],
    scripts: [
      {
        type: "application/ld+json",
        children: JSON.stringify(ORG_JSON_LD),
      },
    ],
  }),
  shellComponent: RootShell,
  component: RootComponent,
  notFoundComponent: NotFoundComponent,
  errorComponent: ErrorComponent,
});

function RootShell({ children }: { children: ReactNode }) {
  return (
    <html lang="en">
      <head>
        <HeadContent />
      </head>
      <body>
        {children}
        <Scripts />
        <script src="https://code.tidio.co/vxue7ykeev7yoeznacaxqponmi6cdh9f.js" async />
      </body>
    </html>
  );
}

function RootComponent() {
  const { queryClient } = Route.useRouteContext();
  const pathname = useRouterState({ select: (s) => s.location.pathname });
  const isAdmin = pathname.startsWith("/admin") || pathname === "/portal";

  useEffect(() => {
    window.scrollTo({ top: 0, behavior: "smooth" });
  }, [pathname]);

  return (
    <QueryClientProvider client={queryClient}>
      {/* HeadContent also covers SPA/cPanel (no RootShell / Scripts) */}
      <HeadContent />
      <I18nProvider>
        <div className="flex min-h-screen flex-col">
          {!isAdmin && <SiteHeader />}
          <main className="flex-1">
            <Outlet />
          </main>
          {!isAdmin && <SiteFooter />}
        </div>
        <Toaster position="top-right" />
      </I18nProvider>
    </QueryClientProvider>
  );
}
