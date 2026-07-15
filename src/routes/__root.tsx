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
import { Toaster } from "sonner";

function NotFoundComponent() {
  return (
    <div className="flex min-h-screen items-center justify-center bg-background px-4">
      <div className="max-w-md text-center">
        <div className="eyebrow">Error 404</div>
        <h1 className="mt-4 font-serif text-5xl text-navy">Page not found</h1>
        <p className="mt-3 text-sm text-muted-foreground">
          The page you're looking for doesn't exist or has been moved.
        </p>
        <Link
          to="/"
          className="mt-8 inline-flex items-center bg-navy px-5 py-2.5 text-xs uppercase tracking-widest text-navy-foreground"
        >
          Return home
        </Link>
      </div>
    </div>
  );
}

function ErrorComponent({ error, reset }: { error: Error; reset: () => void }) {
  const router = useRouter();
  useEffect(() => {
    reportLovableError(error, { boundary: "tanstack_root_error_component" });
  }, [error]);
  return (
    <div className="flex min-h-screen items-center justify-center bg-background px-4">
      <div className="max-w-md text-center">
        <div className="eyebrow">Something went wrong</div>
        <h1 className="mt-4 font-serif text-3xl text-navy">This page didn't load</h1>
        <p className="mt-3 text-sm text-muted-foreground">
          You can try refreshing or head back home.
        </p>
        <div className="mt-6 flex justify-center gap-3">
          <button
            onClick={() => { router.invalidate(); reset(); }}
            className="bg-navy px-5 py-2.5 text-xs uppercase tracking-widest text-navy-foreground"
          >
            Try again
          </button>
          <a href="/" className="border border-navy px-5 py-2.5 text-xs uppercase tracking-widest text-navy">
            Go home
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
      { name: "description", content: "ECMA-licensed Securities Investment Advisor. Corporate finance, listing solutions, transaction advisory and private equity for Ethiopia's next generation of issuers." },
      { property: "og:title", content: "BluFin Capital Advisory — Institutional Capital Markets Advisory" },
      { property: "og:description", content: "ECMA-licensed Securities Investment Advisor. Corporate finance, listing solutions, transaction advisory and private equity for Ethiopia's next generation of issuers." },
      { property: "og:site_name", content: "BluFin Capital Advisory" },
      { property: "og:type", content: "website" },
      { name: "twitter:card", content: "summary_large_image" },
      { name: "twitter:title", content: "BluFin Capital Advisory — Institutional Capital Markets Advisory" },
      { name: "twitter:description", content: "ECMA-licensed Securities Investment Advisor. Corporate finance, listing solutions, transaction advisory and private equity for Ethiopia's next generation of issuers." },
      { property: "og:image", content: "https://storage.googleapis.com/gpt-engineer-file-uploads/attachments/og-images/ab6ede28-2a8c-417d-81cb-b4323b147f53" },
      { name: "twitter:image", content: "https://storage.googleapis.com/gpt-engineer-file-uploads/attachments/og-images/ab6ede28-2a8c-417d-81cb-b4323b147f53" },
    ],
    links: [
      { rel: "stylesheet", href: appCss },
      { rel: "icon", href: "/favicon.ico", type: "image/x-icon" },
      { rel: "preconnect", href: "https://fonts.googleapis.com" },
      { rel: "preconnect", href: "https://fonts.gstatic.com", crossOrigin: "anonymous" },
      { rel: "stylesheet", href: "https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap" },
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
      <head><HeadContent /></head>
      <body>
        {children}
        <Scripts />
      </body>
    </html>
  );
}

function RootComponent() {
  const { queryClient } = Route.useRouteContext();
  const pathname = useRouterState({ select: (s) => s.location.pathname });
  const isAdmin = pathname.startsWith("/admin") || pathname === "/portal";

  return (
    <QueryClientProvider client={queryClient}>
      <div className="flex min-h-screen flex-col">
        {!isAdmin && <SiteHeader />}
        <main className="flex-1">
          <Outlet />
        </main>
        {!isAdmin && <SiteFooter />}
      </div>
      <Toaster position="top-right" />
    </QueryClientProvider>
  );
}
