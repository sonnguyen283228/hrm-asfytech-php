<!-- Modal Thêm Chức Vụ -->
<div class="modal fade" id="positionModal" tabindex="-1" aria-labelledby="positionModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg">
      <div class="modal-header bg-light border-bottom border-200">
        <h5 class="modal-title text-1000" id="positionModalLabel">Thêm Chức Vụ Mới</h5>
        <button class="btn p-1" type="button" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times fs--1"></span></button>
      </div>
      <form method="post" action="/positions/create" class="needs-validation" novalidate>
        <div class="modal-body p-4 bg-white">
          <div class="mb-3">
            <label class="form-label" for="pos_name">Tên chức danh/vị trí <span class="text-danger">*</span></label>
            <input class="form-control" id="pos_name" name="name" type="text" placeholder="VD: Lập trình viên, Giám đốc..." required />
            <div class="invalid-feedback">Vui lòng nhập tên chức vụ.</div>
          </div>
          <div class="mb-0">
            <label class="form-label" for="pos_desc">Mô tả thêm</label>
            <textarea class="form-control" id="pos_desc" name="description" rows="3" placeholder="Mô tả tóm tắt về vị trí này..."></textarea>
          </div>
        </div>
        <div class="modal-footer border-top-0 px-4 pb-4">
          <button class="btn btn-outline-secondary" type="button" data-bs-dismiss="modal">Hủy</button>
          <button class="btn btn-primary px-4" type="submit">Lưu Chức Vụ</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal Sửa Chức Vụ -->
<div class="modal fade" id="editPositionModal" tabindex="-1" aria-labelledby="editPositionModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg">
      <div class="modal-header bg-light border-bottom border-200">
        <h5 class="modal-title text-1000" id="editPositionModalLabel">Chỉnh Sửa Chức Vụ</h5>
        <button class="btn p-1" type="button" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times fs--1"></span></button>
      </div>
      <form method="post" action="/positions/edit" class="needs-validation" novalidate>
        <input type="hidden" id="edit_pos_id" name="id" value="" />
        <div class="modal-body p-4 bg-white">
          <div class="mb-3">
            <label class="form-label">Tên chức danh/vị trí <span class="text-danger">*</span></label>
            <input class="form-control" id="edit_pos_name" name="name" type="text" required />
            <div class="invalid-feedback">Vui lòng nhập tên chức vụ.</div>
          </div>
          <div class="mb-0">
            <label class="form-label">Mô tả thêm</label>
            <textarea class="form-control" id="edit_pos_desc" name="description" rows="3"></textarea>
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
  function editPosition(data) {
      document.getElementById('edit_pos_id').value = data.id || '';
      document.getElementById('edit_pos_name').value = data.name || '';
      document.getElementById('edit_pos_desc').value = data.description || '';
  }
</script>
