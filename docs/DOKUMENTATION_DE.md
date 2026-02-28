# DSGVO Cookie Banner – Vollständige Dokumentation (Deutsch)

**Version:** 1.0.0 | **Sprache:** Deutsch | **Zielgruppe:** Entwickler & WordPress-Administratoren

---

## Inhaltsverzeichnis

1. [Einführung](#1-einführung)
2. [Installation](#2-installation)
3. [Schnellstart](#3-schnellstart)
4. [Einstellungen](#4-einstellungen)
5. [Cookie-Scanner](#5-cookie-scanner)
6. [Shortcodes](#6-shortcodes)
7. [Script-Blocking](#7-script-blocking)
8. [Einwilligungsprotokoll](#8-einwilligungsprotokoll)
9. [Design & Anpassung](#9-design--anpassung)
10. [JavaScript-API](#10-javascript-api)
11. [Datenbank](#11-datenbank)
12. [DSGVO-Compliance](#12-dsgvo-compliance)
13. [Häufige Fragen (FAQ)](#13-häufige-fragen-faq)
14. [Fehlerbehebung](#14-fehlerbehebung)
15. [Entwickler-Referenz](#15-entwickler-referenz)

---

## 1. Einführung

**DSGVO Cookie Banner** ist ein WordPress-Plugin, das einen rechtskonformen Cookie-Hinweis nach den Anforderungen der Datenschutz-Grundverordnung (DSGVO/GDPR) und den Leitlinien des Europäischen Datenschutzausschusses (EDPB) bereitstellt.

### Rechtliche Grundlage

Das Plugin richtet sich nach folgenden Rechtsgrundlagen:

- **DSGVO Art. 6 Abs. 1 lit. a** – Einwilligung als Rechtsgrundlage
- **DSGVO Art. 7** – Bedingungen für die Einwilligung
- **DSGVO Art. 13** – Informationspflichten bei Datenerhebung
- **ePrivacy-Richtlinie (2002/58/EG)** – Cookie-Regelungen
- **EDPB-Leitlinien 05/2020** – Einwilligung gemäß DSGVO

### Funktionsprinzip

```
Erster Seitenbesuch
       │
       ▼
Kein Einwilligungs-Cookie vorhanden?
       │ JA
       ▼
Cookie-Banner anzeigen
       │
   ┌───┴────────────────────────┐
   │                            │
   ▼                            ▼
"Alle akzeptieren"     "Nur notwendige" / "Einstellungen"
   │                            │
   ▼                            ▼
Alle Kategorien aktiv   Granulare Auswahl
       │                        │
       └───────────┬────────────┘
                   ▼
        Einwilligung speichern
        (Cookie + AJAX-Log)
                   │
                   ▼
        Geblockte Scripts freigeben
```

---

## 2. Installation

### Systemanforderungen

| Komponente | Mindestversion | Empfohlen |
|------------|---------------|-----------|
| WordPress  | 5.8           | 6.4+      |
| PHP        | 7.4           | 8.1+      |
| MySQL      | 5.6           | 8.0+      |
| Browser    | Alle modernen | –         |

### Methode A: ZIP-Upload (empfohlen)

1. Plugin-ZIP herunterladen
2. WordPress Admin → **Plugins → Installieren → Plugin hochladen**
3. ZIP-Datei auswählen → **Jetzt installieren**
4. **Plugin aktivieren**

Bei der Aktivierung wird automatisch die Datenbanktabelle `wp_dcb_consents` angelegt.

### Methode B: FTP/SFTP

```bash
# 1. ZIP entpacken
unzip dsgvo-cookie-banner.zip

# 2. In Plugin-Verzeichnis kopieren
cp -r dsgvo-cookie-banner/ /pfad/zu/wp-content/plugins/

# 3. Berechtigungen setzen
chmod -R 755 /pfad/zu/wp-content/plugins/dsgvo-cookie-banner/
```

Dann im WordPress-Backend unter **Plugins** das Plugin aktivieren.

### Methode C: Composer / WP-CLI

```bash
# WP-CLI
wp plugin install /pfad/zum/dsgvo-cookie-banner.zip --activate

# Über Git direkt ins Plugin-Verzeichnis klonen
cd wp-content/plugins/
git clone https://github.com/sbanse/dsgvo-cookie-banner.git
wp plugin activate dsgvo-cookie-banner
```

---

## 3. Schnellstart

Nach der Aktivierung sind folgende Schritte empfohlen:

### Schritt 1: Cookie-Scan durchführen

Navigieren Sie zu **Cookie Banner → Cookie-Scanner** und klicken Sie auf **„Scan starten"**.

Der Scanner durchsucht automatisch:
- Aktive WordPress-Plugins
- Aktives Theme (`functions.php`)
- Bekannte Drittanbieter-Dienste

### Schritt 2: Cookies prüfen und ergänzen

Überprüfen Sie die erkannten Cookies und ergänzen Sie fehlende manuell über das Formular am Ende der Scanner-Seite.

### Schritt 3: Datenschutzseite einrichten

Fügen Sie auf Ihrer Datenschutzseite den Shortcode ein:

```
[dcb_cookie_list]
```

### Schritt 4: Einstellungen konfigurieren

Unter **Cookie Banner → Einstellungen**:
- Datenschutzseite und Impressum verknüpfen
- Texte anpassen
- Farben und Position einstellen

### Schritt 5: Widerruf-Button einbinden

Fügen Sie auf der Datenschutzseite (und optional im Footer) folgenden Shortcode ein:

```
[dcb_privacy_settings text="Cookie-Einstellungen ändern"]
```

---

## 4. Einstellungen

Erreichbar unter **WordPress Admin → Cookie Banner → Einstellungen**.

### 4.1 Banner-Text

| Feld | Beschreibung | Standard |
|------|-------------|---------|
| Banner-Titel | Überschrift des Cookie-Banners | „Wir verwenden Cookies" |
| Banner-Text | Erklärungstext im Banner | (Standardtext) |
| „Alle akzeptieren" | Beschriftung des Primär-Buttons | „Alle akzeptieren" |
| „Nur notwendige" | Beschriftung des Sekundär-Buttons | „Nur notwendige" |
| „Einstellungen" | Beschriftung des Detailbuttons | „Einstellungen" |
| „Einstellungen speichern" | Button im Detail-Modal | „Einstellungen speichern" |

### 4.2 Design

| Feld | Optionen | Standard |
|------|---------|---------|
| Position | Unten / Oben / Mitte (Modal) | Unten |
| Layout | Leiste / Box | Leiste |
| Primärfarbe | Farbauswahl | `#0073aa` |
| Textfarbe | Farbauswahl | `#333333` |
| Hintergrundfarbe | Farbauswahl | `#ffffff` |

### 4.3 Erweitert

| Feld | Beschreibung | Standard |
|------|-------------|---------|
| Datenschutzseite | Verlinkung im Banner | – |
| Impressum-Seite | Verlinkung im Banner | – |
| Cookie-Laufzeit | Gültigkeitsdauer der Einwilligung in Tagen | 365 |
| Scripts automatisch blockieren | Aktiviert Script-Blocking-Feature | ✅ |
| Einwilligungen protokollieren | Speichert Einwilligungen in DB | ✅ |

---

## 5. Cookie-Scanner

### 5.1 Automatischer Scan

Der Scanner erkennt Cookies anhand von:

**Plugin-Erkennung:** Vergleicht aktive Plugins mit einer internen Zuordnungstabelle. Erkannte Plugin-Familien:

- WooCommerce → Warenkorb-Cookies
- Google Analytics (div. Plugins) → `_ga`, `_gid`, `_gat`
- Matomo/WP-Piwik → `_pk_id`, `_pk_ses`
- Facebook for WooCommerce → `_fbp`, `fr`
- Wordfence → Sicherheits-Cookies

**Theme-Scan:** Durchsucht `functions.php` des aktiven Themes nach Schlüsselwörtern:

```
setcookie | google-analytics | gtag | fbq( | hotjar | youtube.com/embed | stripe.js
```

### 5.2 Cookie-Datenbank

Das Plugin enthält eine Datenbank mit 30+ bekannten Cookies inklusive Kategorie, Anbieter, Zweck und Laufzeit. Diese wird bei jedem Scan zur automatischen Klassifizierung genutzt.

### 5.3 Cookies manuell hinzufügen

Über das Formular auf der Scanner-Seite können Cookies manuell erfasst werden:

| Feld | Pflicht | Beispiel |
|------|---------|---------|
| Cookie-Name | ✅ | `my_tracking_cookie` |
| Kategorie | ✅ | Statistik |
| Anbieter | – | „Mein Analysedienst" |
| Zweck | – | „Zählt Seitenaufrufe" |
| Laufzeit | – | „30 Tage" |

### 5.4 Scan-Ergebnis

Das Scan-Ergebnis wird in der WordPress-Option `dcb_detected_cookies` gespeichert und enthält:

```json
{
  "auto": {
    "_ga": {
      "name": "_ga",
      "category": "statistics",
      "provider": "Google Analytics",
      "purpose": "Unterscheidet Nutzer und Sitzungen",
      "duration": "2 Jahre"
    }
  },
  "manual": {},
  "last_scan": "2024-01-15 10:30:00"
}
```

---

## 6. Shortcodes

### 6.1 `[dcb_cookie_list]`

Gibt eine vollständige, nach Kategorien gruppierte Cookie-Tabelle aus. Ideal für Datenschutzerklärung und Impressum.

**Parameter:**

| Parameter | Werte | Standard | Beschreibung |
|-----------|-------|---------|-------------|
| `category` | `necessary`, `statistics`, `marketing`, `preferences` | alle | Filtert nach Kategorie |
| `style` | `table` | `table` | Ausgabeformat (aktuell: Tabelle) |

**Beispiele:**

```
// Alle Cookies anzeigen
[dcb_cookie_list]

// Nur Statistik-Cookies
[dcb_cookie_list category="statistics"]

// Nur Marketing-Cookies
[dcb_cookie_list category="marketing"]

// Nur notwendige Cookies
[dcb_cookie_list category="necessary"]
```

**HTML-Ausgabe (Beispiel):**

```html
<p class="dcb-last-scan"><small>Zuletzt gescannt: 2024-01-15 10:30:00</small></p>

<div class="dcb-cookie-category">
  <h3 class="dcb-cat-title">Statistik</h3>
  <p class="dcb-cat-desc">...</p>
  <table class="dcb-cookie-table">
    <thead>
      <tr>
        <th>Name</th>
        <th>Anbieter</th>
        <th>Zweck</th>
        <th>Laufzeit</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td><code>_ga</code></td>
        <td>Google Analytics</td>
        <td>Unterscheidet Nutzer und Sitzungen</td>
        <td>2 Jahre</td>
      </tr>
    </tbody>
  </table>
</div>
```

### 6.2 `[dcb_privacy_settings]`

Erzeugt einen Button, der die Cookie-Einstellungen erneut öffnet. Pflichtbestandteil jeder Datenschutzerklärung.

**Parameter:**

| Parameter | Standard | Beschreibung |
|-----------|---------|-------------|
| `text` | „Cookie-Einstellungen ändern" | Button-Beschriftung |

**Beispiele:**

```
[dcb_privacy_settings]
[dcb_privacy_settings text="Meine Cookie-Auswahl anpassen"]
[dcb_privacy_settings text="Einwilligung widerrufen"]
```

### 6.3 `[dcb_cookie_banner]`

Erzeugt einen Button zum manuellen Öffnen des Cookie-Banners.

```
[dcb_cookie_banner]
```

---

## 7. Script-Blocking

Mit Script-Blocking werden Drittanbieter-Scripts erst geladen, nachdem der Nutzer die entsprechende Kategorie akzeptiert hat.

### 7.1 Grundprinzip

Normale `<script>`-Tags werden sofort vom Browser ausgeführt. Durch Änderung des `type`-Attributs auf `text/plain` ignoriert der Browser das Script. Das Plugin erkennt diese markierten Scripts und aktiviert sie nach Einwilligung.

### 7.2 Umsetzung

**Vorher (wird sofort ausgeführt):**
```html
<script src="https://www.googletagmanager.com/gtag/js?id=G-XXXXXXX"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());
  gtag('config', 'G-XXXXXXX');
</script>
```

**Nachher (wird erst nach Einwilligung ausgeführt):**
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

### 7.3 Kategorie-Zuordnung

| `data-dcb-category` | Zugehörige Dienste |
|---------------------|-------------------|
| `necessary` | Immer aktiv, kein Blocking |
| `statistics` | Google Analytics, Matomo, Hotjar |
| `marketing` | Facebook Pixel, Google Ads, LinkedIn |
| `preferences` | Live-Chat, Spracheinstellungen |

### 7.4 WordPress-Hooks für Theme-Entwickler

```php
// Script im Theme korrekt einbinden (blockierbar)
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

## 8. Einwilligungsprotokoll

### 8.1 Was wird gespeichert?

In der Datenbanktabelle `wp_dcb_consents` werden gespeichert:

| Feld | Inhalt | Datenschutz |
|------|--------|-------------|
| `id` | Datensatz-ID (auto) | – |
| `consent_id` | UUID v4 | pseudonym |
| `ip_hash` | SHA-256-Hash der IP | keine Rückführung möglich |
| `consent_data` | JSON mit Kategorien + Zeitstempel | strukturiert |
| `created_at` | Zeitpunkt der Einwilligung | – |

### 8.2 Beispiel-Datensatz

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

### 8.3 Protokoll einsehen

Unter **Cookie Banner → Einwilligungen** sind die letzten 100 Einwilligungen einsehbar.

### 8.4 Protokollierung deaktivieren

Unter **Einstellungen → Erweitert** kann die Protokollierung deaktiviert werden. Achtung: Dies erschwert den DSGVO-Nachweis.

### 8.5 Daten exportieren / löschen

```php
// Alle Einwilligungen abrufen
$consents = DCB_Cookie_Manager::get_consents(1000);

// Per WP-CLI exportieren
wp eval 'echo json_encode(DCB_Cookie_Manager::get_consents(9999));' > consents.json

// Tabelle leeren (DSGVO-Anfrage)
global $wpdb;
$wpdb->query("TRUNCATE TABLE {$wpdb->prefix}dcb_consents");
```

---

## 9. Design & Anpassung

### 9.1 CSS-Variablen

Das Plugin nutzt CSS Custom Properties für einfache Anpassung:

```css
:root {
  --dcb-primary:  #0073aa;  /* Primärfarbe (Buttons, Akzente) */
  --dcb-text:     #333333;  /* Textfarbe */
  --dcb-bg:       #ffffff;  /* Hintergrundfarbe */
  --dcb-radius:   8px;      /* Eckenradius */
  --dcb-shadow:   0 4px 24px rgba(0,0,0,.18);  /* Schatten */
}
```

Diese werden automatisch aus den Plugin-Einstellungen gesetzt.

### 9.2 Eigenes CSS ergänzen

```css
/* Banner-Schriftgröße anpassen */
#dcb-banner {
  font-size: 14px;
}

/* Buttons abrunden */
.dcb-btn {
  border-radius: 25px;
}

/* Modal-Breite anpassen */
#dcb-modal {
  max-width: 680px;
}

/* Cookie-Tabelle im Frontend stylen */
.dcb-cookie-table th {
  background-color: #f0f4f8;
  color: #2d3748;
}
```

### 9.3 Banner-Positionen

```
Position "bottom" (Standard):
┌────────────────────────────────────┐
│         Website-Inhalt             │
│                                    │
├────────────────────────────────────┤
│  🍪 Wir verwenden Cookies  [Alle] [Nur notwendige] [Einstellungen]  │
└────────────────────────────────────┘

Position "top":
┌────────────────────────────────────┐
│  🍪 Wir verwenden Cookies  [Alle] [Nur notwendige] [Einstellungen]  │
├────────────────────────────────────┤
│         Website-Inhalt             │
└────────────────────────────────────┘

Position "center" (Modal):
┌────────────────────────────────────┐
│         Website-Inhalt             │
│   ┌─────────────────────────┐      │
│   │  🍪 Cookie-Banner       │      │
│   │  [Alle] [Nur notw.]     │      │
│   └─────────────────────────┘      │
└────────────────────────────────────┘
```

---

## 10. JavaScript-API

Das Plugin stellt eine globale `DCB`-API bereit:

### 10.1 Verfügbare Methoden

```javascript
// Cookie-Banner erneut öffnen
DCB.openBanner();
```

### 10.2 Custom Events

Das Plugin feuert Custom Events, auf die Sie reagieren können:

```javascript
// Wird ausgelöst wenn Nutzer Einwilligung gibt/ändert
document.addEventListener('dcb:consent', function(event) {
  const consent = event.detail;
  console.log('Einwilligung:', consent);
  // consent.categories.statistics → true/false
  // consent.categories.marketing → true/false

  // Beispiel: Google Analytics nur bei Einwilligung initialisieren
  if (consent.categories.statistics) {
    gtag('config', 'G-XXXXXXX');
  }
});
```

### 10.3 Einwilligung auslesen

```javascript
// Aktuelle Einwilligung aus Cookie lesen
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
  // Marketing-Scripts laden
}
```

### 10.4 PHP-seitige Einwilligung prüfen

```php
/**
 * Prüft ob der Nutzer einer bestimmten Cookie-Kategorie zugestimmt hat.
 *
 * @param string $category  Kategorie: necessary|statistics|marketing|preferences
 * @return bool
 */
function dcb_has_consent( string $category ): bool {
    if ( ! isset( $_COOKIE['dcb_consent'] ) ) return false;
    $consent = json_decode( stripslashes( $_COOKIE['dcb_consent'] ), true );
    return ! empty( $consent['categories'][ $category ] );
}

// Verwendung:
if ( dcb_has_consent('statistics') ) {
    // Analytics-Code ausgeben
}
```

---

## 11. Datenbank

### 11.1 Tabelle `wp_dcb_consents`

```sql
CREATE TABLE wp_dcb_consents (
    id           BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
    consent_id   VARCHAR(64)  NOT NULL,          -- UUID v4
    ip_hash      VARCHAR(64)  NOT NULL,          -- SHA-256 der IP
    consent_data LONGTEXT     NOT NULL,          -- JSON
    created_at   DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    KEY consent_id (consent_id)
);
```

### 11.2 WordPress-Optionen

| Option | Typ | Inhalt |
|--------|-----|--------|
| `dcb_settings` | Array | Plugin-Einstellungen |
| `dcb_detected_cookies` | Array | Scan-Ergebnis + manuelle Cookies |

---

## 12. DSGVO-Compliance

### 12.1 Anforderungen und Umsetzung

| DSGVO-Anforderung | Umsetzung im Plugin |
|-------------------|---------------------|
| Einwilligung vor Datenverarbeitung | Scripts werden blockiert bis Einwilligung erteilt |
| Freiwilligkeit | Gleichwertige Buttons; kein Pre-Ticking |
| Informiertheit | Kategorien mit Beschreibung; Link zur Datenschutzseite |
| Eindeutigkeit | Aktive Handlung erforderlich (kein implizites Consent) |
| Widerruflichkeit | `[dcb_privacy_settings]`-Shortcode |
| Nachweisbarkeit | Einwilligungsprotokoll mit Zeitstempel + IP-Hash |

### 12.2 Was das Plugin NICHT abdeckt

- Die inhaltliche Korrektheit der Datenschutzerklärung
- Rechtliche Beurteilung einzelner Datenverarbeitungen
- Auftragsverarbeitungsverträge (AVV) mit Drittanbietern
- Datenschutz-Folgenabschätzung (DSFA)

> **Empfehlung:** Lassen Sie Ihre Datenschutzerklärung von einem Fachanwalt für IT-Recht oder Datenschutzbeauftragten prüfen.

### 12.3 Empfohlene ergänzende Maßnahmen

1. **Datenschutzerklärung** aktuell halten (alle Dienste dokumentieren)
2. **Impressum** vollständig und erreichbar
3. **Auftragsverarbeitungsverträge** mit Google, Facebook, etc. abschließen
4. **SSL/TLS** für die gesamte Website aktivieren
5. **Datenpannen-Prozess** etablieren (Art. 33 DSGVO)

---

## 13. Häufige Fragen (FAQ)

**F: Der Banner erscheint nicht. Was tun?**
A: Prüfen Sie ob ein Caching-Plugin aktiv ist. Leeren Sie den Cache nach der Plugin-Aktivierung. Prüfen Sie ob JavaScript-Fehler in der Browser-Konsole auftreten.

**F: Kann ich den Banner für eingeloggte Admins ausblenden?**
A: Fügen Sie folgenden Code in die `functions.php` Ihres Themes ein:
```php
add_action('wp_footer', function() {
    if (current_user_can('manage_options')) {
        echo '<style>#dcb-banner-root { display: none !important; }</style>';
    }
});
```

**F: Wie lösche ich alle gespeicherten Einwilligungen?**
A: Über **phpMyAdmin** oder WP-CLI: `wp db query "TRUNCATE TABLE wp_dcb_consents;"`

**F: Funktioniert das Plugin mit Caching-Plugins?**
A: Ja. Das JavaScript läuft client-seitig und prüft den Cookie-Status beim Seitenaufruf. Stellen Sie sicher, dass der `dcb_consent`-Cookie von der Caching-Konfiguration ausgeschlossen ist.

**F: Ist das Plugin mit WPML/Polylang kompatibel?**
A: Die Texte können im Backend angepasst werden. Für vollständige Mehrsprachigkeit müssen die Einstellungsfelder pro Sprache separat konfiguriert werden.

**F: Unterstützt das Plugin Google Consent Mode v2?**
A: In Version 1.0.0 noch nicht. Ergänzung geplant. Als Workaround kann das `dcb:consent`-Event genutzt werden, um `gtag('consent', 'update', {...})` manuell aufzurufen.

---

## 14. Fehlerbehebung

### Banner erscheint nach Akzeptieren immer wieder

Cookie-Laufzeit prüfen. Möglicherweise blockiert ein Browser-Plugin die Cookie-Setzung. Prüfen in der Browser-Konsole:
```javascript
document.cookie  // dcb_consent=... muss vorhanden sein
```

### Geblockte Scripts werden nicht ausgeführt

Stellen Sie sicher, dass `data-dcb-category` korrekt geschrieben ist (Kleinbuchstaben) und der Wert einer der 4 Kategorien entspricht.

### Scan findet keine Cookies

Manuelle Ergänzung nutzen. Der Auto-Scanner erkennt nur bekannte Plugins/Dienste. Führen Sie außerdem einen Browser-basierten Scan durch (z. B. mit dem Cookie-Scanner der Browserkonsole).

### AJAX-Fehler beim Scan

Prüfen Sie ob `admin-ajax.php` erreichbar ist. Manche Sicherheits-Plugins blockieren AJAX-Anfragen. Whitelist-Eintrag für `wp_ajax_dcb_scan` hinzufügen.

---

## 15. Entwickler-Referenz

### Action Hooks

```php
// Wird nach Speichern der Einwilligung ausgelöst
do_action('dcb_consent_saved', $consent_id, $consent_data);

// Wird beim Laden des Banners ausgelöst
do_action('dcb_banner_rendered', $settings);
```

### Filter Hooks

```php
// Cookie-Liste vor Ausgabe filtern
add_filter('dcb_cookies_list', function($cookies) {
    // Eigene Cookies hinzufügen
    $cookies['my_cookie'] = [
        'name'     => 'my_cookie',
        'category' => 'statistics',
        'provider' => 'Mein Dienst',
        'purpose'  => 'Nutzungsstatistik',
        'duration' => '1 Jahr',
    ];
    return $cookies;
});

// Banner-Einstellungen anpassen
add_filter('dcb_banner_settings', function($settings) {
    $settings['banner_title'] = 'Individuelle Überschrift';
    return $settings;
});
```

### Klassen-Referenz

```php
// Einstellungen abrufen
$settings = DCB_Cookie_Manager::get_settings();

// Erkannte Cookies abrufen
$cookies = DCB_Cookie_Manager::get_detected_cookies();

// Einwilligungen abrufen
$consents = DCB_Cookie_Manager::get_consents(50);

// Manuell scannen (z. B. via WP-Cron)
$found = DCB_Cookie_Scanner::scan();
```

### Dateistruktur

```
dsgvo-cookie-banner/
├── dsgvo-cookie-banner.php     # Haupt-Plugin-Datei
├── readme.txt                  # WordPress.org Readme
├── includes/
│   ├── class-cookie-manager.php   # DB, Optionen, Einstellungen
│   ├── class-cookie-scanner.php   # Scan-Logik, Cookie-Datenbank
│   └── class-shortcodes.php       # Shortcode-Definitionen
├── admin/
│   ├── class-admin.php            # Admin-Menü, AJAX-Handler
│   ├── admin.css                  # Backend-Styles
│   ├── admin.js                   # Backend-JavaScript
│   └── views/
│       ├── settings.php           # Einstellungsseite
│       ├── scanner.php            # Scanner-Seite
│       └── consents.php           # Einwilligungsprotokoll
└── public/
    ├── class-frontend.php         # Frontend-Ausgabe, AJAX
    ├── css/frontend.css           # Banner-Styles
    └── js/frontend.js             # Banner-Logik
```

---

*Dokumentation Version 1.0.0 – Stand: 2024*
