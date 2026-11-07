# Package integration notes

Verified APIs from installed `imrandevbd/*` packages (inspect `vendor/` when in doubt). Application adapters live under `app/Support/Integrations/` so vendor code is never patched.

## Laravel version

**Laravel 12** is pinned because `imrandevbd/laravel-admin-panel` and `imrandevbd/laravel-attendance-hub` constrain `illuminate/*` below Laravel 13 at the time of this project.

---

## imrandevbd/drutogoti

Performance / observability layer (query, cache, cookie, image watchers).

| Item | Detail |
|------|--------|
| Env | `DRUTOGOTI_ENABLED=true` (and related `DRUTOGOTI_*` keys in README) |
| Publish | `php artisan vendor:publish --tag=drutogoti-config` |
| Publish | `php artisan vendor:publish --tag=drutogoti-migrations` |

**Usage:** enable via config/env; no facade required for baseline operation. Adapter: `App\Support\Integrations\Drutogoti\DrutogotiBootstrap` if we need route-specific sampling rules.

---

## imrandevbd/laravel-admin-panel

Complementary admin layer alongside Filament panels (theme, RBAC helpers, multi-panel registry).

| Item | Detail |
|------|--------|
| Install | `php artisan admin-panel:install` |
| Register panel | `AdminPanel::registerPanel(Panel::make('id')->path('...'))` |

Namespaces: `ImranDevBD\LaravelAdminPanel\Facades\AdminPanel`, `ImranDevBD\LaravelAdminPanel\Panel\Panel`.

**In this app:** platform operations may register an admin-panel instance that mirrors `/platform` Filament access patterns. Adapter: `App\Support\Integrations\AdminPanel\FilamentAdminPanelBridge`.

---

## imrandevbd/laravel-ai-hub

Single gateway for LLM providers.

| Item | Detail |
|------|--------|
| Publish | `php artisan vendor:publish --tag=ai-hub-config` |
| Prompt | `AIHub::prompt('...')->send()` → `AiResponse` |
| Provider shortcut | `AIHub::gemini()` → `PendingRequest` |
| Tests | `AIHub::fake([...])` |

Per-tenant keys should be resolved in `App\Support\Integrations\AiHub\TenantAiHubConfigurator` before calling the facade.

---

## imrandevbd/laravel-ai-catalog

Scrape / generate catalog drafts for review before publish.

| Item | Detail |
|------|--------|
| Publish | `php artisan vendor:publish --tag=ai-catalog-config` |
| Keyword | `AiCatalog::fromKeyword('dry clean shirt price')->generate()` |
| URL | `AiCatalog::fromUrl('https://example.com/service')->generate()` |
| Approve | `AiCatalog::approve($generation)` |

Adapter: `App\Support\Integrations\AiCatalog\ServiceCatalogImporter` maps approved listings into our service catalog module.

---

## imrandevbd/laravel-attendance-hub

Biometric / device attendance sync and shift math.

| Item | Detail |
|------|--------|
| Publish | `vendor:publish --tag=attendance-hub-config` |
| Publish | `vendor:publish --tag=attendance-hub-migrations` |
| Fake device | `AttendanceHub::fake()` |
| Sync | `AttendanceHub::sync($device)` |
| Calculator | `AttendanceHub::calculator($shift)->calculate('EMP-101', '2026-09-17')` |

Adapter: `App\Support\Integrations\AttendanceHub\BranchDeviceSync`.

---

## imrandevbd/laravel-unicode-pdf

Unicode-safe PDFs (Bangla, Arabic, RTL).

```php
UnicodePdf::preset('bengali')
    ->loadView('pdf.receipt', $data)
    ->download('receipt.pdf');
```

Publish tags per package README (`unicode-pdf-config`, migrations). Adapter: `App\Support\Integrations\UnicodePdf\LaundryPdfFactory` for presets per tenant language.

---

## imrandevbd/laravel-universal-slug

Non-Latin slugs for services, branches, CMS pages.

```php
$slug = UniversalSlug::generate('ধোয়ার সার্ভিস', 'bn');
```

Eloquent: `HasUniversalSlug` trait + `SlugOptions::create()` on models.

Adapter: `App\Support\Integrations\UniversalSlug\TenantSlugOptions` for per-locale source fields.

---

## Adapter layout

```
app/Support/Integrations/
  AdminPanel/
  AiCatalog/
  AiHub/
  AttendanceHub/
  Drutogoti/
  UnicodePdf/
  UniversalSlug/
```

Each adapter documents gaps vs. product needs and delegates to the package facade/manager.
