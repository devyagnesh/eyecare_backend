/**
 * Professional Preloader
 * Modern, elegant loading experience
 */

(function() {
    'use strict';

    let progressInterval = null;
    let currentProgress = 0;

    /**
     * Initialize preloader
     */
    function initPreloader() {
        const preloader = document.getElementById('page-preloader');
        if (!preloader) return;

        // Start progress simulation
        simulateProgress();

        // Hide preloader when page is fully loaded
        hidePreloader();
    }

    /**
     * Simulate loading progress
     */
    function simulateProgress() {
        const progressBar = document.querySelector('.preloader-progress-bar');
        const percentageDisplay = document.querySelector('.preloader-percentage');
        
        if (!progressBar) return;

        // Reset progress
        currentProgress = 0;
        updateProgress(0);

        // Simulate progress with realistic increments
        progressInterval = setInterval(function() {
            // Slower progress as it approaches 90%
            let increment;
            if (currentProgress < 30) {
                increment = 4 + Math.random() * 2;
            } else if (currentProgress < 60) {
                increment = 3 + Math.random() * 1.5;
            } else if (currentProgress < 80) {
                increment = 2 + Math.random() * 1;
            } else {
                increment = 0.5 + Math.random() * 0.5;
            }
            
            currentProgress += increment;
            
            if (currentProgress > 90) {
                currentProgress = 90; // Don't go to 100% until page is loaded
            }
            
            updateProgress(currentProgress);
        }, 80);

        // Complete progress when page loads
        const completeProgress = function() {
            if (progressInterval) {
                clearInterval(progressInterval);
                progressInterval = null;
            }
            // Smoothly animate to 100%
            const targetProgress = 100;
            const animateToComplete = function() {
                if (currentProgress < targetProgress) {
                    currentProgress += 2;
                    if (currentProgress > targetProgress) {
                        currentProgress = targetProgress;
                    }
                    updateProgress(currentProgress);
                    requestAnimationFrame(animateToComplete);
                }
            };
            animateToComplete();
        };

        window.addEventListener('load', completeProgress);
    }

    /**
     * Update progress bar and percentage
     */
    function updateProgress(percentage) {
        const progressBar = document.querySelector('.preloader-progress-bar');
        const percentageDisplay = document.querySelector('.preloader-percentage');
        
        if (progressBar) {
            progressBar.style.width = percentage + '%';
        }
        
        if (percentageDisplay) {
            percentageDisplay.textContent = Math.round(percentage) + '%';
        }
    }

    /**
     * Hide preloader
     */
    function hidePreloader() {
        const preloader = document.getElementById('page-preloader');
        if (!preloader) return;

        function removePreloader() {
            // Add loaded class for fade out animation
            preloader.classList.add('loaded');
            
            // Remove from DOM after animation
            setTimeout(function() {
                if (preloader && preloader.parentNode) {
                    preloader.remove();
                }
            }, 600);
        }

        // Check if page is already loaded
        if (document.readyState === 'complete') {
            // Ensure progress is at 100%
            updateProgress(100);
            // Small delay to ensure everything is rendered
            setTimeout(removePreloader, 400);
        } else {
            // Wait for window load event
            window.addEventListener('load', function() {
                // Ensure progress completes
                updateProgress(100);
                setTimeout(removePreloader, 400);
            });
        }

        // Fallback: Remove after maximum wait time (6 seconds)
        setTimeout(function() {
            if (preloader && preloader.parentNode) {
                updateProgress(100);
                removePreloader();
            }
        }, 6000);
    }

    // Initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initPreloader);
    } else if (document.readyState === 'interactive') {
        // DOM is ready but resources may still be loading
        initPreloader();
    } else {
        // DOM is already complete (page already loaded)
        // This handles cases where script loads after page is ready
        initPreloader();
    }
    
    // Also initialize on window load (in case script loads late)
    window.addEventListener('load', function() {
        const preloader = document.getElementById('page-preloader');
        if (preloader && preloader.parentNode && !preloader.classList.contains('loaded')) {
            // If preloader is still visible, force hide it
            updateProgress(100);
            setTimeout(function() {
                if (preloader && preloader.parentNode) {
                    preloader.classList.add('loaded');
                    setTimeout(function() {
                        if (preloader && preloader.parentNode) {
                            preloader.remove();
                        }
                    }, 600);
                }
            }, 100);
        }
    });

    // Handle page visibility changes
    document.addEventListener('visibilitychange', function() {
        if (!document.hidden) {
            const preloader = document.getElementById('page-preloader');
            if (preloader && preloader.parentNode) {
                // If preloader is still visible after page becomes visible, hide it
                setTimeout(function() {
                    if (preloader && preloader.parentNode) {
                        updateProgress(100);
                        preloader.classList.add('loaded');
                        setTimeout(function() {
                            if (preloader && preloader.parentNode) {
                                preloader.remove();
                            }
                        }, 600);
                    }
                }, 200);
            }
        }
    });

})();
