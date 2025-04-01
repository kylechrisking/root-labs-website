// Animated Background
function createStar() {
    const star = document.createElement('div');
    star.className = 'star';
    star.style.width = Math.random() * 3 + 'px';
    star.style.height = star.style.width;
    star.style.left = Math.random() * window.innerWidth + 'px';
    star.style.top = Math.random() * window.innerHeight + 'px';
    document.getElementById('stars-background').appendChild(star);
    
    setTimeout(() => {
        star.remove();
    }, 3000);
}

function initStars() {
    setInterval(createStar, 100);
}

initStars();

// Custom cursor with smooth animation
const cursor = document.querySelector('.cursor');
const cursorFollower = document.querySelector('.cursor-follower');

let mouseX = 0;
let mouseY = 0;
let followerX = 0;
let followerY = 0;

document.addEventListener('mousemove', (e) => {
    mouseX = e.clientX;
    mouseY = e.clientY;
    
    // The dot (cursor) follows the mouse exactly
    cursor.style.left = mouseX + 'px';
    cursor.style.top = mouseY + 'px';
});

function updateCursor() {
    // Only the follower has smooth movement
    const deltaX = mouseX - followerX;
    const deltaY = mouseY - followerY;
    
    followerX += deltaX * 0.15;
    followerY += deltaY * 0.15;
    
    // Calculate the distance between follower and cursor
    const distance = Math.sqrt(Math.pow(mouseX - followerX, 2) + Math.pow(mouseY - followerY, 2));
    
    // Limit the maximum distance the follower can be from the cursor
    const maxDistance = 50;
    if (distance > maxDistance) {
        const angle = Math.atan2(mouseY - followerY, mouseX - followerX);
        followerX = mouseX - Math.cos(angle) * maxDistance;
        followerY = mouseY - Math.sin(angle) * maxDistance;
    }
    
    cursorFollower.style.left = followerX + 'px';
    cursorFollower.style.top = followerY + 'px';
    
    requestAnimationFrame(updateCursor);
}

updateCursor();

// Cursor hover effects
document.querySelectorAll('a, button, input, textarea').forEach(el => {
    el.addEventListener('mouseenter', () => {
        cursor.classList.add('cursor-hover');
        cursorFollower.classList.add('cursor-hover');
    });
    
    el.addEventListener('mouseleave', () => {
        cursor.classList.remove('cursor-hover');
        cursorFollower.classList.remove('cursor-hover');
    });
});

// Theme toggle
const themeToggle = document.getElementById('theme-toggle');
const htmlElement = document.documentElement;
const moonIcon = document.querySelector('.fa-moon');
const sunIcon = document.querySelector('.fa-sun');

let isDarkMode = true;

themeToggle.addEventListener('click', () => {
    isDarkMode = !isDarkMode;
    
    if (isDarkMode) {
        htmlElement.style.setProperty('--bg-color', '#0a0a0a');
        htmlElement.style.setProperty('--text-color', '#ffffff');
        moonIcon.style.display = 'inline-block';
        sunIcon.style.display = 'none';
    } else {
        htmlElement.style.setProperty('--bg-color', '#ffffff');
        htmlElement.style.setProperty('--text-color', '#0a0a0a');
        moonIcon.style.display = 'none';
        sunIcon.style.display = 'inline-block';
    }
});

// Check for saved theme
const savedTheme = localStorage.getItem('theme') || 'dark';
htmlElement.setAttribute('data-theme', savedTheme);

// Smooth scroll
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        document.querySelector(this.getAttribute('href')).scrollIntoView({
            behavior: 'smooth'
        });
    });
});

// Intersection Observer for fade-in animations
const observerOptions = {
    threshold: 0.1,
    rootMargin: '0px 0px -50px 0px'
};

const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.classList.add('visible');
        }
    });
}, observerOptions);

document.querySelectorAll('.fade-in').forEach(element => {
    observer.observe(element);
});

// Character stats animation
const stats = document.querySelectorAll('.stat-fill');
const statsSection = document.querySelector('#character-stats');

const statsObserver = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            stats.forEach(stat => {
                const targetWidth = stat.getAttribute('data-width');
                setTimeout(() => {
                    stat.style.width = targetWidth + '%';
                }, 200);
            });
        }
    });
}, { threshold: 0.5 });

statsObserver.observe(statsSection);

// Easter egg
let keySequence = '';
const secretCode = 'king';

document.addEventListener('keydown', (e) => {
    keySequence += e.key.toLowerCase();
    
    if (keySequence.includes(secretCode)) {
        const message = document.createElement('div');
        message.textContent = '👑 Long live the King! 👑';
        message.style.position = 'fixed';
        message.style.top = '50%';
        message.style.left = '50%';
        message.style.transform = 'translate(-50%, -50%)';
        message.style.background = 'var(--accent-color)';
        message.style.padding = '1rem 2rem';
        message.style.borderRadius = '5px';
        message.style.zIndex = '9999';
        message.style.animation = 'fadeInOut 2s forwards';
        
        document.body.appendChild(message);
        
        setTimeout(() => {
            document.body.removeChild(message);
        }, 2000);
        
        keySequence = '';
    }
    
    if (keySequence.length > 10) {
        keySequence = '';
    }
});

// Contact form handling
const contactForm = document.querySelector('.contact-form');

contactForm.addEventListener('submit', async (e) => {
    e.preventDefault();
    
    const formData = new FormData(contactForm);
    const data = {
        name: formData.get('name'),
        email: formData.get('email'),
        message: formData.get('message')
    };
    
    try {
        const response = await fetch('/api/contact', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        });
        
        if (response.ok) {
            showNotification('Message sent successfully!', 'success');
            contactForm.reset();
        } else {
            showNotification('Failed to send message. Please try again.', 'error');
        }
    } catch (error) {
        showNotification('An error occurred. Please try again later.', 'error');
    }
});

function showNotification(message, type) {
    const notification = document.createElement('div');
    notification.className = `notification ${type}`;
    notification.textContent = message;
    notification.style.background = type === 'success' ? 'var(--accent-color)' : '#ff4444';
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.remove();
    }, 3000);
}

// Add keyframe animation for notifications
const style = document.createElement('style');
style.textContent = `
    @keyframes fadeInOut {
        0% { opacity: 0; transform: translate(-50%, -60%); }
        10% { opacity: 1; transform: translate(-50%, -50%); }
        90% { opacity: 1; transform: translate(-50%, -50%); }
        100% { opacity: 0; transform: translate(-50%, -40%); }
    }
    
    .notification {
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 1rem 2rem;
        border-radius: 5px;
        color: white;
        z-index: 9999;
        animation: fadeInOut 3s forwards;
    }
`;

document.head.appendChild(style); 