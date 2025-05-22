# Library Management System

A comprehensive Library Management System for educational institutions, built with PHP, MySQL, and Bootstrap 5.

## Features

- **Admin Panel**: Manage books, students, loans, and reports
- **Student Portal**: Allow students to browse books, reserve titles, and manage their loans
- **QR Code System**: Generate QR codes for book loans and verification
- **Responsive Design**: Works on desktop and mobile devices

## Requirements

- PHP 7.4 or higher
- MySQL 5.7 or higher
- Apache/Nginx web server
- XAMPP/WAMP/MAMP for local development

## Installation

1. Clone the repository to your web server's document root (e.g., `htdocs` in XAMPP)

   ```
   git clone https://github.com/abdul-kioum-ahmed-sumon/LMS.git
   ```

2. Import the database schema from `database/lms.sql`

3. Configure the database connection:

   - Open `config/config.php`
   - Update the database credentials if needed

4. Access the system:
   - Admin: [http://localhost/lms-master/](http://localhost/lms-master/)
   - Student: [http://localhost/lms-master/student_login.php](http://localhost/lms-master/student_login.php)

## Default Login

- **Admin**

  - Email: admin@example.com
  - Password: admin123

- **Student**
  - Email: student@example.com
  - Password: student123

## Usage

### Admin Features

- Manage Books: Add, edit, delete, and categorize books
- Manage Students: Approve registrations and manage student accounts
- Issue & Return Books: Manage book loans and returns
- Reports: Generate various reports on books, students, and loans

### Student Features

- Browse available books
- Reserve books
- Track loan status
- Manage profile information

## Security

- Passwords are hashed using PHP's password_hash()
- Input validation and sanitization
- Session-based authentication

## License

This project is licensed under the MIT License - see the LICENSE file for details.

## Credits

Developed by Abdul Kioum Ahmed Sumon
