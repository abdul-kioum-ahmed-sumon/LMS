# Library Management System

A comprehensive Library Management System designed for educational institutions, built with PHP and MySQL, and styled with Bootstrap 5. This system aims to automate library operations and provide a seamless experience for both administrators and students.

## Table of Contents

- [Features](#features)
- [Technology Stack](#technology-stack)
- [Requirements](#requirements)
- [Installation](#installation)
- [Default Login Credentials](#default-login-credentials)
- [Usage](#usage)
- [Security](#security)
- [Screenshots](#screenshots)
- [Contributing](#contributing)
- [License](#license)
- [Credits](#credits)

## Features

### Admin Panel

- **Dashboard:** Overview of library statistics (total books, issued books, active students, etc.).
- **Book Management:**
  - Add new books with details (ISBN, title, author, publisher, category, quantity, cover image).
  - Edit and update existing book information.
  - Delete books from the catalog.
  - View a comprehensive list of all books with search and filter capabilities.
  - Manage book categories.
- **Student Management:**
  - View and manage registered student accounts.
  - Approve or reject new student registrations.
  - View student profiles and borrowing history.
- **Book Issuing & Returns:**
  - Issue books to students.
  - Record book returns and update availability.
  - Track overdue books and manage fines (if applicable).
- **Loan Management:** View all active and past loan records.
- **QR Code System:**
  - Potential for generating QR codes for books for quick identification.
  - Scan QR codes for faster check-out/check-in processes.
- **Reporting & Analytics:**
  - Generate reports on book inventory, popular books, student borrowing patterns, and loan history.
  - Visual charts for data representation.
- **Notice Board Management:** Create and display important notices for users.
- **Magazine & Periodicals Management:** (If applicable) Track magazine subscriptions and issues.
- **User Management (Staff):** Manage accounts for other library staff/admins.
- **Profile Management:** Admins can update their own profile and change passwords.

### Student Portal

- **User Registration & Login:** Secure registration and login for students.
- **Dashboard:** Personalized dashboard with current loans, reservations, and notifications.
- **Book Browsing & Searching:**
  - Browse the entire library catalog.
  - Search for books by title, author, ISBN, or category.
  - View book details, availability, and cover images.
- **Book Reservation:** (If implemented) Reserve books that are currently unavailable.
- **Loan History:** View personal borrowing history.
- **Profile Management:**
  - View and update personal profile information (email, phone, address).
  - Change account password.
- **Notifications:** Receive notifications for due dates, overdue books, or available reservations.

### General Features

- **Responsive Design:** User interface adapts to various screen sizes (desktops, tablets, mobiles).
- **Secure Authentication:** Password hashing and session management.
- **Intuitive User Interface:** Easy-to-navigate design for both admins and students.

## Technology Stack

- **Backend:** PHP
- **Database:** MySQL
- **Frontend:** HTML, CSS, JavaScript, Bootstrap 5
- **Web Server:** Apache (typically used with XAMPP/WAMP) or Nginx

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

## Default Login Credentials

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

## Screenshots

_(Consider adding screenshots of your application here. You can upload them to your GitHub repository in an `assets/screenshots` folder and link them like this:)_
`![Admin Dashboard](assets/screenshots/admin_dashboard.png)`

## Contributing

Contributions are welcome! If you'd like to contribute to this project, please follow these steps:

1. Fork the repository.
2. Create a new branch (`git checkout -b feature/your-feature-name`).
3. Make your changes and commit them (`git commit -m 'Add some feature'`).
4. Push to the branch (`git push origin feature/your-feature-name`).
5. Open a Pull Request.

Please make sure your code adheres to the existing coding style and includes appropriate tests if applicable.

## License

This project is licensed under the MIT License - see the LICENSE file for details.

## Credits

Developed by Abdul Kioum Ahmed Sumon
