<!-- Modal Thêm Phòng Ban -->
<div class="modal fade" id="deptModal" tabindex="-1" aria-labelledby="deptModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg">
      <div class="modal-header bg-light border-bottom border-200">
        <h5 class="modal-title text-1000" id="deptModalLabel">Thêm Phòng Ban Mới</h5>
        <button class="btn p-1" type="button" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times fs--1"></span></button>
      </div>
      <form method="post" action="/departments/create" class="needs-validation" novalidate>
        <div class="modal-body p-4 bg-white">
          <div class="mb-3">
            <label class="form-label" for="dept_name">Tên phòng ban <span class="text-danger">*</span></label>
            <input class="form-control" id="dept_name" name="name" type="text" placeholder="VD: Phòng Hành chính, IT..." required />
            <div class="invalid-feedback">Vui lòng nhập tên phòng ban.</div>
          </div>
          <div class="mb-0">
            <label class="form-label" for="dept_desc">Mô tả chức năng</label>
            <textarea class="form-control" id="dept_desc" name="description" rows="3" placeholder="Ghi chú thêm về chức năng của phòng ban này..."></textarea>
          </div>
        </div>
        <div class="modal-footer border-top-0 px-4 pb-4">
          <button class="btn btn-outline-secondary" type="button" data-bs-dismiss="modal">Hủy</button>
          <button class="btn btn-primary px-4" type="submit">Lưu Phòng Ban</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal Sửa Phòng Ban -->
<div class="modal fade" id="editDeptModal" tabindex="-1" aria-labelledby="editDeptModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg">
      <div class="modal-header bg-light border-bottom border-200">
        <h5 class="modal-title text-1000" id="editDeptModalLabel">Chỉnh Sửa Phòng Ban</h5>
        <button class="btn p-1" type="button" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times fs--1"></span></button>
      </div>
      <form method="post" action="/departments/edit" class="needs-validation" novalidate>
        <input type="hidden" id="edit_dept_id" name="id" value="" />
        <div class="modal-body p-4 bg-white">
          <div class="mb-3">
            <label class="form-label">Tên phòng ban <span class="text-danger">*</span></label>
            <input class="form-control" id="edit_dept_name" name="name" type="text" required />
            <div class="invalid-feedback">Vui lòng nhập tên phòng ban.</div>
          </div>
          <div class="mb-0">
            <label class="form-label">Mô tả chức năng</label>
            <textarea class="form-control" id="edit_dept_desc" name="description" rows="3"></textarea>
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
  function editDept(data) {
      document.getElementById('edit_dept_id').value = data.id || '';
      document.getElementById('edit_dept_name').value = data.name || '';
      document.getElementById('edit_dept_desc').value = data.description || '';
  }
</script>
