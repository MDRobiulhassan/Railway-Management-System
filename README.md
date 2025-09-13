# Railway Management System

A comprehensive web-based railway management system built with Laravel 11, designed for managing train schedules, bookings, tickets, payments, and food orders. This enterprise-grade application provides a complete solution for railway operations with separate user and admin interfaces.

![Laravel](https://img.shields.io/badge/Laravel-11-red?style=flat-square&logo=laravel)
![PHP](https://img.shields.io/badge/PHP-8.2+-blue?style=flat-square&logo=php)
![Bootstrap](https://img.shields.io/badge/Bootstrap-5.3.3-purple?style=flat-square&logo=bootstrap)
![SQLite](https://img.shields.io/badge/SQLite-Database-green?style=flat-square&logo=sqlite)
![DomPDF](https://img.shields.io/badge/DomPDF-PDF_Generation-orange?style=flat-square)

## 🚂 Core Features

### 🎫 User Features
- **Secure Authentication System** - Registration with NID verification and profile photo upload
- **Advanced Train Search** - Search by source, destination, and travel date with real-time availability
- **Multi-Step Booking Flow** - Streamlined 4-step booking process with session management
- **Seat Selection** - Choose from AC, Shovan, and Snigdha class compartments
- **Food Ordering Integration** - Order meals during booking with categorized menu
- **Smart Dashboard** - Separate upcoming and past bookings with status tracking
- **Profile Management** - Update personal information, photo, and contact details
- **Ticket Management** - View, download PDF tickets with QR codes
- **Payment Processing** - Secure payment handling with status tracking

### 🔧 Admin Features
- **Comprehensive Admin Panel** - Complete CRUD operations for all entities
- **Schedule Management** - Create schedules with automatic duration calculation
- **User Management** - Manage user accounts, roles (user/admin), and NID verification
- **Train & Infrastructure** - Manage trains, stations, compartments, and seat arrangements
- **Booking Oversight** - View all bookings grouped by schedule/journey
- **Payment Monitoring** - Track payments with status management
- **Food Service Management** - Manage food items, categories, and orders
- **NID Database** - Government-style NID verification system
- **Dynamic Pricing** - Set class-based ticket prices per compartment

## 🛠️ Technology Stack

### Backend
- **Framework**: Laravel 11 (PHP 8.2+)
- **Database**: SQLite with 20 comprehensive migrations
- **Authentication**: Laravel Sanctum with role-based access control
- **PDF Generation**: DomPDF for ticket generation
- **File Storage**: Laravel Storage with public disk for images
- **Session Management**: Database-driven sessions for booking flow

### Frontend
- **UI Framework**: Bootstrap 5.3.3 with custom CSS
- **JavaScript**: Vanilla JS with AJAX for dynamic interactions
- **Icons**: Bootstrap Icons and custom iconography
- **Responsive Design**: Mobile-first approach with responsive grid
- **Interactive Components**: Modals, dropdowns, and form validation

### Architecture
- **Pattern**: MVC (Model-View-Controller)
- **Middleware**: Custom admin middleware for route protection
- **Controllers**: 9 specialized controllers for different domains
- **Models**: 13 Eloquent models with relationships
- **Views**: 30+ Blade templates with component reusability

## 📋 System Requirements

- **PHP**: 8.2 or higher with extensions (SQLite, GD, OpenSSL, PDO, Mbstring, Tokenizer, XML, Ctype, JSON)
- **Composer**: Latest version for dependency management
- **Node.js & NPM**: For frontend asset compilation
- **Web Server**: Apache/Nginx or Laravel's built-in development server
- **Storage**: Minimum 500MB for application and database

## 🚀 Installation & Setup

### Quick Start
#### 1. Clone the repository
```bash
git clone https://github.com/MDRobiulhassan/Railway-Management-System.git
cd Railway-Management-System
```

#### 2. Install dependencies
```bash
composer install
npm install
```

#### 3. Environment configuration
```bash
cp .env.example .env
php artisan key:generate
```

#### 4. Database setup
```bash
php artisan migrate
```

#### 5. Storage configuration
```bash
php artisan storage:link
```

#### 6. Asset compilation
```bash
npm run build
```

#### 7. Pdf Generation Dependency
```bash
composer require barryvdh/laravel-dompdf
php artisan optimize:clear
```

#### 8. Start development server
```bash
php artisan serve
```


### Production Deployment
```bash
# Optimize for production
composer install --optimize-autoloader --no-dev
php artisan config:cache
php artisan route:cache
php artisan view:cache
npm run build
```

Visit `http://localhost:8000` to access the application.

## 📊 Database Architecture

### Core Tables (20 Migrations)
```sql
-- User Management
users                    # User accounts with NID and photo
nid_db                  # Government NID verification database

-- Railway Infrastructure  
stations                # Railway stations
trains                  # Train information
compartments            # Train compartments with class types
seats                   # Individual seat arrangements

-- Scheduling & Booking
schedules               # Train schedules with routes
bookings                # User booking records
tickets                 # Individual ticket entries
ticket_prices           # Class-based pricing

-- Payment & Food Service
payments                # Payment processing
food_items              # Food menu with categories
food_orders             # Food order management

-- System Tables
sessions                # Session management
cache                   # Application caching
jobs                    # Background job queue
```

### Key Relationships
- **Users** → **Bookings** (1:Many)
- **Bookings** → **Tickets** (1:Many) 
- **Trains** → **Compartments** → **Seats** (1:Many:Many)
- **Schedules** → **Trains** + **Stations** (Many:1:Many)
- **Bookings** → **Food Orders** (1:Many)

## 🎯 Core System Workflows

### 🎫 Booking Process
1. **Search Phase**: User searches trains by route and date
2. **Selection Phase**: Choose schedule with real-time availability
3. **Seat Selection**: Pick compartment class and specific seats
4. **Food Ordering**: Optional meal selection from categorized menu
5. **Payment**: Secure payment processing with validation
6. **Confirmation**: Ticket generation with PDF download

### 🔧 Admin Operations
1. **Schedule Management**: Create routes with automatic duration calculation
2. **User Oversight**: Manage accounts, roles, and NID verification
3. **Infrastructure**: Configure trains, stations, compartments, and pricing
4. **Monitoring**: Track bookings, payments, and food orders by journey
5. **Analytics**: Revenue tracking and booking statistics

### 🔐 Authentication Flow
1. **Registration**: User signup with NID verification requirement
2. **Login**: Secure authentication with role-based redirection
3. **Profile**: Photo upload and personal information management
4. **Authorization**: Middleware-protected admin routes

## 🏗️ System Architecture

### Controllers (9 Specialized)
- **AdminController**: Complete CRUD for all admin entities (42KB)
- **BookingController**: Multi-step booking workflow management
- **AuthController**: User authentication and registration
- **ScheduleController**: Train schedule search and display
- **UserDashboardController**: User booking overview
- **UserProfileController**: Profile management
- **TicketController**: PDF ticket generation
- **SearchController**: Train search functionality

### Models (13 Eloquent)
- **User**: Authentication with NID relationship
- **Schedule**: Train scheduling with duration calculation
- **Booking**: Booking management with relationships
- **Train**: Train information with compartments
- **Ticket**: Individual ticket records
- **FoodItem**: Menu management with image handling
- **Payment**: Payment processing
- **Station, Compartment, Seat**: Infrastructure models
- **NidDb, TicketPrice, FoodOrder**: Supporting entities

### Key Features Implementation
- **Session-based Booking**: Secure multi-step process
- **Real-time Availability**: Dynamic seat calculation
- **File Upload**: Profile photos with storage symlink
- **PDF Generation**: DomPDF integration for tickets
- **AJAX Operations**: Dynamic form handling
- **Role-based Access**: Admin middleware protection

## 🎨 Frontend Architecture

### UI Framework
- **Bootstrap 5.3.3**: Responsive grid and components
- **Custom CSS**: 8 specialized stylesheets
- **JavaScript**: Vanilla JS with AJAX for interactivity
- **Blade Templates**: 30+ reusable view components

### Key UI Components
- **Interactive Modals**: AJAX-powered CRUD operations
- **Dynamic Tables**: Sortable data with search functionality
- **Status Badges**: Visual indicators for booking/payment status
- **Responsive Forms**: Multi-step booking with validation
- **Dashboard Cards**: Statistics and quick actions

## 🔐 Security Features

- **CSRF Protection**: All forms include CSRF tokens
- **Role-based Access**: Admin middleware for protected routes
- **Input Validation**: Comprehensive server-side validation
- **Password Hashing**: Laravel's built-in password hashing
- **File Upload Security**: Validated image uploads with storage isolation
- **Session Security**: Database-driven session management

## 🚀 Performance Optimizations

- **Database Indexing**: Optimized queries with proper relationships
- **Asset Compilation**: Vite for efficient frontend builds
- **Caching**: Laravel cache for improved performance
- **Lazy Loading**: Efficient data loading with Eloquent relationships
- **Session Management**: Database sessions for scalability

## 📱 User Experience Features

### User Interface
- **Mobile Responsive**: Bootstrap grid system
- **Intuitive Navigation**: Clear user flows
- **Real-time Feedback**: AJAX form submissions
- **Visual Status**: Color-coded booking states
- **Search Functionality**: Quick data filtering

### Admin Interface  
- **Unified Design**: Consistent admin panel layout
- **Bulk Operations**: Efficient data management
- **Schedule Grouping**: Journey-based data organization
- **Quick Actions**: Modal-based CRUD operations
- **Analytics Dashboard**: Booking and revenue insights

## 🔧 Development Tools & Standards

- **Code Style**: PSR-12 PHP standards
- **Version Control**: Git with semantic commits
- **Dependency Management**: Composer for PHP, NPM for frontend
- **Testing**: PHPUnit framework setup
- **Documentation**: Comprehensive inline documentation

## 📈 Scalability Considerations

- **Database Design**: Normalized schema with proper relationships
- **Modular Architecture**: Separated concerns with MVC pattern
- **API Ready**: RESTful route structure
- **Configuration Management**: Environment-based settings
- **Error Handling**: Comprehensive exception management

## 🎯 Business Logic

### Booking Rules
- **Advance Booking**: 2-hour cutoff before departure
- **Seat Availability**: Real-time calculation based on active tickets
- **Class Pricing**: Dynamic pricing per compartment class
- **Food Integration**: Optional meal ordering during booking

### Admin Operations
- **Data Grouping**: All admin views grouped by train schedule/journey
- **Auto-calculations**: Schedule duration computed automatically
- **Role Management**: User/Admin role switching
- **NID Verification**: Government-style verification system



## 📁 Detailed Project Structure

```
Railway-Management-System/
├── app/
│   ├── Http/
│   │   ├── Controllers/          # 9 specialized controllers
│   │   │   ├── AdminController.php      (42KB - Complete admin CRUD)
│   │   │   ├── BookingController.php    (Multi-step booking)
│   │   │   ├── AuthController.php       (Authentication)
│   │   │   └── ...
│   │   └── Middleware/
│   │       └── AdminMiddleware.php      (Role protection)
│   ├── Models/                   # 13 Eloquent models
│   │   ├── User.php             (Authentication + NID)
│   │   ├── Schedule.php         (Duration calculation)
│   │   ├── Booking.php          (Relationship management)
│   │   └── ...
│   └── View/Components/         # Reusable view components
├── database/
│   ├── migrations/              # 20 comprehensive migrations
│   │   ├── 2025_08_07_175123_create_users_table.php
│   │   ├── 2025_08_07_175210_create_schedules_table.php
│   │   └── ...
│   └── seeders/
│       ├── FoodItemSeeder.php   (Menu items with images)
│       └── DatabaseSeeder.php
├── resources/
│   ├── views/
│   │   ├── admin/               # 14 admin management pages
│   │   ├── components/          # 4 reusable components
│   │   ├── tickets/             # 2 ticket-related views
│   │   └── *.blade.php          # 15 user-facing pages
│   ├── css/                     # 8 custom stylesheets
│   └── js/                      # Frontend JavaScript
├── routes/
│   └── web.php                  # 168 lines of organized routes
├── public/
│   ├── css/                     # Compiled stylesheets
│   ├── images/                  # Static images (11 files)
│   └── storage/                 # Symlinked storage
├── storage/
│   ├── app/public/              # User uploads
│   └── framework/               # Laravel framework storage
├── config/                      # 10 configuration files
├── composer.json                # PHP dependencies (DomPDF included)
├── package.json                 # Frontend dependencies
└── README.md                    # This comprehensive documentation
```

## 📝 Contributing Guidelines

### Development Workflow
1. **Fork** the repository
2. **Create** feature branch (`git checkout -b feature/amazing-feature`)
3. **Commit** changes (`git commit -m 'Add amazing feature'`)
4. **Push** to branch (`git push origin feature/amazing-feature`)
5. **Open** Pull Request

### Code Standards
- Follow PSR-12 PHP coding standards
- Use meaningful variable and function names
- Add comments for complex business logic
- Maintain consistent indentation
- Write descriptive commit messages

## 📄 License & Legal

This project is open-sourced software licensed under the [MIT License](https://opensource.org/licenses/MIT).

### Third-party Licenses
- **Laravel Framework**: MIT License
- **Bootstrap**: MIT License  
- **DomPDF**: LGPL License

## 👥 Development Team

**Academic Project Team**
- Developed as part of  Software Development Project (SDP)
- Institution: Premier University
- Course: Software Development Project
- **Team Members:**
  - Robiul Hassan - [GitHub](https://github.com/MDRobiulhassan)
  - Samin Osman - [GitHub](https://github.com/samin0sm)
  - MD Tarek Hossen - [GitHub](https://github.com/25tarek)
  - Walid Talal - [GitHub](https://github.com/waliiid3)


## 🙏 Acknowledgments & Credits

- **Laravel Team**: For the robust PHP framework
- **Bootstrap Team**: For the responsive UI framework
- **DomPDF Contributors**: For PDF generation capabilities
- **Academic Supervisors**: For project guidance and mentorship
- **Open Source Community**: For inspiration and best practices

---

**Note**: This is an academic project developed for educational purposes. For production deployment, additional security hardening and performance optimizations may be required.

© 2025 Robiul Hassan. All rights reserved.  
Unauthorized copying, reproduction, or distribution of this project is prohibited.
