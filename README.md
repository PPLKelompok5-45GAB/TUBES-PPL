# Libralink2

Libralink2 is a Laravel-based Learning Management System (LMS) powered by a modern Argon Dashboard UI preset. It provides a scalable core application and comprehensive project documentation.

## Key Components

- **lms-core/**: Main Laravel 10 application, includes:
  - **app/**: Controllers, Models, Events, Listeners, Policies.
  - **config/**: Application configuration files.
  - **database/**: Migrations, Seeders, Factories.
  - **public/**: Web server entry point and static assets.
  - **resources/**: Blade views, Sass/CSS, JavaScript.
  - **routes/**: HTTP and Console route definitions.
  - **tests/**: Unit and Feature tests.


## Getting Started

### Prerequisites

- PHP >= 8.1
- Composer
- Node.js & npm (or Yarn)
- MySQL (or compatible database)

### Installation

1. **Clone the repository**
   ```bash
   git clone <repo_url> Libralink2
   cd Libralink2
   ```
2. **Install root dependencies (Argon preset)**
   ```bash
   composer install
   ```
3. **Install application dependencies**
   ```bash
   cd lms-core
   composer install
   npm install
   npm run build
   ```
4. **Configure environment**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
5. **Set up database**
   - Update database settings in `.env`
   - Run migrations and seeders:
     ```bash
     php artisan migrate --seed
     ```
6. **Start development server**
   ```bash
   php artisan serve
   ```

## Project Structure

```
/lms-core           # Laravel application core
    ├── app              # Controllers, Models, Events, etc.
    ├── config           # Configuration files
    ├── database         # Migrations, Seeders, Factories
    ├── public           # Entry point & static assets
    ├── resources        # Views and front-end assets
    ├── routes           # HTTP & Console routes
    └── tests            # Test suites

```