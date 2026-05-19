/**
 * FinTrack — App JavaScript
 * Minimal JS for AdminLTE integration
 */

// Custom file input label update
$(document).ready(function() {
  // Update custom file input label with selected filename
  $('.custom-file-input').on('change', function() {
    var fileName = $(this).val().split('\\').pop();
    $(this).next('.custom-file-label').addClass("selected").html(fileName);
  });

  // Auto-dismiss alerts after 5 seconds
  setTimeout(function() {
    $('.alert-dismissible').fadeOut('slow');
  }, 5000);
});
