# Project CAR

This is a project to help you get started with a Laravel-based application.

### Installation

1. Git clone this repository
2. Run `composer install` to install the dependencies
3. Run `npm install` to install the dependencies
4. copy `.env.example` to `.env` and update the values
5. Run `php artisan key:generate` to generate a new application key
6. Run `php artisan migrate --seed` to create the database tables
7. Run `composer run dev` to start the development server

### To test the application

1. navigate to `/login` page
2. use `admin@mail.com` as username and `password` as password

### To generate sample data

1. Run `php artisan simulation:create-invoices` to create invoices

### To run tests

1. Run `php artisan test` to run tests

### Features completed

1. Login
2. Datatables
3. Automation (create invoices, late charges, payments)
4. Payment Gateway (Curlec) - WIP & Rework
