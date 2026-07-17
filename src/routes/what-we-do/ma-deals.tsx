import { createFileRoute } from "@tanstack/react-router";
import { ServiceDetailPage } from "../../components/ServiceDetailPage";

export const Route = createFileRoute("/what-we-do/ma-deals")({
  head: () => ({
    meta: [
      { title: "M&A and Deals — BluFin Capital Advisory" },
      { property: "og:url", content: "/what-we-do/ma-deals" },
    ],
    links: [{ rel: "canonical", href: "/what-we-do/ma-deals" }],
  }),
  component: () => <ServiceDetailPage practiceKey="ma" />,
});
