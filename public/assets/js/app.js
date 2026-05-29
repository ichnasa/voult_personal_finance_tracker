/**
 * PLOOM — App JavaScript
 * Minimal JS for Tabler integration (no jQuery dependency)
 */

document.addEventListener('DOMContentLoaded', function () {
  // Auto-dismiss alerts after 5 seconds
  document.querySelectorAll('.alert-dismissible').forEach(function (alert) {
    setTimeout(function () {
      alert.style.transition = 'opacity 0.5s ease';
      alert.style.opacity = '0';
      setTimeout(function () { alert.remove(); }, 500);
    }, 5000);
  });

  // AJAX Pagination (PJAX) for elements with data-pjax-container
  document.addEventListener('click', function (e) {
    const pageLink = e.target.closest('.pagination a');
    if (pageLink) {
      e.preventDefault();
      const url = pageLink.href;
      if (!url || url === '#' || url.includes('javascript:')) return;

      const container = pageLink.closest('[data-pjax-container]');
      if (!container || !container.id) return;

      const containerId = container.id;

      // Loading state
      container.style.transition = 'opacity 0.2s';
      container.style.opacity = '0.5';

      fetch(url)
        .then(response => response.text())
        .then(html => {
          const doc = new DOMParser().parseFromString(html, 'text/html');
          const newContainer = doc.getElementById(containerId);
          if (newContainer) {
            container.innerHTML = newContainer.innerHTML;
          }
          container.style.opacity = '1';
        })
        .catch(err => {
          console.error('Pagination error:', err);
          window.location.href = url; // Fallback to normal navigation
        });
    }
  });

  // Real-time currency formatting & History
  const nominalInputs = document.querySelectorAll('input[name="nominal"], input[name="target_nominal"], input[name="nominal_terkumpul"], input[name="harga_target"], input[name="nominal_budget"]');
  if (nominalInputs.length > 0) {
    const HISTORY_KEY = 'ploom_nominal_history';
    const MAX_HISTORY = 5;

    let history = [];
    try {
      history = JSON.parse(localStorage.getItem(HISTORY_KEY)) || [];
    } catch (e) {
      history = [];
    }

    if (history.length > 0) {
      const dataList = document.createElement('datalist');
      dataList.id = 'nominal-history-list';
      history.forEach(val => {
        const option = document.createElement('option');
        // Format history value for display
        option.value = parseInt(val, 10).toLocaleString('id-ID');
        dataList.appendChild(option);
      });
      document.body.appendChild(dataList);
    }

    nominalInputs.forEach(visibleInput => {
      // 1. Setup hidden input
      const originalName = visibleInput.getAttribute('name');
      visibleInput.removeAttribute('name');
      visibleInput.setAttribute('type', 'text');

      if (history.length > 0) {
        visibleInput.setAttribute('list', 'nominal-history-list');
        visibleInput.setAttribute('autocomplete', 'off');
      }

      const hiddenInput = document.createElement('input');
      hiddenInput.type = 'hidden';
      hiddenInput.name = originalName;
      hiddenInput.value = visibleInput.value;
      visibleInput.parentNode.insertBefore(hiddenInput, visibleInput.nextSibling);

      // 2. Format function
      const formatValue = (val) => {
        let raw = val.toLowerCase().trim();
        let addK = raw.endsWith('k');
        let addM = raw.endsWith('jt');

        if (addK || addM) {
          let multiplier = addK ? 1000 : 1000000;
          let numPart = raw.replace(/(k|jt)$/, '').replace(/\./g, '');
          numPart = numPart.replace(',', '.');
          let parsed = parseFloat(numPart);
          if (!isNaN(parsed)) {
            return Math.round(parsed * multiplier).toString();
          }
        }

        raw = raw.replace(/[^0-9,]/g, '');
        let parts = raw.split(',');
        if (parts.length > 1) {
          return parts[0] + ',' + parts[1];
        }
        return parts[0];
      };

      const updateUI = () => {
        let raw = formatValue(visibleInput.value);
        if (raw.includes(',')) {
          let parts = raw.split(',');
          let intPart = parts[0] ? parseInt(parts[0], 10).toLocaleString('id-ID') : '0';
          visibleInput.value = intPart + ',' + parts[1];
          let floatVal = parseFloat(raw.replace(',', '.'));
          hiddenInput.value = isNaN(floatVal) ? '' : Math.round(floatVal).toString();
        } else {
          hiddenInput.value = raw;
          if (raw) {
            visibleInput.value = parseInt(raw, 10).toLocaleString('id-ID');
          } else {
            visibleInput.value = '';
          }
        }
      };

      // Format initial value if exists
      if (visibleInput.value) {
        updateUI();
      }

      // 3. Listen to input events
      visibleInput.addEventListener('input', function (e) {
        let val = this.value.toLowerCase();
        if (val.endsWith('k') || val.endsWith('jt')) {
          updateUI();
        } else if (val.endsWith('j')) {
          // do nothing, wait for 't'
        } else {
          const cursorPosition = this.selectionStart;
          const oldLength = this.value.length;
          updateUI();
          const newLength = this.value.length;
          let newPos = cursorPosition + (newLength - oldLength);
          this.setSelectionRange(newPos, newPos);
        }
      });

      // Format on blur to clean up any dangling 'j' or comma
      visibleInput.addEventListener('blur', function () {
        if (visibleInput.value.toLowerCase().endsWith('j')) {
          visibleInput.value = visibleInput.value.slice(0, -1);
        }
        if (visibleInput.value.endsWith(',')) {
          visibleInput.value = visibleInput.value.slice(0, -1);
        }
        updateUI();
      });

      // 4. On form submit, save history
      const form = visibleInput.closest('form');
      if (form) {
        form.addEventListener('submit', function () {
          const rawVal = hiddenInput.value;
          if (rawVal && rawVal > 0) {
            history = history.filter(item => item !== rawVal);
            history.unshift(rawVal);
            history = history.slice(0, MAX_HISTORY);
            localStorage.setItem(HISTORY_KEY, JSON.stringify(history));
          }
        });
      }
    });
  }
});
