# DSGVO Cookie Banner – Full Documentation (English)

**Version:** 1.0.0 | **Language:** English | **Audience:** Developers & WordPress Administrators

---

## Table of Contents

1. [Introduction](#1-introduction)
2. [Installation](#2-installation)
3. [Quick Start](#3-quick-start)
4. [Settings](#4-settings)
5. [Cookie Scanner](#5-cookie-scanner)
6. [Shortcodes](#6-shortcodes)
7. [Script Blocking](#7-script-blocking)
8. [Consent Log](#8-consent-log)
9. [Design & Customization](#9-design--customization)
10. [JavaScript API](#10-javascript-api)
11. [Database](#11-database)
12. [GDPR Compliance](#12-gdpr-compliance)
13. [Frequently Asked Questions](#13-frequently-asked-questions)
14. [Troubleshooting](#14-troubleshooting)
15. [Developer Reference](#15-developer-reference)

---

## 1. Introduction

**DSGVO Cookie Banner** is a WordPress plugin that provides a legally compliant cookie notice in accordance with the General Data Protection Regulation (GDPR) and the guidelines of the European Data Protection Board (EDPB).

### Legal Basis

The plugin is designed to comply with:

- **GDPR Art. 6(1)(a)** – Consent as lawful basis for processing
- **GDPR Art. 7** – Conditions for consent
- **GDPR Art. 13** – Information to be provided at the time of data collection
- **ePrivacy Directive (2002/58/EC)** – Cookie regulations
- **EDPB Guidelines 05/2020** – Consent under the GDPR

### How It Works

```
First page visit
      │
      ▼
No consent cookie present?
      │ YES
      ▼
Show cookie banner
      │
  ┌───┴──────────────────────────┐
  │                              │
  ▼                              ▼
"Accept All"          "Necessary Only" / "Settings"
  │                              │
  ▼                              ▼
All categories enabled    Granular selection
      │                          │
      └──────────┬───────────────┘
                 ▼
      Save consent
      (Cookie + AJAX log)
                 │
                 ▼
      Unblock deferred scripts
```

---

## 2. Installation

### System Requirements

| Component  | Minimum | Recommended |
|------------|---------|-------------|
| WordPress  | 5.8     | 6.4+        |
| PHP        | 7.4     | 8.1+        |
| MySQL      | 5.6     | 8.0+        |
| Browser    | All modern browsers | – |

### Method A: ZIP Upload (recommended)

1. Download the plugin ZIP
2. WordPress Admin → **Plugins → Add New → Upload Plugin**
3. Select the ZIP file → **Install Now**
4. **Activate Plugin**

Upon activation, the database table `wp_dcb_consents` is created automatically.

### Method B: FTP/SFTP

```bash
# 1. Unzip the archive
unzip dsgvo-cookie-banner.zip

# 2. Copy to the plugins directory
cp -r dsgvo-cookie-banner/ /path/to/wp-content/plugins/

# 3. Set permissions
chmod -R 755 /path/to/wp-content/plugins/dsgvo-cookie-banner/
```

Then activate the plugin under **Plugins** in the WordPress admin.

### Method C: WP-CLI / Composer

```bash
# WP-CLI
wp plugin install /path/to/dsgvo-cookie-banner.zip --activate

# Clone directly into the plugins directory
cd wp-content/plugins/
git clone https://github.com/sbanse/dsgvo-cookie-banner.git
wp plugin activate dsgvo-cookie-banner
```

---

## 3. Quick Start

After activation, follow these steps:

### Step 1: Run a Cookie Scan

Navigate to **Cookie Banner → Cookie Scanner** and click **"Start Scan"**.

The scanner automatically checks:
- Active WordPress plugins
- Active theme (`functions.php`)
- Known third-party services

### Step 2: Review and Supplement Cookies

Check the detected cookies and add any missing ones manually using the form at the bottom of the scanner page.

### Step 3: Set Up Your Privacy Page

Add the following shortcode to your Privacy Policy page:

```
[dcb_cookie_list]
```

### Step 4: Configure Settings

Under **Cookie Banner → Settings**:
- Link your Privacy Policy and Imprint pages
- Customize the banner text
- Set colors and banner position

### Step 5: Add the Revoke Button

Add the following shortcode to your Privacy Policy page (and optionally in your footer):

```
[dcb_privacy_settings text="Manage Cookie Settings"]
```

---

## 4. Settings

Accessible via **WordPress Admin → Cookie Banner → Settings**.

### 4.1 Banner Text

| Field | Description | Default |
|-------|-------------|---------|
| Banner Title | Heading of the cookie banner | "We use cookies" |
| Banner Text | Explanatory text in the banner | (default text) |
| "Accept All" | Label for the primary button | "Accept All" |
| "Necessary Only" | Label for the secondary button | "Necessary Only" |
| "Settings" | Label for the detail button | "Settings" |
| "Save Settings" | Button in the detail modal | "Save Settings" |

### 4.2 Design

| Field | Options | Default |
|-------|---------|---------|
| Position | Bottom / Top / Center (Modal) | Bottom |
| Layout | Bar / Box | Bar |
| Primary Color | Color picker | `#0073aa` |
| Text Color | Color picker | `#333333` |
| Background Color | Color picker | `#ffffff` |

### 4.3 Advanced

| Field | Description | Default |
|-------|-------------|---------|
| Privacy Policy Page | Linked in the banner footer | – |
| Imprint Page | Linked in the banner footer | – |
| Cookie Lifetime | Validity of consent in days | 365 |
| Auto-block scripts | Enables the script-blocking feature | ✅ |
| Log consents | Saves consent records to the database | ✅ |

---

## 5. Cookie Scanner

### 5.1 Automatic Scan

The scanner detects cookies based on:

**Plugin detection:** Compares active plugins against an internal mapping table. Detected plugin families:

- WooCommerce → cart cookies
- Google Analytics (various plugins) → `_ga`, `_gid`, `_gat`
- Matomo / WP-Piwik → `_pk_id`, `_pk_ses`
- Facebook for WooCommerce → `_fbp`, `fr`
- Wordfence → security cookies

**Theme scan:** Searches the active theme's `functions.php` for keywords:

```
setcookie | google-analytics | gtag | fbq( | hotjar | youtube.com/embed | stripe.js
```

### 5.2 Cookie Database

The plugin contains a database of 30+ well-known cookies with category, provider, purpose, and lifetime. This is used for automatic classification during each scan.

### 5.3 Add Cookies Manually

Use the form on the scanner page to add cookies manually:

| Field | Required | Example |
|-------|----------|---------|
| Cookie Name | ✅ | `my_tracking_cookie` |
| Category | ✅ | Statistics |
| Provider | – | "My Analytics Service" |
| Purpose | – | "Counts page views" |
| Lifetime | – | "30 days" |

### 5.4 Scan Result Format

The result is stored in the WordPress option `dcb_detected_cookies`:

```json
{
  "auto": {
    "_ga": {
      "name": "_ga",
      "category": "statistics",
      "provider": "Google Analytics",
      "purpose": "Distinguishes users and sessions",
      "duration": "2 years"
    }
  },
  "manual": {},
  "last_scan": "2024-01-15 10:30:00"
}
```

---

## 6. Shortcodes

### 6.1 `[dcb_cookie_list]`

Outputs a complete, category-grouped cookie table. Ideal for Privacy Policy and Imprint pages.

**Parameters:**

| Parameter | Values | Default | Description |
|-----------|--------|---------|-------------|
| `category` | `necessary`, `statistics`, `marketing`, `preferences` | all | Filter by category |
| `style` | `table` | `table` | Output format |

**Examples:**

```
// Show all cookies
[dcb_cookie_list]

// Only statistics cookies
[dcb_cookie_list category="statistics"]

// Only marketing cookies
[dcb_cookie_list category="marketing"]

// Only necessary cookies
[dcb_cookie_list category="necessary"]
```

**Sample HTML output:**

```html
<p class="dcb-last-scan"><small>Last scanned: 2024-01-15 10:30:00</small></p>

<div class="dcb-cookie-category">
  <h3 class="dcb-cat-title">Statistics</h3>
  <p class="dcb-cat-desc">...</p>
  <table class="dcb-cookie-table">
    <thead>
      <tr>
        <th>Name</th>
        <th>Provider</th>
        <th>Purpose</th>
        <th>Lifetime</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td><code>_ga</code></td>
        <td>Google Analytics</td>
        <td>Distinguishes users and sessions</td>
        <td>2 years</td>
      </tr>
    </tbody>
  </table>
</div>
```

### 6.2 `[dcb_privacy_settings]`

Renders a button that reopens the cookie settings. Required on every Privacy Policy page.

**Parameters:**

| Parameter | Default | Description |
|-----------|---------|-------------|
| `text` | "Manage Cookie Settings" | Button label |

**Examples:**

```
[dcb_privacy_settings]
[dcb_privacy_settings text="Customize My Cookie Preferences"]
[dcb_privacy_settings text="Withdraw Consent"]
```

### 6.3 `[dcb_cookie_banner]`

Renders a button to manually trigger the cookie banner.

```
[dcb_cookie_banner]
```

---

## 7. Script Blocking

Script blocking ensures third-party scripts are only loaded after the user has accepted the relevant category.

### 7.1 Core Principle

Regular `<script>` tags are executed immediately by the browser. By changing the `type` attribute to `text/plain`, the browser ignores the script. The plugin detects these marked scripts and activates them after consent is granted.

### 7.2 Implementation

**Before (executes immediately):**
```html
<script src="https://www.googletagmanager.com/gtag/js?id=G-XXXXXXX"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());
  gtag('config', 'G-XXXXXXX');
</script>
```

**After (executes only after consent):**
```html
<script type="text/plain" data-dcb-category="statistics"
        src="https://www.googletagmanager.com/gtag/js?id=G-XXXXXXX"></script>
<script type="text/plain" data-dcb-category="statistics">
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());
  gtag('config', 'G-XXXXXXX');
</script>
```

### 7.3 Category Mapping

| `data-dcb-category` | Associated Services |
|---------------------|---------------------|
| `necessary` | Always active, no blocking |
| `statistics` | Google Analytics, Matomo, Hotjar |
| `marketing` | Facebook Pixel, Google Ads, LinkedIn |
| `preferences` | Live chat, language settings |

### 7.4 WordPress Hook for Theme Developers

```php
// Conditionally output a blockable script in your theme
add_action('wp_head', function() {
    $consent = isset($_COOKIE['dcb_consent'])
        ? json_decode(stripslashes($_COOKIE['dcb_consent']), true)
        : null;
    $statistics_ok = $consent['categories']['statistics'] ?? false;
    ?>
    <script type="<?php echo $statistics_ok ? 'text/javascript' : 'text/plain'; ?>"
            data-dcb-category="statistics"
            src="https://www.googletagmanager.com/gtag/js?id=G-XXXXXXX">
    </script>
    <?php
});
```

---

## 8. Consent Log

### 8.1 What Is Stored?

The database table `wp_dcb_consents` stores:

| Field | Content | Privacy |
|-------|---------|---------|
| `id` | Record ID (auto increment) | – |
| `consent_id` | UUID v4 | pseudonymous |
| `ip_hash` | SHA-256 hash of the IP address | not reversible |
| `consent_data` | JSON with categories + timestamp | structured |
| `created_at` | Time of consent | – |

### 8.2 Sample Record

```json
{
  "version": "1.0",
  "timestamp": "2024-01-15T10:30:00.000Z",
  "categories": {
    "necessary": true,
    "statistics": true,
    "marketing": false,
    "preferences": false
  }
}
```

### 8.3 Viewing the Log

Under **Cookie Banner → Consents** you can view the last 100 consent records.

### 8.4 Disabling Consent Logging

Under **Settings → Advanced**, consent logging can be disabled. Note: this makes GDPR compliance harder to demonstrate.

### 8.5 Exporting / Deleting Data

```php
// Retrieve all consent records
$consents = DCB_Cookie_Manager::get_consents(1000);

// Export via WP-CLI
wp eval 'echo json_encode(DCB_Cookie_Manager::get_consents(9999));' > consents.json

// Clear the table (for GDPR deletion requests)
global $wpdb;
$wpdb->query("TRUNCATE TABLE {$wpdb->prefix}dcb_consents");
```

---

## 9. Design & Customization

### 9.1 CSS Variables

The plugin uses CSS Custom Properties for easy customization:

```css
:root {
  --dcb-primary:  #0073aa;  /* Primary color (buttons, accents) */
  --dcb-text:     #333333;  /* Text color */
  --dcb-bg:       #ffffff;  /* Background color */
  --dcb-radius:   8px;      /* Border radius */
  --dcb-shadow:   0 4px 24px rgba(0,0,0,.18);  /* Box shadow */
}
```

These are automatically applied from the plugin settings.

### 9.2 Adding Custom CSS

```css
/* Adjust banner font size */
#dcb-banner {
  font-size: 14px;
}

/* Make buttons fully rounded */
.dcb-btn {
  border-radius: 25px;
}

/* Widen the modal */
#dcb-modal {
  max-width: 680px;
}

/* Style the cookie table on the frontend */
.dcb-cookie-table th {
  background-color: #f0f4f8;
  color: #2d3748;
}
```

### 9.3 Banner Positions

```
Position "bottom" (default):
┌──────────────────────────────────────┐
│            Website Content           │
│                                      │
├──────────────────────────────────────┤
│  🍪 Cookie Banner  [Accept All] [Necessary Only] [Settings]  │
└──────────────────────────────────────┘

Position "top":
┌──────────────────────────────────────┐
│  🍪 Cookie Banner  [Accept All] [Necessary Only] [Settings]  │
├──────────────────────────────────────┤
│            Website Content           │
└──────────────────────────────────────┘

Position "center" (modal):
┌──────────────────────────────────────┐
│            Website Content           │
│   ┌───────────────────────────┐      │
│   │  🍪 Cookie Banner         │      │
│   │  [Accept All] [Necessary] │      │
│   └───────────────────────────┘      │
└──────────────────────────────────────┘
```

---

## 10. JavaScript API

The plugin exposes a global `DCB` API:

### 10.1 Available Methods

```javascript
// Reopen the cookie banner
DCB.openBanner();
```

### 10.2 Custom Events

The plugin dispatches custom events you can listen to:

```javascript
// Fired when the user grants or updates consent
document.addEventListener('dcb:consent', function(event) {
  const consent = event.detail;
  console.log('Consent:', consent);
  // consent.categories.statistics → true/false
  // consent.categories.marketing  → true/false

  // Example: initialize Google Analytics only after consent
  if (consent.categories.statistics) {
    gtag('config', 'G-XXXXXXX');
  }
});
```

### 10.3 Reading Consent Client-Side

```javascript
// Read the current consent from the cookie
function getConsent() {
  const cookie = document.cookie.split('; ')
    .find(c => c.startsWith('dcb_consent='));
  if (!cookie) return null;
  try {
    return JSON.parse(decodeURIComponent(cookie.split('=')[1]));
  } catch {
    return null;
  }
}

const consent = getConsent();
if (consent?.categories?.marketing) {
  // Load marketing scripts
}
```

### 10.4 Checking Consent Server-Side (PHP)

```php
/**
 * Checks whether the user has consented to a specific cookie category.
 *
 * @param string $category  One of: necessary|statistics|marketing|preferences
 * @return bool
 */
function dcb_has_consent( string $category ): bool {
    if ( ! isset( $_COOKIE['dcb_consent'] ) ) return false;
    $consent = json_decode( stripslashes( $_COOKIE['dcb_consent'] ), true );
    return ! empty( $consent['categories'][ $category ] );
}

// Usage:
if ( dcb_has_consent('statistics') ) {
    // Output analytics code
}
```

---

## 11. Database

### 11.1 Table `wp_dcb_consents`

```sql
CREATE TABLE wp_dcb_consents (
    id           BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
    consent_id   VARCHAR(64)  NOT NULL,          -- UUID v4
    ip_hash      VARCHAR(64)  NOT NULL,          -- SHA-256 of IP
    consent_data LONGTEXT     NOT NULL,          -- JSON
    created_at   DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    KEY consent_id (consent_id)
);
```

### 11.2 WordPress Options

| Option | Type | Content |
|--------|------|---------|
| `dcb_settings` | Array | Plugin settings |
| `dcb_detected_cookies` | Array | Scan results + manual cookies |

---

## 12. GDPR Compliance

### 12.1 Requirements and Implementation

| GDPR Requirement | Plugin Implementation |
|-----------------|----------------------|
| Consent before data processing | Scripts are blocked until consent is given |
| Freely given | Equally prominent buttons; no pre-ticking |
| Informed | Category descriptions; link to Privacy Policy |
| Unambiguous | Requires active action (no implicit consent) |
| Withdrawable | `[dcb_privacy_settings]` shortcode |
| Demonstrable | Consent log with timestamp + IP hash |

### 12.2 What the Plugin Does NOT Cover

- The legal accuracy of your Privacy Policy
- Legal assessment of individual processing activities
- Data Processing Agreements (DPAs) with third parties
- Data Protection Impact Assessments (DPIA)

> **Recommendation:** Have your Privacy Policy reviewed by a qualified data protection lawyer or DPO.

### 12.3 Recommended Supplementary Measures

1. Keep your **Privacy Policy** up to date (document all services)
2. Ensure your **Imprint/Legal Notice** is complete and accessible
3. Sign **Data Processing Agreements** with Google, Facebook, etc.
4. Enable **SSL/TLS** across the entire website
5. Establish a **data breach response process** (GDPR Art. 33)

---

## 13. Frequently Asked Questions

**Q: The banner doesn't appear. What should I do?**
A: Check whether a caching plugin is active. Clear your cache after activating the plugin. Check the browser console for JavaScript errors.

**Q: Can I hide the banner for logged-in admins?**
A: Add the following snippet to your theme's `functions.php`:
```php
add_action('wp_footer', function() {
    if (current_user_can('manage_options')) {
        echo '<style>#dcb-banner-root { display: none !important; }</style>';
    }
});
```

**Q: How do I delete all stored consent records?**
A: Via phpMyAdmin or WP-CLI: `wp db query "TRUNCATE TABLE wp_dcb_consents;"`

**Q: Does the plugin work with caching plugins?**
A: Yes. The JavaScript runs client-side and checks the cookie status on every page load. Make sure the `dcb_consent` cookie is excluded from your caching configuration.

**Q: Is the plugin compatible with WPML/Polylang?**
A: Text fields can be adjusted in the backend. For full multilingual support, settings must be configured separately per language.

**Q: Does the plugin support Google Consent Mode v2?**
A: Not yet in version 1.0.0. Planned for a future release. As a workaround, you can use the `dcb:consent` event to call `gtag('consent', 'update', {...})` manually.

---

## 14. Troubleshooting

### Banner reappears after accepting

Check the cookie lifetime setting. A browser extension may be blocking cookies. Verify in the browser console:
```javascript
document.cookie  // dcb_consent=... must be present
```

### Blocked scripts are not executed

Make sure `data-dcb-category` is spelled correctly (lowercase) and the value matches one of the 4 categories.

### Scanner finds no cookies

Use manual entry. The auto-scanner only detects known plugins/services. You can also run a browser-based cookie scan using the browser's developer tools.

### AJAX error during scan

Check whether `admin-ajax.php` is accessible. Some security plugins block AJAX requests. Add a whitelist entry for `wp_ajax_dcb_scan`.

---

## 15. Developer Reference

### Action Hooks

```php
// Fired after a consent record is saved
do_action('dcb_consent_saved', $consent_id, $consent_data);

// Fired when the banner is rendered
do_action('dcb_banner_rendered', $settings);
```

### Filter Hooks

```php
// Filter the cookie list before output
add_filter('dcb_cookies_list', function($cookies) {
    // Add a custom cookie
    $cookies['my_cookie'] = [
        'name'     => 'my_cookie',
        'category' => 'statistics',
        'provider' => 'My Service',
        'purpose'  => 'Usage statistics',
        'duration' => '1 year',
    ];
    return $cookies;
});

// Modify banner settings programmatically
add_filter('dcb_banner_settings', function($settings) {
    $settings['banner_title'] = 'Custom Heading';
    return $settings;
});
```

### Class Reference

```php
// Get plugin settings
$settings = DCB_Cookie_Manager::get_settings();

// Get detected cookies
$cookies = DCB_Cookie_Manager::get_detected_cookies();

// Get consent records
$consents = DCB_Cookie_Manager::get_consents(50);

// Trigger a scan (e.g., via WP-Cron)
$found = DCB_Cookie_Scanner::scan();
```

### File Structure

```
dsgvo-cookie-banner/
├── dsgvo-cookie-banner.php     # Main plugin file
├── readme.txt                  # WordPress.org readme
├── includes/
│   ├── class-cookie-manager.php   # DB, options, settings
│   ├── class-cookie-scanner.php   # Scan logic, cookie database
│   └── class-shortcodes.php       # Shortcode definitions
├── admin/
│   ├── class-admin.php            # Admin menu, AJAX handlers
│   ├── admin.css                  # Backend styles
│   ├── admin.js                   # Backend JavaScript
│   └── views/
│       ├── settings.php           # Settings page
│       ├── scanner.php            # Scanner page
│       └── consents.php           # Consent log page
└── public/
    ├── class-frontend.php         # Frontend output, AJAX
    ├── css/frontend.css           # Banner styles
    └── js/frontend.js             # Banner logic
```

---

*Documentation Version 1.0.0 – Updated: 2024*
