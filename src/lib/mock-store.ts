// Client-side mock persistence via localStorage.
// Seeded on first load; used by public pages + admin CRUD.

export type Transaction = {
  id: string;
  client: string;
  sector: "Retail" | "Logistics" | "Tech" | "Manufacturing" | "Financial Services" | "Energy";
  scale: "Under $5M" | "$5M–$25M" | "$25M–$100M" | "$100M+";
  service: "Corporate Finance" | "Listing Solutions" | "Transaction Advisory" | "Private Equity";
  summary: string;
  milestone: string;
  year: number;
};

export type Article = {
  id: string;
  title: string;
  category: "Market Commentary" | "Sector Research" | "Announcement" | "Regulatory";
  excerpt: string;
  body: string;
  publishedAt: string; // ISO
  readMinutes: number;
};

export type Lead = {
  id: string;
  name: string;
  email: string;
  company: string;
  capitalNeeds: string;
  sector: string;
  service: string;
  message: string;
  submittedAt: string;
  status: "new" | "reviewing" | "contacted" | "archived";
};

const KEYS = {
  txns: "blufin.transactions",
  articles: "blufin.articles",
  leads: "blufin.leads",
  auth: "blufin.auth",
} as const;

const SEED_TXNS: Transaction[] = [
  { id: "t1", client: "Habesha Retail Group", sector: "Retail", scale: "$25M–$100M", service: "Corporate Finance", summary: "Advised on ETB 3.2B growth capital raise to fund 42-store national expansion.", milestone: "Closed Q3 2025", year: 2025 },
  { id: "t2", client: "Awash Logistics Holdings", sector: "Logistics", scale: "$100M+", service: "Transaction Advisory", summary: "Sell-side advisor on strategic stake divestment to a regional infrastructure fund.", milestone: "Completed Q1 2025", year: 2025 },
  { id: "t3", client: "Adigrat Manufacturing Co.", sector: "Manufacturing", scale: "$5M–$25M", service: "Private Equity", summary: "Structured mezzanine financing to modernize production line and expand export capacity.", milestone: "Deployed 2024", year: 2024 },
  { id: "t4", client: "SabaTech PLC", sector: "Tech", scale: "$5M–$25M", service: "Listing Solutions", summary: "Lead advisor on Ethiopia's inaugural cohort of ESX equity listings.", milestone: "Listed 2025", year: 2025 },
  { id: "t5", client: "Blue Nile Energy", sector: "Energy", scale: "$100M+", service: "Corporate Finance", summary: "Structured project finance package for 120 MW solar development portfolio.", milestone: "Financial close 2024", year: 2024 },
  { id: "t6", client: "Rift Valley Bank", sector: "Financial Services", scale: "$25M–$100M", service: "Transaction Advisory", summary: "Buy-side advisor on acquisition of specialty microfinance platform.", milestone: "Completed 2023", year: 2023 },
  { id: "t7", client: "Merkato Wholesale", sector: "Retail", scale: "Under $5M", service: "Corporate Finance", summary: "Working capital facility restructuring and vendor financing arrangement.", milestone: "Closed 2023", year: 2023 },
  { id: "t8", client: "Omo Freight", sector: "Logistics", scale: "$5M–$25M", service: "Private Equity", summary: "Series B growth investment alongside a pan-African logistics fund.", milestone: "Deployed 2024", year: 2024 },
];

const SEED_ARTICLES: Article[] = [
  { id: "a1", title: "Ethiopia's Capital Markets: A Watershed Moment", category: "Market Commentary", excerpt: "As the Ethiopian Securities Exchange opens, we examine what the coming decade holds for issuers and institutional allocators.", body: "The launch of the Ethiopian Securities Exchange marks a structural pivot for domestic capital formation...", publishedAt: "2026-06-12", readMinutes: 7 },
  { id: "a2", title: "Sector Deep-Dive: The Emerging Ethiopian Logistics Corridor", category: "Sector Research", excerpt: "Infrastructure investment and cross-border trade liberalization are creating a new class of investable logistics platforms.", body: "Logistics has quietly become one of the most compelling sectors in East Africa...", publishedAt: "2026-05-04", readMinutes: 11 },
  { id: "a3", title: "BluFin Advises on Landmark ETB 3.2B Retail Financing", category: "Announcement", excerpt: "Our team acted as sole financial advisor on the largest private retail capital raise of the year.", body: "We are pleased to announce the closing of a landmark transaction...", publishedAt: "2026-04-19", readMinutes: 4 },
  { id: "a4", title: "ECMA Regulatory Brief: What the 2026 Framework Means for Issuers", category: "Regulatory", excerpt: "A practical read of the newest ECMA guidance and its implications for prospective public issuers.", body: "The 2026 ECMA guidance introduces significant clarifications on disclosure...", publishedAt: "2026-03-02", readMinutes: 9 },
];

function read<T>(key: string, fallback: T): T {
  if (typeof window === "undefined") return fallback;
  try {
    const raw = localStorage.getItem(key);
    if (!raw) return fallback;
    return JSON.parse(raw) as T;
  } catch {
    return fallback;
  }
}
function write<T>(key: string, value: T) {
  if (typeof window === "undefined") return;
  localStorage.setItem(key, JSON.stringify(value));
}

export function ensureSeeded() {
  if (typeof window === "undefined") return;
  if (!localStorage.getItem(KEYS.txns)) write(KEYS.txns, SEED_TXNS);
  if (!localStorage.getItem(KEYS.articles)) write(KEYS.articles, SEED_ARTICLES);
  if (!localStorage.getItem(KEYS.leads)) write(KEYS.leads, []);
}

// Transactions
export const txnStore = {
  list: () => { ensureSeeded(); return read<Transaction[]>(KEYS.txns, []); },
  save: (list: Transaction[]) => write(KEYS.txns, list),
  upsert(t: Transaction) {
    const list = this.list();
    const i = list.findIndex((x) => x.id === t.id);
    if (i >= 0) list[i] = t; else list.unshift(t);
    this.save(list);
  },
  remove(id: string) { this.save(this.list().filter((t) => t.id !== id)); },
};

// Articles
export const articleStore = {
  list: () => { ensureSeeded(); return read<Article[]>(KEYS.articles, []); },
  save: (list: Article[]) => write(KEYS.articles, list),
  upsert(a: Article) {
    const list = this.list();
    const i = list.findIndex((x) => x.id === a.id);
    if (i >= 0) list[i] = a; else list.unshift(a);
    this.save(list);
  },
  remove(id: string) { this.save(this.list().filter((a) => a.id !== id)); },
};

// Leads
export const leadStore = {
  list: () => { ensureSeeded(); return read<Lead[]>(KEYS.leads, []); },
  save: (list: Lead[]) => write(KEYS.leads, list),
  add(l: Lead) { this.save([l, ...this.list()]); },
  updateStatus(id: string, status: Lead["status"]) {
    this.save(this.list().map((l) => l.id === id ? { ...l, status } : l));
  },
  remove(id: string) { this.save(this.list().filter((l) => l.id !== id)); },
};

// Auth (mock)
export const authStore = {
  isSignedIn(): boolean {
    if (typeof window === "undefined") return false;
    return localStorage.getItem(KEYS.auth) === "1";
  },
  signIn(user: string, pass: string): boolean {
    if (user === "admin" && pass === "blufin2026") {
      localStorage.setItem(KEYS.auth, "1");
      return true;
    }
    return false;
  },
  signOut() { localStorage.removeItem(KEYS.auth); },
};

export function uid() {
  return Math.random().toString(36).slice(2, 10);
}
