# POS System — IT0049 (CodeIgniter 4)

A Point of Sale (POS) web application built with CodeIgniter 4 and MySQL for
IT0049 Web System Technologies.

**Hosted link:** _add your hosted URL here_

## Features

| Activity | What was added |
| --- | --- |
| TFA1 | Landing, About, Customer Accounts, and User Accounts pages. |
| TFA2 | Static arrays replaced with a MySQL database. `CustomerModel` and `UserModel` retrieve records with Query Builder (`findAll()`). |
| TFA3 | New/Edit forms for customers and users with validation, plus avatar upload for users (JPG/PNG, max 2MB, resized to a 300×300 thumbnail). |

### Pages

| URL | Description |
| --- | --- |
| `/` | Landing page |
| `/about` | About page |
| `/customers` | Customer Accounts listing |
| `/customers/new` | New Customer form |
| `/customers/{id}/edit` | Edit Customer form |
| `/users` | User Accounts listing with avatars |
| `/users/new` | New User form |
| `/users/{id}/edit` | Edit User form with profile picture upload |

### Validation rules

- **Customer:** full name required; email required and must be valid; phone optional (numbers, spaces, `+ ( ) -`).
- **User:** username required, unique, and limited to letters, numbers, underscores, and dashes; full name required.
- **Avatar:** optional; must be a JPG or PNG no larger than 2MB.

Invalid forms are redisplayed with an error under each field and the values the user entered.
All forms are protected with CodeIgniter's CSRF filter.

### Avatar upload

On the Edit User form, the uploaded image is validated, cropped and resized to a 300×300
thumbnail with CodeIgniter's Image service, and saved to `public/uploads/avatars/` under a
random filename. Only that filename is stored in the `users.avatar` column. Users without
an avatar show `public/images/avatar-placeholder.svg`.

## Requirements

- PHP 8.2 or newer with the `intl`, `mbstring`, `mysqli`, and `gd` extensions
- MySQL 5.7+ or MariaDB 10.3+ (XAMPP works)
- PHP `upload_max_filesize` of at least `2M`

## Setup

1. **Clone the repository**

   ```bash
   git clone https://github.com/CristineTio/IT0049_project.git
   cd IT0049_project
   ```

2. **Create the database and import the export**

   Create a database named `pos_db`, then import `database/pos_db.sql`.
   With phpMyAdmin: create `pos_db`, open it, and use **Import** on the SQL file.
   From the command line:

   ```bash
   mysql -u root -p -e "CREATE DATABASE pos_db"
   mysql -u root -p pos_db < database/pos_db.sql
   ```

3. **Configure the environment**

   Copy `.env.example` to `.env` and set your database credentials:

   ```ini
   database.default.hostname=localhost
   database.default.database=pos_db
   database.default.username=root
   database.default.password=
   ```

4. **Run the app**

   ```bash
   php spark serve
   ```

   Then open <http://localhost:8080>.

## Database

`database/pos_db.sql` contains both tables and 5 sample records in each.

```sql
CREATE TABLE customers (
  id INT AUTO_INCREMENT PRIMARY KEY,
  full_name VARCHAR(100) NOT NULL,
  email VARCHAR(100) NOT NULL,
  phone VARCHAR(20),
  created_at DATETIME NOT NULL
);

CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) NOT NULL UNIQUE,
  full_name VARCHAR(100) NOT NULL,
  avatar VARCHAR(255) NULL,          -- added in TFA3
  created_at DATETIME NOT NULL
);
```

## Project structure

```
app/
  Config/Routes.php          Route definitions
  Controllers/
    Pages.php                Landing and About pages
    Customers.php            List, create, and edit customers
    Users.php                List, create, and edit users; avatar upload
  Models/
    CustomerModel.php        customers table
    UserModel.php            users table
  Views/
    templates/               Shared header and footer
    pages/                   Landing and About
    customers/               index (listing) and form (new/edit)
    users/                   index (listing) and form (new/edit)
database/pos_db.sql          Database export
public/
  css/style.css              Stylesheet
  images/                    Placeholder avatar
  uploads/avatars/           Uploaded avatar thumbnails
```

## Deploying

1. Upload the project and import `database/pos_db.sql` into the host's MySQL database.
2. Point the web root to the `public/` folder.
3. Create `.env` on the server with the host's database credentials, plus:

   ```ini
   CI_ENVIRONMENT=production
   app.baseURL=https://your-domain.example/
   ```

4. Make sure `writable/` and `public/uploads/avatars/` are writable by the web server.
