/**
 * Main JavaScript
 * Handles core functionality and interactions
 */

document.addEventListener('DOMContentLoaded', () => {
    // State management
    const state = {
        isMenuOpen: false,
        currentSection: '',
        konamiIndex: 0,
        copyrightClicks: 0,
        isFormSubmitting: false
    };

    // Configuration
    const config = {
        apiEndpoint: 'http://localhost:4567/contact',
        messageTimeout: 3000,
        scrollOffset: 300,
        konamiCode: ['ArrowUp', 'ArrowUp', 'ArrowDown', 'ArrowDown', 'ArrowLeft', 'ArrowRight', 'ArrowLeft', 'ArrowRight', 'b', 'a'],
        floatingEmoji: ['⚡', '💻', '🚀', '✨', '🔮', '⭐'],
        carouselAutoplayDelay: 5000
    };

    // Cache DOM elements
    const elements = {
        contactForm: document.getElementById('contactForm'),
        mobileNavToggle: document.querySelector('.mobile-nav-toggle'),
        mobileMenu: document.querySelector('.mobile-menu'),
        mobileLinks: document.querySelectorAll('.mobile-link'),
        sections: document.querySelectorAll('section'),
        navLinks: document.querySelectorAll('.nav-link, .mobile-link'),
        logo: document.querySelector('.logo'),
        copyrightText: document.querySelector('.copyright-text'),
        adminLink: document.querySelector('.admin-link'),
        projectGrid: document.querySelector('.project-grid'),
        formInputs: document.querySelectorAll('.form-group input, .form-group textarea')
    };

    /**
     * Contact Form Handling
     */
    function initContactForm() {
        if (!elements.contactForm) return;

        elements.contactForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            if (state.isFormSubmitting) return;

            const submitBtn = elements.contactForm.querySelector('.submit-btn');
            submitBtn.classList.add('loading');
            state.isFormSubmitting = true;

            const formData = {
                name: elements.contactForm.querySelector('#name').value.trim(),
                email: elements.contactForm.querySelector('#email').value.trim(),
                message: elements.contactForm.querySelector('#message').value.trim()
            };

            // Basic validation
            if (!formData.name || !formData.email || !formData.message) {
                showFormMessage('Please fill in all fields', 'error');
                submitBtn.classList.remove('loading');
                state.isFormSubmitting = false;
                return;
            }

            try {
                const response = await fetch(config.apiEndpoint, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(formData)
                });

                const data = await response.json();

                if (!response.ok) {
                    throw new Error(data.error || 'Failed to send message');
                }

                // Success handling
                elements.contactForm.reset();
                elements.contactForm.classList.add('form-success');
                showFormMessage(data.message || 'Message sent successfully!', 'success');

            } catch (error) {
                console.error('Error sending message:', error);
                showFormMessage(error.message, 'error');
            } finally {
                submitBtn.classList.remove('loading');
                state.isFormSubmitting = false;
            }
        });
    }

    /**
     * Show form message with type (success/error)
     */
    function showFormMessage(message, type) {
        const messageElement = document.createElement('div');
        messageElement.className = `form-message ${type}-message`;
        messageElement.textContent = message;
        elements.contactForm.appendChild(messageElement);

        // Remove message after timeout
        setTimeout(() => {
            messageElement.classList.add('fade-out');
            setTimeout(() => {
                messageElement.remove();
                elements.contactForm.classList.remove('form-success');
            }, 300);
        }, config.messageTimeout);
    }

    /**
     * Form Input Animations
     */
    function initFormInputs() {
        elements.formInputs.forEach(input => {
            const handleInputState = () => {
                const parent = input.parentElement;
                parent.classList.toggle('has-value', input.value.trim() !== '');
            };

            input.addEventListener('focus', () => {
                input.parentElement.classList.add('focused');
            });

            input.addEventListener('blur', () => {
                input.parentElement.classList.remove('focused');
                handleInputState();
            });

            // Initial state check
            handleInputState();
        });
    }

    /**
     * Mobile Navigation
     */
    function initMobileNav() {
        if (!elements.mobileNavToggle) return;

        const toggleMenu = () => {
            state.isMenuOpen = !state.isMenuOpen;
            elements.mobileNavToggle.classList.toggle('active', state.isMenuOpen);
            elements.mobileMenu.classList.toggle('active', state.isMenuOpen);
            document.body.classList.toggle('menu-open', state.isMenuOpen);
        };

        elements.mobileNavToggle.addEventListener('click', toggleMenu);

        elements.mobileLinks.forEach(link => {
            link.addEventListener('click', () => {
                if (state.isMenuOpen) toggleMenu();
            });
        });

        // Close menu on escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && state.isMenuOpen) toggleMenu();
        });
    }

    /**
     * Scroll-based Navigation Highlight
     */
    function initScrollHighlight() {
        const highlightNavigation = () => {
            const scrollPosition = window.scrollY + config.scrollOffset;

            elements.sections.forEach(section => {
                const sectionTop = section.offsetTop;
                const sectionHeight = section.clientHeight;
                const sectionId = section.getAttribute('id');

                if (scrollPosition >= sectionTop && scrollPosition < sectionTop + sectionHeight) {
                    if (state.currentSection !== sectionId) {
                        state.currentSection = sectionId;
                        elements.navLinks.forEach(link => {
                            link.classList.toggle('active', link.getAttribute('href').slice(1) === sectionId);
                        });
                    }
                }
            });
        };

        // Throttle scroll event
        let scrollTimeout;
        window.addEventListener('scroll', () => {
            if (!scrollTimeout) {
                scrollTimeout = setTimeout(() => {
                    highlightNavigation();
                    scrollTimeout = null;
                }, 100);
            }
        }, { passive: true });

        // Initial highlight
        highlightNavigation();
    }

    /**
     * Project Carousel
     */
    function initProjectCarousel() {
        if (!elements.projectGrid) return;

        const projects = elements.projectGrid.querySelectorAll('.project-card:not(.hidden-project)');
        if (projects.length <= 1) return;

        let currentIndex = 0;
        let autoplayInterval;

        const showProject = (index) => {
            projects.forEach((project, i) => {
                project.style.display = i === index ? 'block' : 'none';
                project.classList.toggle('active', i === index);
            });
        };

        // Create navigation
        const nav = document.createElement('div');
        nav.className = 'project-nav';
        nav.innerHTML = `
            <button class="prev" aria-label="Previous project">←</button>
            <div class="project-indicators"></div>
            <button class="next" aria-label="Next project">→</button>
        `;

        // Add indicators
        const indicators = nav.querySelector('.project-indicators');
        projects.forEach((_, i) => {
            const dot = document.createElement('button');
            dot.className = 'project-indicator';
            dot.setAttribute('aria-label', `Go to project ${i + 1}`);
            dot.addEventListener('click', () => goToProject(i));
            indicators.appendChild(dot);
        });

        elements.projectGrid.appendChild(nav);

        // Navigation functions
        const goToProject = (index) => {
            currentIndex = index;
            showProject(currentIndex);
            updateIndicators();
            resetAutoplay();
        };

        const prevProject = () => {
            goToProject((currentIndex - 1 + projects.length) % projects.length);
        };

        const nextProject = () => {
            goToProject((currentIndex + 1) % projects.length);
        };

        const updateIndicators = () => {
            const dots = indicators.querySelectorAll('.project-indicator');
            dots.forEach((dot, i) => {
                dot.classList.toggle('active', i === currentIndex);
            });
        };

        // Add event listeners
        nav.querySelector('.prev').addEventListener('click', prevProject);
        nav.querySelector('.next').addEventListener('click', nextProject);

        // Autoplay functionality
        const startAutoplay = () => {
            autoplayInterval = setInterval(nextProject, config.carouselAutoplayDelay);
        };

        const resetAutoplay = () => {
            clearInterval(autoplayInterval);
            startAutoplay();
        };

        // Touch support
        let touchStartX = 0;
        elements.projectGrid.addEventListener('touchstart', (e) => {
            touchStartX = e.touches[0].clientX;
        }, { passive: true });

        elements.projectGrid.addEventListener('touchend', (e) => {
            const touchEndX = e.changedTouches[0].clientX;
            const diff = touchStartX - touchEndX;

            if (Math.abs(diff) > 50) {
                if (diff > 0) nextProject();
                else prevProject();
            }
        }, { passive: true });

        // Initialize
        showProject(0);
        updateIndicators();
        startAutoplay();
    }

    /**
     * Hidden Admin Link
     */
    function initAdminLink() {
        if (!elements.copyrightText || !elements.adminLink) return;

        elements.copyrightText.addEventListener('click', () => {
            state.copyrightClicks++;
            if (state.copyrightClicks === 3) {
                elements.adminLink.style.display = 'inline';
                setTimeout(() => {
                    elements.adminLink.style.display = 'none';
                    state.copyrightClicks = 0;
                }, config.messageTimeout);
            }
        });
    }

    /**
     * Konami Code Easter Egg
     */
    function initKonamiCode() {
        document.addEventListener('keydown', (e) => {
            if (e.key === config.konamiCode[state.konamiIndex]) {
                state.konamiIndex++;
                
                if (state.konamiIndex === config.konamiCode.length) {
                    document.body.classList.add('matrix-mode');
                    setTimeout(() => {
                        document.body.classList.remove('matrix-mode');
                    }, 5000);
                    state.konamiIndex = 0;
                }
            } else {
                state.konamiIndex = 0;
            }
        });
    }

    /**
     * 'K' Key Easter Egg
     */
    function initFloatingElements() {
        document.addEventListener('keydown', (e) => {
            if (e.key.toLowerCase() === 'k') {
                for (let i = 0; i < 20; i++) {
                    const element = document.createElement('div');
                    element.className = 'floating-element';
                    element.style.left = `${Math.random() * 100}vw`;
                    element.style.animationDuration = `${Math.random() * 2 + 1}s`;
                    element.innerHTML = config.floatingEmoji[Math.floor(Math.random() * config.floatingEmoji.length)];
                    document.body.appendChild(element);

                    setTimeout(() => {
                        element.remove();
                    }, 3000);
                }
            }
        });
    }

    // Initialize all functionality
    initContactForm();
    initFormInputs();
    initMobileNav();
    initScrollHighlight();
    initProjectCarousel();
    initAdminLink();
    initKonamiCode();
    initFloatingElements();
}); 