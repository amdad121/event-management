# Event Management System

The Event Management System is a web-based application designed to simplify the process of managing events and attendees. It allows users to create, view, edit, and delete events while managing attendees for each event. The app also includes pagination, sorting, and filtering features to easily navigate through events and attendees. The project is built using PHP (Object-Oriented Programming) and uses MySQL as the database. It also includes functionality for user authentication (login and registration) and supports event search and CSV export for attendees.

## Features

1. User Authentication
2. CRUD Operations for Events
3. Manage Attendees
4. Global Search
5. Pagination, Sorting, and Filtering
6. Export Attendees Data
7. Ajax based Event Registration

## Installation Instructions

### Prerequisites

-   PHP 7.4+
-   MySQL database.
-   Apache or Nginx web server (eg. XAMPP)

### Installation

1. Clone the Repository:

    ```bash
    git clone https://github.com/amdad121/event-management.git
    cd event-management
    ```

2. Set Up Database:

    Login to MySQL:

    ```bash
    mysql -u root -p
    ```

    then enter your password

    Create a new MySQL database:

    ```bash
    CREATE DATABASE event_management;
    ```

    Import the SQL file provided in the repository to create necessary tables:

    ```bash
    source ./event_management.sql;
    ```

3. Configure the Database Connection:
   Go to the classes/Database.php file and update the database credentials:

    ```php
    private $host = "localhost";
    private $database_name = "event_management";
    private $username = "root";
    private $password = ""; // Change if needed
    ```

4. Start Web Server:

    - If using XAMPP move the project folder into the htdocs directory.
    - Run the web server:

        ```bash
        php -S localhost:8000
        ```

        Open the browser and navigate to `http://localhost:8000`.

5. Testing Login Credentials:

    Email: `amdad@test.com`

    Password: `123456`

## How to Use

1. Login using the credentials above or register a new user.
2. Once logged in, you can add new events with a name, description, and date.
3. You can manage your events (edit or delete).
4. Add attendees to events and view attendee lists for each event.
5. Use the search bar, sorting dropdown, and filter options to easily navigate through events and attendees.
6. Export a list of attendees for a specific event as a CSV file.

## Technology Used

-   PHP
-   MySQL
-   HTML
-   CSS (Tailwind CSS)
-   Nodejs for Tailwind CSS
-   JQuery for Ajax
