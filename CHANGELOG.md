# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]
### Added
- Blog database setup with MariaDB
- Initial schema import with users and categories
- PHP mysqli extension installation
- Apache mod_headers module configuration
- Debug toggle functionality for better development experience
- Improved session handling and security measures
- Enhanced user role management system
- Login logs table for tracking user activity

### Changed
- Separated blog and portfolio CSS files
- Updated .htaccess configurations
- Reorganized file structure for better separation
- Improved debug information display with toggle functionality
- Enhanced security by removing sensitive setup scripts
- Updated .gitignore to exclude all sensitive files
- Improved user authentication flow
- Enhanced error handling and logging

### Security
- Removed sensitive setup scripts after use
- Added comprehensive .gitignore rules
- Enhanced session security
- Improved password handling
- Added login activity logging
- Protected sensitive configuration files

### Known Issues (To Be Fixed)
- Portfolio styles not loading properly (CSS not being applied)
- Blog returning 500 error on main page
- Apache header configuration needs review
- File permissions may need adjustment

## [1.2.0] - 2024-04-01
### Added
- Personal portfolio section integrated into the website
- Interactive character stats section with animations
- Custom cursor with smooth animations
- Dark/light mode toggle with local storage persistence
- Animated background with purple stars effect
- Easter egg keyboard interactions
- New sections: About, Work, Resume, Contact
- Project showcase with hover effects
- Social media integration
- Contact form with security improvements

### Changed
- Updated navigation structure to accommodate portfolio sections
- Enhanced footer design with three-column layout
- Improved responsive design for better mobile experience
- Refined color scheme to support both Root Labs and portfolio sections

### Security
- Removed exposed email address from HTML
- Added secure form handling for contact submissions
- Implemented hidden email field for form processing

## [1.1.0] - 2024-11-03
### Added
- reCAPTCHA integration for spam prevention
- PHP-based contact form processing
- Enhanced form validation and security

### Changed
- Converted contact_form.html to PHP structure
- Updated contact form references in contact.php
- Improved form handling and submission process

## [1.0.1] - 2024-11-01
### Added
- robots.txt with Disallow rules for error.html and future admin pages
- sitemap.xml with monthly changefreq and priority rules for SEO
- Updated services menu options

### Changed
- Modified footer social links
- Updated Facebook link
- Temporarily hidden certain social media icons for future use
- Fixed robots.txt configuration error

## [1.0.0] - 2024-06-15
### Added
- Initial release of the blog platform
- User authentication system with role-based access control
- Blog post management with categories and tags
- Comment system with moderation
- Admin dashboard with statistics
- Responsive design for all devices
- SEO-friendly URLs and meta tags

### Security
- Implemented password hashing using PHP's password_hash()
- Added CSRF protection
- Implemented SQL injection prevention using prepared statements
- Added XSS protection with proper output escaping
- Configured secure session handling
- Added input validation and sanitization
- Created .gitignore to exclude sensitive files
- Added .env.example for configuration without exposing credentials

### Fixed
- Fixed session handling to prevent redirect loops
- Fixed memory exhaustion issue in debug logging
- Improved error handling throughout the application
- Fixed user role verification in authentication

## [0.9.0] - 2024-06-01
### Added
- Beta testing version with core functionality
- Database schema and initial tables
- Basic admin interface
- User management system

### Changed
- Refactored code for better organization
- Improved security measures
- Enhanced error handling

## [0.8.0] - 2024-05-15
### Added
- Alpha testing version
- Basic blog functionality
- User authentication
- Post management

### Known Issues
- Session handling issues
- Memory usage in debug mode
- Role-based access control not fully implemented 