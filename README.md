🔗 Integration with Student Management System

This Library Management System is integrated with the Student Management System through an API to retrieve student data and ensure centralized data management.

The system avoids duplication of student records by directly fetching student information from the Student Management System.

The API is used in the issue_book.php module, where the librarian can select a student when issuing a book.

⚙️ How It Works

1️⃣ 📡 The Library Management System sends a request to the Student Management System API.
2️⃣ 📄 The API returns student data in JSON format.
3️⃣ 👨‍🎓 The student list is displayed in the Issue Book form.
4️⃣ 📚 When a book is issued, it is linked to the selected student record.

📡 API Endpoints Used
👨‍🎓 Get Students
GET /api/get_students.php

📌 Retrieves student records from the Student Management System for use in the Library System.

📚 Get Issued Books (NEW)
GET /api/get-issued-books.php

📌 Retrieves all issued book records with:

Book title
Student ID
Issue date
Return date

📌 Used for:

Reports
Dashboard display
System integration
🔄 System Communication Flow
🎓 Student Management System
        ↓
📡 API (get_students.php)
        ↓
📚 Library Management System
        ↓
📝 issue_book.php
        ↓
👨‍🎓 Book issued to student
📊 Issued Books Data Flow
📚 issued_books table
        ↓
📡 get-issued-books.php API
        ↓
📊 Dashboard / Reports / UI display
🔑 Default Login Credentials

Use the following credentials to access the system after installation:

👤 Username: admin
🔒 Password: admin123

⚠️ These credentials are for demonstration purposes only. It is recommended to change them after installation.
