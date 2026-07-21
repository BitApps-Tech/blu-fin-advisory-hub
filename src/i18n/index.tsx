import {
  createContext,
  useCallback,
  useContext,
  useEffect,
  useMemo,
  useState,
  type ReactNode,
} from "react";
import { en, type Dictionary } from "./locales/en";
import { isLocale, LOCALE_STORAGE_KEY, LOCALES, type Locale } from "./types";

type I18nContextValue = {
  locale: Locale;
  setLocale: (locale: Locale) => void;
  t: Dictionary;
  locales: typeof LOCALES;
};

const I18nContext = createContext<I18nContextValue | null>(null);

const cache: Partial<Record<Locale, Dictionary>> = { en };

async function loadDictionary(locale: Locale): Promise<Dictionary> {
  const hit = cache[locale];
  if (hit) return hit;

  let dict: Dictionary = en;
  switch (locale) {
    case "am":
      dict = (await import("./locales/am")).am;
      break;
    case "om":
      dict = (await import("./locales/om")).om;
      break;
    case "ti":
      dict = (await import("./locales/ti")).ti;
      break;
    case "so":
      dict = (await import("./locales/so")).so;
      break;
    case "zh":
      dict = (await import("./locales/zh")).zh;
      break;
    default:
      dict = en;
  }

  cache[locale] = dict;
  return dict;
}

function readStoredLocale(): Locale {
  if (typeof window === "undefined") return "en";
  try {
    const stored = window.localStorage.getItem(LOCALE_STORAGE_KEY);
    if (isLocale(stored)) return stored;
  } catch {
    /* ignore */
  }
  return "en";
}

export function I18nProvider({ children }: { children: ReactNode }) {
  const [locale, setLocaleState] = useState<Locale>("en");
  const [dict, setDict] = useState<Dictionary>(en);
  const [ready, setReady] = useState(false);

  useEffect(() => {
    const initial = readStoredLocale();
    setLocaleState(initial);
    if (initial === "en") {
      setReady(true);
      return;
    }
    let cancelled = false;
    void loadDictionary(initial).then((d) => {
      if (cancelled) return;
      setDict(d);
      setReady(true);
    });
    return () => {
      cancelled = true;
    };
  }, []);

  const setLocale = useCallback((next: Locale) => {
    setLocaleState(next);
    try {
      window.localStorage.setItem(LOCALE_STORAGE_KEY, next);
    } catch {
      /* ignore */
    }
    if (typeof document !== "undefined") {
      document.documentElement.lang = next;
    }
    if (next === "en") {
      setDict(en);
      return;
    }
    void loadDictionary(next).then(setDict);
  }, []);

  useEffect(() => {
    if (!ready) return;
    document.documentElement.lang = locale;
  }, [locale, ready]);

  const value = useMemo<I18nContextValue>(
    () => ({
      locale,
      setLocale,
      t: dict,
      locales: LOCALES,
    }),
    [locale, setLocale, dict],
  );

  return <I18nContext.Provider value={value}>{children}</I18nContext.Provider>;
}

export function useI18n() {
  const ctx = useContext(I18nContext);
  if (!ctx) throw new Error("useI18n must be used within I18nProvider");
  return ctx;
}

export { LOCALES, type Locale, type Dictionary };
