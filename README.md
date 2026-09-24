# Symfony Blog & Content Management Application

A web application built with **PHP and Symfony** as a training and portfolio project.

The project demonstrates practical experience with Symfony application architecture, Doctrine ORM, authentication, forms, event handling, services, database migrations, localization and administrative CRUD functionality.

## Features

### Public Application

- Blog homepage and individual post pages
- User registration and authentication
- Contact form with feedback persistence and email handling
- Data export functionality
- English and Russian localized routes

### Administration

- Post management (create, read, update and delete)
- Category management
- Pagination of post lists
- Bulk deletion of selected posts
- Form handling and validation

## Application Architecture

The application follows Symfony's MVC architecture and separates application logic into controllers, entities, repositories, forms, services and event handlers.

### Doctrine Entities

The application uses five Doctrine entities:

- `User` — application users and authentication data
- `Post` — blog posts
- `Category` — post categories
- `Feedback` — contact form submissions
- `Activity` — application activity records

Database schema changes are managed through **Doctrine Migrations**.

### Forms

Symfony Form components are used for:

- post management
- category management
- user registration
- contact/feedback handling

### Events and Listeners

The project contains custom event-driven functionality using:

- `PostChangesEvent`
- `PostChangesListener`
- `AppListener`
- `AuthSuccessHandler`

This demonstrates the use of Symfony's event system for separating application behavior from controller logic.

### Services and Export

Data export functionality is separated into dedicated services:

- `ExportCsv`
- `ExportJson`
- `ExportSerialize`
- `ExportInterface`

This provides a common abstraction for different export formats.

### Additional Symfony Components

The project also includes:

- custom Symfony console command (`NotificationCommand`)
- paginator subscriber (`PaginatorSubscriber`)
- custom Twig extension (`MathExtension`)
- Symfony Mailer integration
- Doctrine repositories
- authentication and authorization
- localized EN/RU routes

## Tech Stack

| Technology | Usage |
| --- | --- |
| PHP 8.3 | Backend language |
| Symfony 6.4 | Application framework |
| Doctrine ORM | Database persistence |
| MariaDB | Relational database |
| Twig | Server-side templates |
| Symfony Forms | Form processing and validation |
| Symfony Security | Authentication and authorization |
| Symfony Mailer | Email handling |
| KnpPaginatorBundle | Pagination |
| JavaScript | Client-side functionality |
| CSS / Bootstrap | User interface |
| Composer | PHP dependency management |
| Doctrine Migrations | Database schema versioning |
| PHPUnit | Testing |
| Git | Version control |

## Project Structure

```text
src/
├── Command/          Custom Symfony console commands
├── Controller/       HTTP request handling
├── Entity/           Doctrine entities
├── EventListener/    Application events and listeners
├── Form/             Symfony form types
├── Repository/       Doctrine repositories
├── Service/          Application services and export logic
├── Subscriber/       Event subscribers
└── Twig/             Custom Twig extensions
```

## Installation

### Requirements

- PHP 8.3
- Composer
- MariaDB
- Symfony CLI (recommended)

Clone the repository:

```bash
git clone git@github.com:SNiessen77/Symfony.git
cd Symfony
```

Install PHP dependencies:

```bash
composer install
```

Create a local environment configuration:

```bash
touch .env.local
```

Configure your local database and application secret in `.env.local`:

```dotenv
APP_SECRET=your-local-secret
DATABASE_URL="mysql://user:password@127.0.0.1:3306/database?serverVersion=10.11.14-MariaDB&charset=utf8mb4"
```

Create the database if necessary:

```bash
php bin/console doctrine:database:create
```

Run database migrations:

```bash
php bin/console doctrine:migrations:migrate
```

Start the application:

```bash
symfony serve
```

The application is then available at:

```text
http://127.0.0.1:8000
```

## Development

Useful Symfony commands:

```bash
php bin/console debug:router
php bin/console doctrine:mapping:info
php bin/console doctrine:migrations:status
```

Run tests with:

```bash
php bin/phpunit
```

## About This Project

This repository is a **training and portfolio project** developed to deepen practical knowledge of PHP and the Symfony framework.

It demonstrates backend development concepts including MVC architecture, relational data modelling, Doctrine ORM, authentication, form processing, event-driven application logic, reusable services and database migrations.

The project is separate from proprietary applications developed in a professional environment and contains no employer source code or confidential business data.
