# Library Management System

A comprehensive system for managing a library's resources and operations.

## New Features - Student Book Booking System

We've upgraded the Library Management System with the following new features:

### Student Authentication

- Secure student login system using unique student IDs
- Student registration with ID verification
- Session/token-based authentication with secure password storage

### Book Booking System

- Students can browse available books and reserve them online
- Real-time book availability tracking
- QR code generation for book collection
- Staff verification system for book issuance

### Installation Instructions

1. Import the database schema from `lms.sql` to set up the initial database
2. Run the update script `update_schema.sql` to add the new tables and fields:
   ```
   mysql -u username -p lms < update_schema.sql
   ```
3. Make sure the web server has write permissions to the necessary directories

### Staff Instructions

1. Students will register and log in using their student IDs
2. When a student books a book, they will receive a booking ID and QR code
3. At the library counter, staff can scan the QR code or enter the booking ID manually using the verification page
4. The system will show the book and student details for verification
5. After verification, staff can issue the book with a single click

### Student Instructions

1. Register or log in using your student ID and password
2. Browse available books in the catalog
3. Book a book by clicking the "Book Now" button
4. Note your booking ID or show the QR code at the library counter to collect your book

## Original Features

- Book Management
- Student Management
- Borrowing and Returning
- Reporting and Statistics
- And more...

## Credits

Developer: Original Developer + Upgrade Team

## Technologies:

    # Frontend: HTML, CSS, Bootstrap
    # Backend: PHP
    # Database: MySQL

## Modules

    # Books management
    # Students management
    # Loans management
    # Membership management
        # Plans
        # Create Membership
    # My profile
    # Change password

## To Login

    # Login Credentials to access the dashboard
        Email: abdulkioumahmed@gmail.com
        Password: 1252
