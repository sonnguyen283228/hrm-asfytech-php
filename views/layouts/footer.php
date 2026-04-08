    </div> <!-- End .content -->
    
    <!-- Footer -->
    <footer class="footer position-absolute" style="bottom: 0; width: 100%;">
      <div class="row g-0 justify-content-center align-items-center h-100">
        <div class="col-12 text-center">
            <?= site_get('footer_html', '') ?>
            <?php $ft = site_get('footer_text', 'Công ty cổ phần ASFY TECH'); $ft = str_replace('YECH','TECH',$ft); ?>
            <p class="mb-0 text-600"><?= htmlspecialchars($ft ?: 'Công ty cổ phần ASFY TECH') ?> <span class="d-none d-sm-inline-block">| </span><br class="d-sm-none" /> 2026 &copy;</p>
        </div>
      </div>
    </footer>
    
  </main>
  
  <!-- Scripts -->
  <script src="/phoenix/vendors/popper/popper.min.js"></script>
  <script src="/phoenix/vendors/bootstrap/bootstrap.min.js"></script>
  <script src="/phoenix/assets/js/phoenix.js"></script>
  <script src="/phoenix/vendors/feather-icons/feather.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
  <script>
    feather.replace();
    
    // Global SweetAlert Toast System
    const Toast = Swal.mixin({
      toast: true,
      position: 'top-end',
      showConfirmButton: false,
      timer: 3500,
      timerProgressBar: true,
      didOpen: (toast) => {
        toast.addEventListener('mouseenter', Swal.stopTimer)
        toast.addEventListener('mouseleave', Swal.resumeTimer)
      }
    });
  </script>

  <?php if (!empty($_SESSION['success'])): ?>
  <script>
    Toast.fire({
      icon: 'success',
      title: <?= json_encode($_SESSION['success']) ?>
    });
  </script>
  <?php unset($_SESSION['success']); endif; ?>

  <?php if (!empty($_SESSION['error'])): ?>
  <script>
    Toast.fire({
      icon: 'error',
      title: <?= json_encode($_SESSION['error']) ?>
    });
  </script>
  <?php unset($_SESSION['error']); endif; ?>

</body>
</html>
