      <?= site_get('footer_html', '') ?>
      <?php $ft = str_replace('YECH','TECH',site_get('footer_text', '© ASFY TECH')); ?>
      <div class="text-center text-700 fs-10 mt-3"><?= htmlspecialchars($ft ?: '© ASFY TECH') ?></div>
    </div>
  </main>
  <script src="/theme-phoenix/vendors/popper/popper.min.js"></script>
  <script src="/theme-phoenix/vendors/bootstrap/bootstrap.min.js"></script>
</body>
</html>
