<div class="row align-items-center justify-content-between pe-0 fs--1 mt-3">
  <div class="col-auto d-flex">
    <p class="mb-0 d-none d-sm-block me-3 fw-semi-bold text-900" data-list-info="data-list-info"></p>
    <?php if ($show_all_btn ?? false): ?>
    <a class="fw-semi-bold" href="#!" data-list-view="*">Xem tất cả<span class="fas fa-angle-right ms-1" data-fa-transform="down-1"></span></a>
    <a class="fw-semi-bold d-none" href="#!" data-list-view="less">Thu gọn<span class="fas fa-angle-right ms-1" data-fa-transform="down-1"></span></a>
    <?php endif; ?>
  </div>
  <div class="col-auto d-flex">
    <button class="page-link" data-list-pagination="prev"><span class="fas fa-chevron-left"></span></button>
    <ul class="mb-0 pagination"></ul>
    <button class="page-link pe-0" data-list-pagination="next"><span class="fas fa-chevron-right"></span></button>
  </div>
</div>
