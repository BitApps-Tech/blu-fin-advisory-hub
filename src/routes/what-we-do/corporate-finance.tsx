import { createFileRoute } from "@tanstack/react-router";
import { ServiceDetailPage } from "../../components/ServiceDetailPage";

export const Route = createFileRoute("/what-we-do/corporate-finance")({
  head: () => ({
    meta: [
      { title: "Corporate Finance & Advisory — BluFin Capital Advisory" },
      { property: "og:url", content: "/what-we-do/corporate-finance" },
    ],
    links: [{ rel: "canonical", href: "/what-we-do/corporate-finance" }],
  }),
  component: () => <ServiceDetailPage practiceKey="corporate" />,
});
