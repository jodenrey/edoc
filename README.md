# eDoc E-Channeling System

A web-based platform for managing doctor appointments, connecting patients, doctors, and administrators.

## Description

This project provides an online system for booking and managing medical appointments. It features distinct interfaces and functionalities for administrators, doctors, and patients to streamline the e-channeling process.

## Features

*   **Admin Panel:**
    *   Manage doctors (add, edit, delete)
    *   Manage patients
    *   View and manage appointments
    *   Manage doctor schedules/sessions
    *   Generate reports/receipts (including PDF)
*   **Doctor Portal:**
    *   View assigned appointments
    *   Manage personal schedule/sessions
    *   View patient details
    *   Add checkup remarks/notes
    *   Manage settings
*   **Patient Portal:**
    *   Register and login
    *   Search for doctors
    *   View doctor schedules
    *   Book appointments
    *   View personal appointment history
    *   Manage personal profile/settings
    *   View checkup forms/details

## Technologies Used

*   PHP
*   MySQL (or a similar relational database)
*   HTML
*   CSS
*   JavaScript (likely)
*   Composer (for PHP dependencies, e.g., TCPDF for PDF generation)

## Setup and Installation

1.  **Clone the repository:**
    ```bash
    git clone <your-repository-url>
    cd edoc-echanneling-main
    ```
2.  **Database Setup:**
    *   Create a MySQL database (e.g., `edoc`).
    *   Import the database schema from `sql_database_edoc.sql`:
        ```bash
        mysql -u <username> -p <database_name> < sql_database_edoc.sql
        ```
    *   Configure the database connection details (hostname, username, password, database name) in `connection.php`.
3.  **Install PHP Dependencies:**
    *   Make sure you have [Composer](https://getcomposer.org/) installed.
    *   Run the following command in the project root directory:
        ```bash
        composer install
        ```
4.  **Web Server:**
    *   Set up a web server (like Apache with XAMPP/WAMP/MAMP or Nginx) to point to the project's root directory (`c:\xampp\htdocs\edoc-echanneling-main` in your case).
    *   Ensure PHP is correctly configured on your web server.

## Usage

1.  Access the application through your web server (e.g., `http://localhost/edoc-echanneling-main/`).
2.  **Login/Signup:** Use the login or signup pages.
3.  Access different portals:
    *   **Admin:** Likely accessed via `/admin/` (e.g., `http://localhost/edoc-echanneling-main/admin/`)
    *   **Doctor:** Likely accessed via `/doctor/` (e.g., `http://localhost/edoc-echanneling-main/doctor/`)
    *   **Patient:** Likely accessed via `/patient/` (e.g., `http://localhost/edoc-echanneling-main/patient/`)

## Screenshots

Screenshots of the application interface are available in the `/Screenshots` directory.
