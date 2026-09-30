# Travela

**English** | [Tiếng Việt](README_VI.md)

Travela is a tour booking website built with Laravel 9 and MySQL. This academic project focuses on tour discovery, booking, experimental payment flows, and tour data administration.


## Main features

### Customer application

- Account registration with email activation.
- Login with a local account or Google.
- Browse, search, and filter tours by region, duration, price, and rating.
- View tour details, images, itineraries, and reviews.
- Book tours for adults and children.
- Office, PayPal, and MoMo payment options in experimental mode.
- View booked tours and tour history.
- Review a tour after completion.
- Update profile information, avatar, and password.
- Send contact requests to administrators.

### Administration

- Dashboard statistics for bookings, revenue, and tours by region.
- Three-step tour creation: information, images, and timeline.
- Edit, hide, or delete tours.
- Manage users and account status.
- Confirm and complete bookings and update their payment status.
- View booking details, generate PDFs, and send information by email.
- Manage and reply to customer messages.
- Update administrator profile information and avatar.

## Technology stack

- PHP `^8.0.2`
- Laravel 9
- MySQL
- Blade, Bootstrap, jQuery, and AJAX
- Laravel Socialite for Google Login
- Dompdf for PDF invoices
- PayPal SDK and MoMo sandbox for experimental payments
- A separate recommendation API at `http://127.0.0.1:5555`

The frontend uses assets stored directly in `public`, so `npm install` is not required to start the project.

## Requirements

- Windows 10/11
- PHP 8.0.2 or newer
- Composer
- MySQL or XAMPP
- The PHP `pdo_mysql` extension

SMTP, Google OAuth, and PayPal sandbox settings are additionally required when using their respective features.

## Quick setup on Windows

### 1. Clone the repository

```bash
git clone https://github.com/dienakdz/travela.git
cd travela
```

### 2. Get the quick-setup package

The password-protected `fast-setup.zip` archive contains:

- `setup.bat`
- `UPDATE_TOUR_DATES.md`

Contact the author for the password, extract both files into the project root, and run:

```bat
setup.bat
```

The script will:

1. Check PHP and Composer.
2. Create `.env` from `.env.example` when it does not exist.
3. Run `composer install`.
4. Generate `APP_KEY` when it is empty.
5. Clear stale Laravel caches.
6. Create the `public/storage` symbolic link.
7. Verify the Laravel environment.

The script does not overwrite an existing `.env` file or `APP_KEY`.

### 3. Prepare the database

The repository does not contain complete migrations for its application tables, and the database dump is not public. Create a `travela` database, import the separately provided backup, and configure `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=travela
DB_USERNAME=root
DB_PASSWORD=
```

The original academic data contains tour dates in the past. After importing the database, follow `UPDATE_TOUR_DATES.md` to synchronize the dates to a new demonstration year. Back up the database before running the update SQL.

### 4. Start the application

```bash
php artisan serve --host=127.0.0.1 --port=8000
```

Open:

- Website: [http://127.0.0.1:8000](http://127.0.0.1:8000)
- Admin: [http://127.0.0.1:8000/admin/login](http://127.0.0.1:8000/admin/login)

The administrator account is stored in the database backup and is not published in the repository.

## Manual setup

When not using `setup.bat`, install the dependencies first:

```bash
composer install
```

On Windows:

```bat
copy .env.example .env
```

On macOS/Linux:

```bash
cp .env.example .env
```

Then run:

```bash
php artisan key:generate
php artisan optimize:clear
php artisan storage:link
```

Finally, configure `.env`, import the database, and start the application with `php artisan serve`.

## Optional configuration

### Email

Configure the `MAIL_*` values in `.env` to enable account activation, contact replies, and booking emails.

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

The home, tour details, search, and tour history pages call a separate service at `http://127.0.0.1:5555`. Its source code is not included in this repository. The core website continues to work when this service is unavailable, but recommendation results may be empty.

## Main database tables

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

## Project structure

```text
travela/
├── app/                 Controllers, models, and application logic
├── config/              Laravel and external-service configuration
├── database/            Default migrations and seeders
├── public/              Public CSS, JavaScript, and images
├── resources/views/     Blade templates for the customer and admin areas
├── routes/              Web route definitions
├── storage/             Laravel-managed logs, cache, and files
├── tests/               Automated tests
└── fast-setup.zip       Password-protected quick-setup package
```

## Testing

```bash
php artisan test
```

The current test suite primarily verifies that the application can start. Changes involving booking, payments, or the database should also be tested through the complete browser flow.

## Contact

- Email: `minhdien.dev@gmail.com`
- GitHub Issues: [github.com/dienakdz/travela/issues](https://github.com/dienakdz/travela/issues)

