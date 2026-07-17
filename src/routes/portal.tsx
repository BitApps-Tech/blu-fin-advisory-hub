import { createFileRoute, Link, useNavigate } from "@tanstack/react-router";
import { useState } from "react";
import { Lock, ArrowLeft } from "lucide-react";
import { authStore } from "../lib/mock-store";
import { Logo } from "../components/Logo";
import { toast } from "sonner";
import photoPortal from "../assets/photo-team.png";

export const Route = createFileRoute("/portal")({
  head: () => ({
    meta: [
      { title: "Portal Login — BluFin Capital Advisory" },
      { name: "robots", content: "noindex" },
    ],
  }),
  component: Portal,
});

function Portal() {
  const navigate = useNavigate();
  const [user, setUser] = useState("");
  const [pass, setPass] = useState("");
  const [err, setErr] = useState("");

  function submit(e: React.FormEvent) {
    e.preventDefault();
    if (authStore.signIn(user, pass)) {
      toast.success("Signed in");
      navigate({ to: "/admin" });
    } else {
      setErr("Invalid credentials. Try admin / blufin2026");
    }
  }

  return (
    <div className="grid min-h-screen grid-cols-1 lg:grid-cols-2">
      <div className="relative hidden overflow-hidden lg:block">
        <img
          src={photoPortal}
          alt=""
          className="absolute inset-0 h-full w-full object-cover object-center"
        />
        <div className="absolute inset-0 bg-navy/75" />
        <div className="relative flex h-full flex-col justify-between p-16 text-navy-foreground">
          <Logo variant="light" />
          <div>
            <div className="eyebrow text-white/60">Administrative Portal</div>
            <h2 className="mt-4 max-w-md font-serif text-4xl leading-tight">
              Secure workspace for BluFin partners and administrative staff.
            </h2>
            <p className="mt-6 max-w-md text-sm text-white/70">
              Role-based access to the CMS, transaction ledger, and lead pipeline.
              All activity is logged and audited.
            </p>
          </div>
          <div className="text-xs text-white/50">© {new Date().getFullYear()} BluFin Capital Advisory PLC</div>
        </div>
      </div>

      <div className="flex items-center justify-center p-8">
        <div className="w-full max-w-sm">
          <Link to="/" className="mb-10 inline-flex items-center gap-2 text-xs uppercase tracking-widest text-slate-warm hover:text-navy">
            <ArrowLeft className="h-3.5 w-3.5" /> Back to site
          </Link>

          <div className="mb-8 flex items-center gap-3">
            <div className="flex h-10 w-10 items-center justify-center border border-hairline">
              <Lock className="h-4 w-4 text-navy" />
            </div>
            <div>
              <div className="eyebrow">Secure sign in</div>
              <div className="font-serif text-2xl text-navy">Portal Access</div>
            </div>
          </div>

          <form onSubmit={submit} className="space-y-5">
            <label className="block">
              <span className="eyebrow mb-2 block">Username</span>
              <input className="w-full border border-hairline bg-background py-3 px-3 text-sm outline-none focus:border-navy" value={user} onChange={(e) => setUser(e.target.value)} />
            </label>
            <label className="block">
              <span className="eyebrow mb-2 block">Password</span>
              <input type="password" className="w-full border border-hairline bg-background py-3 px-3 text-sm outline-none focus:border-navy" value={pass} onChange={(e) => setPass(e.target.value)} />
            </label>
            {err && <div className="text-xs text-destructive">{err}</div>}
            <button className="w-full bg-navy py-3.5 text-xs uppercase tracking-widest text-navy-foreground transition hover:bg-navy/90">
              Sign in
            </button>
          </form>

          <div className="hairline-t mt-10 pt-6 text-xs text-slate-warm">
            Demo credentials: <span className="font-medium text-navy">admin</span> / <span className="font-medium text-navy">blufin2026</span>
          </div>
        </div>
      </div>
    </div>
  );
}
