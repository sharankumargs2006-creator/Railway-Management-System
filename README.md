# Train Ticket Booking System

A web-based application for booking train tickets, managing train schedules, and handling user reservations. Built with PHP, MySQL, HTML, CSS, and JavaScript.

---

## Features

- **User Registration & Login:** Secure authentication for users.
- **Train Search:** Search for trains by source, destination, and date.
- **Booking Tickets:** Reserve seats and receive booking confirmation.
- **View Bookings:** Users can view and manage their bookings.
- **Admin Panel:** Manage trains, schedules, and view all bookings.
- **Email Notifications:** Send booking confirmations (credentials not included for security).
- **Responsive Design:** Works on desktop and mobile devices.

---

## Technologies Used

- **Backend:** PHP
- **Frontend:** HTML, CSS, JavaScript, Bootstrap
- **Database:** MySQL
- **Email:** PHPMailer (credentials/config not included)
- **Version Control:** Git

---

## Setup Instructions

### 1. Clone the Repository

```bash
git clone https://github.com/Raghavendra099/Train-Ticket-Booking-System.git
cd Train-Ticket-Booking-System
```

### 2. Configure the Database

- Import the provided SQL file (`database.sql` or similar) into your MySQL server.
- Update your database connection settings in `config.php` (not included in repo for security).

### 3. Configure Environment Variables

- Create a `.env` file for sensitive data (API keys, email credentials, etc.).
- **Do not commit this file to GitHub.**

### 4. Install Dependencies

- Make sure you have [XAMPP](https://www.apachefriends.org/) or similar PHP environment installed.
- Place the project folder in your `htdocs` directory.

### 5. Run the Application

- Start Apache and MySQL from XAMPP.
- Open your browser and go to:  
  `http://localhost/Train-Ticket-Booking-System/`

---

## File Structure

```
/Train-Ticket-Booking-System
│
├── assets/              # CSS, JS, images
├── includes/            # PHP includes (header, footer, etc.)
├── admin/               # Admin panel files
├── user/                # User dashboard and booking files
├── config.php           # Database config (ignored in repo)
├── emailsender.php      # Email sending logic (ignored in repo)
├── .env                 # Environment variables (ignored in repo)
├── .gitignore
├── index.php
└── README.md
```

---

## Security Notes

- **Sensitive files** like `.env`, `config.php`, and `emailsender.php` are excluded from the repository for security.
- Never commit API keys, passwords, or email credentials to public repositories.

---

## Contribution

1. Fork the repository.
2. Create your feature branch (`git checkout -b feature/YourFeature`).
3. Commit your changes (`git commit -am 'Add new feature'`).
4. Push to the branch (`git push origin feature/YourFeature`).
5. Open a Pull Request.

---

## License

This project is licensed under the [MIT License](LICENSE).

---

## Acknowledgements

- Bootstrap for UI components
- PHPMailer for email functionality
- Open-source community for inspiration

---
