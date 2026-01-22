# Hotel Booking System

A comprehensive hotel booking platform for Bangladeshi clients with support for both domestic and international destinations. Features multiple payment methods including bKash, Nagad, Visa, and Mastercard.

## Features

- 🏨 **Hotel Management**: Browse hotels in Bangladesh and worldwide
- 🔐 **Guest Browsing**: Search and view hotels without login
- 🔑 **Smart Authentication**: Login/register modal appears only when booking
- 💳 **Multiple Payment Methods**: bKash, Nagad, Visa, Mastercard
- 📱 **Responsive Design**: Works on desktop, tablet, and mobile
- 👤 **User Dashboard**: View and manage bookings
- 🎛️ **Admin Panel**: Complete CRUD operations for hotels, rooms, bookings, and users

## Tech Stack

- **Backend**: PHP 7.4+
- **Database**: MySQL
- **Frontend**: HTML5, CSS3, Vanilla JavaScript
- **Design**: Custom CSS with modern gradients and animations

## Installation

### Prerequisites

- XAMPP (Apache + MySQL + PHP)
- Web browser

### Setup Instructions

1. **Copy Files**
   - Place the `hotel_booking` folder in `C:\xampp\htdocs\WBT\`

2. **Import Database**
   - Start XAMPP and launch phpMyAdmin (http://localhost/phpmyadmin)
   - Click "Import" or "SQL" tab
   - Browse and select `database/hotel_booking.sql`
   - Click "Go" to execute

   OR manually create database:
   - Create new database named `hotel_booking`
   - Copy and paste SQL from `database/hotel_booking.sql` into SQL tab
   - Execute

3. **Configure Database**
   - Edit `config/config.php` if your database settings differ:
     ```php
     define('DB_HOST', 'localhost');
     define('DB_USER', 'root');
     define('DB_PASS', '');
     define('DB_NAME', 'hotel_booking');
     ```

4. **Access Application**
   - Website: http://localhost/WBT/hotel_booking/
   - Admin Panel: http://localhost/WBT/hotel_booking/admin/dashboard.php

## Default Credentials

### Admin Login
- **Email**: admin@hotelbooking.com
- **Password**: admin123

## Project Structure

```
hotel_booking/
├── admin/              # Admin panel
│   ├── dashboard.php
│   ├── manage-hotels.php
│   ├── manage-rooms.php
│   ├── manage-bookings.php
│   └── manage-users.php
├── assets/
│   ├── css/           # Stylesheets
│   ├── js/            # JavaScript files
│   └── images/        # Image assets
├── auth/              # Authentication
│   ├── login.php
│   ├── register.php
│   └── logout.php
├── config/            # Configuration
│   └── config.php
├── database/          # Database schema
│   └── hotel_booking.sql
├── includes/          # Core components
│   ├── Database.php
│   ├── Session.php
│   ├── functions.php
│   ├── header.php
│   ├── footer.php
│   └── auth-modal.php
├── payments/          # Payment gateways (placeholders)
├── index.php          # Homepage
├── hotels.php         # Hotel listing
├── hotel-details.php  # Hotel details
├── booking.php        # Booking form
├── payment.php        # Payment selection
└── profile.php        # User profile
```

## Key Features Explanation

### Guest Browsing Flow
1. Users can search hotels without login
2. View hotel listings with filters
3. See hotel details and room information
4. Click "Book Now" triggers authentication modal
5. After login/register, user is redirected to booking page

### Payment Integration
The payment system is set up to support:
- **bKash**: Mobile wallet payment
- **Nagad**: Mobile wallet payment
- **Card Payments**: Visa/Mastercard via SSLCommerz (or similar)

> **Note**: To enable real payments, you need to:
> 1. Obtain API credentials from payment providers
> 2. Update credentials in `config/config.php`
> 3. Implement gateway-specific logic in `payments/` folder

### Admin Panel
Access via: http://localhost/WBT/hotel_booking/admin/dashboard.php

Features:
- Dashboard with statistics
- Hotel management (view, add, edit)
- Room management
- Booking management with status updates
- User management

## Database Schema

- **users**: User accounts (customers and admin)
- **destinations**: Tourist destinations (domestic/international)
- **hotels**: Hotel information
- **rooms**: Room types and pricing
- **bookings**: Booking records
- **payments**: Payment transactions
- **reviews**: Hotel reviews and ratings

## Customization

### Changing Site Name and URL
Edit `config/config.php`:
```php
define('SITE_URL', 'http://localhost/WBT/hotel_booking');
define('SITE_NAME', 'Hotel Booking');
```

### Adding Sample Hotels
Use the admin panel to add hotels, or insert directly via phpMyAdmin

### Styling
Main stylesheet: `assets/css/style.css`
- Uses CSS variables for easy theme customization
- Responsive grid system
- Modern gradient designs

## Browser Support

- Chrome (recommended)
- Firefox
- Safari
- Edge

## Security Notes

- Passwords are hashed using PHP's `password_hash()`
- SQL injection protection via prepared statements
- XSS protection using `htmlspecialchars()`
- Session security configured in `config/config.php`

## Troubleshooting

### Database Connection Error
- Ensure XAMPP MySQL is running
- Verify database credentials in `config/config.php`
- Check if database `hotel_booking` exists

### Login Not Working
- Clear browser cache and cookies
- Check if session is enabled (see `Session.php`)
- Verify admin credentials in database

### Pages Not Loading
- Ensure Apache is running in XAMPP
- Check file paths in `config/config.php`
- Verify mod_rewrite is enabled

## Future Enhancements

- Email verification for new users
- Advanced hotel search filters
- Multi-language support
- Hotel gallery management
- Booking cancellation system
- Review and rating system (frontend)
- Real payment gateway integration
- PDF booking confirmations
- SMS notifications

## Support

For issues or questions, contact: admin@hotelbooking.com

## License

This project is for educational/demonstration purposes.
