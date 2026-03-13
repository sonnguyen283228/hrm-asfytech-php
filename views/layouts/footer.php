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
  <script>
    feather.replace();
  </script>
</body>
</html>
