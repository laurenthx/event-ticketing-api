# 🎟️ Event Ticketing API Platform

This repository hosts a RESTful API built with Laravel 9, designed to power a simple event ticketing system. It features robust authentication using Laravel Passport, comprehensive user and admin capabilities, and follows modern API development best practices.

---

## 🚀 Core Features

### 👤 User Capabilities
*   **Authentication:** Secure user registration (`POST /api/register`) and login (`POST /api/login`) with JWTs managed by Laravel Passport.
*   **Profile Management:** View (`GET /api/user`) and update (`PUT /api/user/profile`) personal profiles, including setting preferred event categories for personalized event discovery.
*   **Event Discovery:**
    *   Browse upcoming events with pagination (`GET /api/events`).
    *   Filter events by category (`?category=...`), venue (`?venue_id=...`), and date range (`?date_from=...`, `?date_to=...`).
    *   Search events by title, description, or venue name (`GET /api/events/search?q=...`).
    *   View detailed information for a specific event (`GET /api/events/{id}`).
    *   Event listings are intelligently sorted to show events in preferred categories first for authenticated users.
*   **Ticket Booking:**
    *   Book tickets for available events (`POST /api/events/{event_id}/tickets`).
    *   System checks for event availability (not in the past) and sufficient seat capacity (utilizing database transactions and pessimistic locking for concurrency safety).
*   **My Bookings:** View personal booked tickets, sorted by event date with upcoming events first (`GET /api/my-tickets`).
*   **Logout:** Securely end user sessions (`POST /api/logout`).

### 🛠 Administrator Capabilities (Protected Routes)
*   **Venue Management:** Full CRUD operations for venues (List, Create, Show, Update, Delete via `GET, POST, PUT, DELETE /api/admin/venues`).
    *   Prevents deletion of venues that have active events.
*   **Event Management:** Full CRUD operations for events (List, Create, Show, Update, Delete via `GET, POST, PUT, DELETE /api/admin/events`).
    *   Prevents deletion of events that have booked tickets.
*   **Access Control:** Admin-specific routes are protected using custom `IsAdmin` middleware.

### ✨ Technical Highlights & Bonus Features
*   **Framework:** Laravel 9.x with PHP 8.0.x
*   **Database:** MySQL with Eloquent ORM for expressive database interactions.
*   **API Authentication:** Laravel Passport for robust OAuth2-based API authentication.
*   **Data Formatting:** Consistent JSON responses using Laravel API Resources.
*   **Input Validation:** Secure and clear input validation using Laravel Form Requests.
*   **Rate Limiting:** Ticket booking endpoint is rate-limited (e.g., 3 requests per minute per user) to prevent abuse.
*   **Seeding:** Comprehensive database seeder for easy setup with sample data, including an admin user.
*   **(Bonus) Email Notifications:** Ticket booking confirmation is currently logged. (Can be extended to send actual emails by configuring Mailtrap/SMTP and uncommenting Mail facade usage in `UserTicketController`).

---

## 🛠️ Setup & Installation Guide

Follow these steps to get the API running locally:

1.  **Clone the Repository:**
    ```bash
    git clone https://github.com/laurenthx/event-ticketing-api.git
    cd event-ticketing-api
    ```

2.  **Install PHP Dependencies:**
    Ensure you have Composer installed ([getcomposer.org](https://getcomposer.org/)).
    ```bash
    composer install
    ```

3.  **Create Environment File & Configure:**
    Copy the example environment file:
    ```bash
    cp .env.example .env
    ```
    Open the newly created `.env` file in your text editor and update the following sections:
    *   `APP_NAME="Event Ticketing API"`
    *   `APP_URL=http://localhost:8000` (or your preferred local development URL)

    *   **Database Connection:**
        ```env
        DB_CONNECTION=mysql
        DB_HOST=127.0.0.1         # Or your MySQL host
        DB_PORT=3306             # Or your MySQL port
        DB_DATABASE=event_ticketing # Create this database in MySQL
        DB_USERNAME=your_mysql_user # Your MySQL username
        DB_PASSWORD=your_mysql_password # Your MySQL password
        ```

    *   **Mail Settings (For development, `log` is recommended to avoid actual email sending):**
        ```env
        MAIL_MAILER=log
        MAIL_HOST=127.0.0.1
        MAIL_PORT=1025
        MAIL_USERNAME=null
        MAIL_PASSWORD=null
        MAIL_ENCRYPTION=null
        MAIL_FROM_ADDRESS="noreply@example.com"
        MAIL_FROM_NAME="${APP_NAME}"
        ```
        *(If you wish to test actual email sending with a tool like Mailtrap, update these accordingly.)*

4.  **Generate Application Key:**
    This key is used for encryption and must be set.
    ```bash
    php artisan key:generate
    ```

5.  **Run Database Migrations:**
    This will create all the necessary tables in the database you configured in `.env`.
    ```bash
    php artisan migrate
    ```

6.  **Install Laravel Passport:**
    This command sets up the OAuth2 server, creating encryption keys and initial "personal access" and "password grant" clients.
    ```bash
    php artisan passport:install
    ```

7.  **Seed the Database (Highly Recommended):**
    This command populates the database with initial sample data, including:
    *   An administrator account.
    *   Several regular user accounts.
    *   Sample venues.
    *   Sample events associated with venues and created by the admin.
    *   Sample tickets booked by users for some events.

    ```bash
    php artisan db:seed
    ```
    **Default Admin Credentials:**
    *   **Email:** `admin@example.com`
    *   **Password:** `password`

8.  **Start the Local Development Server:**
    ```bash
    php artisan serve
    ```
    The API will typically be accessible at `http://localhost:8000`.

---

## 🧪 API Testing with Postman

A comprehensive Postman collection is included in this repository to facilitate testing all API endpoints.

**File:** `Event Ticketing API.postman_collection.json` (located in the project root)

**Instructions for Postman:**
1.  **Import the Collection:** Open Postman, click "Import," and select the `Event Ticketing API.postman_collection.json` file.
2.  **Set Up Environment (Recommended):** Create a Postman environment and add a variable `baseUrl` with the value `http://localhost:8000` (or your `APP_URL`).
3.  **Authentication:**
    *   To test authenticated endpoints, first use the `POST /api/login` request with either the admin credentials (see above) or credentials for a regular user (can be found in the database if seeded, or register a new one).
    *   Copy the `access_token` from the login response.
4.  **Making Authenticated Requests:**
    *   For requests requiring authentication, go to the "Authorization" tab in Postman.
    *   Select "Type" as "Bearer Token."
    *   Paste the copied `access_token` into the "Token" field.
    *   Alternatively, set the `Authorization` header manually: `Bearer <YOUR_ACCESS_TOKEN>`.
5.  The collection is organized into folders (e.g., "Authentication," "Admin - Venues," "User - Events") for easy navigation.

---


