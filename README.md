# ShopSphere API

Laravel backend API for the ShopSphere ecommerce platform.

## Tech Stack
- Laravel 10
- PHP 8
- MySQL
- Sanctum (API Authentication)
- Eloquent ORM

## Features
- User registration & authentication
- Product management 
- Category management
- Order creation and management
- Admin & user roles
- API routes protected with auth middleware

## Installation

```bash
git clone https://github.com/YOUR_USERNAME/shopsphere-api.git
cd shopsphere-api
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan serve
