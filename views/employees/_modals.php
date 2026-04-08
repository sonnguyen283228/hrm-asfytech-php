<!-- Modal Thêm Nhân Sự -->
<div class="modal fade" id="employeeModal" tabindex="-1" aria-labelledby="employeeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg">
      <div class="modal-header bg-light border-bottom border-200">
        <h5 class="modal-title text-1000" id="employeeModalLabel">Thêm Nhân Sự Mới</h5>
        <button class="btn p-1" type="button" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times fs--1"></span></button>
      </div>
      <form method="post" action="/employees/create" class="needs-validation" novalidate>
        <div class="modal-body p-4 bg-white">
          <div class="row g-3">
            <div class="col-12"><h6 class="text-700 fw-bold mb-0">Hồ sơ cá nhân</h6><hr class="mt-2 mb-3"/></div>
            <div class="col-md-6">
              <label class="form-label" for="full_name">Họ và tên <span class="text-danger">*</span></label>
              <input class="form-control" id="full_name" name="full_name" type="text" required />
              <div class="invalid-feedback">Vui lòng nhập họ và tên.</div>
            </div>
            <div class="col-md-6">
              <label class="form-label" for="email">Tài khoản Google (Email) <span class="text-danger">*</span></label>
              <input class="form-control" id="email" name="email" type="email" pattern="^[a-zA-Z0-9._%+-]+@gmail\.com$" placeholder="name@gmail.com" required />
              <div class="invalid-feedback">Bắt buộc sử dụng @gmail.com</div>
            </div>
            <div class="col-md-6">
              <label class="form-label" for="phone">Số điện thoại <span class="text-danger">*</span></label>
              <input class="form-control" id="phone" name="phone" type="tel" pattern="^(0[3|5|7|8|9])+([0-9]{8})$" placeholder="09xxxxxxxx" required />
            </div>
            <div class="col-md-6">
              <label class="form-label" for="birth_date">Ngày sinh</label>
              <input class="form-control" id="birth_date" name="birth_date" type="date" />
            </div>
            <div class="col-md-6">
               <label class="form-label">Tỉnh / Thành phố</label>
               <select class="form-select province-select" id="create_province_id">
                 <option value="">-- Chọn Tỉnh/TP --</option>
               </select>
               <input type="hidden" name="address_city" id="create_address_city" />
            </div>
            <div class="col-md-6">
               <label class="form-label">Phường / Xã</label>
               <select class="form-select ward-select" id="create_ward_id" disabled>
                 <option value="">-- Chọn Phường/Xã --</option>
               </select>
               <input type="hidden" name="address_ward" id="create_address_ward" />
            </div>

            <div class="col-12 mt-4"><h6 class="text-700 fw-bold mb-0">Thông tin công việc</h6><hr class="mt-2 mb-3"/></div>
            
            <div class="col-md-6">
              <label class="form-label" for="department_id">Phòng ban</label>
              <select class="form-select" id="department_id" name="department_id">
                <option value="" selected>-- Chọn phòng ban --</option>
                <?php foreach (($departments ?? []) as $d): ?>
                  <option value="<?= (int)$d['id'] ?>"><?= htmlspecialchars($d['name']) ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-md-6">
              <label class="form-label" for="position_id">Vị trí chức vụ <span class="text-danger">*</span></label>
              <select class="form-select" id="position_id" name="position_id" required>
                <option value="" selected>-- Chọn chức vụ --</option>
                <?php foreach (($positions ?? []) as $p): ?>
                  <option value="<?= (int)$p['id'] ?>"><?= htmlspecialchars($p['name']) ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-md-4">
              <label class="form-label" for="start_date">Ngày vào làm việc</label>
              <input class="form-control" id="start_date" name="start_date" type="date" />
            </div>
            <div class="col-md-4">
              <label class="form-label" for="base_salary">Lương cơ bản (VND)</label>
              <div class="input-group">
                <input class="form-control" id="base_salary" name="base_salary" type="number" min="0" step="1" />
                <span class="input-group-text">đ</span>
              </div>
            </div>
            <div class="col-md-4">
              <label class="form-label" for="role">Quyền hệ thống <span class="text-danger">*</span></label>
              <select class="form-select" id="role" name="role" required>
                <option value="staff" selected>Staff</option>
                <option value="manager">Manager / HR</option>
                <option value="admin">Admin</option>
              </select>
            </div>
          </div>
        </div>
        <div class="modal-footer bg-light border-top border-200 px-4 py-3">
          <button class="btn btn-outline-secondary" type="button" data-bs-dismiss="modal">Hủy</button>
          <button class="btn btn-primary px-5" type="submit">Thêm</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal Sửa Nhân Sự -->
<div class="modal fade" id="editEmployeeModal" tabindex="-1" aria-labelledby="editEmployeeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg">
      <div class="modal-header bg-light border-bottom border-200">
        <h5 class="modal-title text-1000" id="editEmployeeModalLabel">Thay Đổi Thông Tin Nhân Sự</h5>
        <button class="btn p-1" type="button" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times fs--1"></span></button>
      </div>
      <form method="post" action="/employees/edit" class="needs-validation" novalidate>
        <input type="hidden" id="edit_user_id" name="id" value="" />
        
        <div class="modal-body p-4 bg-white">
          <div class="row g-3">
            <div class="col-12"><h6 class="text-700 fw-bold mb-0">Hồ sơ cá nhân</h6><hr class="mt-2 mb-3"/></div>
            
            <div class="col-md-6">
              <label class="form-label" for="edit_full_name">Họ và tên <span class="text-danger">*</span></label>
              <input class="form-control" id="edit_full_name" name="full_name" type="text" required />
            </div>
            <div class="col-md-6">
              <label class="form-label text-500" for="edit_email">Tài khoản Google <span class="text-danger">*</span></label>
              <input class="form-control" id="edit_email" name="email" type="email" pattern="^[a-zA-Z0-9._%+-]+@gmail\.com$" required />
            </div>
            <div class="col-md-6">
              <label class="form-label" for="edit_phone">Số điện thoại <span class="text-danger">*</span></label>
              <input class="form-control" id="edit_phone" name="phone" type="tel" pattern="^(0[3|5|7|8|9])+([0-9]{8})$" required />
            </div>
            <div class="col-md-6">
              <label class="form-label" for="edit_birth_date">Ngày sinh</label>
              <input class="form-control" id="edit_birth_date" name="birth_date" type="date" />
            </div>
            <div class="col-md-6">
               <label class="form-label" for="edit_province_id">Tỉnh / Thành phố</label>
               <select class="form-select province-select" id="edit_province_id">
                 <option value="">-- Chọn Tỉnh/TP --</option>
               </select>
               <input type="hidden" name="address_city" id="edit_address_city" />
            </div>
            <div class="col-md-6">
               <label class="form-label" for="edit_ward_id">Phường / Xã</label>
               <select class="form-select ward-select" id="edit_ward_id" disabled>
                 <option value="">-- Chọn Phường/Xã --</option>
               </select>
               <input type="hidden" name="address_ward" id="edit_address_ward" />
            </div>

            <div class="col-12 mt-4"><h6 class="text-700 fw-bold mb-0">Thông tin công việc</h6><hr class="mt-2 mb-3"/></div>
            
            <div class="col-md-6">
              <label class="form-label" for="edit_department_id">Phòng ban</label>
              <select class="form-select" id="edit_department_id" name="department_id">
                <option value="">-- Chọn phòng ban --</option>
                <?php foreach (($departments ?? []) as $d): ?>
                  <option value="<?= (int)$d['id'] ?>"><?= htmlspecialchars($d['name']) ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-md-6">
              <label class="form-label" for="edit_position_id">Vị trí chức vụ <span class="text-danger">*</span></label>
              <select class="form-select" id="edit_position_id" name="position_id" required>
                <option value="">-- Chọn chức vụ --</option>
                <?php foreach (($positions ?? []) as $p): ?>
                  <option value="<?= (int)$p['id'] ?>"><?= htmlspecialchars($p['name']) ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-md-4">
              <label class="form-label" for="edit_start_date">Ngày vào làm việc</label>
              <input class="form-control" id="edit_start_date" name="start_date" type="date" />
            </div>
            <div class="col-md-4">
              <label class="form-label" for="edit_base_salary">Lương cơ bản (VND)</label>
              <div class="input-group">
                <input class="form-control" id="edit_base_salary" name="base_salary" type="number" min="0" step="1" />
                <span class="input-group-text">đ</span>
              </div>
            </div>
            <div class="col-md-4">
              <label class="form-label" for="edit_role">Quyền hệ thống <span class="text-danger">*</span></label>
              <select class="form-select" id="edit_role" name="role" required>
                <option value="staff">Staff</option>
                <option value="manager">Manager / HR</option>
                <option value="admin">Admin</option>
              </select>
            </div>
          </div>
        </div>
        <div class="modal-footer bg-light border-top border-200 px-4 py-3">
          <button class="btn btn-outline-secondary" type="button" data-bs-dismiss="modal">Hủy</button>
          <button class="btn btn-primary px-5" type="submit">Lưu Thay Đổi</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal Xem Chi Tiết -->
<div class="modal fade" id="viewEmployeeModal" tabindex="-1" aria-labelledby="viewEmployeeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-md modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg">
      <div class="modal-header bg-light border-bottom border-200">
        <h5 class="modal-title text-1000" id="viewEmployeeModalLabel">Hồ Sơ Nhân Sự</h5>
        <button class="btn p-1" type="button" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times fs--1"></span></button>
      </div>
      <div class="modal-body p-4 bg-white text-center">
        <div class="avatar avatar-4xl mb-3">
          <img id="view_avatar" class="rounded-circle shadow-sm" src="/phoenix/assets/img/team/avatar.webp" alt="" />
        </div>
        <h4 id="view_full_name" class="text-1000 fw-bold mb-1">--</h4>
        <p id="view_email" class="text-600 mb-4">--</p>
        
        <div class="text-start">
          <h6 class="text-700 fw-bold mb-2 text-uppercase fs--2">Thông tin liên lạc</h6>
          <div class="d-flex align-items-center mb-2"><span data-feather="phone" class="text-500 me-2" style="width:16px;height:16px"></span><span id="view_phone" class="text-900 fw-semi-bold">--</span></div>
          <div class="d-flex align-items-center mb-2"><span data-feather="calendar" class="text-500 me-2" style="width:16px;height:16px"></span><span id="view_birth_date" class="text-900 fw-semi-bold">--</span></div>
          <div class="d-flex align-items-center mb-4"><span data-feather="map-pin" class="text-500 me-2" style="width:16px;height:16px"></span><span id="view_address" class="text-900 fw-semi-bold">--</span></div>
          
          <h6 class="text-700 fw-bold mb-2 text-uppercase fs--2 mt-4">Thông tin công việc</h6>
          <div class="d-flex align-items-center mb-2"><span data-feather="briefcase" class="text-500 me-2" style="width:16px;height:16px"></span><span class="text-900 fw-semi-bold">Phòng ban: </span><span id="view_department" class="ms-1 text-700">--</span></div>
          <div class="d-flex align-items-center mb-2"><span data-feather="award" class="text-500 me-2" style="width:16px;height:16px"></span><span class="text-900 fw-semi-bold">Vị trí: </span><span id="view_position" class="ms-1 text-700">--</span></div>
          <div class="d-flex align-items-center mb-2"><span data-feather="shield" class="text-500 me-2" style="width:16px;height:16px"></span><span class="text-900 fw-semi-bold">Quyền hạn: </span><span id="view_role" class="ms-1 text-700 text-uppercase badge badge-phoenix badge-phoenix-primary">--</span></div>
          <div class="d-flex align-items-center mb-2"><span data-feather="clock" class="text-500 me-2" style="width:16px;height:16px"></span><span class="text-900 fw-semi-bold">Ngày vào làm: </span><span id="view_start_date" class="ms-1 text-700">--</span></div>
          <div class="d-flex align-items-center mb-2"><span data-feather="dollar-sign" class="text-500 me-2" style="width:16px;height:16px"></span><span class="text-900 fw-semi-bold">Lương CB: </span><span id="view_base_salary" class="ms-1 text-700 fw-bold text-primary">--</span></div>
        </div>
      </div>
    </div>
  </div>
</div>
<script>
  // Prepare Edit Modal
  function editEmployee(data) {
      document.getElementById('edit_user_id').value = data.id || '';
      document.getElementById('edit_full_name').value = data.full_name || '';
      document.getElementById('edit_email').value = data.email || '';
      document.getElementById('edit_phone').value = data.phone || '';
      document.getElementById('edit_birth_date').value = data.birth_date || '';
      document.getElementById('edit_address_city').value = data.address_city || '';
      document.getElementById('edit_address_ward').value = data.address_ward || '';
      document.getElementById('edit_department_id').value = data.department_id || '';
      document.getElementById('edit_position_id').value = data.position_id || '';
      document.getElementById('edit_start_date').value = data.start_date || '';
      document.getElementById('edit_base_salary').value = data.base_salary || '';
      document.getElementById('edit_role').value = (data.role || 'staff').toLowerCase();
      
      // Handle Location Dropdowns Pre-selection
      const provinceSelect = document.getElementById('edit_province_id');
      const wardSelect = document.getElementById('edit_ward_id');
      
      // Reset dropdowns
      provinceSelect.value = '';
      wardSelect.innerHTML = '<option value="">-- Chọn Phường/Xã --</option>';
      wardSelect.disabled = true;

      if (window.locationData && data.address_city) {
          // Find matching province by name
          const province = window.locationData.find(p => p.name === data.address_city);
          if (province) {
              provinceSelect.value = province.code;
              
              // Populate and enable wards
              if (province.wards) {
                  province.wards.forEach(ward => {
                    const option = document.createElement('option');
                    option.value = ward.code;
                    option.textContent = ward.name;
                    option.dataset.name = ward.name;
                    wardSelect.appendChild(option);
                  });
                  wardSelect.disabled = false;
              }

              if (data.address_ward) {
                  // Find matching ward by name
                  const ward = province.wards?.find(w => w.name === data.address_ward);
                  if (ward) {
                      wardSelect.value = ward.code;
                  }
              }
          }
      }

      // Mở modal programmatically (tránh xung đột data-bs-toggle)
      var editModal = new bootstrap.Modal(document.getElementById('editEmployeeModal'));
      editModal.show();
  }

  // Prepare View Detail Modal
  function formatDateVN(dateStr) {
      if(!dateStr) return '--';
      let d = new Date(dateStr);
      if(isNaN(d)) return dateStr;
      return d.toLocaleDateString('vi-VN');
  }

  function viewEmployeeDetail(data) {
      document.getElementById('view_full_name').innerText = data.full_name || '--';
      document.getElementById('view_email').innerText = data.email || '--';
      document.getElementById('view_phone').innerText = data.phone || '--';
      
      let dob = formatDateVN(data.birth_date);
      document.getElementById('view_birth_date').innerText = dob;
      
      let addressParts = [];
      if(data.address_ward) addressParts.push(data.address_ward);
      if(data.address_city) addressParts.push(data.address_city);
      document.getElementById('view_address').innerText = addressParts.length > 0 ? addressParts.join(', ') : '--';
      
      document.getElementById('view_department').innerText = data.department_name || '--';
      document.getElementById('view_position').innerText = data.position || '--';
      document.getElementById('view_role').innerText = data.role || 'STAFF';
      document.getElementById('view_start_date').innerText = formatDateVN(data.start_date);
      
      let formatSalary = data.base_salary ? new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(data.base_salary) : '--';
      document.getElementById('view_base_salary').innerText = formatSalary;
      
      if(data.avatar_url) {
          document.getElementById('view_avatar').src = data.avatar_url;
      } else {
          document.getElementById('view_avatar').src = '/phoenix/assets/img/team/avatar.webp';
      }
      
      // Mở modal programmatically
      var detailModal = new bootstrap.Modal(document.getElementById('viewEmployeeModal'));
      detailModal.show();
      feather.replace();
  }

  // Handle Location Dropdowns Data Fetching and Logic
  document.addEventListener('DOMContentLoaded', function() {
    const apiEndpoint = '/data.json';
    let locationData = [];

    // Fetch data once and store it
    fetch(apiEndpoint)
      .then(response => response.json())
      .then(data => {
        locationData = data;
        populateProvinces('create');
        populateProvinces('edit');
      })
      .catch(error => console.error('Error fetching location data:', error));

    function populateProvinces(prefix) {
      const provinceSelect = document.getElementById(`${prefix}_province_id`);
      if (!provinceSelect) return;
      
      locationData.forEach(province => {
        const option = document.createElement('option');
        option.value = province.code;
        option.textContent = province.name;
        option.dataset.name = province.name;
        provinceSelect.appendChild(option);
      });
    }

    function handleProvinceChange(prefix) {
      const provinceSelect = document.getElementById(`${prefix}_province_id`);
      const wardSelect = document.getElementById(`${prefix}_ward_id`);
      const cityInput = document.getElementById(`${prefix}_address_city`);
      const wardInput = document.getElementById(`${prefix}_address_ward`);

      if (!provinceSelect) return;

      provinceSelect.addEventListener('change', function() {
        const provinceCode = this.value;
        const provinceOption = this.options[this.selectedIndex];
        
        // Reset and disable ward
        wardSelect.innerHTML = '<option value="">-- Chọn Phường/Xã --</option>';
        wardSelect.disabled = true;
        cityInput.value = '';
        wardInput.value = '';

        if (provinceCode) {
          cityInput.value = provinceOption.dataset.name;
          const province = locationData.find(p => p.code == provinceCode);
          if (province && province.wards) {
            province.wards.forEach(ward => {
              const option = document.createElement('option');
              option.value = ward.code;
              option.textContent = ward.name;
              option.dataset.name = ward.name;
              wardSelect.appendChild(option);
            });
            wardSelect.disabled = false;
          }
        }
      });

      wardSelect.addEventListener('change', function() {
         const wardOption = this.options[this.selectedIndex];
         if (this.value) {
            wardInput.value = wardOption.dataset.name;
         } else {
            wardInput.value = '';
         }
      });
    }

    handleProvinceChange('create');
    handleProvinceChange('edit');
    
    // Store the global location data for use in editEmployee
    window.locationData = locationData;
  });
</script>
