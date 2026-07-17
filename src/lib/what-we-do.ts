export const WHAT_WE_DO = [
  {
    to: "/what-we-do/listing-capital-markets",
    label: "Listing & Capital Markets Solutions",
    short: "Listing & Capital Markets",
    eyebrow: "What We Do · 01",
    title: "Listing & Capital Markets Solutions",
    tagline: "The definitive path to public markets.",
    summary:
      "End-to-end preparation and execution for issuers seeking listings and ongoing capital markets access on the Ethiopian Securities Exchange and beyond.",
    points: [
      "ESX listing readiness diagnostics",
      "Prospectus and offering documentation",
      "Governance, disclosure & investor relations design",
      "Post-listing capital markets support",
      "Follow-on offerings and capital structure reviews",
    ],
  },
  {
    to: "/what-we-do/corporate-finance",
    label: "Corporate Finance & Advisory",
    short: "Corporate Finance & Advisory",
    eyebrow: "What We Do · 02",
    title: "Corporate Finance & Advisory",
    tagline: "Capital structure, raised with intention.",
    summary:
      "Senior-led advisory on capital structure, fundraising, and balance-sheet strategy for growth-stage and established Ethiopian institutions.",
    points: [
      "Growth capital & structured equity raises",
      "Debt structuring, refinancing, and syndication",
      "Balance sheet & capital allocation advisory",
      "Fairness opinions and independent valuations",
      "Board-level capital markets strategy",
    ],
  },
  {
    to: "/what-we-do/ma-deals",
    label: "M&A and Deals",
    short: "M&A and Deals",
    eyebrow: "What We Do · 03",
    title: "M&A and Deals",
    tagline: "Discreet execution of complex transactions.",
    summary:
      "Buy-side and sell-side M&A, joint ventures, and cross-border deals executed with institutional discipline from mandate through close.",
    points: [
      "Sell-side & buy-side M&A execution",
      "Joint venture and strategic partnership structuring",
      "Cross-border transaction advisory",
      "Due diligence coordination and negotiation support",
      "Post-merger integration advisory",
    ],
  },
] as const;

export type WhatWeDoService = (typeof WHAT_WE_DO)[number];
