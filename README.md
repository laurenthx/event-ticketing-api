# 🎟️ Event Ticketing API

A Laravel RESTful API for managing events, venues, ticket bookings, and user profiles, built with Passport authentication.

---

## 🚀 Features

### 👤 User Capabilities

-   Register/Login (Passport OAuth2)
-   Browse & search upcoming events
-   Book tickets if available
-   View personal bookings
-   Update profile & preferred categories

### 🛠 Admin Capabilities

-   CRUD events & venues
-   View events and tickets
-   Protect routes via `is_admin` middleware

### ✅ Technical Highlights

-   Laravel 9 with Passport authentication
-   MySQL + Eloquent ORM
-   API Resource formatting
-   Validation with Form Requests
-   Role-based access control
-   Rate-limited booking endpoint

---

## ⚙️ Installation

```bash
git clone https://github.com/laurenthx/event_ticketing_api.git
cd event_ticketing_api
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan passport:install
php artisan serve
```
