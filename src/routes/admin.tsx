import { createFileRoute, useNavigate, Link } from "@tanstack/react-router";
import { useEffect, useMemo, useState } from "react";
import { toast } from "sonner";
import { Plus, Pencil, Trash2, Download, Search, LogOut, LayoutGrid, Newspaper, Inbox } from "lucide-react";
import {
  authStore,
  txnStore,
  articleStore,
  leadStore,
  uid,
  type Transaction,
  type Article,
  type Lead,
  ensureSeeded,
} from "../lib/mock-store";
import { Logo } from "../components/Logo";

export const Route = createFileRoute("/admin")({
  head: () => ({
    meta: [
      { title: "Admin — BluFin Capital Advisory" },
      { name: "robots", content: "noindex" },
    ],
  }),
  component: Admin,
});

type Tab = "transactions" | "articles" | "leads";

function Admin() {
  const navigate = useNavigate();
  const [ready, setReady] = useState(false);
  const [tab, setTab] = useState<Tab>("transactions");

  useEffect(() => {
    if (typeof window === "undefined") return;
    if (!authStore.isSignedIn()) {
      navigate({ to: "/portal" });
      return;
    }
    ensureSeeded();
    setReady(true);
  }, [navigate]);

  if (!ready) return null;

  return (
    <div className="min-h-screen bg-panel">
      {/* Top bar */}
      <header className="hairline-b bg-background">
        <div className="container-editorial flex h-16 items-center justify-between">
          <div className="flex items-center gap-6">
            <Link to="/"><Logo /></Link>
            <span className="hidden text-xs uppercase tracking-widest text-slate-warm md:inline">Administrative Portal</span>
          </div>
          <button
            onClick={() => { authStore.signOut(); navigate({ to: "/portal" }); }}
            className="inline-flex items-center gap-2 border border-hairline px-3 py-2 text-xs uppercase tracking-widest text-navy transition hover:bg-navy hover:text-navy-foreground"
          >
            <LogOut className="h-3.5 w-3.5" /> Sign out
          </button>
        </div>
      </header>

      <div className="container-editorial grid gap-8 py-10 md:grid-cols-12">
        {/* Sidebar */}
        <aside className="md:col-span-3">
          <div className="eyebrow mb-4">Workspace</div>
          <nav className="hairline-t hairline-b divide-y divide-hairline bg-background">
            <TabBtn active={tab === "transactions"} onClick={() => setTab("transactions")} icon={LayoutGrid} label="Transactions" />
            <TabBtn active={tab === "articles"} onClick={() => setTab("articles")} icon={Newspaper} label="Newsroom" />
            <TabBtn active={tab === "leads"} onClick={() => setTab("leads")} icon={Inbox} label="Lead Pipeline" />
          </nav>
        </aside>

        <section className="md:col-span-9">
          {tab === "transactions" && <TransactionsPanel />}
          {tab === "articles" && <ArticlesPanel />}
          {tab === "leads" && <LeadsPanel />}
        </section>
      </div>
    </div>
  );
}

function TabBtn({ active, onClick, icon: Icon, label }: { active: boolean; onClick: () => void; icon: any; label: string }) {
  return (
    <button
      onClick={onClick}
      className={`flex w-full items-center gap-3 px-4 py-3.5 text-left text-sm transition ${active ? "bg-navy text-navy-foreground" : "hover:bg-panel"}`}
    >
      <Icon className="h-4 w-4" strokeWidth={1.5} />
      {label}
    </button>
  );
}

/* ---------------- Transactions ---------------- */

const SECTORS: Transaction["sector"][] = ["Retail", "Logistics", "Tech", "Manufacturing", "Financial Services", "Energy"];
const SCALES: Transaction["scale"][] = ["Under $5M", "$5M–$25M", "$25M–$100M", "$100M+"];
const SERVICES_T: Transaction["service"][] = ["Corporate Finance", "Listing Solutions", "Transaction Advisory", "Private Equity"];

function TransactionsPanel() {
  const [list, setList] = useState<Transaction[]>([]);
  const [editing, setEditing] = useState<Transaction | null>(null);
  const [q, setQ] = useState("");

  const refresh = () => setList(txnStore.list());
  useEffect(refresh, []);

  const filtered = useMemo(
    () => list.filter((t) => !q || `${t.client} ${t.sector} ${t.service}`.toLowerCase().includes(q.toLowerCase())),
    [list, q]
  );

  return (
    <div>
      <PanelHeader
        title="Track Record"
        subtitle="Manage transactions displayed on the public ledger."
        action={
          <button
            onClick={() => setEditing({ id: "", client: "", sector: "Retail", scale: "Under $5M", service: "Corporate Finance", summary: "", milestone: "", year: new Date().getFullYear() })}
            className="inline-flex items-center gap-2 bg-navy px-4 py-2.5 text-xs uppercase tracking-widest text-navy-foreground"
          >
            <Plus className="h-3.5 w-3.5" /> New transaction
          </button>
        }
      />

      <div className="mb-4">
        <SearchInput value={q} onChange={setQ} placeholder="Search transactions…" />
      </div>

      <div className="hairline-t hairline-b bg-background">
        <div className="grid grid-cols-12 gap-4 px-5 py-3 text-[10px] uppercase tracking-widest text-slate-warm hairline-b">
          <div className="col-span-4">Client</div>
          <div className="col-span-2">Sector</div>
          <div className="col-span-2">Scale</div>
          <div className="col-span-2">Year</div>
          <div className="col-span-2 text-right">Actions</div>
        </div>
        {filtered.map((t) => (
          <div key={t.id} className="grid grid-cols-12 items-center gap-4 px-5 py-4 text-sm hairline-b last:border-b-0 hover:bg-panel">
            <div className="col-span-4 font-medium text-navy">{t.client}</div>
            <div className="col-span-2 text-slate-warm">{t.sector}</div>
            <div className="col-span-2 text-slate-warm">{t.scale}</div>
            <div className="col-span-2 text-slate-warm">{t.year}</div>
            <div className="col-span-2 flex justify-end gap-2">
              <IconBtn onClick={() => setEditing(t)} icon={Pencil} label="Edit" />
              <IconBtn onClick={() => { if (confirm("Delete this transaction?")) { txnStore.remove(t.id); refresh(); toast.success("Deleted"); } }} icon={Trash2} label="Delete" />
            </div>
          </div>
        ))}
        {filtered.length === 0 && <div className="p-12 text-center text-sm text-slate-warm">No transactions.</div>}
      </div>

      {editing && (
        <Modal title={editing.id ? "Edit transaction" : "New transaction"} onClose={() => setEditing(null)}>
          <div className="space-y-4">
            <FormField label="Client name"><input className={inp} value={editing.client} onChange={(e) => setEditing({ ...editing, client: e.target.value })} /></FormField>
            <div className="grid grid-cols-2 gap-4">
              <FormField label="Sector">
                <select className={inp} value={editing.sector} onChange={(e) => setEditing({ ...editing, sector: e.target.value as any })}>
                  {SECTORS.map((s) => <option key={s}>{s}</option>)}
                </select>
              </FormField>
              <FormField label="Scale">
                <select className={inp} value={editing.scale} onChange={(e) => setEditing({ ...editing, scale: e.target.value as any })}>
                  {SCALES.map((s) => <option key={s}>{s}</option>)}
                </select>
              </FormField>
              <FormField label="Service">
                <select className={inp} value={editing.service} onChange={(e) => setEditing({ ...editing, service: e.target.value as any })}>
                  {SERVICES_T.map((s) => <option key={s}>{s}</option>)}
                </select>
              </FormField>
              <FormField label="Year"><input type="number" className={inp} value={editing.year} onChange={(e) => setEditing({ ...editing, year: Number(e.target.value) })} /></FormField>
            </div>
            <FormField label="Milestone"><input className={inp} value={editing.milestone} onChange={(e) => setEditing({ ...editing, milestone: e.target.value })} placeholder="e.g. Closed Q3 2025" /></FormField>
            <FormField label="Summary"><textarea rows={4} className={inp} value={editing.summary} onChange={(e) => setEditing({ ...editing, summary: e.target.value })} /></FormField>

            <div className="flex justify-end gap-2 pt-2">
              <button onClick={() => setEditing(null)} className="border border-hairline px-4 py-2.5 text-xs uppercase tracking-widest">Cancel</button>
              <button
                onClick={() => {
                  if (!editing.client.trim()) return toast.error("Client name required");
                  const payload = { ...editing, id: editing.id || uid() };
                  txnStore.upsert(payload);
                  refresh(); setEditing(null); toast.success("Saved");
                }}
                className="bg-navy px-4 py-2.5 text-xs uppercase tracking-widest text-navy-foreground"
              >
                Save
              </button>
            </div>
          </div>
        </Modal>
      )}
    </div>
  );
}

/* ---------------- Articles ---------------- */

const CATS: Article["category"][] = ["Market Commentary", "Sector Research", "Announcement", "Regulatory"];

function ArticlesPanel() {
  const [list, setList] = useState<Article[]>([]);
  const [editing, setEditing] = useState<Article | null>(null);
  const [q, setQ] = useState("");
  const refresh = () => setList(articleStore.list());
  useEffect(refresh, []);

  const filtered = useMemo(
    () => list.filter((a) => !q || `${a.title} ${a.category}`.toLowerCase().includes(q.toLowerCase())),
    [list, q]
  );

  return (
    <div>
      <PanelHeader
        title="Newsroom"
        subtitle="Publish research, commentary and firm announcements."
        action={
          <button
            onClick={() => setEditing({ id: "", title: "", category: "Market Commentary", excerpt: "", body: "", publishedAt: new Date().toISOString().slice(0, 10), readMinutes: 5 })}
            className="inline-flex items-center gap-2 bg-navy px-4 py-2.5 text-xs uppercase tracking-widest text-navy-foreground"
          >
            <Plus className="h-3.5 w-3.5" /> New article
          </button>
        }
      />

      <div className="mb-4"><SearchInput value={q} onChange={setQ} placeholder="Search articles…" /></div>

      <div className="hairline-t hairline-b bg-background">
        {filtered.map((a) => (
          <div key={a.id} className="flex items-center justify-between gap-4 px-5 py-4 hairline-b last:border-b-0 hover:bg-panel">
            <div className="min-w-0">
              <div className="eyebrow">{a.category} · {new Date(a.publishedAt).toLocaleDateString()}</div>
              <div className="mt-1 truncate font-serif text-lg text-navy">{a.title}</div>
              <div className="mt-1 line-clamp-1 text-xs text-slate-warm">{a.excerpt}</div>
            </div>
            <div className="flex gap-2">
              <IconBtn onClick={() => setEditing(a)} icon={Pencil} label="Edit" />
              <IconBtn onClick={() => { if (confirm("Delete this article?")) { articleStore.remove(a.id); refresh(); toast.success("Deleted"); } }} icon={Trash2} label="Delete" />
            </div>
          </div>
        ))}
        {filtered.length === 0 && <div className="p-12 text-center text-sm text-slate-warm">No articles.</div>}
      </div>

      {editing && (
        <Modal title={editing.id ? "Edit article" : "New article"} onClose={() => setEditing(null)}>
          <div className="space-y-4">
            <FormField label="Title"><input className={inp} value={editing.title} onChange={(e) => setEditing({ ...editing, title: e.target.value })} /></FormField>
            <div className="grid grid-cols-3 gap-4">
              <FormField label="Category">
                <select className={inp} value={editing.category} onChange={(e) => setEditing({ ...editing, category: e.target.value as any })}>
                  {CATS.map((c) => <option key={c}>{c}</option>)}
                </select>
              </FormField>
              <FormField label="Publish date"><input type="date" className={inp} value={editing.publishedAt.slice(0, 10)} onChange={(e) => setEditing({ ...editing, publishedAt: e.target.value })} /></FormField>
              <FormField label="Read (min)"><input type="number" className={inp} value={editing.readMinutes} onChange={(e) => setEditing({ ...editing, readMinutes: Number(e.target.value) })} /></FormField>
            </div>
            <FormField label="Excerpt"><textarea rows={3} className={inp} value={editing.excerpt} onChange={(e) => setEditing({ ...editing, excerpt: e.target.value })} /></FormField>
            <FormField label="Body"><textarea rows={7} className={inp} value={editing.body} onChange={(e) => setEditing({ ...editing, body: e.target.value })} /></FormField>

            <div className="flex justify-end gap-2 pt-2">
              <button onClick={() => setEditing(null)} className="border border-hairline px-4 py-2.5 text-xs uppercase tracking-widest">Cancel</button>
              <button
                onClick={() => {
                  if (!editing.title.trim()) return toast.error("Title required");
                  const payload = { ...editing, id: editing.id || uid() };
                  articleStore.upsert(payload);
                  refresh(); setEditing(null); toast.success("Saved");
                }}
                className="bg-navy px-4 py-2.5 text-xs uppercase tracking-widest text-navy-foreground"
              >
                Save
              </button>
            </div>
          </div>
        </Modal>
      )}
    </div>
  );
}

/* ---------------- Leads ---------------- */

const STATUSES: Lead["status"][] = ["new", "reviewing", "contacted", "archived"];

function LeadsPanel() {
  const [list, setList] = useState<Lead[]>([]);
  const [q, setQ] = useState("");
  const [status, setStatus] = useState<string>("all");
  const [selected, setSelected] = useState<Lead | null>(null);
  const refresh = () => setList(leadStore.list());
  useEffect(refresh, []);

  const filtered = useMemo(
    () => list.filter((l) =>
      (status === "all" || l.status === status) &&
      (!q || `${l.name} ${l.email} ${l.company} ${l.sector}`.toLowerCase().includes(q.toLowerCase()))
    ),
    [list, q, status]
  );

  function exportCsv() {
    const headers = ["Name", "Email", "Company", "Capital", "Sector", "Service", "Status", "Submitted", "Message"];
    const rows = filtered.map((l) => [l.name, l.email, l.company, l.capitalNeeds, l.sector, l.service, l.status, l.submittedAt, l.message.replace(/\n/g, " ")]);
    const csv = [headers, ...rows].map((r) => r.map((c) => `"${String(c).replace(/"/g, '""')}"`).join(",")).join("\n");
    const blob = new Blob([csv], { type: "text/csv" });
    const url = URL.createObjectURL(blob);
    const a = document.createElement("a");
    a.href = url; a.download = `blufin-leads-${new Date().toISOString().slice(0, 10)}.csv`;
    a.click();
    URL.revokeObjectURL(url);
    toast.success("Exported CSV");
  }

  return (
    <div>
      <PanelHeader
        title="Lead Pipeline"
        subtitle="Consultation inquiries submitted through the public contact form."
        action={
          <button onClick={exportCsv} className="inline-flex items-center gap-2 border border-navy px-4 py-2.5 text-xs uppercase tracking-widest text-navy transition hover:bg-navy hover:text-navy-foreground">
            <Download className="h-3.5 w-3.5" /> Export CSV
          </button>
        }
      />

      <div className="mb-4 flex flex-wrap gap-3">
        <div className="min-w-[240px] flex-1"><SearchInput value={q} onChange={setQ} placeholder="Search leads…" /></div>
        <select value={status} onChange={(e) => setStatus(e.target.value)} className="border border-hairline bg-background px-3 py-2.5 text-sm outline-none focus:border-navy">
          <option value="all">All statuses</option>
          {STATUSES.map((s) => <option key={s} value={s}>{s}</option>)}
        </select>
      </div>

      <div className="hairline-t hairline-b bg-background">
        <div className="grid grid-cols-12 gap-4 px-5 py-3 text-[10px] uppercase tracking-widest text-slate-warm hairline-b">
          <div className="col-span-3">Name</div>
          <div className="col-span-3">Company</div>
          <div className="col-span-2">Service</div>
          <div className="col-span-2">Submitted</div>
          <div className="col-span-2">Status</div>
        </div>
        {filtered.map((l) => (
          <button
            key={l.id}
            onClick={() => setSelected(l)}
            className="grid w-full grid-cols-12 items-center gap-4 px-5 py-4 text-left text-sm hairline-b last:border-b-0 hover:bg-panel"
          >
            <div className="col-span-3 font-medium text-navy">{l.name}<div className="text-xs font-normal text-slate-warm">{l.email}</div></div>
            <div className="col-span-3 text-slate-warm">{l.company}</div>
            <div className="col-span-2 text-slate-warm">{l.service}</div>
            <div className="col-span-2 text-slate-warm">{new Date(l.submittedAt).toLocaleDateString()}</div>
            <div className="col-span-2"><StatusPill status={l.status} /></div>
          </button>
        ))}
        {filtered.length === 0 && <div className="p-12 text-center text-sm text-slate-warm">No leads yet. Submissions from the public contact form will appear here.</div>}
      </div>

      {selected && (
        <Modal title="Lead detail" onClose={() => setSelected(null)}>
          <div className="space-y-4 text-sm">
            <Row k="Name" v={selected.name} />
            <Row k="Email" v={selected.email} />
            <Row k="Company" v={selected.company} />
            <Row k="Capital needs" v={selected.capitalNeeds} />
            <Row k="Sector" v={selected.sector} />
            <Row k="Service" v={selected.service} />
            <Row k="Submitted" v={new Date(selected.submittedAt).toLocaleString()} />
            <div>
              <div className="eyebrow mb-2">Message</div>
              <div className="hairline-t hairline-b whitespace-pre-wrap py-4 text-foreground/85">{selected.message}</div>
            </div>
            <div>
              <div className="eyebrow mb-2">Status</div>
              <div className="flex gap-2">
                {STATUSES.map((s) => (
                  <button key={s} onClick={() => { leadStore.updateStatus(selected.id, s); setSelected({ ...selected, status: s }); refresh(); }}
                    className={`px-3 py-2 text-xs uppercase tracking-widest transition ${selected.status === s ? "bg-navy text-navy-foreground" : "border border-hairline text-slate-warm hover:text-navy"}`}>
                    {s}
                  </button>
                ))}
              </div>
            </div>
            <div className="flex justify-between pt-4">
              <button
                onClick={() => { if (confirm("Delete this lead?")) { leadStore.remove(selected.id); refresh(); setSelected(null); toast.success("Deleted"); } }}
                className="text-xs uppercase tracking-widest text-destructive hover:underline"
              >Delete</button>
              <button onClick={() => setSelected(null)} className="border border-hairline px-4 py-2.5 text-xs uppercase tracking-widest">Close</button>
            </div>
          </div>
        </Modal>
      )}
    </div>
  );
}

/* ---------------- Shared UI ---------------- */

const inp = "w-full border border-hairline bg-background py-2.5 px-3 text-sm outline-none focus:border-navy";

function PanelHeader({ title, subtitle, action }: { title: string; subtitle: string; action?: React.ReactNode }) {
  return (
    <div className="mb-8 flex flex-wrap items-end justify-between gap-4">
      <div>
        <div className="eyebrow">Panel</div>
        <h2 className="mt-2 font-serif text-3xl text-navy">{title}</h2>
        <p className="mt-1 text-sm text-muted-foreground">{subtitle}</p>
      </div>
      {action}
    </div>
  );
}

function SearchInput({ value, onChange, placeholder }: { value: string; onChange: (v: string) => void; placeholder?: string }) {
  return (
    <div className="relative">
      <Search className="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-warm" />
      <input value={value} onChange={(e) => onChange(e.target.value)} placeholder={placeholder}
        className="w-full border border-hairline bg-background py-2.5 pl-10 pr-3 text-sm outline-none focus:border-navy" />
    </div>
  );
}

function IconBtn({ onClick, icon: Icon, label }: { onClick: () => void; icon: any; label: string }) {
  return (
    <button onClick={onClick} aria-label={label} title={label}
      className="inline-flex h-8 w-8 items-center justify-center border border-hairline text-slate-warm transition hover:border-navy hover:text-navy">
      <Icon className="h-3.5 w-3.5" />
    </button>
  );
}

function FormField({ label, children }: { label: string; children: React.ReactNode }) {
  return (
    <label className="block">
      <span className="eyebrow mb-1.5 block">{label}</span>
      {children}
    </label>
  );
}

function Modal({ title, onClose, children }: { title: string; onClose: () => void; children: React.ReactNode }) {
  return (
    <div className="fixed inset-0 z-50 flex items-start justify-center overflow-y-auto bg-black/50 p-4 md:p-10" onClick={onClose}>
      <div className="w-full max-w-2xl bg-background p-8" onClick={(e) => e.stopPropagation()}>
        <div className="mb-6 flex items-center justify-between">
          <h3 className="font-serif text-2xl text-navy">{title}</h3>
          <button onClick={onClose} className="text-xs uppercase tracking-widest text-slate-warm hover:text-navy">Close</button>
        </div>
        {children}
      </div>
    </div>
  );
}

function StatusPill({ status }: { status: Lead["status"] }) {
  const map: Record<Lead["status"], string> = {
    new: "bg-navy text-navy-foreground",
    reviewing: "border border-navy text-navy",
    contacted: "border border-hairline text-slate-warm",
    archived: "bg-panel text-slate-warm",
  };
  return <span className={`inline-flex px-2 py-1 text-[10px] uppercase tracking-widest ${map[status]}`}>{status}</span>;
}

function Row({ k, v }: { k: string; v: string }) {
  return (
    <div className="grid grid-cols-3 gap-4 border-b border-hairline pb-2">
      <div className="eyebrow">{k}</div>
      <div className="col-span-2 text-foreground/90">{v}</div>
    </div>
  );
}
