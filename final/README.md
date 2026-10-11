# 🎓 StudentHub Portal - Web Development Frameworks (ITUE203)

**Semester:** 3rd Semester (Odd 2026-27)  
**Institution:** Faculty of Technology and Engineering (FTE), Charotar University of Science and Technology (CHARUSAT)  
**Project Title:** StudentHub Portal &bull; Campus Academic & Event Management System  
**Coverage:** Practical 1 to Practical 8 (Fully Implemented & Verified)

---

## 📌 Project Overview & Scope (Practical 1)

**StudentHub Portal** is a unified, accessible, and responsive campus management web application designed for students and college administration. It eliminates campus communication gaps by providing dynamic event discovery, online registrations, secure profile dashboards, interactive FAQs, and server-side form processing backed by safe file storage (CSV/JSON) and a normalized relational database (MySQL PDO).

### 👥 User Roles
1. **Guest / Public Visitor:** Can browse campus information, view upcoming college events, read FAQs, check about/contact details, and register as a new student.
2. **Student (`25DCS200`):** Can login to a personalized dashboard, review registered events, browse dynamic workshops, submit feedback, and update profile data.
3. **Administrator (`admin@studenthub.com`):** Can access the administrative monitoring panel, inspect student metrics, view stored CSV/JSON records, and check live database status.

### 🧩 Key Modules
- **Authentication & Authorization:** Student & Admin login with session handling and credentials autofill.
- **Dynamic Event Discovery (Practical 6):** Real-time search, category filtering, multi-field sorting, and pagination powered by Fetch API.
- **Client & Server-Side Form Validation (Practicals 5 & 7):** Real-time regex input verification, visual password strength meter, sanitization, and CSRF token protection.
- **File Storage Engine (Practical 7):** Atomic file writing (`LOCK_EX`) into CSV and JSON files with an interactive web records inspector (`view_records.php`).
- **Relational Database Engine (Practical 8):** Normalized 3NF MySQL schema (`schema.sql`), PDO connectivity with prepared statements (`db.php`), and automated diagnostics (`test_db.php`).

---

## 🗺️ Sitemap & Navigation Flow (Practical 1)

```
StudentHub Portal
│
├── Home (index.html) ────────────────────── Announcement Slider, Quick Links, Notices
├── About Us (about.html) ────────────────── Mission & Vision Statements
├── Events & Workshops (events.html) ─────── Dynamic JSON Fetch, Filter, Sort, Pagination
├── Student Registration (register.html) ── Regex Validation, Password Meter, PHP POST
├── User Login (login.html) ──────────────── Quick-fill Credentials, Role Redirect
├── Student Dashboard (dashboard.html) ──── Metric Cards, Schedule, Quick Actions
├── Student Profile (profile.html) ──────── Profile Details, Enrolled Courses
├── FAQ & Help (faq.html) ───────────────── Collapsible Accordions, Live Search
├── Contact Us (contact.html) ───────────── Campus Help Desk, Quick Message Form
├── Feedback & Ratings (feedback.html) ──── Star Rating, Category Selection, PHP POST
├── Admin Panel (admin.html) ────────────── Overview Metrics & Activity Logs
├── Stored Records (view_records.php) ───── Practical 7 CSV/JSON Storage Explorer
└── Database Diagnostics (test_db.php) ──── Practical 8 MySQL PDO Connectivity Test
```

---

## 📐 Low-Fidelity Wireframe Schematics (Practical 1)

### 1. Desktop & Tablet Layout
```
+-------------------------------------------------------------------------+
| [Logo] Student Hub Portal               [Theme Toggle] [Login] [Register]|
+-------------------------------------------------------------------------+
| [Home]  [About]  [Events]  [Dashboard]  [FAQ]  [Contact]  [Feedback]    |
+-------------------------------------------------------------------------+
| [📢 Announcement Banner / Slider]                                       |
|                                                                         |
|  +--------------------+  +--------------------+  +--------------------+ |
|  | Notice Board Card  |  | Upcoming Events    |  | Quick Access Links | |
|  +--------------------+  +--------------------+  +--------------------+ |
+-------------------------------------------------------------------------+
| Footer: About | Contact | Feedback | Copyright © Student Hub Portal     |
+-------------------------------------------------------------------------+
```

### 2. Mobile Layout (< 768px)
```
+-------------------------------------------+
| [Logo] Student Hub          [Theme] [☰]   |
+-------------------------------------------+
| [Collapsible Navigation Drawer]           |
| • Home • About • Events • Dashboard ...   |
+-------------------------------------------+
| [📢 Notification Banner]                  |
+-------------------------------------------+
| [Content Slider / Full-width Cards]       |
+-------------------------------------------+
| [Footer Links & Copyright]                |
+-------------------------------------------+
```

---

## 📂 Project Directory Structure

```
final/
│
├── index.html                 # 1. Home page with slider, modal & notice board
├── about.html                 # 2. About page with mission & vision cards
├── register.html              # 3. Student registration (JS validation & PHP POST)
├── login.html                 # 4. Login page with demo credentials autofill
├── dashboard.html             # 5. Student portal dashboard with metrics
├── events.html                # 6. Events directory (Practical 6 Fetch API)
├── profile.html               # 7. Student profile details page
├── contact.html               # 8. Campus contact info and inquiry form
├── feedback.html              # 9. Student feedback submission page
├── admin.html                 # 10. Admin moderation dashboard
├── faq.html                   # 11. Collapsible FAQ accordion with live search
│
├── style.css                  # Responsive Mobile-First CSS (Grid, Flexbox, Themes)
├── script.js                  # DOM interaction, events, validation, theme, Fetch API
├── logo.jpg                   # Portal branding logo image
│
├── events.json                # Practical 6: 16 College Events dataset
├── students.json              # Practical 6: 16 Student Profiles dataset
├── faqs.json                  # Practical 6: 16 FAQ entries dataset
│
├── process_register.php       # Practical 7: Registration handler (validation & storage)
├── process_contact.php        # Practical 7: Contact/Feedback handler
├── view_records.php           # Practical 7 Extension: Stored CSV/JSON records viewer
│
├── db.php                     # Practical 8: Secure MySQL PDO database connection
├── schema.sql                 # Practical 8: Normalized 3NF schema, seeds & stored procedures
├── test_db.php                # Practical 8: Live DB connection test & prepared statement demo
├── er_diagram.md              # Practical 8: ER Model, Mermaid diagram & viva notes
│
├── data/                      # Practical 7 Storage Folder (with file locks)
│   ├── students.csv           # Registered students in CSV format
│   ├── students.json          # Registered students in JSON format
│   ├── feedback.csv           # Submitted feedback in CSV format
│   └── feedback.json          # Submitted feedback in JSON format
│
└── README.md                  # Project documentation & practical guide
```

---

## 🧪 Detailed Practical Status (Practicals 1 to 8)

| Practical | Title | Status | Deliverables & Files |
| :---: | :--- | :---: | :--- |
| **PR 1** | Requirement Analysis, Sitemap, Wireframe & Folder Structure | ✅ **Complete** | `README.md`, Sitemap, Wireframes, Clean Structure |
| **PR 2** | Semantic HTML5 Pages with Accessibility Structure | ✅ **Complete** | 11 semantic HTML5 pages, Skip-to-content, Breadcrumbs, ARIA labels |
| **PR 3** | Responsive UI Design using CSS Grid, Flexbox & Themes | ✅ **Complete** | `style.css`, Mobile-first layout, Dark/Light theme toggle |
| **PR 4** | JavaScript DOM Manipulation & Interactivity | ✅ **Complete** | `script.js`: Slider, Modal, Accordion, Theme switcher, Hamburger |
| **PR 5** | Registration Form Validation & Error Handling | ✅ **Complete** | `register.html`, Regex checks, Password Strength Meter, Real-time checks |
| **PR 6** | External JSON Rendering using Fetch API, Search & Filter | ✅ **Complete** | `events.html`, `events.json` (16 records), `students.json` (16), `faqs.json` (16) |
| **PR 7** | PHP Form Processing, Validation & CSV/JSON Storage | ✅ **Complete** | `process_register.php`, `process_contact.php`, `view_records.php` |
| **PR 8** | MySQL Schema Design, ER Model, PDO & Prepared Statements | ✅ **Complete** | `schema.sql`, `db.php`, `test_db.php`, `er_diagram.md` |

---

## 🚀 How to Run the Project (Step-by-Step Instructions)

### Option A: Static Frontend Testing (Practicals 1 to 6)
You can directly test the frontend pages without a web server:
1. Double-click `index.html` in file explorer to open it in Chrome, Edge, or Firefox.
2. For Fetch API (`events.html`), if opened with VS Code, right-click `index.html` and choose **"Open with Live Server"**.
3. All UI animations, sliders, theme toggles, modal dialogs, and registration validations will work immediately!

---

### Option B: Complete Full-Stack Testing with XAMPP (Practicals 1 to 8)

Follow these exact steps to run PHP form processing and MySQL database connection:

#### Step 1: Copy Project to XAMPP htdocs
Copy this entire `final` folder into your XAMPP web root directory:
```
C:\xampp\htdocs\studenthub\
```

#### Step 2: Start Apache and MySQL in XAMPP
1. Open the **XAMPP Control Panel** from your Windows Start menu or desktop shortcut.
2. Click the **Start** button next to **Apache** (wait until the module turns green).
3. Click the **Start** button next to **MySQL** (wait until the module turns green).

#### Step 3: Import the MySQL Database Schema (Practical 8)
1. Open your browser and visit:  
   👉 **http://localhost/phpmyadmin**
2. In the top navigation bar, click on the **Import** tab.
3. Click **Choose File** (or "Browse") and select:  
   `C:\xampp\htdocs\studenthub\schema.sql`
4. Scroll down and click the **Import** (or **Go**) button.
5. phpMyAdmin will execute the script, create `studenthub_db`, create all 5 tables (`students`, `events`, `registrations`, `feedback`, `users`), insert seed records, and compile the stored procedures.

#### Step 4: Run the Database Diagnostic Test
Open your browser and visit:  
👉 **http://localhost/studenthub/test_db.php**
- You will see a green status badge: **✅ Database Connected Successfully!**
- It displays the MySQL Server Version, table counts, and prepared statement query output.
- **📸 Screenshot Opportunity:** Take a screenshot of this page for your **Practical 8 Post-Lab Submission**!

#### Step 5: Test PHP Form Processing & File Storage (Practical 7)
1. Visit: **http://localhost/studenthub/register.html**
2. Fill out the student registration form and submit.
3. The form will POST to `process_register.php`, execute server-side validation, write the student to `data/students.csv` and `data/students.json`, and display a success page.
4. Visit: **http://localhost/studenthub/view_records.php** to inspect the stored CSV and JSON records.
5. **📸 Screenshot Opportunity:** Take screenshots of `process_register.php` and `view_records.php` for your **Practical 7 Post-Lab Submission**!

---

## 🔑 Demo Login Credentials

For testing login and role-based redirects:
| Role | Identifier / Username | Password | Access Level |
| :--- | :--- | :--- | :--- |
| **Student** | `25DCS200` or `student@studenthub.com` | `student123` | Student Dashboard & Profile |
| **Administrator** | `admin@studenthub.com` or `admin` | `admin123` | Admin Moderation Panel |

*(You can also use the convenient "Fill" buttons on the [login.html](file:///c:/Users/Taksh%20Patel/Desktop/CODING/SEM--3%20coding/WDF/final/login.html) page to automatically test authentication).*
