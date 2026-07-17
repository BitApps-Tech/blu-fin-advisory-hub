import { createFileRoute } from "@tanstack/react-router";
import { ServiceDetailPage } from "../../components/ServiceDetailPage";
import { WHAT_WE_DO } from "../../lib/what-we-do";

const service = WHAT_WE_DO[2];

export const Route = createFileRoute("/what-we-do/ma-deals")({
  head: () => ({
    meta: [
      { title: `${service.title} — BluFin Capital Advisory` },
      { name: "description", content: service.summary },
      { property: "og:title", content: `${service.title} — BluFin Capital Advisory` },
      { property: "og:url", content: service.to },
    ],
    links: [{ rel: "canonical", href: service.to }],
  }),
  component: () => <ServiceDetailPage service={service} />,
});
