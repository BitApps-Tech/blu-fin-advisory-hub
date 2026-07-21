import { createFileRoute } from "@tanstack/react-router";
import { ServiceDetailPage } from "../../components/ServiceDetailPage";
import { pageLinks, pageOgUrl } from "../../lib/seo";

export const Route = createFileRoute("/what-we-do/listing-capital-markets")({
  head: () => ({
    meta: [
      { title: "Listing & Capital Markets Solutions — BluFin Capital Advisory" },
      {
        name: "description",
        content:
          "Listing and capital markets solutions from BluFin Capital Advisory PLC, an ECMA-licensed Securities Investment Advisor.",
      },
      {
        property: "og:title",
        content: "Listing & Capital Markets Solutions — BluFin Capital Advisory",
      },
      pageOgUrl("/what-we-do/listing-capital-markets"),
    ],
    links: pageLinks("/what-we-do/listing-capital-markets"),
  }),
  component: () => <ServiceDetailPage practiceKey="listing" />,
});
