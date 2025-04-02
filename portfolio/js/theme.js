/**
 * Theme JavaScript
 * Handles theme switching and persistence
 */

document.addEventListener('DOMContentLoaded', () => {
    // Configuration
    const config = {
        themes: {
            light: {
                primary: '#6B46C1',
                background: '#ffffff',
                text: '#333333',
                secondaryText: '#666666',
                accent: '#805AD5'
            },
            dark: {
                primary: '#805AD5',
                background: '#1A202C',
                text: '#E2E8F0',
                secondaryText: '#A0AEC0',
                accent: '#6B46C1'
            }
        },
        transitionDuration: 300,
        storageKey: 'theme'
    };

    // State management
    const state = {
        currentTheme: null,
        isTransitioning: false
    };

    // Cache DOM elements
    const elements = {
        themeToggle: document.querySelector('.theme-toggle'),
        sunIcon: document.querySelector('.sun-icon'),
        moonIcon: document.querySelector('.moon-icon'),
        root: document.documentElement
    };

    /**
     * Initialize theme system
     */
    function initTheme() {
        if (!elements.themeToggle) {
            console.error('Theme toggle button not found');
            return;
        }

        // Get initial theme
        const savedTheme = localStorage.getItem(config.storageKey);
        const systemPrefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
        const initialTheme = savedTheme || (systemPrefersDark ? 'dark' : 'light');

        // Set up theme change listener
        const darkModeMediaQuery = window.matchMedia('(prefers-color-scheme: dark)');
        darkModeMediaQuery.addListener(handleSystemThemeChange);

        // Initialize theme
        setTheme(initialTheme, false);
        
        // Add event listeners
        bindEvents();
    }

    /**
     * Bind event listeners
     */
    function bindEvents() {
        // Theme toggle click
        elements.themeToggle.addEventListener('click', handleThemeToggle);

        // Keyboard shortcut (Ctrl/Cmd + Shift + T)
        document.addEventListener('keydown', (e) => {
            if ((e.ctrlKey || e.metaKey) && e.shiftKey && e.key.toLowerCase() === 't') {
                e.preventDefault();
                handleThemeToggle();
            }
        });
    }

    /**
     * Handle theme toggle click
     */
    function handleThemeToggle() {
        if (state.isTransitioning) return;
        
        const newTheme = state.currentTheme === 'light' ? 'dark' : 'light';
        setTheme(newTheme, true);
    }

    /**
     * Handle system theme change
     */
    function handleSystemThemeChange(e) {
        if (!localStorage.getItem(config.storageKey)) {
            setTheme(e.matches ? 'dark' : 'light', true);
        }
    }

    /**
     * Set theme with transition
     */
    async function setTheme(theme, animate = true) {
        if (state.isTransitioning || theme === state.currentTheme) return;

        try {
            state.isTransitioning = true;

            // Update state
            state.currentTheme = theme;
            
            // Store theme preference
            localStorage.setItem(config.storageKey, theme);

            // Update data attribute
            elements.root.setAttribute('data-theme', theme);

            // Update icons
            if (elements.sunIcon && elements.moonIcon) {
                elements.sunIcon.style.display = theme === 'dark' ? 'none' : 'block';
                elements.moonIcon.style.display = theme === 'dark' ? 'block' : 'none';
            }

            // Apply theme colors
            const colors = config.themes[theme];
            Object.entries(colors).forEach(([property, value]) => {
                elements.root.style.setProperty(`--${property}-color`, value);
            });

            // Add transition class if animating
            if (animate) {
                elements.root.classList.add('theme-transition');
                await new Promise(resolve => setTimeout(resolve, config.transitionDuration));
                elements.root.classList.remove('theme-transition');
            }

            // Dispatch theme change event
            dispatchThemeChangeEvent(theme);

        } catch (error) {
            console.error('Error setting theme:', error);
        } finally {
            state.isTransitioning = false;
        }
    }

    /**
     * Dispatch custom theme change event
     */
    function dispatchThemeChangeEvent(theme) {
        const event = new CustomEvent('themechange', {
            detail: { theme, colors: config.themes[theme] }
        });
        document.dispatchEvent(event);
    }

    /**
     * Get current theme
     */
    function getCurrentTheme() {
        return state.currentTheme;
    }

    /**
     * Check if theme is supported
     */
    function isThemeSupported() {
        return (
            window.matchMedia &&
            window.matchMedia('(prefers-color-scheme)').media !== 'not all'
        );
    }

    // Initialize theme system
    initTheme();

    // Expose theme API
    window.themeManager = {
        getCurrentTheme,
        setTheme: (theme) => setTheme(theme, true),
        toggleTheme: handleThemeToggle,
        isSupported: isThemeSupported
    };
}); 