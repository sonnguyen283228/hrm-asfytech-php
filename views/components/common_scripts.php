<script>
  // Bootstrap 5 form validation
  (function () {
    'use strict'
    var forms = document.querySelectorAll('.needs-validation')
    Array.prototype.slice.call(forms).forEach(function (form) {
      form.addEventListener('submit', function (event) {
        if (!form.checkValidity()) {
          event.preventDefault()
          event.stopPropagation()
        }
        form.classList.add('was-validated')
      }, false)
    });
    
    // Auto hide alerts after 4s
    setTimeout(function() {
      document.querySelectorAll('.auto-dismiss-alert').forEach(function(alertNode) {
        var alert = new bootstrap.Alert(alertNode)
        alert.close()
      })
    }, 4000);
  })();
</script>
