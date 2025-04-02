/**
 * Custom Cursor JavaScript
 * Handles custom cursor animations and interactions
 */

document.addEventListener('DOMContentLoaded', () => {
    // Cache DOM elements
    const cursorOuter = document.querySelector('.cursor-outer');
    const cursorInner = document.querySelector('.cursor-inner');
    
    // Exit if cursors aren't found (fallback to default cursor)
    if (!cursorOuter || !cursorInner) return;
    
    let mouseX = 0;
    let mouseY = 0;
    let cursorOuterX = 0;
    let cursorOuterY = 0;
    let isHovered = false;
    
    // Use RAF for smooth cursor movement
    function updateCursor() {
        // Calculate the distance to move
        const deltaX = mouseX - cursorOuterX;
        const deltaY = mouseY - cursorOuterY;
        
        // Smooth movement with easing
        cursorOuterX += deltaX * 0.2;
        cursorOuterY += deltaY * 0.2;
        
        // Apply transforms with hardware acceleration
        cursorOuter.style.transform = `translate3d(${cursorOuterX}px, ${cursorOuterY}px, 0) ${isHovered ? 'scale(1.5)' : 'scale(1)'}`;
        cursorInner.style.transform = `translate3d(${mouseX}px, ${mouseY}px, 0)`;
        
        requestAnimationFrame(updateCursor);
    }
    
    // Optimize mousemove with RAF
    let rafPending = false;
    
    document.addEventListener('mousemove', (e) => {
        mouseX = e.clientX;
        mouseY = e.clientY;
        
        // Immediate update for inner cursor
        cursorInner.style.transform = `translate3d(${mouseX}px, ${mouseY}px, 0)`;
        
        if (!rafPending) {
            rafPending = true;
            requestAnimationFrame(() => {
                rafPending = false;
            });
        }
    });
    
    // Initialize cursor animation
    requestAnimationFrame(updateCursor);
    
    // Show cursors when mouse moves
    document.addEventListener('mouseenter', () => {
        cursorOuter.style.opacity = '1';
        cursorInner.style.opacity = '1';
    });
    
    // Hide cursors when mouse leaves
    document.addEventListener('mouseleave', () => {
        cursorOuter.style.opacity = '0';
        cursorInner.style.opacity = '0';
    });
    
    // Handle cursor interactions
    const interactiveElements = document.querySelectorAll(`
        a, 
        button, 
        input[type="button"], 
        input[type="submit"], 
        input[type="reset"],
        .project-card,
        .social-link,
        .nav-link,
        .theme-toggle
    `);
    
    interactiveElements.forEach(element => {
        element.addEventListener('mouseenter', () => {
            isHovered = true;
            cursorOuter.classList.add('hovered');
            cursorInner.classList.add('hovered');
            
            // Change cursor color based on element type
            if (element.classList.contains('project-card')) {
                cursorOuter.style.borderColor = 'var(--accent-color)';
                cursorOuter.style.backgroundColor = 'rgba(var(--accent-rgb), 0.1)';
            } else if (element.classList.contains('social-link')) {
                cursorOuter.style.borderColor = 'var(--accent-color-light)';
                cursorOuter.style.backgroundColor = 'rgba(var(--accent-rgb), 0.2)';
            } else {
                cursorOuter.style.borderColor = 'var(--accent-color)';
                cursorOuter.style.backgroundColor = 'transparent';
            }
        });

        element.addEventListener('mouseleave', () => {
            isHovered = false;
            cursorOuter.classList.remove('hovered');
            cursorInner.classList.remove('hovered');
            cursorOuter.style.borderColor = '';
            cursorOuter.style.backgroundColor = '';
        });
    });
    
    // Handle cursor states for text selection
    document.addEventListener('selectstart', () => {
        cursorInner.classList.add('selecting');
        cursorOuter.classList.add('selecting');
    });
    
    document.addEventListener('selectionchange', () => {
        if (document.getSelection().toString().length === 0) {
            cursorInner.classList.remove('selecting');
            cursorOuter.classList.remove('selecting');
        }
    });
    
    // Handle cursor states for dragging
    document.addEventListener('mousedown', () => {
        cursorInner.classList.add('clicking');
        cursorOuter.classList.add('clicking');
    });
    
    document.addEventListener('mouseup', () => {
        cursorInner.classList.remove('clicking');
        cursorOuter.classList.remove('clicking');
    });
    
    // Handle cursor performance during scroll
    let scrollTimeout;
    window.addEventListener('scroll', () => {
        if (!scrollTimeout) {
            cursorOuter.style.opacity = '0';
            cursorInner.style.opacity = '0';
            
            scrollTimeout = setTimeout(() => {
                cursorOuter.style.opacity = '1';
                cursorInner.style.opacity = '1';
                scrollTimeout = null;
            }, 150);
        }
    }, { passive: true });
}); 