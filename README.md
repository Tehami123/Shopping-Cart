# Arts Online Shopping Cart

This project is a portable PHP and MySQL/MariaDB application.

## Setup Instructions

1. **Install XAMPP** (or any AMP stack of your choice).
2. **Start Apache and MySQL** via the XAMPP Control Panel.
3. **Copy the project** into the `htdocs` directory (e.g., `C:\xampp\htdocs\Shopping Cart`).
4. **Import the Database**:
   - Open phpMyAdmin (usually `http://localhost/phpmyadmin`).
   - The included `database.sql` script will automatically create the `arts_shop` database and all required tables.
   - Import `database.sql` directly.
5. **Open the project** in your browser via `http://localhost/Shopping%20Cart/` (adjust the URL if your folder name is different).

## Admin and Employee Credentials

**Important**: Because the original password hashes from the live database were not provided, you must update the password hashes in `database.sql` before importing, or create new users manually.

In `database.sql`, look for the `users` table `INSERT` statements at the end of the file. Replace:
- `REPLACE_WITH_ADMIN_BCRYPT_HASH` with your admin's actual bcrypt hash.
- `REPLACE_WITH_EMPLOYEE_BCRYPT_HASH` with your employee's actual bcrypt hash.

Seed accounts created (once hashes are updated):
- **Admin Email**: admin@artsshop.local
- **Employee Email**: employee@artsshop.local
