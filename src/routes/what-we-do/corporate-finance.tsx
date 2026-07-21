import { createFileRoute } from "@tanstack/react-router";
import { ServiceDetailPage } from "../../components/ServiceDetailPage";
import { pageLinks, pageOgUrl } from "../../lib/seo";

export const Route = createFileRoute("/what-we-do/corporate-finance")({
  head: () => ({
    meta: [
      { title: "Corporate Finance & Advisory — BluFin Capital Advisory" },
      {
        name: "description",
        content:
          "Corporate finance and strategic advisory services from BluFin Capital Advisory PLC.",
      },
      { property: "og:title", content: "Corporate Finance & Advisory — BluFin Capital Advisory" },
      pageOgUrl("/what-we-do/corporate-finance"),
    ],
    links: pageLinks("/what-we-do/corporate-finance"),
  }),
  component: () => <ServiceDetailPage practiceKey="corporate" />,
});
