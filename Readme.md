# Belsignum Language Modes Extension

This TYPO3 extension allows you to dynamically override the `fallbackType` for `SiteLanguage` **per page and language** at runtime.

## 🧩 Features

- Define language fallback behavior (`free`, `fallback`, `strict`) per language version of each page
- Fully compatible with TYPO3 v12
- Uses `SiteFinder` and `SiteConfiguration` XCLASS for clean and early injection
- Modifies both `SiteLanguage` and `LanguageAspect` at runtime
- Configuration is cached safely and applies consistently across the TYPO3 request lifecycle
- No core patch required since v12

## 🔧 How it works

- A `select` field is added to the `pages` table (`tx_languagemodes_mode`)
- This field is **only visible in translated pages** (i.e., `sys_language_uid > 0`)
- When a page is resolved, the SiteFinder XCLASS loads the language-specific configuration from the DB and replaces the SiteLanguage for the current request
- Additionally, the `LanguageAspect` is updated to ensure proper behavior in content fetching and overlays

## 🛠 Installation

Install via Composer:

```bash
composer require belsignum/languagemodes
```

## ⚙️ Configuration

No special configuration needed. Once the extension is installed:

- Open the page module
- Switch to a language version of a page (not the default language)
- You will see a select field labeled `Language fallback mode` with the options:
    - Free
    - Fallback
    - Strict

If no value is selected, the global fallback behavior from the site config applies.

## 🧠 Why we use `SiteFinder` / `SiteConfiguration` – and not Middleware, Events or Hooks

TYPO3 offers multiple ways to influence site and language resolution.
After extensive evaluation, this extension **intentionally avoids** Middleware or Event-based approaches for the following reasons:

### ❌ Why not Middleware?

- Middlewares like `typo3/cms-frontend/site` resolve the site and language context **before** any custom fallback logic can take effect.
- Injecting a modified `SiteLanguage` or `LanguageAspect` into `$GLOBALS['TYPO3_REQUEST']` **after site resolution** has **no influence** on the request lifecycle.
- Custom middlewares cannot safely override fallbackType without risk of unstable or version-dependent behavior.

### ❌ Why not Events?

- Events such as `BootCompletedEvent` or `SiteConfigurationBeforeWriteEvent` are either:
    - **Too early** (before routing and site resolution),
    - **Too late** (after the fallback logic has already been applied),
    - or only trigger on configuration **write operations**, not at runtime.
- As of TYPO3 v12, there is **no `ModifyResolvedSiteLanguageEvent`** or similar that would allow request-based manipulation of fallback behavior.

### ❌ Why not Hooks?

- While legacy hooks like `configArrayPostProc` are still technically available in TYPO3 v12, they no longer have meaningful influence on language resolution.
- These hooks operate after `TSFE` initialization and only affect TypoScript-based values like `config.sys_language_mode`.
- Since TYPO3 v9+, language fallback is controlled via `SiteLanguage` and `Context`, not TypoScript.
- Hook-based solutions are not reliable in modern TYPO3 setups and are not suitable for manipulating `SiteLanguage` or `LanguageAspect`.

➡️ Therefore, we avoid using legacy hooks and rely on early runtime overrides instead.

### ✅ Why `SiteFinder` and `SiteConfiguration` XCLASSing is used

- `SiteFinder` and `SiteConfiguration` are part of the early site and language resolution process.
- By XCLASSing both, the extension can:
    - Load the language-specific configuration from the database
    - Dynamically set the `fallbackType` per language and page
    - Safely inject a new `SiteLanguage` and corresponding `LanguageAspect` into the runtime context
- This approach is:
    - Core-compliant
    - Fully runtime-safe
    - Patch-free
    - Compatible with TYPO3 v12+


## 🧑‍💻 Credits

Developed by [Belsignum](https://www.belsignum.com) with ❤️ for TYPO3 integrators.

## 📝 License

Licensed under the [MIT License](https://opensource.org/licenses/MIT)
