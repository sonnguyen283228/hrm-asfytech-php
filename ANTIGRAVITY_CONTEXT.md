# ANTIGRAVITY_CONTEXT.md

PROJECT: HRM APP WEB (PHP)
PATH: F:\Project\hrm-php
PRIMARY_COLOR: #4285f4
REFERENCE_STYLE: Phoenix + asfy.vn vibe

## 1) OBJECTIVE
Xây dựng và hoàn thiện app HRM theo đúng tài liệu mô tả chức năng, ưu tiên giao diện chuyên nghiệp, chuẩn quản trị doanh nghiệp, giữ code ổn định và dễ bảo trì.

## 2) BUSINESS SCOPE
### Module 1: Quản lý nhân sự, phòng ban, chức vụ
- Thêm/sửa/khóa nhân sự
- Thêm/sửa/khóa phòng ban
- Thêm/sửa/khóa chức vụ
- Chấm công hằng ngày
- Danh sách nhân sự có lọc + export Excel/PDF
- Danh sách phòng ban

### Module 2: Quản lý dự án
- Thêm/sửa/khóa dự án
- Gán nhân sự vào dự án + vai trò trong dự án
- Thêm chi tiết triển khai (nội dung, người thực hiện, ngày thực hiện, số ngày)
- Tính tỷ lệ hoàn thành dự án theo trọng số thời gian

### Trang tổng quan
- Tổng số nhân sự
- Tổng số dự án theo trạng thái
- Tỷ lệ hoàn thành các dự án đang triển khai
- Báo cáo chấm công tháng hiện tại (Excel/PDF)

### Phân quyền
- Admin
- HR
- Nhân viên

## 3) HARD RULES (BẮT BUỘC)
1. Account admin chính: nguyensonmbt@gmail.com
2. Email nhân sự chỉ chấp nhận @gmail.com
3. Nhân sự bị khóa (is_active = 0) không được login
4. Điện thoại theo chuẩn VN
5. Ngày sinh phải >= 18 tuổi
6. Lương cơ bản là số nguyên VND
7. Thời gian dự kiến dự án tính từ tổng chi tiết triển khai (không nhập tay)
8. Tỷ lệ hoàn thành dự án:
   progress = SUM(progress_detail * weight_detail)
   weight_detail = so_ngay_detail / tong_so_ngay_detail
9. HR sửa dữ liệu phải qua luồng duyệt Admin trước khi public
10. Admin có quyền tùy biến header/footer/logo/favicon/footer text

## 4) DATA MODEL REQUIREMENTS
Bảng chính cần có:
- users
- departments
- positions
- attendance_logs
- projects
- project_members
- project_details
- approval_requests
- site_settings
- leave_requests (phục vụ mở rộng)

## 5) UI/UX REQUIREMENTS
- Tông màu chính: #4285f4
- Style hiện đại, giống dashboard admin chuyên nghiệp
- Layout nhất quán giữa các page
- Sidebar + top header rõ ràng
- Form dễ đọc, không vỡ layout
- Modal thêm/sửa dữ liệu phải chuẩn responsive
- Login page theo mẫu đã thống nhất

## 6) PAGE-BY-PAGE ACCEPTANCE CRITERIA
### Login
- Giao diện sạch, centered card
- Có login thường + Google login
- Không hiển thị text lỗi font

### Home Dashboard
- Có KPI card
- Có menu tác vụ nhanh
- Dữ liệu số liệu hiển thị đúng

### Nhân sự
- Bảng đầy đủ cột: tên, tuổi, avatar, trạng thái online/offline, thời gian làm việc, lương, phòng ban, chức vụ
- Lọc theo tên / ngày bắt đầu / phòng ban
- Modal thêm nhân sự không vỡ
- Export Excel/PDF theo filter

### Phòng ban
- Tên, số lượng nhân sự, mô tả
- CRUD + khóa theo rule

### Dự án
- Bảng dự án + trạng thái + % hoàn thành
- Có chi tiết triển khai từng dự án
- Có gán nhân sự + vai trò

### Settings (Admin)
- Tùy biến header/footer/logo/favicon/footer text
- Hỗ trợ upload kéo-thả logo/favicon

## 7) SECURITY & QUALITY
- Không hardcode secrets/token
- Validate input server-side
- Escape output HTML
- Session login giữ 1 ngày
- Không phá vỡ route cũ khi refactor UI

## 8) DELIVERY WORKFLOW FOR ANTIGRAVITY
Khi làm task, luôn theo thứ tự:
1. Read current file(s)
2. Edit minimal scope
3. Keep backward compatibility
4. Commit message rõ nghĩa
5. Push branch/main theo policy repo
6. Ghi chú migration nếu DB thay đổi

## 9) STANDARD TASK PROMPT (DÙNG LẠI)
TASK:
<ghi rõ page/module cần làm>

SCOPE FILES:
<danh sách file được phép sửa>

DO NOT TOUCH:
<file cấm sửa>

ACCEPTANCE:
<checklist pass/fail cụ thể>

OUTPUT:
- Updated files
- DB migration (nếu có)
- Commit message gợi ý

## 10) NOTES
- Nếu gặp lỗi GitHub Push Protection: scrub secret trước khi push.
- Nếu cPanel Git báo local modified: discard/reset rồi update from remote.
- Ưu tiên UTF-8 (không BOM) để tránh lỗi font và strict_types.
