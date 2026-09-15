# ACONS — Website Kiến trúc & Xây dựng

Bộ khung full-stack cho website ACONS, gồm website công khai tối ưu nội dung/SEO và trang quản trị AdminLTE 4.

## Công nghệ

- Laravel 12
- PHP 8.2+ (tương thích PHP 8.2.12 của XAMPP)
- MySQL/MariaDB của XAMPP
- Blade, Bootstrap 5.3, JavaScript và jQuery
- AdminLTE 4 từ package npm chính thức `admin-lte`, biên dịch bằng Vite
- Vite 8

## Chức năng đã có

- Homepage: Hero video/fallback, giới thiệu, thống kê, dịch vụ, lọc dự án, lý do lựa chọn, testimonials, partners, bài viết, CTA.
- Dự án: danh sách, tìm kiếm, lọc danh mục, chi tiết, gallery modal, bản vẽ và dự án liên quan.
- Dịch vụ: nội dung và quy trình làm việc.
- Liên hệ: validation, honeypot chống spam, rate limit và flash message.
- Admin: custom session authentication, phân quyền admin, dashboard, CRUD dự án/danh mục/dịch vụ, xử lý liên hệ và cấu hình website.
- Upload: ảnh đại diện, gallery, floor plan trên `public` disk.
- Bảo mật: CSRF, escaped Blade output, Form Request, MIME/size validation, route rate limiting và session regeneration.

## Lưu ý cho XAMPP

Laravel 12 hỗ trợ PHP 8.2, vì vậy dự án chạy trực tiếp với PHP 8.2.12 của XAMPP trên máy này.

Phương án dễ nhất:

1. Bật **MySQL** trong XAMPP Control Panel.
2. Mở PowerShell tại thư mục dự án.
3. Nếu lệnh `php` chưa trỏ đến XAMPP, dùng đường dẫn `C:\\xampp\\php\\php.exe` thay cho `php` trong các lệnh Artisan.
4. Chạy Laravel bằng `php artisan serve`; không bắt buộc bật Apache.

## Tích hợp AdminLTE 4

AdminLTE được cài trực tiếp từ package npm chính thức, không phụ thuộc một Laravel wrapper:

```powershell
npm install
npm run build
```

- `resources/css/adminlte.css` import AdminLTE, Bootstrap Icons và OverlayScrollbars.
- `resources/js/adminlte.js` import Bootstrap, AdminLTE và khởi tạo sidebar.
- `resources/views/layouts/admin.blade.php` chứa cấu trúc navbar, sidebar, content và footer của AdminLTE 4.
- `vite.config.js` biên dịch riêng assets cho website công khai và trang quản trị.

Không cần tải/copy thủ công thư mục `dist` từ GitHub; `package.json` và `package-lock.json` đã khóa dependency cần thiết.

## Cài đặt local

### 1. Tạo database

Mở `http://localhost/phpmyadmin`, tạo database:

```text
acons
```

Chọn collation `utf8mb4_unicode_ci`.

### 2. Cài dependency

```powershell
composer install
npm install
```

### 3. Tạo và chỉnh `.env`

```powershell
Copy-Item .env.example .env
php artisan key:generate
```

Cấu hình MySQL và tài khoản admin:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=acons
DB_USERNAME=root
DB_PASSWORD=

ACONS_ADMIN_EMAIL=admin@acons.local
ACONS_ADMIN_PASSWORD=Admin@123456
```

Tài khoản local mặc định là `admin@acons.local` / `Admin@123456`. Hãy thay mật khẩu này trước khi triển khai thật và không commit `.env` lên Git.

### 4. Tạo bảng và dữ liệu mẫu

```powershell
php artisan migrate --seed
php artisan storage:link
```

Seeder tạo tài khoản admin từ `ACONS_ADMIN_EMAIL` và `ACONS_ADMIN_PASSWORD` trong `.env`.

### 5. Chạy ứng dụng

Terminal thứ nhất:

```powershell
php artisan serve
```

Terminal thứ hai:

```powershell
npm run dev
```

Truy cập:

- Website: `http://localhost:8000`
- Admin: `http://localhost:8000/admin/login`
- phpMyAdmin: `http://localhost/phpmyadmin`

### 6. Chạy bằng Apache của XAMPP (tùy chọn)

Nếu muốn truy cập bằng `http://acons.local`, thêm VirtualHost có `DocumentRoot` trỏ đúng vào thư mục `public`:

```apache
<VirtualHost *:80>
    ServerName acons.local
    DocumentRoot "C:/xampp/htdocs/acons/public"

    <Directory "C:/xampp/htdocs/acons/public">
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

Thêm `127.0.0.1 acons.local` vào file hosts của Windows, bảo đảm module `rewrite` được bật, rồi khởi động lại Apache.

## Kiểm tra

```powershell
php artisan test
npm run build
php artisan route:list --except-vendor
```

## Các file nên đọc trước

- `docs/ARCHITECTURE.md`: sơ đồ database, luồng hệ thống và toàn bộ routes.
- `routes/web.php`, `routes/admin.php`: routing.
- `database/migrations`: schema database.
- `app/Models`: model và relationships.
- `app/Services/ProjectService.php`: nghiệp vụ tạo/cập nhật dự án và upload.
- `resources/views/layouts/app.blade.php`: layout website.
- `resources/views/layouts/admin.blade.php`: layout AdminLTE.
- `resources/css/adminlte.css`, `resources/js/adminlte.js`: assets AdminLTE.

## Chuẩn bị production

- Đặt `APP_ENV=production`, `APP_DEBUG=false` và URL HTTPS chính xác.
- Không dùng tài khoản `root` cho database production.
- Web root phải trỏ vào thư mục `public`, không trỏ vào root dự án.
- Cấu hình queue worker, backup database, email, HTTPS, cache và object storage/CDN.
- Tối ưu ảnh thành WebP/AVIF và tạo nhiều kích thước trước khi website đi vào vận hành thực tế.
