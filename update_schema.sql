-- Add password field to students table if it doesn't exist
ALTER TABLE students ADD COLUMN IF NOT EXISTS password VARCHAR(255) NULL;

-- Add verification status field to students table if it doesn't exist
ALTER TABLE students ADD COLUMN IF NOT EXISTS verified TINYINT(1) DEFAULT 0;

-- Add issued_at timestamp to book_loans table if it doesn't exist
ALTER TABLE book_loans ADD COLUMN IF NOT EXISTS issued_at DATETIME NULL;

-- Create or modify student_reset_password table for password reset functionality
CREATE TABLE IF NOT EXISTS student_reset_password (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    reset_code VARCHAR(10) NOT NULL,
    created_at DATETIME NOT NULL,
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE
);

-- Indexes for better performance
CREATE INDEX IF NOT EXISTS idx_student_email ON students(email);
CREATE INDEX IF NOT EXISTS idx_student_dept_id ON students(dept_id);
CREATE INDEX IF NOT EXISTS idx_book_loans_student ON book_loans(student_id);
CREATE INDEX IF NOT EXISTS idx_book_loans_book ON book_loans(book_id);

-- Adding default admin credentials if not exists (for testing only, change in production)
-- Default: admin@example.com / password
INSERT INTO administrators (username, password, name, email, status, created_at)
SELECT 'admin', '$2y$10$H.jC8BaxQBLxTSU0qVp5meCdnItLF36YVFDfj9mvyx5FYqeJ0RvRq', 'System Admin', 'admin@example.com', 1, NOW()
WHERE NOT EXISTS (SELECT 1 FROM administrators WHERE username = 'admin');

-- Update existing student accounts to have default password if password is NULL
-- This sets all existing student accounts to have password 'password123' (hashed)
UPDATE students 
SET password = '$2y$10$mELUrH7H9Q3j0FAbXFpz0.gRHQpOLm9VF0xioVJC/JjpV3WOl.Ypm', 
    verified = 1, 
    status = 1
WHERE password IS NULL OR password = ''; 