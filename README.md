# Come Garden

Come Garden is a modular Laravel-based ecosystem platform designed for community resource management.

## Overview

The system includes the following core modules:

- Seed Bank System  
- Tool Library System  
- Marketplace System  
- Volunteer Management System  

It provides a unified platform supporting wallets, transactions, bookings, and service tracking.

## Tech Stack

- Laravel 13.x
- PHP 8.3+
- MySQL
- Node.js + Vite
- XAMPP (Apache + MySQL)
- Blade Templates
- Vanilla JavaScript

## Project Setup (Local Development)

### 1. Requirements

Install the following:

- XAMPP (Apache, MySQL, PHP)
- Composer
- Node.js (LTS recommended)
- Git

### 2. Clone the Repository

```bash
cd C:\xampp\htdocs
git clone https://github.com/The-Software-Team/Come_Garden.git
cd Come_Garden
```

### 3. Install Dependencies

Before installing dependencies, ensure your environment matches the project requirements:

- PHP 8.3+
- Composer (latest version recommended)
- Node.js (LTS recommended)

Then run:

```bash
composer install
npm install
cp .env.example .env
```


### 4. Start XAMPP

start the following services:

- Apache
- MySQL

### 5. Create Database

Open:
```code
http://localhost/phpmyadmin
```
#### in databases, create new database and name it `come_garden_db`

## 6. Run Migrations 
```bash
php artisan migrate:fresh --seed
```

## 7. Generate App key &  Start
```bash
php artisan key:generate
npm run dev
```

## 8. Test
Open in browser `http://localhost/Come_Garden/public/seedbank`

After setup:
- Deposit a seed
- open `localhost/phpmyadmin`
- in your database, verify data added in:
    - transactions
    - seed_batches

### PHP Configuration (Important)
#### If you encounter missing PHP extensions or Composer errors:

Fix (XAMPP): `xampp/php/php.ini-production → php.ini`

#### Required Extensions

Enable (remove ; from) these in php.ini:
```code
extension=fileinfo
extension=mbstring
extension=openssl
extension=pdo_mysql
extension=curl
extension=zip
```
#### Verify Extensions:
```bash
php -m
```
