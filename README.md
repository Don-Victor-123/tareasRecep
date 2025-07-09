# Hotel Reception Tasks

Simple PHP application for managing reception tasks in a hotel.

## Requirements
- PHP 8.0 or higher with PDO MySQL extension
- MySQL or MariaDB server

## Setup

1. Create the database and tables:
   ```sh
   mysql -u root -p < sql/schema.sql
   ```
2. Generate a hashed password for your user:
   ```sh
   php -r "echo password_hash('your_password', PASSWORD_DEFAULT);"
   ```
Insert a user into the `users` table using the generated hash **(las
contraseñas deben almacenarse cifradas; no funcionarán en texto plano)**:
```sql
INSERT INTO users (username, role, password)
VALUES ('admin', 'admin', '<paste hash>');
-- Opcionalmente inserta más usuarios con rol 'receptionist'
```

Si ya tienes usuarios creados con contraseñas sin cifrar, actualiza su
contraseña de esta manera:
```sql
UPDATE users SET password = '<hash>' WHERE username = 'tu_usuario';
```

## Configuration

Database credentials are read from environment variables. Defaults are shown in parentheses.

- `DB_HOST` (localhost)
- `DB_NAME` (hotel_tasks)
- `DB_USER` (root)
- `DB_PASS` (empty)

## Running

Start the built-in PHP web server from the project root:

```sh
php -S localhost:8000
```

Then open `http://localhost:8000/login.php` in your browser.

Admin users pueden gestionar cuentas visitando `users.php` después de iniciar
sesión.

