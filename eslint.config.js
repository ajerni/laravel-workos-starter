import globals from "globals";
import tseslint from "typescript-eslint";
import pluginVue from "eslint-plugin-vue";

export default [
  {
    ignores: ["node_modules/**", "vendor/**", "public/**", ".git/**"],
  },
  // Base config for all files
  {
    files: ["**/*.{js,mjs,cjs,ts,mts,cts,vue}"],
    languageOptions: {
      globals: {
        ...globals.browser,
        ...globals.node,
      },
    },
  },
  // TypeScript config
  ...tseslint.configs.recommended,
  // Vue config
  ...pluginVue.configs["flat/recommended"],
  // Specific Vue file config
  {
    files: ["**/*.vue"],
    languageOptions: {
      parserOptions: {
        parser: tseslint.parser,
        ecmaVersion: "latest",
        sourceType: "module",
        extraFileExtensions: [".vue"],
      },
    },
    rules: {
      // Disable problematic rules for Vue
      "@typescript-eslint/no-unused-vars": "off",
      "@typescript-eslint/no-explicit-any": "warn",
      "vue/multi-word-component-names": "off",
      "vue/no-unused-vars": "off",
      "vue/valid-v-model": "off",
    },
  },
]; 