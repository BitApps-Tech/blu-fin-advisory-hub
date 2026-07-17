import { createFileRoute } from "@tanstack/react-router";
import { ServiceDetailPage } from "../../components/ServiceDetailPage";

export const Route = createFileRoute("/what-we-do/listing-capital-markets")({
  head: () => ({
    meta: [
      { title: "Listing & Capital Markets Solutions — BluFin Capital Advisory" },
      { property: "og:url", content: "/what-we-do/listing-capital-markets" },
    ],
    links: [{ rel: "canonical", href: "/what-we-do/listing-capital-markets" }],
  }),
  component: () => <ServiceDetailPage practiceKey="listing" />,
});
