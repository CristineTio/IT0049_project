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
> You can also skip `spark serve`: put the project in `C:\xampp\htdocs\IT0049_project`,
> set `app.baseURL=http://localhost/IT0049_project/` in `.env`, and open that address
> while Apache is running.

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

This is a PHP and MySQL application, so it needs a host that runs PHP and MySQL.
**GitHub Pages cannot run it**: Pages only serves static files, so it shows this README
instead of the app.

Free PHP hosts such as [InfinityFree](https://www.infinityfree.com) work. The root
`.htaccess` file passes every request to `public/`, so the whole project can be uploaded
into the host's web folder (usually `htdocs`) without changing any settings.

1. **Create the hosting account and database.** Sign up, create a website, then create a
   MySQL database from the control panel. Note the database host, name, username, and
   password that the control panel shows.
2. **Import the database.** Open phpMyAdmin from the control panel, select your database,
   and import `database/pos_db.sql` from the **Import** tab.
3. **Create `.env`.** Download this repository (**Code → Download ZIP**) and extract it.
   In the project folder, create a file named `.env` with your site address and the
   database details from step 1:

   ```ini
   CI_ENVIRONMENT=production
   app.baseURL=http://your-site.example.com/

   database.default.hostname=sql000.example.com
   database.default.database=your_database_name
   database.default.username=your_database_user
   database.default.password=your_database_password
   database.default.DBDriver=MySQLi
   database.default.port=3306
   ```

4. **Upload the files.** Upload everything inside the project folder, including the
   `.htaccess` and `.env` files, into `htdocs` using the host's File Manager or an FTP
   client such as FileZilla.
5. **Open your site address.** The POS home page should appear. Log in with the demo
   account and change the demo passwords.

If your host lets you set the document root, you can point it to `public/` instead; the
root `.htaccess` is then simply not used. Make sure `writable/` and `public/uploads/`
are writable by the web server.
