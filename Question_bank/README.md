# Question Bank System

A complete Question Bank management system with PDF upload functionality for BAUST Library.

## 🚀 Features

- ✅ **Add questions** with PDF file uploads (max 10MB)
- ✅ **Edit existing questions** and update PDFs
- ✅ **Delete questions** and associated PDF files
- ✅ **View questions** in admin and student interfaces
- ✅ **PDF download/view** functionality
- ✅ **Responsive design** with Bootstrap 5
- ✅ **DataTables** for better data management
- ✅ **Security features** (SQL injection prevention, XSS protection)

## 📋 Setup Instructions

### 1. **Database Setup**

First, run the database setup script:

```
http://localhost/lms-master/Question_bank/setup.php
```

This will:

- Create the `previous_questions` table if it doesn't exist
- Add the `file_path` column for PDF storage
- Create the `uploads` directory
- Set proper permissions

### 2. **Test the System**

Run the test script to verify everything is working:

```
http://localhost/lms-master/Question_bank/test.php
```

## 📁 File Structure

```
Question_bank/
├── index.php              # Admin view - list all questions
├── add.php                # Add new question form
├── edit.php               # Edit question form
├── delete.php             # Delete question functionality
├── student_view.php       # Student view (read-only)
├── setup.php              # Database setup script
├── test.php               # System test script
├── uploads/               # PDF file storage
└── README.md              # This file
```

## 🎯 Usage

### **For Administrators:**

1. **Add Questions**: Go to `add.php`

   - Fill in course, subject, year, semester, and question
   - Optionally upload a PDF file (max 10MB)
   - Click "Add Question"

2. **View Questions**: Go to `index.php`

   - See all questions in a table format
   - Click "View PDF" to download PDF files
   - Use "Edit" or "Delete" buttons for management

3. **Edit Questions**: Click "Edit" on any question
   - Modify question details
   - Upload a new PDF to replace the existing one
   - Click "Update Question"

### **For Students:**

1. **View Questions**: Go to `student_view.php`
   - Browse all questions
   - Download PDF files by clicking "View PDF"
   - No editing capabilities (read-only)

## 🗄️ Database Schema

The `previous_questions` table has the following structure:

```sql
CREATE TABLE `previous_questions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `course` varchar(100) DEFAULT NULL,
  `subject` varchar(100) DEFAULT NULL,
  `year` int(11) DEFAULT NULL,
  `semester` int(11) DEFAULT NULL,
  `question` text DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

## 🔒 Security Features

- ✅ SQL injection prevention with `mysqli_real_escape_string`
- ✅ XSS prevention with `htmlspecialchars`
- ✅ File type validation (PDF only)
- ✅ File size limits (10MB max)
- ✅ Unique filename generation
- ✅ Proper file deletion on question removal

## 🔧 Troubleshooting

### **Common Issues:**

1. **"Database connection failed"**

   - Check if XAMPP/WAMP is running
   - Verify database credentials
   - Ensure 'lms' database exists

2. **"File upload fails"**

   - Check PHP upload limits in php.ini
   - Ensure uploads directory is writable
   - Verify file is PDF format

3. **"PDF links not working"**
   - Check if file exists in uploads directory
   - Verify file path in database
   - Ensure web server can access uploads folder

### **PHP Configuration:**

Make sure your `php.ini` has these settings:

```ini
upload_max_filesize = 10M
post_max_size = 10M
max_execution_time = 300
```

## 🔗 Quick Links

- **Admin Interface**: `http://localhost/lms-master/Question_bank/index.php`
- **Add Question**: `http://localhost/lms-master/Question_bank/add.php`
- **Student View**: `http://localhost/lms-master/Question_bank/student_view.php`
- **Setup**: `http://localhost/lms-master/Question_bank/setup.php`
- **Test**: `http://localhost/lms-master/Question_bank/test.php`

## 📞 Support

If you encounter any issues:

1. **Run the test script**: `test.php`
2. **Check the setup script**: `setup.php`
3. **Verify file permissions** on the uploads directory
4. **Check web server error logs**

## 🎉 Getting Started

1. **Start XAMPP/WAMP** (Apache & MySQL)
2. **Run setup**: Visit `setup.php`
3. **Test system**: Visit `test.php`
4. **Add questions**: Visit `add.php`
5. **View questions**: Visit `index.php`

---

**Made with ❤️ for BAUST Library**
