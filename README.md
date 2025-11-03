# 🏢 Meeting Room Booking System

A web application that simulates a corporate environment where employees can **book meeting rooms** within a company.  
The project follows an **MVC (Model–View–Controller)** architecture and is fully containerized using **Docker**, making it portable and easy to deploy.

---

## 🚀 Overview

The **Meeting Room Booking System** allows authenticated users to:
- View available meeting rooms.
- Book a room for a specific date and time slot.
- View and manage their own reservations.
- Administrators can manage users, meeting rooms, and monitor all bookings.

This system aims to replicate a realistic company workflow for managing shared spaces efficiently.

---

## 🧰 Tech Stack

| Layer | Technology |
|-------|-------------|
| **Frontend** | HTML5, CSS3, JavaScript |
| **Backend** | PHP 8.2 |
| **Database** | MySQL 8.0 |
| **Web Server** | Apache |
| **Database UI** | phpMyAdmin |
| **Containerization** | Docker & Docker Compose |

---

## 🧱 Architecture

The application is built using a **PHP MVC architecture**:
- **Router**: Handles HTTP requests and maps URLs to controllers.  
- **Controllers**: Contain business logic and handle user actions.  
- **Models**: Interact with the MySQL database using PDO.  
- **Views**: Display dynamic data in HTML to the user.

**Docker** is used to containerize the full environment:
- `app` → PHP + Apache web server  
- `mysql` → MySQL 8.0 database  
- `phpmyadmin` → Web interface for database management

---

## 🗄️ Database Structure

The database includes three main entities:

- **User** → `Username`, `Password`, `Administrator`  
- **MeetingRoom** → `ID`, `Capacity`, `Floor`, `Building`  
- **Booking** → `Date`, `TimeSlot`, `RoomID`, `Username`

Each booking is uniquely identified by a combination of **Date**, **TimeSlot**, and **RoomID**, ensuring that no two bookings overlap for the same room and time.

---

## ⚙️ Installation & Setup

### 1️⃣ Clone the repository
```bash
git clone https://github.com/<your-username>/MeetingRoomBookingSystem.git
cd MeetingRoomBookingSystem
```

### 2️⃣ Build and run with Docker
```bash
#Run this command in the /docker folder
docker-compose up -d --build

```
This command will automatically:

- Build all containers
- Initialize the MySQL database
- Launch the web and phpMyAdmin services

### 3️⃣ Access the application
- Web App: http://localhost:8080
- phpMyAdmin: http://localhost:8081
  - User: root
  - Password: root_password

### 4️⃣ Default system credentials

| Role  | Username       | Password |
| ----- | -------------- | -------- |
| Admin | `maioneemilio` | `user1`  |
| User  | `mai092`       | `user2`  |

---

# 🧩 Project Structure
MeetingRoomBookingSystem/  
│  
├── php-apache/  
│   ├── Dockerfile  
│   ├── apache-config.conf  
│   └── .htaccess  
│  
├── public/  
│   └── index.php  
│  
├── app/  
│   ├── controllers/  
│   ├── models/  
│   ├── views/  
│   └── router.php  
│  
├── docker-compose.yml  
└── README.md  

---

# 🧠 Key Features

- Authentication (login/logout)
- Role-based access (Admin/User)
- Meeting room management (CRUD)
- Booking system with validation
- AJAX-based real-time updates
- Responsive and lightweight interface
- Secure database operations (Prepared Statements)

---

# 📦 Environment Configuration

The Docker setup includes:

- Apache with PHP 8.2 and enabled mod_rewrite

- MySQL with an initialized schema

- phpMyAdmin interface for database inspection

Environment variables (set in docker-compose.yml):

DB_HOST: mysql  
DB_NAME: meeting_rooms  
DB_USER: app_user  
DB_PASS: app_password  

---

# 🧑‍💻 Author

Emilio Maione



