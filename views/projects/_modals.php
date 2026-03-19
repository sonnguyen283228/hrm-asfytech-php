<!-- Modal Trương Mới Dự Án -->
<div class="modal fade" id="createProjectModal" tabindex="-1" aria-labelledby="createProjectModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg">
      <div class="modal-header bg-light border-bottom border-200">
        <h5 class="modal-title text-1000" id="createProjectModalLabel">Chỉ Định Dự Án Mới</h5>
        <button class="btn p-1" type="button" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times fs--1"></span></button>
      </div>
      <form method="post" action="/projects/create" class="needs-validation" novalidate>
        <div class="modal-body p-4 bg-white">
          <div class="mb-3">
            <label class="form-label">Tên dự án <span class="text-danger">*</span></label>
            <input class="form-control" name="name" type="text" required placeholder="Tên gọi của dự án..." />
            <div class="invalid-feedback">Vui lòng nhập tên dự án.</div>
          </div>
          <div class="mb-3">
            <label class="form-label">Ngày bắt đầu <span class="text-danger">*</span></label>
            <input class="form-control" name="start_date" type="date" required />
            <div class="invalid-feedback">Ngày bắt đầu không được để trống.</div>
          </div>
          <div class="mb-0">
            <label class="form-label">Mô tả (Không bắt buộc)</label>
            <textarea class="form-control" name="description" rows="3" placeholder="Ghi chú thêm..."></textarea>
          </div>
        </div>
        <div class="modal-footer border-top-0 px-4 pb-4">
          <button class="btn btn-outline-secondary" type="button" data-bs-dismiss="modal">Hủy</button>
          <button class="btn btn-primary px-4" type="submit">Thêm Dự Án Mới</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal Sửa Dự Án -->
<div class="modal fade" id="editProjectModal" tabindex="-1" aria-labelledby="editProjectModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg">
      <div class="modal-header bg-light border-bottom border-200">
        <h5 class="modal-title text-1000" id="editProjectModalLabel">Cập Nhật Dự Án</h5>
        <button class="btn p-1" type="button" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times fs--1"></span></button>
      </div>
      <form method="post" action="/projects/edit" class="needs-validation" novalidate>
        <input type="hidden" id="edit_proj_id" name="id" value="" />
        <div class="modal-body p-4 bg-white">
          <div class="mb-3">
            <label class="form-label">Tên dự án <span class="text-danger">*</span></label>
            <input class="form-control" id="edit_proj_name" name="name" type="text" required />
            <div class="invalid-feedback">Vui lòng nhập tên dự án.</div>
          </div>
          <div class="mb-3">
            <label class="form-label">Ngày bắt đầu <span class="text-danger">*</span></label>
            <input class="form-control" id="edit_proj_start" name="start_date" type="date" required />
            <div class="invalid-feedback">Ngày bắt đầu không được để trống.</div>
          </div>
          <div class="mb-0">
            <label class="form-label">Trạng thái tổng quan</label>
            <select class="form-select" id="edit_proj_status" name="status">
              <option value="Kế hoạch">Kế hoạch</option>
              <option value="Đang triển khai">Đang triển khai</option>
              <option value="Tạm dừng">Tạm dừng</option>
              <option value="Hoàn thành">Hoàn thành</option>
            </select>
          </div>
        </div>
        <div class="modal-footer border-top-0 px-4 pb-4">
          <button class="btn btn-outline-secondary" type="button" data-bs-dismiss="modal">Hủy</button>
          <button class="btn btn-primary px-4" type="submit">Lưu Thay Đổi</button>
        </div>
      </form>
    </div>
  </div>
</div>
<script>
  function editProject(data) {
      document.getElementById('edit_proj_id').value = data.id || '';
      document.getElementById('edit_proj_name').value = data.name || '';
      if(data.start_date) {
         // Cắt lấy format YYYY-MM-DD
         document.getElementById('edit_proj_start').value = data.start_date.substring(0, 10);
      } else {
         document.getElementById('edit_proj_start').value = '';
      }
      document.getElementById('edit_proj_status').value = data.status || 'Kế hoạch';
  }
</script>
