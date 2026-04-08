<!-- Generic Confirm Modal -->
<div class="modal fade" id="confirmActionModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-sm">
    <div class="modal-content shadow-lg border-0">
      <div class="modal-body p-4 text-center">
        <div class="text-warning mb-3">
            <span data-feather="alert-circle" style="width: 48px; height: 48px;"></span>
        </div>
        <h5 class="mb-3 text-1000" id="confirmModalTitle">Xác nhận</h5>
        <p class="text-700 fs--1 mb-4" id="confirmModalMessage">Bạn có chắc chắn thực hiện hành động này không?</p>
        <div class="d-flex justify-content-center gap-2">
            <button class="btn btn-outline-secondary btn-sm px-4" type="button" data-bs-dismiss="modal">Hủy bỏ</button>
            <button class="btn btn-primary btn-sm px-4" id="btnConfirmAction" type="button">Đồng ý</button>
        </div>
      </div>
    </div>
  </div>
</div>

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

    // Custom Confirm Dialog for forms
    let pendingConfirmForm = null;
    document.querySelectorAll('form.confirm-action').forEach(function(form) {
      form.addEventListener('submit', function(e) {
        e.preventDefault();
        pendingConfirmForm = form;
        const msg = form.getAttribute('data-message') || 'Bạn có chắc chắn thực hiện hành động này không?';
        document.getElementById('confirmModalMessage').innerText = msg;
        
        var confirmModal = new bootstrap.Modal(document.getElementById('confirmActionModal'));
        confirmModal.show();
        
        if(typeof feather !== 'undefined') feather.replace();
      });
    });

    document.getElementById('btnConfirmAction')?.addEventListener('click', function() {
      if (pendingConfirmForm) {
        pendingConfirmForm.submit();
      }
    });

  })();
</script>
