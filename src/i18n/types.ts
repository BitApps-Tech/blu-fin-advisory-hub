export const LOCALES = [
  { code: "en", label: "English", native: "English" },
  { code: "am", label: "Amharic", native: "አማርኛ" },
  { code: "om", label: "Afan Oromo", native: "Afaan Oromoo" },
  { code: "ti", label: "Tigrigna", native: "ትግርኛ" },
  { code: "so", label: "Somali", native: "Soomaali" },
  { code: "zh", label: "Chinese", native: "中文" },
] as const;

export type Locale = (typeof LOCALES)[number]["code"];

export const LOCALE_STORAGE_KEY = "blufin-locale";

export function isLocale(value: string | null | undefined): value is Locale {
  return LOCALES.some((locale) => locale.code === value);
}
