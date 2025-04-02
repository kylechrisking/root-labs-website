# Root Labs Blog

A modern, secure, and feature-rich blog platform built with PHP.

## Features

- User authentication and role-based access control
- Blog post management with categories and tags
- Comment system with moderation
- Responsive design for all devices
- SEO-friendly URLs and meta tags
- Security features including CSRF protection and password hashing

## Requirements

- PHP 7.4 or higher
- MySQL 5.7 or higher
- Apache/Nginx web server
- mod_rewrite enabled (for Apache)
- PDO PHP extension
- GD PHP extension (for image handling)

## Installation

1. Clone the repository:
   ```
   git clone https://github.com/yourusername/root-labs-website.git
   cd root-labs-website
   ```

2. Create a `.env` file based on `.env.example`:
   ```
   cp .env.example .env
   ```

3. Edit the `.env` file with your database and site configuration.

4. Import the database schema:
   ```
   mysql -u your_username -p your_database_name < blog/schema.sql
   ```

5. Set up the web server to point to the `blog` directory.

6. Set proper permissions:
   ```
   chmod -R 755 blog
   chmod -R 777 blog/uploads
   chmod -R 777 blog/cache
   chmod -R 777 blog/logs
   ```

7. Create an admin user:
   ```
   php blog/admin/create_admin.php
   ```

## Usage

### Admin Panel

Access the admin panel at `/blog/admin/` and log in with your admin credentials.

### Blog Posts

- Create, edit, and delete blog posts
- Manage categories and tags
- Moderate comments
- View statistics

### User Management

- Create and manage user accounts
- Assign roles (admin, editor, user)
- Reset passwords

## Security

This application implements several security measures:

- Password hashing using PHP's password_hash()
- CSRF protection
- SQL injection prevention using prepared statements
- XSS protection with proper output escaping
- Session security with proper configuration
- Input validation and sanitization

## Development

### Directory Structure

- `blog/` - Main application directory
  - `admin/` - Admin panel files
  - `assets/` - CSS, JavaScript, and images
  - `includes/` - PHP includes and functions
  - `uploads/` - User uploaded files
  - `cache/` - Cache files
  - `logs/` - Log files

### Coding Standards

- Follow PSR-4 autoloading standards
- Use PSR-12 coding style
- Document all functions and classes
- Write unit tests for critical functionality

## License

This project is licensed under the MIT License - see the LICENSE file for details.

## Support

For support, please open an issue in the GitHub repository or contact the administrator.
