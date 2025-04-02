<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kyle King | Portfolio</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="assets/images/favicon.ico">
    <link rel="apple-touch-icon" sizes="180x180" href="assets/images/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="assets/images/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="assets/images/favicon-16x16.png">
    <link rel="manifest" href="assets/site.webmanifest">
    
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
<body>
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
                    <p>My technical expertise includes:</p>
                    <ul class="skills-list">
                        <li>Full Stack Development</li>
                        <li>System Administration</li>
                        <li>Network Infrastructure</li>
                        <li>Cloud Services</li>
                        <li>Cybersecurity</li>
                        <li>VMware Virtualization</li>
                        <li>Windows Server</li>
                        <li>Active Directory</li>
                        <li>Ruby on Rails</li>
                        <li>JavaScript Development</li>
                    </ul>
                </div>
                <div class="about-cards">
                    <div class="card">
                        <h3>Full Stack Developer</h3>
                        <p>Building web applications and solutions</p>
                        <span class="year">2019 - Present</span>
                    </div>
                    <div class="card">
                        <h3>Root Labs</h3>
                        <p>Co-founder & Lead Developer</p>
                        <span class="year">2024 - Present</span>
                    </div>
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
                                <span class="stat-label">Level</span>
                                <span class="stat-value" data-value="24">0</span>
                            </div>
                        </div>
                        <div class="specializations">
                            <span class="spec-tag">Full Stack Engineer</span>
                            <span class="spec-tag">Systems Engineer</span>
                            <span class="spec-tag">Project Manager</span>
                            <span class="spec-tag">Team Lead</span>
                        </div>
                    </div>

                    <div class="stat-group achievements">
                        <div class="stat-item completion-rate" data-tooltip="Projects successfully completed and deployed">
                            <div class="stat-icon">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                                    <polyline points="22 4 12 14.01 9 11.01"></polyline>
                                </svg>
                            </div>
                            <div class="stat-info">
                                <span class="stat-label">Quest Completion</span>
                                <div class="progress-bar">
                                    <div class="progress-fill" data-percentage="95"></div>
                                    <div class="stat-detail">projects completed: 5</div>
                                </div>
                            </div>
                        </div>
                        <div class="stat-item energy-stats" data-tooltip="Daily espresso consumption for optimal coding performance">
                            <div class="stat-icon">☕</div>
                            <div class="stat-info">
                                <span class="stat-label">Energy Source</span>
                                <div class="progress-bar">
                                    <div class="progress-fill" data-percentage="75"></div>
                                    <div class="stat-detail">3-4 cups/day</div>
                                </div>
                            </div>
                        </div>
                        <div class="stat-item" data-tooltip="Hours spent debugging and optimizing code">
                            <div class="stat-icon">⚔️</div>
                            <div class="stat-info">
                                <span class="stat-label">Debug Power</span>
                                <div class="progress-bar">
                                    <div class="progress-fill" data-percentage="80"></div>
                                    <div class="stat-detail">over 500 hours</div>
                                </div>
                            </div>
                        </div>
                        <div class="stat-item" data-tooltip="Ability to learn and adapt to new technologies">
                            <div class="stat-icon">📚</div>
                            <div class="stat-info">
                                <span class="stat-label">Learning Rate</span>
                                <div class="progress-bar">
                                    <div class="progress-fill" data-percentage="90"></div>
                                    <div class="stat-detail">advanced proficiency</div>
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
                        <img src="assets/images/rootlabs-preview.png" alt="Root Labs Website Preview">
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

                <!-- Flutter Project -->
                <div class="project-card">
                    <div class="project-image">
                        <img src="assets/images/flutter-preview.png" alt="Flutter Project Preview">
                        <div class="project-overlay">
                            <div class="project-links">
                                <a href="https://github.com/kylechrisking/Flutter" target="_blank" class="project-link">View Code</a>
                            </div>
                        </div>
                    </div>
                    <div class="project-info">
                        <h3>Flutter</h3>
                        <p>[Senior Project]: A Twitter clone built with Ruby on Rails, showcasing social media functionality and web development skills.</p>
                        <div class="project-tech">
                            <span>Ruby</span>
                            <span>Rails</span>
                            <span>Web Development</span>
                        </div>
                    </div>
                </div>

                <!-- Taskly Project -->
                <div class="project-card">
                    <div class="project-image">
                        <img src="assets/images/taskly-preview.png" alt="Taskly Project Preview">
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
                        <img src="assets/images/it-empire-preview.png" alt="IT Empire Idle Game Preview">
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

                <!-- Rootbound Project -->
                <div class="project-card">
                    <div class="project-image">
                        <img src="assets/images/rootbound-preview.png" alt="Rootbound Game Preview">
                        <div class="project-overlay">
                            <div class="project-links">
                                <a href="https://github.com/kylechrisking/Rootbound" target="_blank" class="project-link">View Code</a>
                                <a href="https://www.rootlabs.us" target="_blank" class="project-link">Visit Site</a>
                            </div>
                        </div>
                    </div>
                    <div class="project-info">
                        <h3>Rootbound</h3>
                        <p>A cozy farming and exploration game where you play as a magical tree spirit, featuring dynamic quest systems, critter interactions, and museum collection mechanics.</p>
                        <div class="project-tech">
                            <span>Unity</span>
                            <span>C#</span>
                            <span>Game Development</span>
                            <span>UI Toolkit</span>
                        </div>
                    </div>
                </div>

                <!-- Blog Project -->
                <div class="project-card">
                    <div class="project-image">
                        <img src="assets/images/blog-preview.png" alt="Personal Blog Preview">
                        <div class="project-overlay">
                            <div class="project-links">
                                <a href="../blog" target="_blank" class="project-link">View Live</a>
                                <a href="https://github.com/kylechrisking/blog" target="_blank" class="project-link">View Code</a>
                            </div>
                        </div>
                    </div>
                    <div class="project-info">
                        <h3>Personal Blog</h3>
                        <p>A custom-built blog platform with admin panel, markdown support, and AI-powered content generation.</p>
                        <div class="project-tech">
                            <span>PHP</span>
                            <span>MySQL</span>
                            <span>JavaScript</span>
                            <span>OpenAI API</span>
                        </div>
                    </div>
                </div>

                <!-- More Coming Soon -->
                <div class="project-card hidden-project">
                    <div class="project-image">
                        <div class="project-placeholder">
                            <span class="coming-soon-icon">✨</span>
                            <div class="coming-soon-text">
                                <h3>More Coming Soon</h3>
                                <p>Stay tuned for future projects!</p>
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

                <div class="resume-section">
                    <h3>Technical Skills</h3>
                    <div class="skills-grid">
                        <div class="skill-category">
                            <h4>Systems & Infrastructure</h4>
                            <ul>
                                <li>
                                    <span>AS400 / Unix AIX</span>
                                    <div class="skill-bar">
                                        <div class="skill-progress" style="width: 50%"></div>
                                    </div>
                                </li>
                                <li>
                                    <span>VMware ESXi</span>
                                    <div class="skill-bar">
                                        <div class="skill-progress" style="width: 50%"></div>
                                    </div>
                                </li>
                                <li>
                                    <span>Windows Server</span>
                                    <div class="skill-bar">
                                        <div class="skill-progress" style="width: 75%"></div>
                                    </div>
                                </li>
                                <li>
                                    <span>Active Directory</span>
                                    <div class="skill-bar">
                                        <div class="skill-progress" style="width: 100%"></div>
                                    </div>
                                </li>
                                <li>
                                    <span>Linux</span>
                                    <div class="skill-bar">
                                        <div class="skill-progress" style="width: 90%"></div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                        <div class="skill-category">
                            <h4>Networking & Security</h4>
                            <ul>
                                <li>
                                    <span>TCP/IP</span>
                                    <div class="skill-bar">
                                        <div class="skill-progress" style="width: 90%"></div>
                                    </div>
                                </li>
                                <li>
                                    <span>DNS / DHCP</span>
                                    <div class="skill-bar">
                                        <div class="skill-progress" style="width: 90%"></div>
                                    </div>
                                </li>
                                <li>
                                    <span>VPN</span>
                                    <div class="skill-bar">
                                        <div class="skill-progress" style="width: 95%"></div>
                                    </div>
                                </li>
                                <li>
                                    <span>Data Encryption</span>
                                    <div class="skill-bar">
                                        <div class="skill-progress" style="width: 90%"></div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                        <div class="skill-category">
                            <h4>Development</h4>
                            <ul>
                                <li>
                                    <span>Ruby on Rails</span>
                                    <div class="skill-bar">
                                        <div class="skill-progress" style="width: 40%"></div>
                                    </div>
                                </li>
                                <li>
                                    <span>JavaScript</span>
                                    <div class="skill-bar">
                                        <div class="skill-progress" style="width: 85%"></div>
                                    </div>
                                </li>
                                <li>
                                    <span>HTML/CSS</span>
                                    <div class="skill-bar">
                                        <div class="skill-progress" style="width: 95%"></div>
                                    </div>
                                </li>
                                <li>
                                    <span>System Scripts</span>
                                    <div class="skill-bar">
                                        <div class="skill-progress" style="width: 60%"></div>
                                    </div>
                                </li>
                                <li>
                                    <span>Documentation</span>
                                    <div class="skill-bar">
                                        <div class="skill-progress" style="width: 100%"></div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                        <div class="skill-category">
                            <h4>Professional Skills</h4>
                            <ul>
                                <li>
                                    <span>Team Leadership</span>
                                    <div class="skill-bar">
                                        <div class="skill-progress" style="width: 90%"></div>
                                    </div>
                                </li>
                                <li>
                                    <span>Project Management</span>
                                    <div class="skill-bar">
                                        <div class="skill-progress" style="width: 85%"></div>
                                    </div>
                                </li>
                                <li>
                                    <span>Customer Relations</span>
                                    <div class="skill-bar">
                                        <div class="skill-progress" style="width: 100%"></div>
                                    </div>
                                </li>
                                <li>
                                    <span>Technical Training</span>
                                    <div class="skill-bar">
                                        <div class="skill-progress" style="width: 95%"></div>
                                    </div>
                                </li>
                                <li>
                                    <span>Problem Solving</span>
                                    <div class="skill-bar">
                                        <div class="skill-progress" style="width: 95%"></div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="skills-tooltip">
                        <p><small>* Bars represent proficiency level from basic (25%) to expert (100%)</small></p>
                    </div>
                </div>

                <div class="resume-section">
                    <h3>Certifications</h3>
                    <div class="resume-item">
                        <h4>Professional Licensing</h4>
                        <p>IN Gaming License LEVEL 2</p>
                        <span class="year">Dec 2024 - Dec 2027</span>
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
                    <p class="footer-easter-egg">Try typing 'king' for a royal surprise ✨</p>
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