# 📝 BlogCMS - Content Management System

**BlogCMS** is a lightweight, performant platform designed to manage blog content efficiently. Built from scratch using core PHP, it provides a secure and intuitive interface for Administrators, Authors, and Visitors.

## 📂 Initial File Structure

This project follows a clean, organized structure to separate logic, configuration, and views:

```text
BlogCMS/
├── assets/              # Static files
│   ├── css/             # Stylesheets (Tailwind output)
│   ├── js/              # JavaScript files
│   └── images/          # Site assets
├── config/              # Configuration files
│   └── db.php           # Database connection (PDO)
├── includes/            # Reusable PHP snippets
│   ├── header.php       # HTML Head & Navigation
│   └── footer.php       # HTML Footer
├── admin/               # Admin Dashboard area
│   ├── dashboard.php
│   └── users.php
├── uploads/             # User uploaded images
├── index.php            # Homepage (Public view)
├── login.php            # Authentication page
├── article.php          # Single article view
└── README.md            # Project documentation