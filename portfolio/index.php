<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kyle King | Portfolio</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="images/favicon.ico">
    <link rel="apple-touch-icon" sizes="180x180" href="images/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="images/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="images/favicon-16x16.png">
    <link rel="manifest" href="images/site.webmanifest">
    
    <!-- CSS Files -->
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/animations.css">
    <link rel="stylesheet" href="css/blog.css">
    <link rel="stylesheet" href="css/responsive.css">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.3/html2pdf.bundle.min.js"></script>
    <script src="js/theme.js"></script>
</head>
<body class="portfolio-section">
    <!-- Custom Cursor -->
    <div class="cursor-outer"></div>
    <div class="cursor-inner"></div>

    <!-- Particle Canvas Background -->
    <canvas id="particleCanvas"></canvas>

    <!-- Loading Screen -->
    <div class="loading-screen">
        <div class="loader"></div>
    </div>

    <!-- Navigation -->
    <nav class="main-nav">
        <div class="logo">KK</div>
        <div class="nav-links">
            <a href="#home" class="nav-link">Home</a>
            <a href="#about" class="nav-link">About</a>
            <a href="#work" class="nav-link">Work</a>
            <a href="#resume" class="nav-link">Resume</a>
            <a href="#contact" class="nav-link">Contact</a>
            <a href="../blog" class="nav-link">Blog</a>
            <button class="theme-toggle" aria-label="Toggle theme">
                <svg class="sun-icon" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <circle cx="12" cy="12" r="5"></circle>
                    <line x1="12" y1="1" x2="12" y2="3"></line>
                    <line x1="12" y1="21" x2="12" y2="23"></line>
                    <line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line>
                    <line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line>
                    <line x1="1" y1="12" x2="3" y2="12"></line>
                    <line x1="21" y1="12" x2="23" y2="12"></line>
                    <line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line>
                    <line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line>
                </svg>
                <svg class="moon-icon" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path>
                </svg>
            </button>
        </div>
        <div class="mobile-nav-toggle">
            <div class="hamburger">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>
    </nav>

    <div class="mobile-menu">
        <div class="mobile-menu-inner">
            <a href="#home" class="mobile-link">Home</a>
            <a href="#about" class="mobile-link">About</a>
            <a href="#work" class="mobile-link">Work</a>
            <a href="#resume" class="mobile-link">Resume</a>
            <a href="#contact" class="mobile-link">Contact</a>
        </div>
    </div>

    <!-- Main Content -->
    <main>
    <div id="stars-background"></div>
    <div class="cursor"></div>
    <div class="cursor-follower"></div>
    
    <!-- Hero Section -->
    <section id="home" class="hero">
        <div class="crown-container">
            <div class="crown hidden">
                👑
            </div>
        </div>
        <h1 class="glitch-text">Kyle King</h1>
        <h2 class="subtitle">IT Systems Engineer & Full Stack Developer</h2>
        <p class="tagline">Crafting <span class="highlight">innovative solutions</span> through code</p>
        <div class="scroll-indicator hero-scroll">
            <span>Scroll to explore</span>
            <div class="scroll-arrow">↓</div>
        </div>
    </section>
    
    <!-- About Section -->
    <section id="about" class="about">
        <div class="about-content reveal">
            <h2 class="section-title">About Me</h2>
            <div class="about-grid">
                <div class="about-text">
                    <p>IT Systems Engineer & Full Stack Developer with a passion for creating innovative solutions and a strong foundation in web development since 2019. Currently serving as an Information Technology System Engineer at Bally's Evansville Casino & Hotel and Chief of Operations at Root Labs US.</p>
                </div>
            </div>
            <div class="character-panel">
                <div class="character-stats">
                    <div class="stat-group class-info">
                        <div class="class-title">Character Stats</div>
                        <div class="stat-item level">
                            <div class="stat-icon">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"></path>
                                </svg>
                            </div>
                            <div class="stat-info">
                                <span class="stat-label">Experience Level</span>
                                <span class="stat-value" data-value="24">0</span>
                                <div class="stat-detail">Years in Tech: 5</div>
                            </div>
                        </div>
                        <div class="specializations">
                            <span class="spec-tag" data-tooltip="Proficient in full-stack web development and modern frameworks">Full Stack Engineer</span>
                            <span class="spec-tag" data-tooltip="Expert in IT infrastructure and system administration">Systems Engineer</span>
                            <span class="spec-tag" data-tooltip="Leading teams and managing technical projects">Project Manager</span>
                            <span class="spec-tag" data-tooltip="Guiding technical direction and team development">Team Lead</span>
                        </div>
                    </div>

                    <div class="stat-group achievements">
                        <div class="stat-item system-mastery" data-tooltip="Proficiency in managing complex IT systems">
                            <div class="stat-icon">🖥️</div>
                            <div class="stat-info">
                                <span class="stat-label">System Mastery</span>
                                <div class="progress-bar">
                                    <div class="progress-fill" data-percentage="95"></div>
                                    <div class="stat-detail">Expert in VMware, Windows Server, Active Directory</div>
                                </div>
                            </div>
                        </div>
                        <div class="stat-item code-proficiency" data-tooltip="Programming languages and development skills">
                            <div class="stat-icon">⌨️</div>
                            <div class="stat-info">
                                <span class="stat-label">Code Proficiency</span>
                                <div class="progress-bar">
                                    <div class="progress-fill" data-percentage="90"></div>
                                    <div class="stat-detail">JavaScript, PHP, Ruby on Rails</div>
                                </div>
                            </div>
                        </div>
                        <div class="stat-item network-security" data-tooltip="Network infrastructure and security expertise">
                            <div class="stat-icon">🛡️</div>
                            <div class="stat-info">
                                <span class="stat-label">Network & Security</span>
                                <div class="progress-bar">
                                    <div class="progress-fill" data-percentage="85"></div>
                                    <div class="stat-detail">Infrastructure, Cybersecurity, Cloud Services</div>
                                </div>
                            </div>
                        </div>
                        <div class="stat-item problem-solving" data-tooltip="Ability to tackle complex technical challenges">
                            <div class="stat-icon">🔧</div>
                            <div class="stat-info">
                                <span class="stat-label">Problem Solving</span>
                                <div class="progress-bar">
                                    <div class="progress-fill" data-percentage="92"></div>
                                    <div class="stat-detail">500+ technical issues resolved</div>
                                </div>
                            </div>
                        </div>
                        <div class="stat-item learning-rate" data-tooltip="Speed and efficiency in mastering new technologies">
                            <div class="stat-icon">📚</div>
                            <div class="stat-info">
                                <span class="stat-label">Learning Rate</span>
                                <div class="progress-bar">
                                    <div class="progress-fill" data-percentage="95"></div>
                                    <div class="stat-detail">Rapid adaptation to new technologies</div>
                                </div>
                            </div>
                        </div>
                        <div class="stat-item leadership" data-tooltip="Team management and project leadership capabilities">
                            <div class="stat-icon">👥</div>
                            <div class="stat-info">
                                <span class="stat-label">Leadership</span>
                                <div class="progress-bar">
                                    <div class="progress-fill" data-percentage="88"></div>
                                    <div class="stat-detail">Managing technical teams and projects</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Add scroll indicator between About and Work sections -->
    <div class="scroll-indicator-section">
        <div class="scroll-indicator-wrapper">
            <div class="scroll-indicator section-scroll">
                <span>More Below</span>
                <div class="scroll-arrow">↓</div>
            </div>
        </div>
    </div>
    
    <!-- Work Section -->
    <section id="work" class="work">
        <div class="work-content reveal">
            <h2 class="section-title">Featured Work</h2>
            <div class="project-grid">
                <!-- Root Labs Project -->
                <div class="project-card">
                    <div class="project-image">
                        <img src="images/rootlabs-preview.png" alt="Root Labs Website Preview">
                        <div class="project-overlay">
                            <div class="project-links">
                                <a href="https://www.rootlabs.us" target="_blank" class="project-link">Visit Site</a>
                            </div>
                        </div>
                    </div>
                    <div class="project-info">
                        <h3>Root Labs</h3>
                        <p>Professional IT consulting firm website with modern design and functionality.</p>
                        <div class="project-tech">
                            <span>HTML</span>
                            <span>CSS</span>
                            <span>JavaScript</span>
                            <span>PHP</span>
                        </div>
                    </div>
                </div>

                <!-- King Mechanical Specialty Project -->
                <div class="project-card">
                    <div class="project-image">
                        <img src="images/king-mechanical-preview.png" alt="King Mechanical Specialty Website Preview">
                        <div class="project-overlay">
                            <div class="project-links">
                                <a href="#" class="project-link">Coming Soon</a>
                            </div>
                        </div>
                    </div>
                    <div class="project-info">
                        <h3>King Mechanical Specialty</h3>
                        <p>Website redesign for a mechanical specialty company, featuring modern UI and improved user experience.</p>
                        <div class="project-tech">
                            <span>HTML</span>
                            <span>CSS</span>
                            <span>JavaScript</span>
                            <span>PHP</span>
                        </div>
                    </div>
                </div>

                <!-- Taskly Project -->
                <div class="project-card">
                    <div class="project-image">
                        <img src="images/taskly-preview.png" alt="Taskly Project Preview">
                        <div class="project-overlay">
                            <div class="project-links">
                                <a href="https://github.com/kylechrisking/taskly" target="_blank" class="project-link">View Code</a>
                            </div>
                        </div>
                    </div>
                    <div class="project-info">
                        <h3>Taskly</h3>
                        <p>Web-based To-Do-List application for efficient task management and organization.</p>
                        <div class="project-tech">
                            <span>JavaScript</span>
                            <span>HTML</span>
                            <span>CSS</span>
                        </div>
                    </div>
                </div>

                <!-- IT Empire Idle -->
                <div class="project-card">
                    <div class="project-image">
                        <img src="images/it-empire-preview.png" alt="IT Empire Idle Game Preview">
                        <div class="project-overlay">
                            <div class="project-links">
                                <a href="https://github.com/kylechrisking/it-empire-idle" target="_blank" class="project-link">View Code</a>
                            </div>
                        </div>
                    </div>
                    <div class="project-info">
                        <h3>IT Empire Idle</h3>
                        <p>Browser-based idle game centered around IT industry themes and progression.</p>
                        <div class="project-tech">
                            <span>JavaScript</span>
                            <span>Game Development</span>
                            <span>Web Development</span>
                        </div>
                    </div>
                </div>

                <!-- More Coming Soon -->
                <div class="project-card">
                    <div class="project-image">
                        <div class="project-placeholder">
                            <span class="coming-soon-icon">✨</span>
                            <div class="coming-soon-text">
                                <h3>More Coming Soon</h3>
                                <p>Stay tuned for future projects! Follow our <a href="../blog" class="blog-link">blog</a> for updates and behind-the-scenes content.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Resume Section -->
    <section id="resume" class="resume">
        <div class="resume-content reveal">
            <h2 class="section-title">Resume</h2>
            <div class="resume-grid">
                <div class="resume-section">
                    <h3>Education</h3>
                    <div class="resume-item">
                        <h4>Henderson County High School</h4>
                        <p>Information Technology Program</p>
                        <span class="year">2016 - 2019</span>
                        <ul>
                            <li>Principles of IT and Advanced IT coursework</li>
                            <li>Advanced Software & Game Development</li>
                            <li>Web Development</li>
                            <li>Cybersecurity</li>
                        </ul>
                    </div>
                    <div class="resume-item">
                        <h4>Independent Technology Development</h4>
                        <p>Self-Directed Learning & Project Experience</p>
                        <span class="year">2019 - Present</span>
                        <ul>
                            <li>Developed and deployed multiple full-stack web applications</li>
                            <li>Built practical solutions for real-world business challenges</li>
                            <li>Established strong foundation in modern web technologies</li>
                        </ul>
                    </div>
                    
                    <h3>Certifications</h3>
                    <div class="resume-item">
                        <h4>Professional Licensing</h4>
                        <p>IN Gaming License LEVEL 2</p>
                        <span class="year">Dec 2024 - Dec 2027</span>
                    </div>
                    <div class="resume-item">
                        <h4>CompTIA A+</h4>
                        <p>IT Fundamentals & Technical Support</p>
                        <span class="year">In Progress</span>
                    </div>
                </div>
                
                <div class="resume-section">
                    <h3>Professional Experience</h3>
                    <div class="resume-item">
                        <h4>Information Technology System Engineer</h4>
                        <p>Bally's Evansville Casino & Hotel</p>
                        <span class="year">Dec 2024 - Present</span>
                        <ul>
                            <li>Manage diverse IT infrastructure including AS400, Unix AIX, VMWare ESXi, and Windows Server systems</li>
                            <li>Implement and maintain backup/recovery procedures and security protocols</li>
                            <li>Administer Active Directory and manage system access controls</li>
                            <li>Monitor and optimize system performance across multiple platforms</li>
                            <li>Ensure compliance with gaming industry regulations and security standards</li>
                        </ul>
                    </div>
                    <div class="resume-item">
                        <h4>Chief of Operations & Principal Technician</h4>
                        <p>Root Labs US</p>
                        <span class="year">Oct 2024 - Present</span>
                        <ul>
                            <li>Lead operational strategies and technical service delivery</li>
                            <li>Provide comprehensive IT support and cybersecurity solutions</li>
                            <li>Develop and implement custom web solutions for clients</li>
                            <li>Manage network infrastructure and cloud services</li>
                            <li>Specialize in IT, networking, security, and web development</li>
                        </ul>
                    </div>
                    <div class="resume-item">
                        <h4>Service Manager & Computer Repair Technician</h4>
                        <p>Computers Plus</p>
                        <span class="year">Mar 2021 - May 2024</span>
                        <ul>
                            <li>Managed service operations and technical team</li>
                            <li>Performed advanced diagnostics and repairs on various devices</li>
                            <li>Provided technical support and customer service</li>
                            <li>Specialized in Windows, iOS, and electronics repair</li>
                        </ul>
                    </div>
                    <div class="resume-item">
                        <h4>Advanced Repair Agent</h4>
                        <p>Best Buy</p>
                        <span class="year">Nov 2019 - Mar 2021</span>
                        <ul>
                            <li>Performed complex repairs on computers and electronics</li>
                            <li>Provided technical support and troubleshooting</li>
                            <li>Maintained high customer satisfaction ratings</li>
                        </ul>
                    </div>
                </div>

                <div class="resume-section skills-section">
                    <h3>Technical Skills</h3>
                    <div class="skills-container">
                        <div class="skills-column">
                            <div class="skill-category">
                                <h4>Systems & Infrastructure</h4>
                                <div class="skill-tags">
                                    <span class="skill-tag">AS400 / Unix AIX</span>
                                    <span class="skill-tag">VMware ESXi</span>
                                    <span class="skill-tag">Windows Server</span>
                                    <span class="skill-tag">Active Directory</span>
                                    <span class="skill-tag">Linux</span>
                                </div>
                            </div>
                            <div class="skill-category">
                                <h4>Networking & Security</h4>
                                <div class="skill-tags">
                                    <span class="skill-tag">TCP/IP</span>
                                    <span class="skill-tag">DNS / DHCP</span>
                                    <span class="skill-tag">VPN</span>
                                    <span class="skill-tag">Data Encryption</span>
                                </div>
                            </div>
                        </div>
                        <div class="skills-column">
                            <div class="skill-category">
                                <h4>Development</h4>
                                <div class="skill-tags">
                                    <span class="skill-tag">Ruby on Rails</span>
                                    <span class="skill-tag">JavaScript</span>
                                    <span class="skill-tag">HTML/CSS</span>
                                    <span class="skill-tag">System Scripts</span>
                                    <span class="skill-tag">Documentation</span>
                                </div>
                            </div>
                            <div class="skill-category">
                                <h4>Professional Skills</h4>
                                <div class="skill-tags">
                                    <span class="skill-tag">Team Leadership</span>
                                    <span class="skill-tag">Project Management</span>
                                    <span class="skill-tag">Customer Relations</span>
                                    <span class="skill-tag">Technical Training</span>
                                    <span class="skill-tag">Problem Solving</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="resume-download">
                <a href="#" class="download-btn" onclick="generatePDF()">
                    <span>Download Resume</span>
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                        <polyline points="7 10 12 15 17 10"></polyline>
                        <line x1="12" y1="15" x2="12" y2="3"></line>
                    </svg>
                </a>
            </div>
        </div>
    </section>
    
    <!-- Contact Section -->
    <section id="contact" class="contact">
        <div class="contact-content reveal">
            <h2 class="section-title">Get In Touch</h2>
            <div class="contact-grid">
                <div class="contact-info">
                    <h3>Let's Connect</h3>
                    <p>Whether you have a project in mind or just want to chat, I'm always open to discussing new opportunities and ideas.</p>
                    <div class="social-links">
                        <a href="https://www.linkedin.com/in/kyle-king-53a86b1b0/" target="_blank" class="social-link">
                            <span class="social-icon">in</span>
                            <span class="social-text">LinkedIn</span>
                        </a>
                        <a href="https://github.com/kylechrisking" target="_blank" class="social-link">
                            <span class="social-icon">
                                <svg height="16" viewBox="0 0 16 16" width="16">
                                    <path fill="currentColor" d="M8 0C3.58 0 0 3.58 0 8c0 3.54 2.29 6.53 5.47 7.59.4.07.55-.17.55-.38 0-.19-.01-.82-.01-1.49-2.01.37-2.53-.49-2.69-.94-.09-.23-.48-.94-.82-1.13-.28-.15-.68-.52-.01-.53.63-.01 1.08.58 1.23.82.72 1.21 1.87.87 2.33.66.07-.52.28-.87.51-1.07-1.78-.2-3.64-.89-3.64-3.95 0-.87.31-1.59.82-2.15-.08-.2-.36-1.02.08-2.12 0 0 .67-.21 2.2.82.64-.18 1.32-.27 2-.27.68 0 1.36.09 2 .27 1.53-1.04 2.2-.82 2.2-.82.44 1.1.16 1.92.08 2.12.51.56.82 1.27.82 2.15 0 3.07-1.87 3.75-3.65 3.95.29.25.54.73.54 1.48 0 1.07-.01 1.93-.01 2.2 0 .21.15.46.55.38A8.013 8.013 0 0016 8c0-4.42-3.58-8-8-8z"></path>
                                </svg>
                            </span>
                            <span class="social-text">GitHub</span>
                        </a>
                        <a href="https://www.rootlabs.us" target="_blank" class="social-link">
                            <span class="social-icon">⚡</span>
                            <span class="social-text">Root Labs</span>
                        </a>
                    </div>
                </div>
                <div class="contact-form-container">
                    <form id="contactForm" class="contact-form">
                        <div class="form-group">
                            <input type="text" id="name" name="name" required>
                            <label for="name">Name</label>
                            <div class="form-line"></div>
                        </div>
                        <div class="form-group">
                            <input type="email" id="email" name="email" required>
                            <label for="email">Email</label>
                            <div class="form-line"></div>
                        </div>
                        <div class="form-group">
                            <textarea id="message" name="message" required></textarea>
                            <label for="message">Message</label>
                            <div class="form-line"></div>
                        </div>
                        <button type="submit" class="submit-btn">
                            <span class="btn-text">Send Message</span>
                            <span class="btn-icon">→</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer Section -->
    <footer class="footer">
        <div class="footer-content">
            <div class="footer-grid">
                <div class="footer-info">
                    <div class="footer-logo">KK</div>
                    <p>Building innovative solutions with passion and precision.</p>
                </div>
                <div class="footer-links">
                    <h4>Quick Links</h4>
                    <nav>
                        <a href="#home">Home</a>
                        <a href="#about">About</a>
                        <a href="#work">Work</a>
                        <a href="#resume">Resume</a>
                        <a href="#contact">Contact</a>
                    </nav>
                </div>
                <div class="footer-social">
                    <h4>Connect</h4>
                    <div class="social-icons">
                        <a href="https://www.linkedin.com/in/kyle-king-53a86b1b0/" target="_blank" class="social-icon">
                            <span>in</span>
                        </a>
                        <a href="https://github.com/kylechrisking" target="_blank" class="social-icon">
                            <span>
                                <svg height="16" viewBox="0 0 16 16" width="16">
                                    <path fill="currentColor" d="M8 0C3.58 0 0 3.58 0 8c0 3.54 2.29 6.53 5.47 7.59.4.07.55-.17.55-.38 0-.19-.01-.82-.01-1.49-2.01.37-2.53-.49-2.69-.94-.09-.23-.48-.94-.82-1.13-.28-.15-.68-.52-.01-.53.63-.01 1.08.58 1.23.82.72 1.21 1.87.87 2.33.66.07-.52.28-.87.51-1.07-1.78-.2-3.64-.89-3.64-3.95 0-.87.31-1.59.82-2.15-.08-.2-.36-1.02.08-2.12 0 0 .67-.21 2.2.82.64-.18 1.32-.27 2-.27.68 0 1.36.09 2 .27 1.53-1.04 2.2-.82 2.2-.82.44 1.1.16 1.92.08 2.12.51.56.82 1.27.82 2.15 0 3.07-1.87 3.75-3.65 3.95.29.25.54.73.54 1.48 0 1.07-.01 1.93-.01 2.2 0 .21.15.46.55.38A8.013 8.013 0 0016 8c0-4.42-3.58-8-8-8z"></path>
                                </svg>
                            </span>
                        </a>
                        <a href="https://www.rootlabs.us" target="_blank" class="social-icon">
                            <span>⚡</span>
                        </a>
                    </div>
                </div>
            </div>
            <div class="footer-bottom">
                <p class="copyright-text">&copy; 2024 Kyle King. All rights reserved.</p>
                <a href="../blog/admin/" class="admin-link" style="display: none;">Admin Panel</a>
                <div class="footer-easter-eggs">
                    <p class="footer-easter-egg">Press 'K' to discover something special</p>
                    <p class="footer-easter-egg">Try typing 'king' for a royal surprise 👑✨</p>
                </div>
            </div>
        </div>
    </footer>
    </main>

    <!-- Scripts -->
    <script src="js/main.js"></script>
    <script src="js/cursor.js"></script>
    <script src="js/particles.js"></script>
    <script src="js/animations.js"></script>
    <script src="js/resume.js"></script>
    <script src="js/easter-eggs.js"></script>
</body>
</html> 