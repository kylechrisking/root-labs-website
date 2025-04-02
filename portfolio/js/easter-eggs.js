/**
 * Easter Eggs JavaScript
 * Handles fun interactive surprises and animations
 */

document.addEventListener('DOMContentLoaded', () => {
    // State management
    const state = {
        kingBuffer: '',
        isAnimating: false,
        lastKeyTime: 0
    };

    // Configuration
    const config = {
        kingCode: 'king',
        bufferTimeout: 1000,
        scrollDelay: 500,
        crownDuration: 3000,
        sparkleCount: 20,
        sparkleColors: ['#FFD700', '#FFA500', '#FF8C00', '#FFE4B5', '#FFDAB9'],
        sparkleSize: {
            min: 5,
            max: 15
        }
    };

    // Cache DOM elements
    const heroSection = document.querySelector('.hero');
    const crown = document.querySelector('.crown');

    // Exit if required elements aren't found
    if (!heroSection || !crown) return;

    // Create sparkle container for better performance
    const sparkleContainer = document.createElement('div');
    sparkleContainer.className = 'sparkle-container';
    heroSection.appendChild(sparkleContainer);

    /**
     * Handle keydown events for the 'king' easter egg
     */
    function handleKeydown(e) {
        const currentTime = Date.now();
        
        // Clear buffer if timeout exceeded
        if (currentTime - state.lastKeyTime > config.bufferTimeout) {
            state.kingBuffer = '';
        }
        
        // Update state
        state.lastKeyTime = currentTime;
        state.kingBuffer += e.key.toLowerCase();
        
        // Keep buffer size manageable
        if (state.kingBuffer.length > config.kingCode.length) {
            state.kingBuffer = state.kingBuffer.slice(-config.kingCode.length);
        }
        
        // Check for activation
        if (state.kingBuffer.includes(config.kingCode) && !state.isAnimating) {
            activateKingEasterEgg();
        }
    }

    /**
     * Activate the king easter egg animation
     */
    function activateKingEasterEgg() {
        state.isAnimating = true;
        
        // Smooth scroll to top
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
        
        // Show crown after scroll
        setTimeout(() => {
            requestAnimationFrame(() => {
                crown.classList.remove('hidden');
                crown.classList.add('show');
                
                // Create sparkles with staggered animation
                createSparkleEffect();
                
                // Reset after animation
                setTimeout(() => {
                    crown.classList.remove('show');
                    crown.classList.add('hidden');
                    state.isAnimating = false;
                }, config.crownDuration);
            });
        }, config.scrollDelay);
        
        // Reset buffer
        state.kingBuffer = '';
    }

    /**
     * Create sparkle effect with optimized animations
     */
    function createSparkleEffect() {
        // Clear existing sparkles
        sparkleContainer.innerHTML = '';
        
        // Create sparkles with staggered animation
        for (let i = 0; i < config.sparkleCount; i++) {
            setTimeout(() => {
                createSparkle();
            }, i * 50); // Stagger creation
        }
    }

    /**
     * Create individual sparkle with random properties
     */
    function createSparkle() {
        const sparkle = document.createElement('div');
        sparkle.className = 'sparkle';
        
        // Random position
        const left = Math.random() * 100;
        const top = Math.random() * 100;
        
        // Random size
        const size = Math.random() * (config.sparkleSize.max - config.sparkleSize.min) + config.sparkleSize.min;
        
        // Random color
        const color = config.sparkleColors[Math.floor(Math.random() * config.sparkleColors.length)];
        
        // Random rotation
        const rotation = Math.random() * 360;
        
        // Apply styles with hardware acceleration
        sparkle.style.cssText = `
            left: ${left}%;
            top: ${top}%;
            width: ${size}px;
            height: ${size}px;
            background-color: ${color};
            transform: translate3d(0, 0, 0) rotate(${rotation}deg);
        `;
        
        // Add to container
        sparkleContainer.appendChild(sparkle);
        
        // Trigger animation
        requestAnimationFrame(() => {
            sparkle.style.opacity = '0';
            sparkle.style.transform = `translate3d(${Math.random() * 100 - 50}px, ${Math.random() * 100 - 50}px, 0) rotate(${rotation + 180}deg)`;
        });
        
        // Cleanup
        setTimeout(() => {
            sparkle.remove();
        }, 1000);
    }

    // Additional easter eggs
    function setupAdditionalEasterEggs() {
        // Hidden project easter egg
        const hiddenProjects = document.querySelectorAll('.hidden-project');
        hiddenProjects.forEach(project => {
            project.addEventListener('click', () => {
                if (!project.classList.contains('revealed')) {
                    project.classList.add('revealed');
                    createSparkleEffect();
                }
            });
        });

        // Logo click easter egg
        const logo = document.querySelector('.logo');
        if (logo) {
            let clickCount = 0;
            let clickTimer;

            logo.addEventListener('click', () => {
                clickCount++;
                clearTimeout(clickTimer);

                if (clickCount === 3) {
                    createSparkleEffect();
                    logo.classList.add('spin');
                    setTimeout(() => logo.classList.remove('spin'), 1000);
                    clickCount = 0;
                } else {
                    clickTimer = setTimeout(() => {
                        clickCount = 0;
                    }, 500);
                }
            });
        }
    }

    // Event listeners
    document.addEventListener('keydown', handleKeydown);
    setupAdditionalEasterEggs();
}); 