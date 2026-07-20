import { createFileRoute, redirect } from "@tanstack/react-router";

export const Route = createFileRoute("/about/team/")({
  beforeLoad: () => {
    throw redirect({ to: "/about/team/board" });
  },
});
