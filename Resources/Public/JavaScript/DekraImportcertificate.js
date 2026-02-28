/**
 * DEKRA Importcertificate24 – Frontend JavaScript
 * VIN-Lookup und Formular-Validierung
 */
(function () {
  'use strict';

  // =========================================================================
  // VIN-Lookup
  // =========================================================================
  document.addEventListener('DOMContentLoaded', function () {

    const vinInput = document.querySelector('[data-vin-lookup]');
    const lookupBtn = document.getElementById('vinLookupBtn');
    const resultBox = document.getElementById('vinLookupResult');

    if (vinInput && lookupBtn && resultBox) {

      // Auto-uppercase
      vinInput.addEventListener('input', function () {
        const pos = this.selectionStart;
        this.value = this.value.toUpperCase().replace(/[IOQ]/g, '');
        this.setSelectionRange(pos, pos);
      });

      lookupBtn.addEventListener('click', function () {
        const vin = vinInput.value.trim();

        if (vin.length !== 17) {
          showResult('warning', '⚠️ Bitte geben Sie eine vollständige 17-stellige FIN/VIN ein.');
          return;
        }

        if (!/^[A-HJ-NPR-Z0-9]{17}$/.test(vin)) {
          showResult('error', '❌ Ungültige FIN. Die Buchstaben I, O und Q sind nicht erlaubt.');
          return;
        }

        lookupBtn.disabled = true;
        lookupBtn.textContent = '⏳ Suche...';

        // AJAX-Anfrage an TYPO3 Backend Route
        const url = TYPO3.settings.ajaxUrls['dekra_importcertificate_vehicle_lookup'];

        fetch(url, {
          method: 'POST',
          headers: { 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
          body: JSON.stringify({ vin: vin }),
        })
          .then(function (res) { return res.json(); })
          .then(function (data) {
            if (data.found) {
              showResult(
                'success',
                '✅ Fahrzeug gefunden: <strong>' + escHtml(data.make) + ' ' + escHtml(data.model) +
                ' (' + data.year + ')</strong>' +
                (data.estimatedPrice ? ' | Geschätzter Preis: <strong>' + escHtml(data.estimatedPrice) + '</strong>' : '') +
                ' | Lieferzeit: ' + escHtml(data.deliveryDays || '3–7 Werktage')
              );

              // Auto-fill fields if empty
              autoFill('vehicleMake', data.make);
              autoFill('vehicleModel', data.model);
              autoFill('vehicleYear', data.year);
              autoFillSelect('vehicleCategory', data.category);
            } else {
              showResult(
                'warning',
                '⚠️ Zu dieser FIN wurden keine Daten gefunden. Bitte füllen Sie die Felder manuell aus.'
              );
            }
          })
          .catch(function () {
            showResult('info', 'ℹ️ FIN-Suche nicht verfügbar. Bitte füllen Sie die Felder manuell aus.');
          })
          .finally(function () {
            lookupBtn.disabled = false;
            lookupBtn.innerHTML = '<span class="dekra-btn-icon">🔍</span> FIN prüfen';
          });
      });
    }

    // =========================================================================
    // Certificate Type Card Highlighting
    // =========================================================================
    document.querySelectorAll('.dekra-certificate-type-card').forEach(function (card) {
      const radio = card.querySelector('input[type="radio"]');
      if (radio) {
        radio.addEventListener('change', function () {
          document.querySelectorAll('.dekra-certificate-type-card').forEach(function (c) {
            c.style.borderColor = '';
            c.style.background = '';
          });
          if (this.checked) {
            card.style.borderColor = 'var(--dekra-green)';
            card.style.background = 'var(--dekra-green-light)';
          }
        });

        // Init on load
        if (radio.checked) {
          card.style.borderColor = 'var(--dekra-green)';
          card.style.background = 'var(--dekra-green-light)';
        }
      }
    });

    // =========================================================================
    // Form Validation
    // =========================================================================
    const form = document.querySelector('.dekra-certificate-request form');
    if (form) {
      form.addEventListener('submit', function (e) {
        let valid = true;

        // Check required fields
        form.querySelectorAll('[required]').forEach(function (field) {
          if (!field.value.trim()) {
            field.style.borderColor = '#dc3545';
            valid = false;
          } else {
            field.style.borderColor = '';
          }
        });

        // Email validation
        const emailField = form.querySelector('input[type="email"]');
        if (emailField && emailField.value && !validateEmail(emailField.value)) {
          emailField.style.borderColor = '#dc3545';
          valid = false;
        }

        if (!valid) {
          e.preventDefault();
          const firstError = form.querySelector('[style*="dc3545"]');
          if (firstError) {
            firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
            firstError.focus();
          }
        }
      });
    }

    // =========================================================================
    // Helpers
    // =========================================================================
    function showResult(type, message) {
      const colors = {
        success: { bg: '#d4edda', border: '#c3e6cb', color: '#155724' },
        warning: { bg: '#fff3cd', border: '#ffeeba', color: '#856404' },
        error:   { bg: '#f8d7da', border: '#f5c6cb', color: '#721c24' },
        info:    { bg: '#d1ecf1', border: '#bee5eb', color: '#0c5460' },
      };
      const c = colors[type] || colors.info;
      resultBox.style.cssText = 'display:block; background:' + c.bg + '; border:1px solid ' + c.border + '; color:' + c.color + '; border-radius:6px; padding:12px;';
      resultBox.innerHTML = message;
    }

    function autoFill(id, value) {
      const el = document.getElementById(id) || document.querySelector('[name*="[' + id.charAt(0).toUpperCase() + id.slice(1) + ']"]');
      if (el && !el.value && value) el.value = value;
    }

    function autoFillSelect(id, value) {
      const el = document.getElementById(id);
      if (el && value) {
        Array.from(el.options).forEach(function (opt) {
          if (opt.value.toLowerCase() === value.toLowerCase() || opt.text.toLowerCase().includes(value.toLowerCase())) {
            el.value = opt.value;
          }
        });
      }
    }

    function escHtml(str) {
      return String(str).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
    }

    function validateEmail(email) {
      return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
    }
  });

})();
