/**
 * FinTrack — App JavaScript
 * Minimal JS for Tabler integration (no jQuery dependency)
 */

document.addEventListener('DOMContentLoaded', function() {
  // Auto-dismiss alerts after 5 seconds
  document.querySelectorAll('.alert-dismissible').forEach(function(alert) {
    setTimeout(function() {
      alert.style.transition = 'opacity 0.5s ease';
      alert.style.opacity = '0';
      setTimeout(function() { alert.remove(); }, 500);
    }, 5000);
  });
});
