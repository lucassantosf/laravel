# Laravel Project Repository

## Overview
This repository contains multiple Laravel projects and coding challenges for development practice and reference.

## Project Structure

### 🚀 Boilerplate V12 (`boilerplate_v12/`)
A Laravel 12 boilerplate project providing a robust starting point for building APIs with comprehensive authentication and business logic features.

#### Key Features:
- **Authentication**: Laravel Passport (OAuth2) with token-based authentication
- **Authorization**: ACL with Spatie Permission package for role-based access control
- **Architecture**: Structured layers (Controllers, Services, Repositories) for separation of concerns
- **Docker**: Complete containerized development environment (MySQL, Redis)
- **Business Features**:
  - Appointment system with CRUD operations
  - Gemini AI integration for conversational appointment management
  - Post management with exports
  - User management with roles and permissions
- **Automation**: Job queuing with Redis and Artisan commands scheduling
- **Testing**: Comprehensive test coverage (Feature and Unit tests)
- **Exports**: Excel/PDF export functionality using maatwebsite/excel

#### Tech Stack:
- PHP 8.2+
- Laravel Framework 12.0
- Laravel Passport 13.0
- MySQL Database
- Redis for caching and queues
- Docker & Docker Compose

#### Main Components:
- **App/**: Core application logic
  - `Controllers/`: Handle HTTP requests (Auth, User, Post, Appointment, GeminiAi)
  - `Models/`: Eloquent models (User, Post, Appointment)
  - `Services/`: Business logic layer with service pattern
  - `Repositories/`: Data access abstraction
  - `Clients/`: External API clients (GeminiAiClient)
  - `Exports/`: Export classes (Excel/PDF generation)
  - `Jobs/`: Background job processing
  - `Console/Commands/`: Artisan commands
- **Database/**: Migrations, seeders, and factories
- **Routes/**: API and web route definitions
- **Tests/**: Feature and unit tests with coverage examples
- **Environment/**: Docker configurations for development

---

### ⚡ Boilerplate V13 (`boilerplate_v13/`)
A modern Laravel 13 professional boilerplate with improved architecture and updated dependencies, building upon the V12 foundation with enhancements.

#### Key Features:
- **Infrastructure**: PHP 8.4 + Docker (Nginx, MySQL 9.0, Redis)
- **Authentication**: Laravel Passport (OAuth2/JWT) with improved token management
- **Security**: Enhanced ACL with Spatie Permission & route-based middleware
- **Architecture**: Improved abstract base classes (Model, Repository, Service, Controller)
- **Implementation**: Scalable CRUD examples (Post & User) with better code organization
- **Features**:
  - Advanced exports (CSV) without external dependencies
  - Queue/Job system with Redis driver
  - Task scheduling workflows
  - Comprehensive test coverage with RefreshDatabase trait
- **Development**: Improved developer experience with updated tooling

#### Improvements over V12:
- **Performance**: Updated to PHP 8.4 and Laravel 13.x
- **Dependencies**: Removed heavy packages (maatwebsite/excel, guzzlehttp) for lighter footprint
- **Architecture**: Better abstraction with AbstractRepository and AbstractService
- **Testing**: Enhanced test setup with automatic database migrations
- **Code Quality**: Modern PHP practices and type hints

#### Tech Stack:
- PHP 8.4+
- Laravel Framework 13.8
- Laravel Passport 13.7
- MySQL 9.0
- Redis for caching and queues
- Docker & Docker Compose
- Native CSV exports (no Excel package dependency)

#### Main Components:
- **App/**: Enhanced application structure
  - `Controllers/`: Improved base controller with error handling
  - `Models/`: BaseModel with common functionality
  - `Services/`: AbstractService pattern with business logic
  - `Repositories/`: AbstractRepository for data access
  - `Exports/`: Native CSV export implementation
  - `Jobs/`: Queue job processing
  - `Console/Commands/`: Scheduled Artisan commands
- **Database/**: Updated migrations with new MySQL 9.0 features
- **Tests/**: Enhanced test suite with UserTest and PostTest coverage

---

### 💻 LeetCode Challenges (`leet_code_challenges/`)
A comprehensive collection of PHP coding exercises and algorithm challenges solved in PHP, designed to enhance programming skills and problem-solving abilities.

#### Content:
- **70+ Algorithm Challenges**: Solved LeetCode problems implemented in PHP
- **Various Categories**:
  - Array and String manipulation
  - Binary Tree algorithms
  - Dynamic Programming
  - Mathematical problems
  - Bit manipulation
  - Graph algorithms
  - Database queries (SQL)

#### Example Challenges:
- Two Sum, Valid Parentheses, Merge Two Sorted Lists
- Binary Tree operations (traversal, inversion, symmetry)
- Dynamic Programming (Climbing Stairs, Pascal's Triangle)
- Mathematical operations (Roman to Integer, Add Binary)
- Bit manipulation (Number Complement, Hamming Distance)
- Array algorithms (Move Zeroes, Contains Duplicate)

#### Purpose:
- Practice PHP programming concepts
- Improve algorithmic thinking and problem-solving
- Explore different approaches to common coding challenges
- Build a portfolio of solved algorithmic problems

#### Usage:
```bash
# Run any challenge
php leet_code_challenges/1_two_sum.php
php leet_code_challenges/20_valid_parentheses.php
```

---

## Getting Started

### For Laravel Projects:
1. Clone the repository
2. Navigate to desired boilerplate:
   ```bash
   cd boilerplate_v12/  # or boilerplate_v13/
   ```
3. Copy environment file: `cp .env.example .env`
4. Install dependencies: `composer install`
5. Start Docker containers: `docker-compose up -d`
6. Run migrations: `php artisan migrate`
7. Start development server: `php artisan serve`

### For Code Challenges:
1. Navigate to challenges directory:
   ```bash
   cd leet_code_challenges/
   ```
2. Run individual challenges:
   ```bash
   php 1_two_sum.php
   ```

## Development Workflow

### Laravel V12:
```bash
cd boilerplate_v12/
docker-compose up -d
composer install
php artisan migrate
php artisan serve
# Run tests
./vendor/bin/phpunit
```

### Laravel V13:
```bash
cd boilerplate_v13/
docker-compose up -d
composer install
php artisan migrate
php artisan serve
# Run tests
php artisan test
```

## Project Comparison

| Feature | V12 | V13 |
|---------|-----|-----|
| PHP Version | 8.2+ | 8.4+ |
| Laravel Version | 12.0 | 13.8 |
| Database | MySQL 8.0 | MySQL 9.0 |
| Exports | maatwebsite/excel | Native CSV |
| AI Integration | Gemini AI | Not included |
| Appointment System | Complete CRUD | Not included |
| External Dependencies | More packages | Streamlined |
| Architecture | Good | Improved abstraction |
| Testing | Basic examples | Enhanced coverage |
