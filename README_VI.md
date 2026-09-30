# Travela

**Tiếng Việt** | [English](README.md)

Travela là website đặt tour du lịch được xây dựng bằng Laravel 9 và MySQL. Đây là project đồ án, tập trung vào quy trình tìm kiếm tour, đặt tour, thanh toán thử nghiệm và quản trị dữ liệu tour.


## Chức năng chính

### Phía khách hàng

- Đăng ký tài khoản và kích hoạt qua email.
- Đăng nhập bằng tài khoản thường hoặc Google.
- Xem, tìm kiếm và lọc tour theo khu vực, thời gian, giá và đánh giá.
- Xem chi tiết tour, hình ảnh, lịch trình và đánh giá.
- Đặt tour cho người lớn và trẻ em.
- Hỗ trợ hình thức thanh toán tại văn phòng, PayPal và MoMo ở chế độ thử nghiệm.
- Theo dõi tour đã đặt và lịch sử tour.
- Đánh giá tour sau khi hoàn thành.
- Cập nhật hồ sơ, ảnh đại diện và mật khẩu.
- Gửi nội dung liên hệ cho quản trị viên.

### Phía quản trị

- Dashboard thống kê booking, doanh thu và tour theo khu vực.
- Thêm tour theo ba bước: thông tin, hình ảnh và timeline.
- Sửa, ẩn hoặc xóa tour.
- Quản lý người dùng và trạng thái tài khoản.
- Xác nhận, hoàn thành và cập nhật trạng thái thanh toán booking.
- Xem chi tiết booking, tạo PDF và gửi thông tin qua email.
- Quản lý và phản hồi liên hệ của khách hàng.
- Cập nhật thông tin và ảnh đại diện quản trị viên.

## Công nghệ sử dụng

- PHP `^8.0.2`
- Laravel 9
- MySQL
- Blade, Bootstrap, jQuery và AJAX
- Laravel Socialite cho Google Login
- Dompdf cho hóa đơn PDF
- PayPal SDK và MoMo sandbox cho thanh toán thử nghiệm
- Một recommendation API riêng tại `http://127.0.0.1:5555`

Frontend hiện sử dụng các asset đã được lưu trực tiếp trong thư mục `public`, vì vậy không cần chạy `npm install` để khởi động project.

## Yêu cầu môi trường

- Windows 10/11
- PHP 8.0.2 trở lên
- Composer
- MySQL hoặc XAMPP
- PHP extension `pdo_mysql`

Các tính năng tương ứng sẽ cần thêm cấu hình SMTP, Google OAuth và PayPal sandbox trong `.env`.

## Cài đặt nhanh trên Windows

### 1. Clone repository

```bash
git clone https://github.com/dienakdz/travela.git
cd travela
```

### 2. Lấy bộ cài đặt nhanh

File `fast-setup.zip` được đặt mật khẩu và chứa:

- `setup.bat`
- `UPDATE_TOUR_DATES.md`

Liên hệ tác giả để nhận mật khẩu, sau đó giải nén hai file vào thư mục gốc của project và chạy:

```bat
setup.bat
```

Script sẽ tự động:

1. Kiểm tra PHP và Composer.
2. Tạo `.env` từ `.env.example` nếu chưa có.
3. Chạy `composer install`.
4. Tạo `APP_KEY` nếu đang trống.
5. Xóa Laravel cache cũ.
6. Tạo symbolic link cho `public/storage`.
7. Kiểm tra môi trường Laravel.

Script không ghi đè `.env` hoặc `APP_KEY` đã tồn tại.

### 3. Chuẩn bị database

Project không có migration đầy đủ cho các bảng nghiệp vụ và không public database dump trong repository. Cần tạo database `travela`, import database backup được cung cấp riêng, sau đó cấu hình trong `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=travela
DB_USERNAME=root
DB_PASSWORD=
```

Dữ liệu đồ án ban đầu sử dụng ngày tour trong quá khứ. Sau khi import database, có thể sử dụng hướng dẫn trong `UPDATE_TOUR_DATES.md` để đồng bộ ngày tour sang năm demo mới. Hãy backup database trước khi chạy câu SQL cập nhật.

### 4. Khởi động ứng dụng

```bash
php artisan serve --host=127.0.0.1 --port=8000
```

Truy cập:

- Website: [http://127.0.0.1:8000](http://127.0.0.1:8000)
- Admin: [http://127.0.0.1:8000/admin/login](http://127.0.0.1:8000/admin/login)

Tài khoản admin được lưu trong database backup và không được public trong repository.

## Cài đặt thủ công

Nếu không sử dụng `setup.bat`, chạy lần lượt:

```bash
composer install
```

Windows:

```bat
copy .env.example .env
```

macOS/Linux:

```bash
cp .env.example .env
```

Sau đó chạy:

```bash
php artisan key:generate
php artisan optimize:clear
php artisan storage:link
```

Tiếp theo cấu hình `.env`, import database và khởi động ứng dụng bằng `php artisan serve`.

## Các cấu hình tùy chọn

### Email

Cấu hình `MAIL_*` trong `.env` để sử dụng kích hoạt tài khoản, phản hồi liên hệ và gửi thông tin booking.

### Google Login

```env
GOOGLE_CLIENT_ID=
GOOGLE_CLIENT_SECRET=
GOOGLE_REDIRECT=http://127.0.0.1:8000/auth/google/callback
```

### PayPal sandbox

```env
PAYPAL_MODE=sandbox
PAYPAL_SANDBOX_CLIENT_ID=
PAYPAL_SANDBOX_CLIENT_SECRET=
```

### Recommendation API

Các trang home, chi tiết tour, tìm kiếm và lịch sử tour có gọi một service riêng tại `http://127.0.0.1:5555`. Source code của service này không nằm trong repository. Khi service không chạy, các chức năng chính của website vẫn hoạt động nhưng danh sách gợi ý có thể trống.

## Các bảng dữ liệu chính

- `tbl_admin`
- `tbl_users`
- `tbl_tours`
- `tbl_images`
- `tbl_timeline`
- `tbl_booking`
- `tbl_checkout`
- `tbl_reviews`
- `tbl_contact`
- `tbl_history`

## Cấu trúc project

```text
travela/
├── app/                 Controllers, Models và logic ứng dụng
├── config/              Cấu hình Laravel và dịch vụ ngoài
├── database/            Migration mặc định và seeder
├── public/              CSS, JavaScript và hình ảnh public
├── resources/views/     Blade templates cho client và admin
├── routes/              Khai báo web routes
├── storage/             Log, cache và file do Laravel quản lý
├── tests/               Automated tests
└── fast-setup.zip       Bộ cài đặt nhanh có mật khẩu
```

## Kiểm tra project

```bash
php artisan test
```

Test suite hiện chủ yếu kiểm tra ứng dụng có thể khởi động. Với các thay đổi liên quan đến booking, thanh toán hoặc database, nên kiểm tra thêm bằng flow thực tế trên trình duyệt.

## Liên hệ

- Email: `minhdien.dev@gmail.com`
- GitHub Issues: [github.com/dienakdz/travela/issues](https://github.com/dienakdz/travela/issues)

