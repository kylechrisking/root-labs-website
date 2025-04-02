/**
 * Particles JavaScript
 * Handles interactive particle background animation
 */

document.addEventListener('DOMContentLoaded', () => {
    // Configuration
    const config = {
        particleCount: 100,
        baseSize: 3,
        baseSpeed: 0.2,
        baseColor: '107, 70, 193', // RGB for #6b46c1
        interactionRadius: 150,
        interactionStrength: 0.002,
        particleOpacity: 0.7,
        connectionDistance: 0, // Set to 0 to disable connections
        connectionOpacity: 0,
        fps: 60,
        glowEffect: true,
        glowSize: 15,
        glowOpacity: 0.3
    };

    // Get canvas context
    const canvas = document.getElementById('particleCanvas');
    if (!canvas) return;
    
    const ctx = canvas.getContext('2d', { alpha: true });
    let animationFrameId;
    let lastTime = 0;
    const frameInterval = 1000 / config.fps;

    // Particle class with enhanced features
    class Particle {
        constructor() {
            this.reset();
            // Initial random position
            this.x = Math.random() * canvas.width;
            this.y = Math.random() * canvas.height;
        }

        reset() {
            // Base properties
            this.size = Math.random() * config.baseSize + 2;
            this.baseSpeedX = (Math.random() - 0.5) * config.baseSpeed;
            this.baseSpeedY = (Math.random() - 0.5) * config.baseSpeed;
            
            // Velocity for mouse interaction
            this.vx = this.baseSpeedX;
            this.vy = this.baseSpeedY;
            
            // Target for smooth mouse interaction
            this.targetX = null;
            this.targetY = null;
            
            // Opacity for fade effects
            this.opacity = config.particleOpacity;
            
            // Glow effect
            this.glowSize = this.size * 3;
            this.glowOpacity = config.glowOpacity;
        }

        update() {
            // Update velocity
            this.vx = this.vx * 0.98 + this.baseSpeedX * 0.02;
            this.vy = this.vy * 0.98 + this.baseSpeedY * 0.02;
            
            // Update position
            this.x += this.vx;
            this.y += this.vy;
            
            // Wrap around edges with smooth transition
            if (this.x > canvas.width + this.size) {
                this.x = -this.size;
            } else if (this.x < -this.size) {
                this.x = canvas.width + this.size;
            }
            
            if (this.y > canvas.height + this.size) {
                this.y = -this.size;
            } else if (this.y < -this.size) {
                this.y = canvas.height + this.size;
            }
        }

        draw() {
            // Draw glow effect
            if (config.glowEffect) {
                const gradient = ctx.createRadialGradient(
                    this.x, this.y, 0,
                    this.x, this.y, this.glowSize
                );
                gradient.addColorStop(0, `rgba(${config.baseColor}, ${this.glowOpacity})`);
                gradient.addColorStop(1, `rgba(${config.baseColor}, 0)`);
                
                ctx.beginPath();
                ctx.arc(this.x, this.y, this.glowSize, 0, Math.PI * 2);
                ctx.fillStyle = gradient;
                ctx.fill();
            }
            
            // Draw particle
            ctx.beginPath();
            ctx.arc(this.x, this.y, this.size, 0, Math.PI * 2);
            ctx.fillStyle = `rgba(${config.baseColor}, ${this.opacity})`;
            ctx.fill();
        }

        interact(mouseX, mouseY) {
            const dx = mouseX - this.x;
            const dy = mouseY - this.y;
            const dist = Math.sqrt(dx * dx + dy * dy);
            
            if (dist < config.interactionRadius) {
                const force = (config.interactionRadius - dist) / config.interactionRadius;
                this.vx += dx * config.interactionStrength * force;
                this.vy += dy * config.interactionStrength * force;
                this.opacity = config.particleOpacity * (1 + force * 0.5);
                this.glowOpacity = config.glowOpacity * (1 + force * 0.5);
            } else {
                this.opacity = config.particleOpacity;
                this.glowOpacity = config.glowOpacity;
            }
        }
    }

    // Particle system management
    class ParticleSystem {
        constructor() {
            this.particles = [];
            this.mouseX = null;
            this.mouseY = null;
            this.isRunning = true;
            
            // Initialize particles
            this.init();
            
            // Bind event listeners
            this.bindEvents();
        }

        init() {
            // Create particles
            for (let i = 0; i < config.particleCount; i++) {
                this.particles.push(new Particle());
            }
        }

        bindEvents() {
            // Handle window resize
            window.addEventListener('resize', () => {
                this.resize();
            }, { passive: true });

            // Handle mouse movement
            canvas.addEventListener('mousemove', (e) => {
                const rect = canvas.getBoundingClientRect();
                this.mouseX = e.clientX - rect.left;
                this.mouseY = e.clientY - rect.top;
            }, { passive: true });

            // Handle mouse leave
            canvas.addEventListener('mouseleave', () => {
                this.mouseX = null;
                this.mouseY = null;
            }, { passive: true });
        }

        resize() {
            canvas.width = window.innerWidth;
            canvas.height = window.innerHeight;
        }

        update() {
            this.particles.forEach(particle => {
                particle.update();
                if (this.mouseX !== null && this.mouseY !== null) {
                    particle.interact(this.mouseX, this.mouseY);
                }
            });
        }

        draw() {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            
            // Draw particles
            this.particles.forEach(particle => {
                particle.draw();
            });
        }

        animate(currentTime) {
            if (!this.isRunning) return;

            animationFrameId = requestAnimationFrame(time => this.animate(time));

            // Control frame rate
            const delta = currentTime - lastTime;
            if (delta < frameInterval) return;

            lastTime = currentTime - (delta % frameInterval);

            this.update();
            this.draw();
        }

        start() {
            if (!this.isRunning) {
                this.isRunning = true;
                this.animate(0);
            }
        }

        stop() {
            this.isRunning = false;
            if (animationFrameId) {
                cancelAnimationFrame(animationFrameId);
            }
        }

        reset() {
            this.particles.forEach(particle => particle.reset());
        }
    }

    // Initialize and start the particle system
    const particleSystem = new ParticleSystem();
    particleSystem.resize();
    particleSystem.start();

    // Handle visibility change to pause/resume animation
    document.addEventListener('visibilitychange', () => {
        if (document.hidden) {
            particleSystem.stop();
        } else {
            particleSystem.start();
        }
    });

    // Expose particle system to window for potential external control
    window.particleSystem = particleSystem;
}); 