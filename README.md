# POS System — IT0049 Midterm Project (CodeIgniter 4)

A complete Point-of-Sale (POS) web application built with CodeIgniter 4 and MySQL for
IT0049 Web System Technologies. Staff log in to record sales, manage products and
inventory, and manage customer and staff accounts.

**Hosted link:** _add your hosted URL here_

### Demo login

All seeded staff accounts use the password `password123` (set in
`app/Database/Seeds/UserSeeder.php`). Log in as **admin** to get started.
Change this password after deploying.

## Features

| Area | What it does |
| --- | --- |
| **Authentication** | Staff log in with a username and password. Every management page is protected by an `auth` filter; guests are redirected to the login page. |
| **Record Sale** | Pick a product, an optional customer, and a quantity. The sale is saved and the product's stock decreases in one transaction. Sales larger than the available stock are rejected with a clear message. |
| **Sales History** | Lists every sale with its product, customer (or walk-in), staff member, quantity, total price, and date, 20 per page. |
| **Products** | List, add, edit, and archive products. Each product can have a JPG/PNG image, resized to a 400×400 display copy. Low and out-of-stock items are highlighted. |
| **Customers** | List, add, edit, and delete customers. |
| **Staff** | List, add, edit, and delete staff accounts with hashed passwords and a JPG/PNG avatar resized to a 300×300 thumbnail. |

## Requirements

- PHP 8.2 or newer with the `intl`, `mbstring`, `mysqli`, and `gd` extensions
- MySQL 5.7+ or MariaDB 10.3+ (XAMPP works)

## Setup

1. **Clone the repository**

   ```bash
   git clone https://github.com/CristineTio/IT0049_project.git
   cd IT0049_project
   ```

2. **Configure the environment**

   Copy `.env.example` to `.env` and set your database credentials:

   ```ini
   database.default.hostname=localhost
   database.default.database=pos_db
   database.default.username=root
   database.default.password=
   ```

3. **Create an empty database named `pos_db`**, then load the tables and sample data
   using **one** of these options:

   - **Migrations and seeders** (recommended):

     ```bash
     php spark migrate --all
     php spark db:seed DatabaseSeeder
     ```

   - **SQL import:** import `database/pos_db.sql` with phpMyAdmin (**Import** tab) or:

     ```bash
     mysql -u root -p pos_db < database/pos_db.sql
     ```

4. **Run the app**

   ```bash
   php spark serve
   ```

   Then open <http://localhost:8080> and log in with the demo account above.

> **XAMPP on Windows:** enable `extension=intl` and `extension=gd` in
> `C:\xampp\php\php.ini`. If `php` is not recognized, run `C:\xampp\php\php.exe spark serve`.

## Database design

```
products ─┐
customers ─┼──< sales      (customer_id is optional for walk-in sales)
users ─────┘               (sold_by = the staff member who recorded the sale)
```

The tables follow the project schema. The migrations are in `app/Database/Migrations/`,
the sample data in `app/Database/Seeds/`, and a full export in `database/pos_db.sql`.

Design decisions:

- **Archiving instead of hard deletes.** `products`, `customers`, and `users` have an extra
  nullable `deleted_at` column. Sales reference these tables through foreign keys, so a
  deleted product, customer, or staff member is hidden from the app (CodeIgniter soft
  deletes) instead of being removed. Past sales keep showing their names, and the
  foreign keys stay valid.
- **Safe stock updates.** `SaleModel::record()` decreases the stock with
  `UPDATE … WHERE stock_quantity >= quantity` and inserts the sale inside a single
  transaction. If two sales race for the last units, the second one is rejected instead
  of making the stock negative.
- **Prices come from the database.** The sale total is calculated on the server from the
  product's current price, never from the submitted form.

## Validation and security

- **Forms** are validated on the server. Invalid forms are redisplayed with an error under
  each field and the values that were entered.
- **Passwords** are hashed with `password_hash()` (bcrypt) in `UserModel` before saving.
  Leaving the password blank when editing keeps the current one.
- **Login** regenerates the session ID and is limited to 10 attempts per minute per IP.
- **Uploads** must be JPG or PNG images of at most 2MB. They are cropped, resized, and
  re-encoded with CodeIgniter's Image service, saved under a random filename in
  `public/uploads/`, and only the filename is stored in the database.
- **CSRF protection** is enabled for every form, and all output is escaped with `esc()`.
- **Delete** actions use POST forms with a confirmation prompt. Staff cannot delete their
  own account while logged in.

## Pages

| URL | Description | Login required |
| --- | --- | --- |
| `/` | Home page (shortcuts for logged-in staff) | No |
| `/about` | About page | No |
| `/login` | Staff login | No |
| `/sales/new` | Record Sale | Yes |
| `/sales` | Sales History | Yes |
| `/products` | Products (`/new`, `/{id}/edit`, `/{id}/delete`) | Yes |
| `/customers` | Customers (`/new`, `/{id}/edit`, `/{id}/delete`) | Yes |
| `/users` | Staff (`/new`, `/{id}/edit`, `/{id}/delete`) | Yes |

## Project structure

```
app/
  Config/Routes.php           Routes, grouped by the auth and guest filters
  Controllers/
    Auth.php                  Login and logout
    Pages.php                 Home and About pages
    Products.php              Product CRUD with image upload
    Customers.php             Customer CRUD
    Users.php                 Staff CRUD with passwords and avatar upload
    Sales.php                 Record Sale and Sales History
  Database/
    Migrations/               Table definitions
    Seeds/                    Sample data
  Exceptions/SaleException.php  Sale errors shown to staff
  Filters/
    AuthFilter.php            Requires a logged-in staff member
    GuestFilter.php           Keeps logged-in staff off the login page
  Helpers/
    auth_helper.php           current_user()
    pos_helper.php            peso(), avatar_url(), product_image_url()
  Libraries/ImageUploader.php Shared image validation, resizing, and storage
  Models/                     ProductModel, CustomerModel, UserModel, SaleModel
  Views/                      Pages grouped by feature, plus shared templates
database/pos_db.sql           Database export
public/
  css/style.css               Stylesheet
  js/app.js                   Delete confirmations, upload size check, sale total
  images/                     Placeholder images
  uploads/                    Uploaded product images and avatars
```

## Deploying

1. Upload the project and load the database with the SQL import (or migrations and seeders).
2. Point the web root to the `public/` folder.
3. Create `.env` on the server with the host's database credentials, plus:

   ```ini
   CI_ENVIRONMENT=production
   app.baseURL=https://your-domain.example/
   ```

4. Make sure `writable/` and `public/uploads/` are writable by the web server.
5. Log in and change the demo passwords.
