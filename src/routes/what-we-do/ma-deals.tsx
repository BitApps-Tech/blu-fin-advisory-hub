import { createFileRoute } from "@tanstack/react-router";
import { ServiceDetailPage } from "../../components/ServiceDetailPage";
import { pageLinks, pageOgUrl } from "../../lib/seo";

export const Route = createFileRoute("/what-we-do/ma-deals")({
  head: () => ({
    meta: [
      { title: "M&A and Deals — BluFin Capital Advisory" },
      {
        name: "description",
        content:
          "Mergers, acquisitions, and transaction advisory from BluFin Capital Advisory PLC.",
      },
      { property: "og:title", content: "M&A and Deals — BluFin Capital Advisory" },
      pageOgUrl("/what-we-do/ma-deals"),
    ],
    links: pageLinks("/what-we-do/ma-deals"),
  }),
  component: () => <ServiceDetailPage practiceKey="ma" />,
});
