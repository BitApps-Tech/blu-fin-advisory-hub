export type OrgNodeId =
  | "board"
  | "audit"
  | "complianceEthics"
  | "remuneration"
  | "ceo"
  | "cco"
  | "cro"
  | "rm"
  | "ccs"
  | "investment"
  | "transaction"
  | "infoSystem"
  | "hr"
  | "legal"
  | "accounting";

export type OrgNode = {
  id: OrgNodeId;
  label: string;
  subtitle: string;
  line: "governance" | "first" | "second" | "support" | "committee";
  description: string;
  responsibilities: string[];
  note?: string;
  teamTo?:
    | { to: "/about/team/board" }
    | { to: "/about/team/appointed" }
    | { to: "/about/team/$memberId"; params: { memberId: string } };
};

/** Interactive organogram — matches BluFin Capital Advisory PLC official chart. */
export const ORG_NODES: Record<OrgNodeId, OrgNode> = {
  board: {
    id: "board",
    label: "Board of Directors",
    subtitle: "Highest governance body",
    line: "governance",
    description:
      "The Board of Directors constitutes the highest governance body of BluFin Capital Advisory PLC. It is responsible for overall strategic direction, oversight of management, protection of client interests, and ensuring the firm operates with integrity and in full compliance with applicable laws and regulations.",
    responsibilities: [
      "Approving the firm's strategic plan, risk appetite, and annual budget",
      "Overseeing governance, risk management, and compliance frameworks",
      "Appointing and supervising the CEO and other key function holders",
      "Ensuring timely and accurate regulatory reporting to ECMA",
    ],
    teamTo: { to: "/about/team/board" },
  },
  audit: {
    id: "audit",
    label: "Audit Committee",
    subtitle: "Board committee",
    line: "committee",
    description:
      "The Audit Committee oversees the integrity of financial statements, internal controls, the external audit process, capital adequacy, and whistleblowing mechanisms. It reports to the Board of Directors.",
    responsibilities: [
      "Overseeing integrity of financial statements and internal financial controls",
      "Reviewing effectiveness of the risk management framework",
      "Supervising the external audit process",
      "Monitoring capital adequacy and financial resilience",
    ],
  },
  complianceEthics: {
    id: "complianceEthics",
    label: "Compliance & Ethics Committee",
    subtitle: "Board committee",
    line: "committee",
    description:
      "The Compliance & Ethics Committee oversees adherence to ECMA directives, AML/CFT obligations, the Code of Conduct, and staff awareness — with the Chief Compliance Officer typically serving as committee secretary.",
    responsibilities: [
      "Overseeing compliance with ECMA directives and AML/CFT obligations",
      "Reviewing periodic compliance reports from the CCO",
      "Approving material changes to the Compliance Framework and Code of Conduct",
      "Monitoring resolution of regulatory breaches or client complaints",
    ],
  },
  remuneration: {
    id: "remuneration",
    label: "Remuneration Committee",
    subtitle: "Board committee",
    line: "committee",
    description:
      "The Remuneration Committee recommends remuneration policy for directors and key staff, oversees Fit and Proper assessments, and leads succession planning — ensuring incentives do not encourage excessive risk-taking.",
    responsibilities: [
      "Recommending remuneration policy aligned with long-term value and risk management",
      "Approving Fit and Proper assessments of key function holders for ECMA",
      "Succession planning for senior management",
      "Ensuring remuneration does not incentivise excessive risk-taking or misconduct",
    ],
  },
  ceo: {
    id: "ceo",
    label: "Chief Executive Officer (CEO)",
    subtitle: "First line of defence",
    line: "first",
    description:
      "As the highest-ranking executive, the CEO is responsible for overall strategic leadership, stakeholder management, and financial performance. This role leads the First Line of Defence and is accountable to the Board for implementing the approved strategy within the established risk and compliance framework.",
    responsibilities: [
      "Strategic leadership and business development",
      "Regulatory alignment and market leadership",
      "Leading first-line units: Chief Research Officer, Relationship Managers, and Chief Corporate Service",
      "Accountability to the Board for strategy execution and performance",
    ],
    teamTo: { to: "/about/team/$memberId", params: { memberId: "yitbarek" } },
  },
  cco: {
    id: "cco",
    label: "Chief Compliance Officer (CCO)",
    subtitle: "Second line of defence",
    line: "second",
    description:
      "As the head of the Second Line of Defence, the CCO provides independent oversight of all regulatory, compliance, and ethical matters across the firm.",
    note: "Direct and solid reporting line to the Board on compliance matters. Administrative (dashed) reporting line to the CEO for operational logistics.",
    responsibilities: [
      "Developing and maintaining the Compliance Framework, Risk Management Framework, and Code of Conduct",
      "Acting as the primary liaison with ECMA on regulatory matters",
      "Overseeing the AML/CFT program as Money Laundering Reporting Officer (MLRO)",
      "Independently monitoring adherence to policies and regulations, reporting findings to the Board",
    ],
    teamTo: { to: "/about/team/$memberId", params: { memberId: "bizuayehu" } },
  },
  cro: {
    id: "cro",
    label: "Chief Research Officer",
    subtitle: "Head of Advisory",
    line: "first",
    description:
      "Reporting directly to the CEO, the Chief Research Officer leads analytical and strategic research functions and oversees Investment Advisory and Transaction Advisory.",
    responsibilities: [
      "Leading investment and transaction advisory teams",
      "Setting methodologies for research, due diligence, modelling, and valuations",
      "Ensuring advisory quality and research-led recommendations",
    ],
    teamTo: { to: "/about/team/$memberId", params: { memberId: "abebe" } },
  },
  rm: {
    id: "rm",
    label: "Relationship Managers",
    subtitle: "Client coverage",
    line: "first",
    description:
      "Relationship Managers report directly to the CEO and focus on client relationship management and coverage.",
    note: "Role is strictly limited to client relationship management. Does not engage in regulated activities. All advisory activities, including tailored recommendations, are performed by the advisory team.",
    responsibilities: [
      "Client relationship coverage and stewardship",
      "Business development coordination with advisory leadership",
      "Escalation to CEO and advisory teams — without performing regulated advisory activities",
    ],
  },
  ccs: {
    id: "ccs",
    label: "Chief Corporate Service",
    subtitle: "Centralized support",
    line: "support",
    description:
      "Reporting directly to the CEO, Chief Corporate Service manages the essential support functions that allow the business to run.",
    responsibilities: [
      "Information systems and technology infrastructure",
      "Human resources and talent administration",
      "Legal services and corporate governance support",
      "Accounting and corporate reporting",
    ],
  },
  investment: {
    id: "investment",
    label: "Investment Advisory",
    subtitle: "Under Chief Research Officer",
    line: "first",
    description:
      "Investment Advisory researches financial markets, identifies asset trends, and generates strategic analysis for investment vehicles under CRO methodologies.",
    responsibilities: [
      "Capital market research and investment analysis",
      "Due diligence methodologies for investment opportunities",
      "Research insights that support client investment decisions",
    ],
  },
  transaction: {
    id: "transaction",
    label: "Transaction Advisory",
    subtitle: "Under Chief Research Officer",
    line: "first",
    description:
      "Transaction Advisory provides research and technical analysis for mergers and acquisitions, corporate restructurings, financial modelling, and valuations.",
    responsibilities: [
      "M&A and restructuring analysis",
      "Transaction due diligence and financial modelling",
      "Company and asset valuations",
    ],
  },
  infoSystem: {
    id: "infoSystem",
    label: "Information System",
    subtitle: "Corporate services",
    line: "support",
    description:
      "Information System manages critical technological infrastructure, including network security, data management, enterprise systems, and technical support.",
    responsibilities: [
      "Network security and data management",
      "Enterprise systems implementation",
      "Technical support for the firm",
    ],
  },
  hr: {
    id: "hr",
    label: "Human Resource",
    subtitle: "Corporate services",
    line: "support",
    description:
      "Human Resource covers strategic management of human capital, including talent acquisition, employee relations, training, professional development, and benefits administration.",
    responsibilities: [
      "Talent acquisition and employee relations",
      "Training and professional development",
      "Benefits administration",
    ],
  },
  legal: {
    id: "legal",
    label: "Legal Service",
    subtitle: "Corporate services",
    line: "support",
    description:
      "Legal Service is responsible for the organisation's legal affairs, including corporate governance, contract management, regulatory guidance, litigation management, and intellectual property protection.",
    responsibilities: [
      "Corporate governance and contract management",
      "Regulatory guidance and litigation management",
      "Intellectual property protection",
    ],
  },
  accounting: {
    id: "accounting",
    label: "Accounting and Reporting",
    subtitle: "Corporate services",
    line: "support",
    description:
      "Accounting and Reporting oversees financial operations, including accuracy of financial statements, corporate reporting compliance, auditing processes, and general ledger maintenance.",
    responsibilities: [
      "Financial statements and general ledger",
      "Corporate reporting compliance",
      "Support for audit processes",
    ],
  },
};

export const CORE_SERVICES = [
  "Investment Advisory, including preparation for capital markets",
  "Capital Market Research and Analysis",
  "Financial Planning",
] as const;

export const GOVERNANCE_INTRO =
  "The company is led by an experienced Board of Directors and a management team with deep local expertise and international standards of practice. Our governance structure is robust, with independent board oversight and dedicated committees for Audit, Compliance & Ethics, and Remuneration.";

export const STRUCTURE_INTRO =
  "BluFin Capital Advisory PLC is a privately held firm, established by founding partners together with strategic investors. The company is backed by seven shareholders, including three founding partners who bring over 50 years of combined experience in strategy, capital markets, organizational transformation, and financial advisory.";

export const BOARD_COMPOSITION =
  "The Board comprises five highly qualified professionals with complementary expertise in law, banking and finance, corporate governance, academia, and industrial entrepreneurship. Collectively, the Board provides robust oversight, regulatory compliance, and strategic direction, with all members meeting ECMA's Fit and Proper criteria.";

export type BoardCommittee = {
  id: string;
  title: string;
  composition: string;
  responsibilities: string[];
};

export const BOARD_COMMITTEES: BoardCommittee[] = [
  {
    id: "audit-risk",
    title: "Audit Committee",
    composition:
      "At least two non-executive directors (majority independent), chaired by an independent director. The CCO and CEO may attend by invitation.",
    responsibilities: [
      "Overseeing integrity of financial statements and internal financial controls",
      "Reviewing effectiveness of the risk management framework",
      "Supervising the external audit process",
      "Monitoring capital adequacy and financial resilience",
      "Reviewing whistleblowing mechanisms and irregularities",
    ],
  },
  {
    id: "compliance-ethics",
    title: "Compliance & Ethics Committee",
    composition:
      "At least two non-executive directors. The Chief Compliance Officer serves as secretary to the committee.",
    responsibilities: [
      "Overseeing compliance with ECMA directives and AML/CFT obligations",
      "Reviewing periodic compliance reports from the CCO",
      "Approving material changes to the Compliance Framework and Code of Conduct",
      "Monitoring resolution of regulatory breaches or client complaints",
      "Ensuring staff training and awareness programs",
    ],
  },
  {
    id: "remuneration",
    title: "Remuneration Committee",
    composition:
      "At least two independent non-executive directors. The CEO may provide input but is not a voting member.",
    responsibilities: [
      "Recommending remuneration policy aligned with long-term value and risk management",
      "Approving Fit and Proper assessments of key function holders for ECMA",
      "Succession planning for senior management",
      "Ensuring remuneration does not incentivise excessive risk-taking or misconduct",
    ],
  },
];
