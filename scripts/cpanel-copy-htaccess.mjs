import { copyFileSync, existsSync, renameSync } from "node:fs";
import { join } from "node:path";

const outDir = join(process.cwd(), "dist-cpanel");
const publicDir = join(process.cwd(), "public");
const spaHtml = join(outDir, "index.cpanel.html");
const indexHtml = join(outDir, "index.html");

if (!existsSync(outDir)) {
  console.error("Missing dist-cpanel — run build:cpanel first.");
  process.exit(1);
}

if (existsSync(spaHtml)) {
  renameSync(spaHtml, indexHtml);
  console.log("Renamed index.cpanel.html → index.html");
}

for (const file of [".htaccess", "robots.txt", "sitemap.xml", "llms.txt"]) {
  const src = join(publicDir, file);
  if (existsSync(src)) {
    copyFileSync(src, join(outDir, file));
    console.log(`Copied ${file} → dist-cpanel/`);
  } else {
    console.warn(`public/${file} missing`);
  }
}

if (!existsSync(indexHtml)) {
  console.error("Missing dist-cpanel/index.html after build.");
  process.exit(1);
}

console.log("cPanel static build ready in dist-cpanel/");
