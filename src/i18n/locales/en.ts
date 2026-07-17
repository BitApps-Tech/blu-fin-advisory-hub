export type Dictionary = {
  nav: {
    home: string;
    about: string;
    whatWeDo: string;
    overview: string;
    news: string;
    careers: string;
    contact: string;
    emailUs: string;
    menu: string;
  };
  footer: {
    blurb: string;
    whatWeDo: string;
    firm: string;
    office: string;
    social: string;
    about: string;
    news: string;
    careers: string;
    trackRecord: string;
    contact: string;
    rights: string;
    license: string;
  };
  common: {
    requestConsultation: string;
    learnMore: string;
    speakWithPartner: string;
    getInTouch: string;
    bookConsultation: string;
    atAGlance: string;
    readMore: string;
    close: string;
    submitInquiry: string;
    sending: string;
    returnHome: string;
    tryAgain: string;
    goHome: string;
  };
  home: {
    eyebrow: string;
    headline: string;
    ctaPrimary: string;
    ctaSecondary: string;
    glanceLegal: string;
    glanceLicense: string;
    glanceHq: string;
    visionLabel: string;
    foundingEyebrow: string;
    foundingTitle: string;
    valuesEyebrow: string;
    valuesTitle: string;
    practicesEyebrow: string;
    practicesTitle: string;
    allServices: string;
    firmEyebrow: string;
    firmTitle: string;
    firmCta: string;
    engageEyebrow: string;
    engageTitle: string;
  };
  about: {
    eyebrow: string;
    headline: string;
    legalEyebrow: string;
    legalTitle: string;
    ownershipEyebrow: string;
    ownershipTitle: string;
    vision: string;
    mission: string;
    valuesEyebrow: string;
    valuesTitle: string;
    legalLabels: {
      legalName: string;
      tradingName: string;
      legalForm: string;
      regNo: string;
      tin: string;
      registeredAddress: string;
      businessLicense: string;
      office: string;
    };
  };
  whatWeDo: {
    eyebrow: string;
    headline: string;
    intro: string;
    scope: string;
    related: string;
  };
  careers: {
    eyebrow: string;
    headline: string;
    intro: string;
    howWeWork: string;
    openings: string;
    openingsTitle: string;
    openingsBody: string;
  };
  contact: {
    eyebrow: string;
    headline: string;
    intro: string;
    office: string;
    advisoryDesk: string;
    telephone: string;
    social: string;
    fullName: string;
    email: string;
    company: string;
    capitalNeeds: string;
    sector: string;
    service: string;
    message: string;
    select: string;
    consent: string;
    success: string;
  };
  insights: {
    eyebrow: string;
    headline: string;
    all: string;
    empty: string;
    minRead: string;
  };
  trackRecord: {
    eyebrow: string;
    headline: string;
    intro: string;
    search: string;
    industry: string;
    dealScale: string;
    deals: string;
    empty: string;
  };
  errors: {
    notFoundEyebrow: string;
    notFoundTitle: string;
    notFoundBody: string;
    errorEyebrow: string;
    errorTitle: string;
    errorBody: string;
  };
  company: {
    legalForm: string;
    businessLicense: string;
    registeredAddress: string;
    status: string;
    ownership: string;
    foundingPhilosophy: string;
    vision: string;
    visionElaboration: string;
    mission: string;
    missionElaboration: string;
    values: { title: string; body: string }[];
  };
  practices: {
    listing: {
      label: string;
      short: string;
      eyebrow: string;
      title: string;
      tagline: string;
      summary: string;
      points: string[];
    };
    corporate: {
      label: string;
      short: string;
      eyebrow: string;
      title: string;
      tagline: string;
      summary: string;
      points: string[];
    };
    ma: {
      label: string;
      short: string;
      eyebrow: string;
      title: string;
      tagline: string;
      summary: string;
      points: string[];
    };
  };
};

export const en: Dictionary = {
  nav: {
    home: "Home",
    about: "About Us",
    whatWeDo: "What We Do",
    overview: "Overview",
    news: "News & Articles",
    careers: "Careers",
    contact: "Contact Us",
    emailUs: "Email Us",
    menu: "Menu",
  },
  footer: {
    blurb:
      "A Private Limited Company licensed to operate as a Securities Investment Advisor under the Ethiopian Capital Market Authority (ECMA).",
    whatWeDo: "What We Do",
    firm: "Firm",
    office: "Office",
    social: "Social",
    about: "About Us",
    news: "News & Articles",
    careers: "Careers",
    trackRecord: "Track Record",
    contact: "Contact Us",
    rights: "All rights reserved.",
    license: "Licensed by the Ethiopian Capital Market Authority · License #ECMA-SIA-2024",
  },
  common: {
    requestConsultation: "Request Consultation",
    learnMore: "Learn more",
    speakWithPartner: "Speak with a partner",
    getInTouch: "Get in Touch",
    bookConsultation: "Book a Consultation",
    atAGlance: "At a glance",
    readMore: "Read more",
    close: "Close",
    submitInquiry: "Submit Inquiry",
    sending: "Sending…",
    returnHome: "Return home",
    tryAgain: "Try again",
    goHome: "Go home",
  },
  home: {
    eyebrow: "Licensed Securities Investment Advisor · ECMA",
    headline: "Ethical, insightful, and compliant advisory for Ethiopia's capital market.",
    ctaPrimary: "Request Consultation",
    ctaSecondary: "About BluFin",
    glanceLegal: "Legal form",
    glanceLicense: "License",
    glanceHq: "Headquarters",
    visionLabel: "Our vision",
    foundingEyebrow: "Founding philosophy",
    foundingTitle: "Independent advice for a once-in-a-generation market.",
    valuesEyebrow: "Core values",
    valuesTitle: "Non-negotiable beliefs that guide every engagement.",
    practicesEyebrow: "What we do",
    practicesTitle: "Three practices. One fiduciary standard.",
    allServices: "All services →",
    firmEyebrow: "The firm",
    firmTitle: "Agile, client-focused, and aligned with our founding principles.",
    firmCta: "Read our company profile",
    engageEyebrow: "Engage BluFin",
    engageTitle: "Empowering informed financial decisions for Ethiopia's capital market.",
  },
  about: {
    eyebrow: "About Us",
    headline: "Ethiopia's capital market, advised with integrity and objectivity.",
    legalEyebrow: "Corporate & legal details",
    legalTitle: "Formal company profile and legal status",
    ownershipEyebrow: "Ownership",
    ownershipTitle: "Founded by Ethiopian finance professionals.",
    vision: "Vision",
    mission: "Mission",
    valuesEyebrow: "Core values",
    valuesTitle: "Guiding principles embedded in our culture.",
    legalLabels: {
      legalName: "Legal Name",
      tradingName: "Trading Name",
      legalForm: "Legal Form",
      regNo: "Commercial Registration No.",
      tin: "Tax Identification Number (TIN)",
      registeredAddress: "Registered Address",
      businessLicense: "Business License",
      office: "Office",
    },
  },
  whatWeDo: {
    eyebrow: "What We Do",
    headline: "Three practices, engineered for the transactions that define institutions.",
    intro:
      "Every engagement is led by a senior partner. Every mandate carries the full weight of our firm's institutional discipline and regulatory expertise.",
    scope: "Scope of work",
    related: "Related practices",
  },
  careers: {
    eyebrow: "Careers",
    headline: "Join a firm built on integrity, objectivity, and professional excellence.",
    intro:
      "Our core values guide the actions and behaviours of every employee. We look for professionals who share our commitment to ethical, insightful, and compliant advisory.",
    howWeWork: "How we work",
    openings: "Openings",
    openingsTitle: "We review expressions of interest on a rolling basis.",
    openingsBody:
      "There are no formal openings listed at this time. If your experience aligns with our investment and transaction advisory practice, send a confidential introduction to",
  },
  contact: {
    eyebrow: "Contact",
    headline: "Confidential consultation with our senior team.",
    intro: "Submissions are reviewed personally by a partner. Response typically within one business day.",
    office: "Office",
    advisoryDesk: "Advisory desk",
    telephone: "Telephone",
    social: "Social",
    fullName: "Full name",
    email: "Email",
    company: "Company",
    capitalNeeds: "Estimated capital needs",
    sector: "Sector",
    service: "Service requested",
    message: "How can we help?",
    select: "Select…",
    consent:
      "By submitting, you consent to BluFin storing your inquiry to respond. Information is treated as strictly confidential.",
    success: "Your inquiry has been received. A senior partner will be in touch.",
  },
  insights: {
    eyebrow: "News & Articles",
    headline: "Research, commentary and announcements.",
    all: "All",
    empty: "No articles in this category yet.",
    minRead: "min read",
  },
  trackRecord: {
    eyebrow: "Track record",
    headline: "A ledger of completed mandates.",
    intro:
      "Selected transactions on which BluFin has served as advisor. Filter by industry vertical, transaction scale, or search by client or service.",
    search: "Search client, sector, or service",
    industry: "Industry",
    dealScale: "Deal scale",
    deals: "deals",
    empty: "No transactions match the current filters.",
  },
  errors: {
    notFoundEyebrow: "Error 404",
    notFoundTitle: "Page not found",
    notFoundBody: "The page you're looking for doesn't exist or has been moved.",
    errorEyebrow: "Something went wrong",
    errorTitle: "This page didn't load",
    errorBody: "You can try refreshing or head back home.",
  },
  company: {
    legalForm: "Private Limited Company (PLC)",
    businessLicense: "Securities Investment Advisory",
    registeredAddress: "Addis Ababa, Ethiopia",
    status:
      "BluFin Capital Advisory PLC is a Private Limited Company (PLC) formally incorporated, licensed, and in good standing under the laws of Ethiopia. The company has been established specifically to operate as a licensed Securities Investment Advisor within the regulatory framework of the Ethiopian Capital Market Authority (ECMA).",
    ownership:
      "BluFin Capital Advisory PLC is founded and owned by a dedicated group of seasoned Ethiopian finance professionals. The founding partners bring a complementary blend of extensive, hands-on experience in corporate finance, transaction advisory, private equity, and the Ethiopian regulatory landscape.",
    foundingPhilosophy:
      "The firm was born from a shared philosophy that the launch of Ethiopia's capital market represents a once-in-a-generation opportunity to build an institution grounded in integrity, objectivity, and a deep-seated commitment to client success. The founders identified a critical gap in the emerging market for a specialized, independent advisory firm that operates free from the conflicts of interest inherent in larger, integrated financial institutions. Our ownership structure ensures that the firm remains agile, client-focused, and fully aligned with its founding principles.",
    vision:
      "To be Ethiopia's most trusted and respected investment advisory firm, setting the standard for integrity, professionalism, and client success.",
    visionElaboration:
      'Our vision extends beyond market share. "Trusted" signifies an unwavering commitment to ethical conduct and placing client interests first. "Respected" signifies a reputation built on the quality of our research, the objectivity of our advice, and our tangible contribution to market development. We aim to be the firm that clients, regulators, and peers look to as the benchmark for excellence.',
    mission:
      "To deliver ethical, insightful, and compliant investment and transaction advisory services that empower informed financial decisions and contribute to the robust development of the Ethiopian capital market.",
    missionElaboration:
      'Our mission is action oriented. "Ethical" underscores our commitment to our fiduciary duty. "Insightful" highlights our foundation in rigorous, research-driven analysis. "Compliant" speaks to our operational discipline and profound respect for the regulatory framework. "Empower" is central to our purpose; we succeed by equipping our clients with the knowledge and advice they need to succeed.',
    values: [
      {
        title: "Integrity First",
        body: "Integrity is the bedrock of our firm. It means acting with honesty and transparency in all our dealings and adhering to both the letter and the spirit of all laws. We have a zero-tolerance policy for unethical behaviour, and our commitment to integrity is the ultimate safeguard for our clients' interests.",
      },
      {
        title: "Uncompromising Objectivity",
        body: "Our advice is our product, and its value is derived from its objectivity. All our recommendations are based on independent, rigorous, and dispassionate research. Our clients' success depends on the clarity and impartiality of our insights.",
      },
      {
        title: "Client-Centric Fiduciary Standard",
        body: "We hold ourselves to a fiduciary standard. This means that the client's best interest is the single lens through which we make all decisions. This principle is operationalized through our robust suitability process, ensuring every recommendation is aligned with the client's specific needs, goals, and risk profile.",
      },
      {
        title: "Professional Excellence",
        body: "We are committed to delivering a standard of service that reflects our ambition to be a market leader. This demands a culture of continuous learning, professional development, and a relentless pursuit of excellence in everything we do.",
      },
    ],
  },
  practices: {
    listing: {
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
    corporate: {
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
    ma: {
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
  },
};
