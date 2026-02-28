<div align="center">

# 🚗 dekra_importcertificate

### TYPO3 13 Extension – DEKRA Importcertificate24 Integration

[![TYPO3](https://img.shields.io/badge/TYPO3-13.x-orange.svg)](https://typo3.org)
[![PHP](https://img.shields.io/badge/PHP-8.1+-blue.svg)](https://php.net)
[![License](https://img.shields.io/badge/License-GPL--2.0--or--later-green.svg)](LICENSE)
[![Version](https://img.shields.io/badge/Version-1.0.0-brightgreen.svg)](https://github.com/your-org/dekra_importcertificate/releases)

**Integrate the [DEKRA Importcertificate24](https://dekra-importcertificate24.de) portal directly into your TYPO3 website.**  
Let customers request vehicle import certificates without leaving your site.

[Deutsch](#deutsch) · [English](#english) · [Installation](#installation) · [Plugins](#plugins) · [Configuration](#configuration)

</div>

---

## Deutsch

### Was macht diese Extension?

`dekra_importcertificate` bindet das Portal **dekra-importcertificate24.de** in TYPO3 13 ein. Fahrzeugbesitzer und Händler können direkt auf Ihrer Website kostenlose, unverbindliche Anfragen für Importzertifikate (§ 21 StVZO, § 13 FZV, Abgasgutachten u. a.) stellen.

**Kernfeatures:**
- 🔍 **Automatischer VIN/FIN-Lookup** – Fahrzeugdaten per AJAX abrufen
- 📋 **3-stufiger Anfrageassistent** – geführter Prozess mit Fortschrittsanzeige
- 💾 **Datenspeicherung im TYPO3-Backend** – alle Anfragen zentral verwalten
- 📧 **E-Mail-Bestätigung** – automatisch an Kunden und Admin
- 🧪 **Sandbox-Modus** – vollständig testbar ohne API-Key
- 🎨 **Responsives Design** – mobile-optimierte Templates
- 🔧 **Vollständig anpassbar** – Templates und CSS per TypoScript überschreibbar

---

## English

### What does this extension do?

`dekra_importcertificate` integrates the **dekra-importcertificate24.de** portal into TYPO3 13. Vehicle owners and dealers can submit free, non-binding requests for import certificates (§ 21 StVZO, § 13 FZV, emission reports, etc.) directly on your website.

**Key Features:**
- 🔍 **Automatic VIN/FIN lookup** – fetch vehicle data via AJAX
- 📋 **3-step request wizard** – guided process with step indicator
- 💾 **TYPO3 backend storage** – manage all requests centrally
- 📧 **Email confirmation** – automatic notifications to customer and admin
- 🧪 **Sandbox mode** – fully testable without an API key
- 🎨 **Responsive design** – mobile-optimised templates
- 🔧 **Fully customisable** – override templates and CSS via TypoScript

---

## Requirements

| Component | Version |
|-----------|---------|
| TYPO3 CMS | `^13.0` |
| PHP | `^8.1` |
| EXT:extbase | Core (included) |
| EXT:fluid | Core (included) |
| MySQL / MariaDB | 8.0+ / 10.4+ |

---

## Installation

### Composer (recommended)

```bash
composer require vendor/dekra-importcertificate
```

### Manual

Copy the `dekra_importcertificate` folder into your TYPO3 `packages/` or `typo3conf/ext/` directory.

### After Installation

1. **Activate** the extension in *Admin Tools → Extensions*
2. **Update database**: *Admin Tools → Maintenance → Analyze Database Structure*
3. **Include TypoScript**: Add the static template *"DEKRA Importcertificate24"* to your root template
4. **Configure** the extension (see [Configuration](#configuration))

---

## Plugins

This extension ships **four plugins**, all registerable as content elements:

| Plugin | Key | Description |
|--------|-----|-------------|
| Zertifikatsanfrage | `CertificateRequest` | 3-step wizard: vehicle data → certificate & contact → summary & submit |
| Fahrzeugsuche | `VehicleSearch` | VIN/FIN lookup with AJAX vehicle data retrieval |
| Beispielzertifikate | `SampleCertificates` | Showcase of sample certificates by origin country |
| FAQ | `Faq` | Structured FAQ: import process, customs, pricing |

### Adding a Plugin to a Page

1. Open a page in the TYPO3 backend
2. *Add content element → Plugins tab*
3. Select the desired plugin
4. Save and flush caches

---

## Configuration

### Extension Settings

Go to *Admin Tools → Settings → Extension Configuration → `dekra_importcertificate`*:

| Setting | Default | Description |
|---------|---------|-------------|
| `apiKey` | *(empty)* | API key provided by DEKRA Importcertificate24 |
| `partnerId` | *(empty)* | Your partner ID |
| `sandboxMode` | `1` | `1` = demo mode (mock data), `0` = live |
| `senderEmail` | `info@example.com` | From-address for outgoing emails |
| `senderName` | `DEKRA Importcertificate` | From-name for outgoing emails |
| `adminEmail` | *(empty)* | Internal recipient for new-request alerts |

> **Sandbox mode**: Without an API key or with `sandboxMode = 1`, the extension returns plausible mock data. The full form flow, email dispatch, and backend storage all work normally.

### TypoScript

The static template sets sensible defaults. Override as needed:

```typoscript
plugin.tx_dekraimportcertificate {
    persistence {
        storagePid = 42    # PID of your storage page
    }

    view {
        # Override templates from your SitePackage
        templateRootPaths.10 = EXT:your_site/Resources/Private/DekraImportcertificate/Templates/
        partialRootPaths.10  = EXT:your_site/Resources/Private/DekraImportcertificate/Partials/
    }

    settings {
        pages {
            requestPid      = 10   # Page with the request form
            confirmationPid = 11   # Confirmation page PID
            statusPid       = 12   # Status check page PID
        }
    }
}
```

---

## How It Works

```
User visits page with CertificateRequest plugin
            │
            ▼
┌────────────────────────┐
│  Step 1 – Vehicle Data │
│  · Enter VIN/FIN       │
│  · AJAX lookup         │◄── DekraApiService::lookupVehicleByVin()
│  · Make / Model / Year │
│  · Origin country      │
└──────────┬─────────────┘
           │  Session storage
           ▼
┌────────────────────────────────┐
│  Step 2 – Certificate & Contact│
│  · Choose certificate type     │
│  · Enter name, address, email  │
│  · Optional: express service   │
└──────────┬─────────────────────┘
           │  Session storage
           ▼
┌─────────────────────────┐
│  Step 3 – Summary       │
│  · Review all data      │
│  · Accept Privacy / ToS │
│  · Submit               │
└──────────┬──────────────┘
           │
           ▼
    ┌──────┴───────┐
    │              │
    ▼              ▼
Save to DB    Call DEKRA API
(always)      (if API key set)
    │              │
    └──────┬───────┘
           │
           ▼
  Send confirmation emails
  (customer + admin)
           │
           ▼
  Confirmation page
  with reference number
```

---

## Directory Structure

```
dekra_importcertificate/
├── Classes/
│   ├── Controller/
│   │   ├── AjaxController.php            # AJAX VIN lookup endpoint
│   │   ├── CertificateController.php     # Main 3-step form controller
│   │   ├── FaqController.php
│   │   ├── SampleCertificateController.php
│   │   └── VehicleSearchController.php
│   ├── Domain/
│   │   ├── Model/
│   │   │   └── CertificateRequest.php    # Domain model with all fields
│   │   └── Repository/
│   │       └── CertificateRequestRepository.php
│   └── Service/
│       ├── DekraApiService.php           # All DEKRA API calls
│       └── EmailService.php             # Customer & admin emails
├── Configuration/
│   ├── Backend/AjaxRoutes.php
│   ├── Services.yaml                    # DI autowiring
│   ├── TCA/
│   │   ├── Overrides/tt_content.php    # Plugin registration
│   │   └── tx_..._certificaterequest.php
│   └── TypoScript/
│       ├── constants.typoscript
│       └── setup.typoscript
├── Resources/
│   ├── Private/
│   │   ├── Templates/Certificate/
│   │   │   ├── Index.html              # Landing page
│   │   │   ├── Step1.html
│   │   │   ├── Step2.html
│   │   │   ├── Step3.html
│   │   │   └── Confirmation.html
│   │   ├── Partials/StepIndicator.html
│   │   ├── Layouts/Default.html
│   │   └── Language/                   # locallang.xlf files
│   └── Public/
│       ├── Css/DekraImportcertificate.css
│       └── JavaScript/DekraImportcertificate.js
├── composer.json
├── ext_conf_template.txt
├── ext_emconf.php
├── ext_localconf.php
└── ext_tables.sql
```

---

## Customising Templates

Copy any template to your SitePackage and register the path:

```typoscript
plugin.tx_dekraimportcertificate.view {
    templateRootPaths.10 = EXT:your_site/Resources/Private/DekraImportcertificate/Templates/
}
```

Then create `EXT:your_site/.../Templates/Certificate/Step1.html` to override Step 1, etc.

### CSS Custom Properties

The stylesheet uses CSS variables – override them without modifying the extension:

```css
/* In your site CSS */
:root {
    --dekra-green:       #009b4e;
    --dekra-green-dark:  #007a3d;
    --dekra-blue:        #0055a5;
}
```

---

## Backend

All submitted requests appear in the TYPO3 backend under *Web → List* on the storage page.

**Status workflow:**

```
new  →  pending  →  processing  →  completed
                                ↘  rejected
```

| Status | Description |
|--------|-------------|
| `new` | Just received, not yet transmitted to API |
| `pending` | Transmitted to DEKRA API |
| `processing` | DEKRA is working on it |
| `completed` | Certificate created and sent |
| `rejected` | Could not be processed |

---

## API Integration

`DekraApiService` handles all communication with the DEKRA portal:

```php
// Inject via constructor (autowired)
public function __construct(
    private readonly DekraApiService $dekraApiService
) {}

// VIN lookup
$vehicleData = $this->dekraApiService->lookupVehicleByVin('1HGCM82633A123456');

// Submit request
$response = $this->dekraApiService->submitCertificateRequest($certificateRequest);

// Check status
$status = $this->dekraApiService->getRequestStatus('DEKRA-20250201-AB12CD');
```

When `sandboxMode = 1` or no API key is set, all methods return mock data – no real HTTP requests are made.

---

## Troubleshooting

| Problem | Solution |
|---------|----------|
| Plugin not visible in backend | Flush all caches |
| Form doesn't save data | Check `persistence.storagePid` in TypoScript |
| VIN lookup fails | Verify API key or enable `sandboxMode = 1` |
| Emails not sending | Check mail config in TYPO3 Install Tool |
| CSS not loading | Ensure TypoScript static template is included |
| Database table missing | Run *Analyze Database Structure* in Admin Tools |

---

## Contributing

1. Fork this repository
2. Create a feature branch: `git checkout -b feature/my-feature`
3. Commit your changes: `git commit -m 'Add my feature'`
4. Push to the branch: `git push origin feature/my-feature`
5. Open a Pull Request

Please follow [TYPO3 Coding Guidelines](https://docs.typo3.org/m/typo3/reference-coreapi/main/en-us/CodingGuidelines/Index.html).

---

## Changelog

### 1.0.0 (2025-02-01)
- Initial release
- 4 plugins: CertificateRequest, VehicleSearch, SampleCertificates, Faq
- 3-step form wizard with session-based state
- AJAX VIN/FIN lookup
- DekraApiService with sandbox mode
- Email service (customer confirmation + admin notification)
- Full TYPO3 backend TCA integration
- Responsive CSS with CSS custom properties

---

## License

This extension is released under the **GPL-2.0-or-later** license.  
See [LICENSE](LICENSE) for details.

---

<div align="center">

**Portal:** [dekra-importcertificate24.de](https://dekra-importcertificate24.de) · **TYPO3:** [typo3.org](https://typo3.org)

</div>
