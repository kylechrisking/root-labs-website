<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kyle King | Portfolio</title>
    <link rel="stylesheet" href="css/portfolio.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div id="stars-background"></div>
    <div class="cursor"></div>
    <div class="cursor-follower"></div>
    
    <nav class="navbar">
        <a href="#" class="logo">KK</a>
        <ul class="nav-links">
            <li><a href="#home">Home</a></li>
            <li><a href="#about">About</a></li>
            <li><a href="#work">Work</a></li>
            <li><a href="#resume">Resume</a></li>
            <li><a href="#contact">Contact</a></li>
            <li><a href="/blog">Blog</a></li>
            <li>
                <button id="theme-toggle" class="theme-toggle">
                    <i class="fas fa-moon"></i>
                    <i class="fas fa-sun"></i>
                </button>
            </li>
        </ul>
    </nav>
    
    <section id="home" class="hero fade-in">
        <h1>Kyle King</h1>
        <div class="hero-text">
            <p class="title">IT Systems Engineer & Full Stack Developer</p>
            <p class="subtitle">Crafting <span class="accent">innovative solutions</span> through code</p>
        </div>
        <div class="scroll-indicator">
            <p class="scroll-text">Scroll to Explore</p>
            <div class="scroll-arrow">
                <i class="fas fa-chevron-down"></i>
            </div>
        </div>
    </section>
    
    <section id="about">
        <div class="about-left">
            <h2 class="about-title">About Me</h2>
            <div class="about-content">
                <p>IT Systems Engineer & Full Stack Developer with a passion for creating innovative solutions and a strong foundation in web development since 2019. Currently serving as an Information Technology System Engineer at Bally's Evansville Casino & Hotel and Chief of Operations at Root Labs US.</p>
                <div class="about-expertise">
                    <h3 class="expertise-title">My technical expertise includes:</h3>
                    <div class="expertise-grid">
                        <div class="expertise-item">Full Stack Development</div>
                        <div class="expertise-item">System Administration</div>
                        <div class="expertise-item">Network Infrastructure</div>
                        <div class="expertise-item">Cloud Services</div>
                        <div class="expertise-item">Cybersecurity</div>
                        <div class="expertise-item">VMware Virtualization</div>
                        <div class="expertise-item">Windows Server</div>
                        <div class="expertise-item">Active Directory</div>
                        <div class="expertise-item">Ruby on Rails</div>
                        <div class="expertise-item">JavaScript Development</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="about-cards">
            <div class="about-card">
                <h3>Full Stack Developer</h3>
                <p>Building web applications and solutions</p>
                <p class="date">2019 - Present</p>
            </div>
            <div class="about-card">
                <h3>Root Labs</h3>
                <p>Co-founder & Lead Developer</p>
                <p class="date">2024 - Present</p>
            </div>
        </div>
    </section>
    
    <section id="character-stats">
        <h2 class="stats-title">CHARACTER STATS</h2>
        
        <div class="level-indicator">
            <div class="level-icon">
                <i class="fas fa-layer-group"></i>
            </div>
            <div class="level-info">
                <span class="level-label">LEVEL</span>
                <span class="level-number">24</span>
            </div>
        </div>

        <div class="role-badges">
            <span class="role-badge">Full Stack Engineer</span>
            <span class="role-badge">Systems Engineer</span>
            <span class="role-badge">Project Manager</span>
            <span class="role-badge">Team Lead</span>
        </div>

        <div class="stats-container">
            <div class="stat-item">
                <div class="stat-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-info">
                    <h3>QUEST COMPLETION</h3>
                    <div class="stat-bar">
                        <div class="stat-fill" style="width: 85%;"></div>
                    </div>
                    <span class="stat-detail">projects completed: 5</span>
                </div>
            </div>

            <div class="stat-item">
                <div class="stat-icon">
                    <i class="fas fa-coffee"></i>
                </div>
                <div class="stat-info">
                    <h3>ENERGY SOURCE</h3>
                    <div class="stat-bar">
                        <div class="stat-fill" style="width: 90%;"></div>
                    </div>
                    <span class="stat-detail">3-4 cups/day</span>
                </div>
            </div>

            <div class="stat-item">
                <div class="stat-icon">
                    <i class="fas fa-bug"></i>
                </div>
                <div class="stat-info">
                    <h3>DEBUG POWER</h3>
                    <div class="stat-bar">
                        <div class="stat-fill" style="width: 75%;"></div>
                    </div>
                    <span class="stat-detail">over 500 hours</span>
                </div>
            </div>

            <div class="stat-item">
                <div class="stat-icon">
                    <i class="fas fa-brain"></i>
                </div>
                <div class="stat-info">
                    <h3>LEARNING RATE</h3>
                    <div class="stat-bar">
                        <div class="stat-fill" style="width: 95%;"></div>
                    </div>
                    <span class="stat-detail">advanced proficiency</span>
                </div>
            </div>
        </div>

        <div class="scroll-more">
            <span class="scroll-text">More Below</span>
            <i class="fas fa-chevron-down scroll-arrow"></i>
        </div>
    </section>
    
    <section id="work">
        <h2 class="work-title">Featured Work</h2>
        
        <div class="projects-grid">
            <a href="https://www.rootlabs.us/" target="_blank" class="project-card">
                <div class="project-image">
                    <img src="images/root-labs.jpg" alt="Root Labs">
                </div>
                <div class="project-info">
                    <h3>Root Labs</h3>
                    <p>Professional IT consulting firm website with modern design and functionality.</p>
                    <div class="project-tags">
                        <span class="tag">HTML</span>
                        <span class="tag">CSS</span>
                        <span class="tag">JavaScript</span>
                        <span class="tag">PHP</span>
                    </div>
                </div>
            </a>

            <a href="https://github.com/kylechrisking/Flutter" target="_blank" class="project-card">
                <div class="project-image">
                    <img src="images/flutter.jpg" alt="Flutter">
                </div>
                <div class="project-info">
                    <h3>Flutter</h3>
                    <p>[Senior Project]: A Twitter clone built with Ruby on Rails, showcasing social media functionality and web development skills.</p>
                    <div class="project-tags">
                        <span class="tag">Ruby</span>
                        <span class="tag">Rails</span>
                        <span class="tag">Web Development</span>
                    </div>
                </div>
            </a>

            <a href="https://github.com/kylechrisking/taskly" target="_blank" class="project-card">
                <div class="project-image">
                    <img src="images/taskly.jpg" alt="Taskly">
                </div>
                <div class="project-info">
                    <h3>Taskly</h3>
                    <p>Web-based To-Do-List application for efficient task management and organization.</p>
                    <div class="project-tags">
                        <span class="tag">JavaScript</span>
                        <span class="tag">HTML</span>
                        <span class="tag">CSS</span>
                    </div>
                </div>
            </a>

            <a href="https://github.com/kylechrisking/it-empire-idle" target="_blank" class="project-card">
                <div class="project-image">
                    <img src="images/it-empire.jpg" alt="IT Empire Idle">
                </div>
                <div class="project-info">
                    <h3>IT Empire Idle</h3>
                    <p>An idle game about building your IT empire from the ground up.</p>
                    <div class="project-tags">
                        <span class="tag">JavaScript</span>
                        <span class="tag">HTML</span>
                        <span class="tag">CSS</span>
                    </div>
                </div>
            </a>

            <a href="https://github.com/kylechrisking/Rootbound" target="_blank" class="project-card">
                <div class="project-image">
                    <img src="images/rootbound.jpg" alt="Rootbound">
                </div>
                <div class="project-info">
                    <h3>Rootbound</h3>
                    <p>A text-based adventure game exploring the depths of a mysterious digital world.</p>
                    <div class="project-tags">
                        <span class="tag">Python</span>
                        <span class="tag">Game Development</span>
                    </div>
                </div>
            </a>

            <a href="/blog" class="project-card">
                <div class="project-image">
                    <img src="images/blog.jpg" alt="Blog">
                </div>
                <div class="project-info">
                    <h3>Blog</h3>
                    <p>Personal blog sharing insights about technology, development, and IT infrastructure.</p>
                    <div class="project-tags">
                        <span class="tag">Writing</span>
                        <span class="tag">Tech</span>
                        <span class="tag">Tutorials</span>
                    </div>
                </div>
            </a>
        </div>
    </section>
    
    <section id="resume">
        <h2 class="resume-title">Resume</h2>
        
        <div class="resume-grid">
            <div class="resume-column">
                <h3 class="section-title">Education</h3>
                
                <div class="education-item">
                    <h4>Henderson County High School</h4>
                    <p class="program">Information Technology Program</p>
                    <p class="date">2016 - 2019</p>
                    <ul class="bullet-list">
                        <li>Principles of IT and Advanced IT coursework</li>
                        <li>Advanced Software & Game Development</li>
                        <li>Web Development</li>
                        <li>Cybersecurity</li>
                    </ul>
                </div>

                <div class="education-item">
                    <h4>Independent Technology Development</h4>
                    <p class="program">Self-Directed Learning & Project Experience</p>
                    <p class="date">2019 - Present</p>
                    <ul class="bullet-list">
                        <li>Developed and deployed multiple full-stack web applications</li>
                        <li>Built practical solutions for real-world business challenges</li>
                        <li>Established strong foundation in modern web technologies</li>
                    </ul>
                </div>
            </div>

            <div class="resume-column">
                <h3 class="section-title">Professional Experience</h3>
                
                <div class="experience-item">
                    <h4>Information Technology System Engineer</h4>
                    <p class="company">Bally's Evansville Casino & Hotel</p>
                    <p class="date">Dec 2024 - Present</p>
                    <ul class="bullet-list">
                        <li>Manage diverse IT infrastructure including AS400, Unix AIX, VMWare ESXi, and Windows Server systems</li>
                        <li>Implement and maintain backup/recovery procedures and security protocols</li>
                        <li>Administer Active Directory and manage system access controls</li>
                        <li>Monitor and optimize system performance across multiple platforms</li>
                        <li>Ensure compliance with gaming industry regulations and security standards</li>
                    </ul>
                </div>

                <div class="experience-item">
                    <h4>Chief of Operations & Principal Technician</h4>
                    <p class="company">Root Labs US</p>
                    <p class="date">Oct 2024 - Present</p>
                    <ul class="bullet-list">
                        <li>Lead operational strategies and technical service delivery</li>
                        <li>Provide comprehensive IT support and cybersecurity solutions</li>
                        <li>Develop and implement custom web solutions for clients</li>
                        <li>Manage network infrastructure and cloud services</li>
                        <li>Specialize in IT, networking, security, and web development</li>
                    </ul>
                </div>

                <div class="experience-item">
                    <h4>Service Manager & Computer Repair Technician</h4>
                    <p class="company">Root Labs US</p>
                    <p class="date">Oct 2024 - Present</p>
                </div>
            </div>
        </div>
    </section>
    
    <section id="contact">
        <h2 class="contact-title">Get In Touch</h2>
        
        <div class="contact-container">
            <div class="contact-left">
                <h3>Let's Connect</h3>
                <p>Whether you have a project in mind or just want to chat, I'm always open to discussing new opportunities and ideas.</p>
                
                <div class="email-disclaimer">
                    <p>Messages sent through this form will be delivered to my personal email address.</p>
                    <p class="note">For Root Labs business inquiries, please visit <a href="https://rootlabs.us/contact" target="_blank">rootlabs.us/contact</a></p>
                </div>
                
                <div class="social-links">
                    <a href="https://linkedin.com/in/kylechrisking" target="_blank" class="social-link">
                        <i class="fab fa-linkedin"></i>
                        <span>LinkedIn</span>
                    </a>
                    <a href="https://github.com/kylechrisking" target="_blank" class="social-link">
                        <i class="fab fa-github"></i>
                        <span>GitHub</span>
                    </a>
                    <a href="https://www.rootlabs.us" target="_blank" class="social-link">
                        <i class="fas fa-bolt"></i>
                        <span>Root Labs</span>
                    </a>
                </div>
            </div>
            
            <div class="contact-right">
                <form class="contact-form" action="send-email.php" method="POST">
                    <input type="hidden" name="to_email" value="kylechrisking@gmail.com">
                    <div class="form-group">
                        <input type="text" id="name" name="name" placeholder="Name" required>
                    </div>
                    <div class="form-group">
                        <input type="email" id="email" name="email" placeholder="Email" required>
                    </div>
                    <div class="form-group">
                        <textarea id="message" name="message" placeholder="Message" required></textarea>
                    </div>
                    <button type="submit" class="submit-btn">
                        Send Message
                        <i class="fas fa-arrow-right"></i>
                    </button>
                </form>
            </div>
        </div>
    </section>
    
    <footer class="footer">
        <div class="footer-content">
            <div class="footer-left">
                <div class="footer-logo">KK</div>
                <p class="footer-tagline">Building innovative solutions with passion and precision.</p>
                <p class="copyright">&copy; 2024 Kyle King. All rights reserved.</p>
            </div>
            
            <div class="footer-middle">
                <h3>Quick Links</h3>
                <ul class="footer-links">
                    <li><a href="#home">Home</a></li>
                    <li><a href="#about">About</a></li>
                    <li><a href="#work">Work</a></li>
                    <li><a href="#resume">Resume</a></li>
                    <li><a href="#contact">Contact</a></li>
                </ul>
            </div>
            
            <div class="footer-right">
                <h3>Connect</h3>
                <div class="social-icons">
                    <a href="https://linkedin.com/in/kylechrisking" target="_blank" class="social-icon">
                        <i class="fab fa-linkedin"></i>
                    </a>
                    <a href="https://github.com/kylechrisking" target="_blank" class="social-icon">
                        <i class="fab fa-github"></i>
                    </a>
                    <a href="https://www.rootlabs.us" target="_blank" class="social-icon">
                        <i class="fas fa-bolt"></i>
                    </a>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <div class="footer-dot"></div>
            <p class="easter-egg">Press 'K' to discover something special</p>
            <p class="easter-egg-hint">Try typing 'king' for a royal surprise ✨</p>
        </div>
    </footer>
    
    <script src="js/portfolio.js"></script>
</body>
</html> 