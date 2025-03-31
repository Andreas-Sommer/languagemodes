# Belsignum Language Modes Extension

This TYPO3 extension `belsignum/languagemodes` allows dynamic per-page configuration of the language fallback mode in TYPO3 v10.

## 🧩 Features

- Configure `sys_language_mode` dynamically **per page**
- Supports the fallback modes: `free`, `fallback`, `strict`
- No TypoScript configuration required
- Editor field available only in the default language

## 🚀 Installation

Install via Composer:

```bash
composer require belsignum/languagemodes
```

## ⚠️ TYPO3 v10 – Core Patch Required

In TYPO3 v10, the core class `TYPO3\CMS\Core\Site\Entity\SiteLanguage` must be patched to add the method `setFallbackType()`.

This patch **must be integrated manually** via the root-level `composer.json`, using `cweagans/composer-patches`.
Modify your paths based on root.

### Example setup in composer.json:

```json
"extra": {
  "patches-file": "composer.patches.json"
}
```

### Example composer.patches.json:

```json
{
  "typo3/cms-core": {
    "Add setFallbackType method to SiteLanguage": "patches/SiteLanguage.diff"
  }
}
```

Then run:

```bash
composer install
```

> ❗ Without this patch, the extension will throw a RuntimeException during execution.

## ⚙️ Usage

1. In the page properties (default language only), set the field `Language mode override` to one of the options:
    - **Standard (inherited)**: Use global/default config
    - **Strict**: Content must exist in current language
    - **Fallback**: Use fallback chain as configured in site
    - **Free**: Freely mix content across fallbacks

2. The configured value applies globally for the page – not per language.
   TYPO3 v10 does not support per-language fallback configuration at runtime.

3. Fallback behavior is controlled via the (patched) `SiteLanguage` object during request initialization.

## 🧑‍💻 Credits

Developed by [Belsignum](https://www.belsignum.com) with ❤️ for TYPO3 integrators.

## 📄 License

Licensed under the MIT License.
