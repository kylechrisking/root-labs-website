/**
 * Resume JavaScript
 * Handles resume PDF generation and animations
 */

document.addEventListener('DOMContentLoaded', () => {
    // Configuration
    const config = {
        pdfOptions: {
            margin: 10,
            filename: 'resume.pdf',
            image: { type: 'jpeg', quality: 0.98 },
            html2canvas: { 
                scale: 2,
                useCORS: true,
                letterRendering: true
            },
            jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' }
        },
        animationDuration: 300,
        hoverScale: 1.02
    };

    // Cache DOM elements
    const elements = {
        downloadBtn: document.querySelector('.download-resume'),
        resumeItems: document.querySelectorAll('.resume-item'),
        loadingSpinner: document.querySelector('.loading-spinner'),
        container: document.querySelector('.resume-container')
    };

    // State management
    const state = {
        isGenerating: false
    };

    /**
     * Initialize resume functionality
     */
    function initResume() {
        if (!elements.container) {
            console.error('Resume container not found');
            return;
        }

        bindEvents();
        initHoverEffects();
    }

    /**
     * Bind event listeners
     */
    function bindEvents() {
        if (elements.downloadBtn) {
            elements.downloadBtn.addEventListener('click', handleDownload);
        }

        // Handle print shortcut (Ctrl/Cmd + P)
        document.addEventListener('keydown', (e) => {
            if ((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === 'p') {
                e.preventDefault();
                handleDownload();
            }
        });
    }

    /**
     * Initialize hover effects for resume items
     */
    function initHoverEffects() {
        elements.resumeItems.forEach(item => {
            item.addEventListener('mouseenter', () => {
                item.style.transform = `scale(${config.hoverScale})`;
                item.style.transition = `transform ${config.animationDuration}ms ease`;
            });

            item.addEventListener('mouseleave', () => {
                item.style.transform = 'scale(1)';
            });
        });
    }

    /**
     * Handle resume download
     */
    async function handleDownload() {
        if (state.isGenerating) return;

        try {
            state.isGenerating = true;
            showLoading(true);

            // Prepare container for PDF generation
            const container = elements.container.cloneNode(true);
            prepareContainerForPDF(container);

            // Generate PDF
            await generatePDF(container);

            showNotification('Resume downloaded successfully!', 'success');

        } catch (error) {
            console.error('Error generating PDF:', error);
            showNotification('Failed to generate PDF. Please try again.', 'error');
        } finally {
            state.isGenerating = false;
            showLoading(false);
        }
    }

    /**
     * Prepare container for PDF generation
     */
    function prepareContainerForPDF(container) {
        // Remove interactive elements
        container.querySelectorAll('button, .hover-effect').forEach(el => {
            el.remove();
        });

        // Apply print styles
        container.style.background = '#ffffff';
        container.style.padding = '20px';
        container.style.maxWidth = '210mm'; // A4 width
        
        // Format sections for better PDF layout
        formatSections(container);
    }

    /**
     * Format resume sections for PDF
     */
    function formatSections(container) {
        // Format experience section
        const experiences = container.querySelectorAll('.experience-item');
        experiences.forEach(exp => {
            exp.style.pageBreakInside = 'avoid';
            exp.style.marginBottom = '15px';
        });

        // Format skills section
        const skills = container.querySelectorAll('.skill-item');
        skills.forEach(skill => {
            skill.style.display = 'inline-block';
            skill.style.margin = '5px';
        });

        // Format education section
        const education = container.querySelectorAll('.education-item');
        education.forEach(edu => {
            edu.style.pageBreakInside = 'avoid';
            edu.style.marginBottom = '15px';
        });
    }

    /**
     * Generate PDF using html2pdf
     */
    async function generatePDF(container) {
        // Create temporary container
        const tempContainer = document.createElement('div');
        tempContainer.style.position = 'absolute';
        tempContainer.style.left = '-9999px';
        tempContainer.appendChild(container);
        document.body.appendChild(tempContainer);

        try {
            await html2pdf()
                .set(config.pdfOptions)
                .from(container)
                .save();
        } finally {
            // Cleanup
            document.body.removeChild(tempContainer);
        }
    }

    /**
     * Show/hide loading spinner
     */
    function showLoading(show) {
        if (elements.loadingSpinner) {
            elements.loadingSpinner.style.display = show ? 'block' : 'none';
        }
        if (elements.downloadBtn) {
            elements.downloadBtn.disabled = show;
        }
    }

    /**
     * Show notification
     */
    function showNotification(message, type) {
        const event = new CustomEvent('showNotification', {
            detail: { message, type }
        });
        document.dispatchEvent(event);
    }

    // Initialize resume functionality
    initResume();

    // Expose resume API
    window.resumeManager = {
        downloadPDF: handleDownload
    };
}); 