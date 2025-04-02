/**
 * Animations JavaScript
 * Handles all animations, transitions, and dynamic effects
 */

document.addEventListener('DOMContentLoaded', () => {
    // Performance optimization: Store frequently accessed elements
    const loadingScreen = document.querySelector('.loading-screen');
    const revealElements = document.querySelectorAll('.reveal');
    const sectionTitles = document.querySelectorAll('.section-title');
    const characterPanel = document.querySelector('.character-panel');
    const projectGrid = document.querySelector('.project-grid');
    
    // Loading screen animation
    window.addEventListener('load', () => {
        // Use requestAnimationFrame for smoother animation
        requestAnimationFrame(() => {
            setTimeout(() => {
                loadingScreen.style.opacity = '0';
                setTimeout(() => {
                    loadingScreen.style.display = 'none';
                }, 500);
            }, 1500);
        });
    });

    // Scroll reveal functionality
    const revealThreshold = 150;
    
    function isElementInView(element, threshold) {
        const rect = element.getBoundingClientRect();
        return (rect.top < window.innerHeight - threshold);
    }
    
    function reveal() {
        // Performance optimization: Cache window height
        const windowHeight = window.innerHeight;
        
        // Handle reveal elements
        revealElements.forEach(element => {
            if (isElementInView(element, revealThreshold)) {
                element.classList.add('active');
            }
        });

        // Handle section titles
        sectionTitles.forEach(title => {
            if (isElementInView(title, revealThreshold)) {
                title.classList.add('visible');
            }
        });

        // Handle character panel
        if (characterPanel && isElementInView(characterPanel, revealThreshold)) {
            initializeCharacterStats();
        }
    }

    // Optimize scroll performance with throttle
    let scrollTimeout;
    window.addEventListener('scroll', () => {
        if (!scrollTimeout) {
            scrollTimeout = setTimeout(() => {
                reveal();
                scrollTimeout = null;
            }, 16); // ~60fps
        }
    });
    
    // Initial reveal check
    reveal();

    // Glitch text effect
    const glitchText = document.querySelector('.glitch-text');
    if (glitchText) {
        glitchText.setAttribute('data-text', glitchText.textContent);
    }

    // Smooth scroll with improved performance
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });

    // Project card hover effects with hardware acceleration
    const projectCards = document.querySelectorAll('.project-card');
    projectCards.forEach(card => {
        // Use transform3d for hardware acceleration
        card.style.transform = 'translate3d(0, 0, 0)';
        
        card.addEventListener('mouseenter', () => {
            requestAnimationFrame(() => {
                card.style.transform = 'translate3d(0, -10px, 0)';
            });
        });

        card.addEventListener('mouseleave', () => {
            requestAnimationFrame(() => {
                card.style.transform = 'translate3d(0, 0, 0)';
            });
        });
    });

    // Hidden project reveal with improved animation
    const hiddenProjects = document.querySelectorAll('.hidden-project');
    hiddenProjects.forEach(project => {
        project.addEventListener('click', () => {
            requestAnimationFrame(() => {
                project.classList.add('revealed');
            });
        });
    });

    // Intersection Observer for project cards
    if (projectGrid) {
        const projectObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    requestAnimationFrame(() => {
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translate3d(0, 0, 0)';
                    });
                }
            });
        }, { 
            threshold: 0.1,
            rootMargin: '50px'
        });

        projectGrid.querySelectorAll('.project-card').forEach(card => {
            card.style.opacity = '0';
            card.style.transform = 'translate3d(0, 20px, 0)';
            projectObserver.observe(card);
        });
    }

    // Skill bar animations
    function animateSkills() {
        const skillBars = document.querySelectorAll('.skill-progress');
        skillBars.forEach(bar => {
            const target = bar.style.width;
            bar.style.width = '0%';
            requestAnimationFrame(() => {
                setTimeout(() => {
                    bar.style.width = target;
                }, 300);
            });
        });
    }

    // Character stats animation with improved performance
    function initializeCharacterStats() {
        const progressFills = document.querySelectorAll('.progress-fill');
        const statValues = document.querySelectorAll('.stat-value');
        
        // Animate progress bars with requestAnimationFrame
        progressFills.forEach(fill => {
            const percentage = fill.getAttribute('data-percentage');
            requestAnimationFrame(() => {
                setTimeout(() => {
                    fill.style.width = `${percentage}%`;
                }, 200);
            });
        });
        
        // Optimize stat value animations
        statValues.forEach(stat => {
            const value = parseInt(stat.getAttribute('data-value'));
            if (value) {
                let current = 0;
                const increment = value / 15;
                const duration = 375; // 15 steps * 25ms
                const startTime = performance.now();
                
                function updateValue(currentTime) {
                    const elapsed = currentTime - startTime;
                    const progress = Math.min(elapsed / duration, 1);
                    
                    current = Math.floor(value * progress);
                    stat.textContent = current;
                    
                    if (progress < 1) {
                        requestAnimationFrame(updateValue);
                    }
                }
                
                requestAnimationFrame(updateValue);
            }
        });
    }

    // Initialize skill animations when skills section comes into view
    const skillsSection = document.querySelector('.skills-grid');
    if (skillsSection) {
        const skillsObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    animateSkills();
                    skillsObserver.unobserve(entry.target);
                }
            });
        }, { threshold: 0.2 });
        
        skillsObserver.observe(skillsSection);
    }
}); 