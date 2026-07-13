# NexusPM

NexusPM is a professional, high-performance project management web application built with Laravel 12. It serves as an enterprise-grade solution featuring multi-tenant organizations, project-specific collaboration, real-time Kanban boards, event-driven auditing, and activity logging.

---

## 🚀 Tech Stack & Core Technologies

- **Backend Framework**: Laravel 12.x
- **Development Tooling**: Laragon, Docker (Sprint 5)
- **Database**: MySQL 8.x
- **Cache & Queue Driver**: Redis & Horizon
- **Authentication**: Laravel Breeze
- **Authorization**: Spatie Permission (with Custom Policies)
- **API & Testing**: PHPUnit/Pest
- **Code Quality**: Laravel Pint (CS) & Larastan (Static Analysis)
- **Activity Logging**: Spatie Activitylog

---

## 🏗️ Architectural Decisions

This project follows modern, robust development practices:
- **No Repository Pattern**: We leverage Eloquent directly combined with dedicated single-responsibility **Actions** and **Services** (e.g., `CreateProjectAction`).
- **PHP Enums**: Hardcoded strings are avoided. Business-critical states like `ProjectStatus`, `TaskStatus`, `TaskPriority`, and `ProjectRole` are strictly typed PHP Enums.
- **Audit Fields**: Tracking resource changes using `created_by`, `updated_by`, and `deleted_by` fields.
- **Strict Database Constraints**: Unique scoped indexes on pivot tables and scoped slugs (e.g., project slugs are unique per organization).
- **GitHub Actions CI**: Automated code style formatting (Pint) and static analysis (Larastan) on every pull request.

---

## 🛠️ Installation & Setup

### Prerequisites
- PHP 8.3+
- Composer
- MySQL/Redis (via Laragon or Docker)

### Steps
1. **Clone the repository**:
   ```bash
   git clone https://github.com/yourusername/nexuspm.git
   cd nexuspm
   ```
2. **Install Composer dependencies**:
   ```bash
   composer install
   ```
3. **Configure Environment File**:
   Copy `.env.example` to `.env` and configure your database and redis credentials.
4. **Generate Application Key**:
   ```bash
   php artisan key:generate
   ```
5. **Run Migrations & Seeders**:
   ```bash
   php artisan migrate --seed
   ```
6. **Code Styling & Analysis**:
   ```bash
   # Linting
   ./vendor/bin/pint
   # Static Analysis
   ./vendor/bin/phpstan analyse
   ```

---

## 📅 Roadmap (Sprints)

### Sprint 0 (Tooling & CI)
- [x] Code standard enforcement (Pint, Larastan)
- [x] Barryvdh IDE Helper & Local Dev packages
- [x] CI workflows (GitHub Actions)
- [x] Initial README.md structure

### Sprint 1 (Foundations)
- [ ] Authentication Setup (Breeze)
- [ ] Multi-tenant Organizations
- [ ] Projects and Membership Scoping
- [ ] Policies & Form Requests validation

### Sprint 2 (Core Logic)
- [ ] Tasks, Comments, and Polymorphic Attachments
- [ ] Task Checklist

### Sprint 3 (User Management & Policies)
- [ ] Spatie Roles & Permissions
- [ ] Interactive Dashboard

### Sprint 4 (Events & Queue Optimization)
- [ ] Event-driven Notifications and Mailers
- [ ] Horizon configuration for Redis Queue
- [ ] Activity logs hookups (Spatie Activitylog)

### Sprint 5 (Polishing & Delivery)
- [ ] API Endpoints
- [ ] Complete Test Suite (Feature/Unit)
- [ ] Dockerization
- [ ] Final Presentation
