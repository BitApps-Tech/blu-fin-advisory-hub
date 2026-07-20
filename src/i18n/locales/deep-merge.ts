export function deepMerge<T extends object>(base: T, patch: Partial<T>): T {
  const result = { ...base };

  for (const key of Object.keys(patch) as (keyof T)[]) {
    const value = patch[key];
    const baseValue = base[key];

    if (
      value &&
      typeof value === "object" &&
      !Array.isArray(value) &&
      baseValue &&
      typeof baseValue === "object" &&
      !Array.isArray(baseValue)
    ) {
      result[key] = deepMerge(baseValue as object, value as object) as T[keyof T];
    } else if (value !== undefined) {
      result[key] = value as T[keyof T];
    }
  }

  return result;
}
