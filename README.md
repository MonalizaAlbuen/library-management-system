🔗 Integration with Student Management System

This Library Management System is integrated with the Student Management System through an API to retrieve student data.

The API is used in the issue_book.php module, where the system fetches the list of students from the Student Management System. This allows the librarian to select a student when issuing a book.

Instead of storing duplicate student records in the library database, the system retrieves the student list directly from the Student Management System.

##⚙️ How It Works

1️⃣ 📡 The Library Management System sends a request to the Student Management System API.

2️⃣ 📄 The API endpoint returns student data in JSON format.

3️⃣ 👨‍🎓 The student list is displayed in the Issue Book form.

4️⃣ 📚 When a book is issued, it is linked to the selected student.

##📡 API Endpoint Used
GET /api/get-students.php

📌 This endpoint retrieves student records from the Student Management System and displays them in the Issue Book module (issue_book.php).

##🔄 System Communication Flow
🎓 Student Management System
        ↓
📡 API (get-students.php)
        ↓
📚 Library Management System
        ↓
📝 issue_book.php
        ↓
👨‍🎓 Book issued to student


##🔑 Default Login Credentials

Use the following credentials to access the system after installation:

👤 Username: admin

🔒 Password: admin123

⚠️ These credentials are provided for demonstration purposes. It is recommended to change the password after installation.
