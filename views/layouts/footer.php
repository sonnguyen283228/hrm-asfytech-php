      <?= site_get('footer_html', '') ?>
      <?php $ft = site_get('footer_text', 'Công ty cổ phần ASFY TECH'); $ft = str_replace('YECH','TECH',$ft); ?>
      <div class="text-center text-700 fs-10 mt-3" style="padding:10px 14px;border-top:1px solid #e7edf9;">
        <?= htmlspecialchars($ft ?: 'Công ty cổ phần ASFY TECH') ?>
      </div>
    </div>
  </main>
  <script src="/theme-phoenix/vendors/popper/popper.min.js"></script>
  <script src="/theme-phoenix/vendors/bootstrap/bootstrap.min.js"></script>
</body>
</html>
