/**
 * Dashboard JavaScript - Sarana Prasarana
 * Menangani interaksi dan animasi pada halaman dashboard
 */

document.addEventListener('DOMContentLoaded', function() {
    
    // ===================================
    // TANGGAL DAN WAKTU
    // ===================================
    function updateDateTime() {
        const dateElement = document.getElementById('current-date');
        
        if (dateElement) {
            const options = { 
                weekday: 'long', 
                year: 'numeric', 
                month: 'long', 
                day: 'numeric' 
            };
            const now = new Date();
            dateElement.textContent = now.toLocaleDateString('id-ID', options);
        }
    }

    // Update tanggal saat halaman dimuat
    updateDateTime();

    // ===================================
    // ANIMASI FADE IN UNTUK CARDS
    // ===================================
    function initFadeInAnimation() {
        const cards = document.querySelectorAll('.stat-card, .info-card');
        
        cards.forEach((card, index) => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            
            setTimeout(() => {
                card.style.transition = 'all 0.6s ease';
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, index * 100);
        });
    }

    // ===================================
    // HOVER EFFECTS ENHANCEMENT
    // ===================================
    function initHoverEffects() {
        const statCards = document.querySelectorAll('.stat-card');
        
        statCards.forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-8px) scale(1.02)';
                this.style.boxShadow = '0 15px 35px rgba(0, 0, 0, 0.15)';
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0) scale(1)';
                this.style.boxShadow = '0 3px 10px rgba(0, 0, 0, 0.08)';
            });
        });
    }

    // ===================================
    // TABLE ROW CLICK EFFECTS
    // ===================================
    function initTableEffects() {
        const tableRows = document.querySelectorAll('.info-table tbody tr');
        
        tableRows.forEach(row => {
            row.addEventListener('click', function() {
                // Remove active class from all rows
                tableRows.forEach(r => r.classList.remove('active-row'));
                
                // Add active class to clicked row
                this.classList.add('active-row');
                
                // Add ripple effect
                createRippleEffect(this, event);
            });
        });
    }

    // ===================================
    // RIPPLE EFFECT
    // ===================================
    function createRippleEffect(element, event) {
        const ripple = document.createElement('div');
        const rect = element.getBoundingClientRect();
        const size = Math.max(rect.width, rect.height);
        const x = event.clientX - rect.left - size / 2;
        const y = event.clientY - rect.top - size / 2;
        
        ripple.style.cssText = `
            position: absolute;
            width: ${size}px;
            height: ${size}px;
            left: ${x}px;
            top: ${y}px;
            background: rgba(99, 102, 241, 0.3);
            border-radius: 50%;
            transform: scale(0);
            animation: ripple 0.6s ease-out;
            pointer-events: none;
            z-index: 1;
        `;
        
        element.style.position = 'relative';
        element.style.overflow = 'hidden';
        element.appendChild(ripple);
        
        setTimeout(() => {
            ripple.remove();
        }, 600);
    }

    // ===================================
    // BADGE ANIMATIONS
    // ===================================
    function initBadgeAnimations() {
        const badges = document.querySelectorAll('.category-badge, .quantity-badge');
        
        badges.forEach(badge => {
            badge.addEventListener('mouseenter', function() {
                this.style.transform = 'scale(1.1) rotate(2deg)';
            });
            
            badge.addEventListener('mouseleave', function() {
                this.style.transform = 'scale(1) rotate(0deg)';
            });
        });
    }

    // ===================================
    // SCROLL ANIMATIONS
    // ===================================
    function initScrollAnimations() {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('fade-in');
                }
            });
        }, {
            threshold: 0.1
        });

        const elements = document.querySelectorAll('.stat-card, .info-card');
        elements.forEach(el => observer.observe(el));
    }

    // ===================================
    // UTILITY FUNCTIONS
    // ===================================
    function addRippleAnimation() {
        const style = document.createElement('style');
        style.textContent = `
            @keyframes ripple {
                to {
                    transform: scale(2);
                    opacity: 0;
                }
            }
            
            .active-row {
                background: linear-gradient(135deg, #e0f2fe, #f0f9ff) !important;
                border-left: 4px solid #6366f1 !important;
            }
            
            .fade-in {
                animation: fadeInUp 0.6s ease-out;
            }
            
            @keyframes fadeInUp {
                from {
                    opacity: 0;
                    transform: translateY(30px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }
        `;
        document.head.appendChild(style);
    }

    // ===================================
    // PERFORMANCE MONITORING
    // ===================================
    function logPerformance() {
        if (window.performance) {
            const loadTime = window.performance.timing.loadEventEnd - window.performance.timing.navigationStart;
            console.log(`Dashboard loaded in ${loadTime}ms`);
        }
    }

    // ===================================
    // INITIALIZATION
    // ===================================
    function init() {
        // Add required CSS animations
        addRippleAnimation();
        
        // Initialize all features
        initFadeInAnimation();
        initHoverEffects();
        initTableEffects();
        initBadgeAnimations();
        initScrollAnimations();
        
        // Log performance
        logPerformance();
        
        console.log('Dashboard initialized successfully');
    }

    // Start initialization
    init();

    // ===================================
    // REFRESH FUNCTIONALITY (if needed)
    // ===================================
    window.refreshDashboard = function() {
        // Add loading state
        document.body.style.opacity = '0.7';
        
        // Simulate refresh
        setTimeout(() => {
            location.reload();
        }, 500);
    };

    // ===================================
    // EXPORT FOR GLOBAL ACCESS
    // ===================================
    window.DashboardJS = {
        updateDateTime,
        refreshDashboard,
        createRippleEffect
    };
});

/**
 * Utility function untuk format angka
 */
function formatNumber(num) {
    return new Intl.NumberFormat('id-ID').format(num);
}

/**
 * Utility function untuk format tanggal
 */
function formatDate(date) {
    return new Intl.DateTimeFormat('id-ID', {
        weekday: 'long',
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    }).format(date);
}
