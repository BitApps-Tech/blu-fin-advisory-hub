import { copyFileSync, existsSync, renameSync } from "node:fs";
import { join } from "node:path";

const outDir = join(process.cwd(), "dist-cpanel");
const htaccessSrc = join(process.cwd(), "public", ".htaccess");
const spaHtml = join(outDir, "index.cpanel.html");
const indexHtml = join(outDir, "index.html");

if (!existsSync(outDir)) {
  console.error("Missing dist-cpanel — run build:cpanel first.");
  process.exit(1);
}

if (existsSync(spaHtml)) {
  if (existsSync(indexHtml)) {
    // Prefer the SPA entry name Apache expects
  }
  renameSync(spaHtml, indexHtml);
  console.log("Renamed index.cpanel.html → index.html");
}

if (existsSync(htaccessSrc)) {
  copyFileSync(htaccessSrc, join(outDir, ".htaccess"));
  console.log("Copied .htaccess → dist-cpanel/");
} else {
  console.warn("public/.htaccess missing");
}

if (!existsSync(indexHtml)) {
  console.error("Missing dist-cpanel/index.html after build.");
  process.exit(1);
}

console.log("cPanel static build ready in dist-cpanel/");
