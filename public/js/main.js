/* ========================================
   Ardh Alfaw - Main JavaScript
   ======================================== */

document.addEventListener('DOMContentLoaded', function() {
    // Initialize all modules
    initNavigation();
    initParticles();
    initScrollAnimations();
    initCounters();
    initProjectFilter();
    initContactForm();
    initBackToTop();
    initSmoothScroll();
    initGalleryLightbox();
    initShowMore();
});

/* ========================================
   Navigation
   ======================================== */
function initNavigation() {
    const navbar = document.getElementById('navbar');
    const navToggle = document.getElementById('navToggle');
    const navLinks = document.getElementById('navLinks');
    const navOverlay = document.getElementById('navOverlay');
    const links = navLinks.querySelectorAll('a');

    function closeMenu() {
        navToggle.classList.remove('active');
        navLinks.classList.remove('active');
        if (navOverlay) navOverlay.classList.remove('active');
    }

    // Scroll handler for navbar
    window.addEventListener('scroll', function() {
        if (window.scrollY > 100) {
            navbar.classList.add('scrolled');
        } else {
            navbar.classList.remove('scrolled');
        }

        // Update active link based on scroll position
        updateActiveLink();
    });

    // Mobile menu toggle
    navToggle.addEventListener('click', function() {
        const isOpen = navLinks.classList.toggle('active');
        navToggle.classList.toggle('active');
        if (navOverlay) navOverlay.classList.toggle('active', isOpen);
    });

    // Close mobile menu when clicking a link
    links.forEach(function(link) {
        link.addEventListener('click', closeMenu);
    });

    // Close mobile menu when clicking overlay
    if (navOverlay) {
        navOverlay.addEventListener('click', closeMenu);
    }

    // Close mobile menu when clicking outside
    document.addEventListener('click', function(e) {
        if (!navbar.contains(e.target) && (!navOverlay || !navOverlay.contains(e.target))) {
            closeMenu();
        }
    });
}

function updateActiveLink() {
    const sections = document.querySelectorAll('section[id]');
    const scrollPos = window.scrollY + 200;

    sections.forEach(function(section) {
        const sectionTop = section.offsetTop;
        const sectionHeight = section.offsetHeight;
        const sectionId = section.getAttribute('id');

        if (scrollPos >= sectionTop && scrollPos < sectionTop + sectionHeight) {
            document.querySelectorAll('.nav-links a').forEach(function(link) {
                link.classList.remove('active');
                if (link.getAttribute('href') === '#' + sectionId) {
                    link.classList.add('active');
                }
            });
        }
    });
}

/* ========================================
   Particle Animation
   ======================================== */
function initParticles() {
    const particleContainer = document.getElementById('particles');
    const particleCount = 50;

    for (let i = 0; i < particleCount; i++) {
        createParticle(particleContainer);
    }
}

function createParticle(container) {
    const particle = document.createElement('div');
    particle.classList.add('particle');

    // Random position
    particle.style.left = Math.random() * 100 + '%';
    particle.style.top = Math.random() * 100 + '%';

    // Random size
    const size = Math.random() * 4 + 2;
    particle.style.width = size + 'px';
    particle.style.height = size + 'px';

    // Random animation delay and duration
    particle.style.animationDelay = Math.random() * 8 + 's';
    particle.style.animationDuration = (Math.random() * 4 + 6) + 's';

    // Random opacity
    particle.style.opacity = Math.random() * 0.5 + 0.2;

    container.appendChild(particle);
}

/* ========================================
   Scroll Reveal Animations
   ======================================== */
function initScrollAnimations() {
    const reveals = document.querySelectorAll('.reveal');

    function checkReveal() {
        const windowHeight = window.innerHeight;
        const revealPoint = 150;

        reveals.forEach(function(element, index) {
            const elementTop = element.getBoundingClientRect().top;

            if (elementTop < windowHeight - revealPoint) {
                // Add delay based on element position in grid
                setTimeout(function() {
                    element.classList.add('active');
                }, index % 3 * 100);
            }
        });
    }

    window.addEventListener('scroll', checkReveal);
    window.addEventListener('load', checkReveal);
    checkReveal(); // Initial check
}

/* ========================================
   Counter Animation
   ======================================== */
function initCounters() {
    const counters = document.querySelectorAll('.stat-number');
    let hasAnimated = false;

    function animateCounters() {
        if (hasAnimated) return;

        const heroSection = document.getElementById('home');
        const heroBottom = heroSection.getBoundingClientRect().bottom;

        if (heroBottom > 0 && heroBottom < window.innerHeight + 200) {
            hasAnimated = true;

            counters.forEach(function(counter) {
                const target = parseInt(counter.getAttribute('data-count'));
                const duration = 2000;
                const step = target / (duration / 16);
                let current = 0;

                function updateCounter() {
                    current += step;
                    if (current < target) {
                        counter.textContent = Math.floor(current);
                        requestAnimationFrame(updateCounter);
                    } else {
                        counter.textContent = target;
                    }
                }

                updateCounter();
            });
        }
    }

    window.addEventListener('scroll', animateCounters);
    window.addEventListener('load', animateCounters);
}

/* ========================================
   Project Filter
   ======================================== */
function initProjectFilter() {
    const filterBtns = document.querySelectorAll('.filter-btn');
    const projectCards = document.querySelectorAll('.project-card');

    filterBtns.forEach(function(btn) {
        btn.addEventListener('click', function() {
            // Update active button
            filterBtns.forEach(function(b) {
                b.classList.remove('active');
            });
            btn.classList.add('active');

            // Filter projects
            const filter = btn.getAttribute('data-filter');

            projectCards.forEach(function(card, index) {
                const category = card.getAttribute('data-category');

                if (filter === 'all' || category === filter) {
                    card.classList.remove('hidden');
                    card.style.animation = 'fadeUp 0.5s ease forwards';
                    card.style.animationDelay = (index * 0.1) + 's';
                } else {
                    card.classList.add('hidden');
                }
            });
        });
    });
}

/* ========================================
   Contact Form - AJAX with Laravel CSRF
   ======================================== */
function initContactForm() {
    const form = document.getElementById('contactForm');
    const modal = document.getElementById('successModal');

    if (!form) return;

    form.addEventListener('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(form);
        const data = {};
        formData.forEach(function(value, key) {
            data[key] = value;
        });

        if (!validateForm(data)) {
            return;
        }

        const submitBtn = form.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري الإرسال...';
        submitBtn.disabled = true;

        // Read CSRF token from the meta tag added by Laravel layout
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        fetch('/contact', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify(data)
        })
        .then(function(response) {
            return response.json();
        })
        .then(function(result) {
            if (result.success) {
                modal.classList.add('active');
                form.reset();
            } else {
                alert('حدث خطأ. الرجاء المحاولة مرة أخرى.');
            }
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        })
        .catch(function() {
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
            alert('حدث خطأ في الاتصال. الرجاء المحاولة مرة أخرى.');
        });
    });

    // Close modal when clicking outside
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            closeModal();
        }
    });
}

function validateForm(data) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    if (!data.name || data.name.trim().length < 2) {
        alert('الرجاء إدخال الاسم الكامل');
        return false;
    }

    if (!data.email || !emailRegex.test(data.email)) {
        alert('الرجاء إدخال بريد إلكتروني صحيح');
        return false;
    }

    if (!data.subject || data.subject.trim().length < 3) {
        alert('الرجاء إدخال موضوع الرسالة');
        return false;
    }

    if (!data.message || data.message.trim().length < 10) {
        alert('الرجاء إدخال رسالة لا تقل عن 10 أحرف');
        return false;
    }

    return true;
}

function closeModal() {
    const modal = document.getElementById('successModal');
    modal.classList.remove('active');
}

// Make closeModal available globally
window.closeModal = closeModal;

/* ========================================
   Back to Top Button
   ======================================== */
function initBackToTop() {
    const backToTop = document.getElementById('backToTop');

    window.addEventListener('scroll', function() {
        if (window.scrollY > 500) {
            backToTop.classList.add('visible');
        } else {
            backToTop.classList.remove('visible');
        }
    });

    backToTop.addEventListener('click', function() {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });
}

/* ========================================
   Smooth Scroll
   ======================================== */
function initSmoothScroll() {
    const links = document.querySelectorAll('a[href^="#"]');

    links.forEach(function(link) {
        link.addEventListener('click', function(e) {
            e.preventDefault();

            const targetId = this.getAttribute('href');
            if (targetId === '#') return;

            const targetElement = document.querySelector(targetId);
            if (targetElement) {
                const navbarHeight = document.getElementById('navbar').offsetHeight;
                const targetPosition = targetElement.offsetTop - navbarHeight;

                window.scrollTo({
                    top: targetPosition,
                    behavior: 'smooth'
                });
            }
        });
    });
}

/* ========================================
   Gallery Lightbox
   ======================================== */
function initGalleryLightbox() {
    const galleryItems = document.querySelectorAll('.gallery-item');
    const lightbox = document.getElementById('lightbox');
    const lightboxClose = document.getElementById('lightboxClose');

    if (!lightbox) return;

    const lightboxIcon = lightbox.querySelector('.lightbox-icon');
    const lightboxCaption = lightbox.querySelector('.lightbox-caption');

    galleryItems.forEach(function(item) {
        item.addEventListener('click', function() {
            var thumbImg    = item.querySelector('.gallery-thumb img');
            var thumbIcon   = item.querySelector('.gallery-thumb i');
            var captionText = item.querySelector('.gallery-overlay span');

            if (thumbImg) {
                lightboxIcon.innerHTML = '<img src="' + thumbImg.src + '" alt="' + (thumbImg.alt || '') + '" class="lightbox-img">';
            } else if (thumbIcon) {
                lightboxIcon.innerHTML = '<i class="' + thumbIcon.className + '" style="font-size:6rem;color:var(--teal-accent)"></i>';
            }

            if (captionText) {
                lightboxCaption.textContent = captionText.textContent;
            }

            lightbox.classList.add('active');
            document.body.style.overflow = 'hidden';
        });
    });

    function closeLightbox() {
        lightbox.classList.remove('active');
        document.body.style.overflow = '';
    }

    lightboxClose.addEventListener('click', closeLightbox);

    lightbox.addEventListener('click', function(e) {
        if (e.target === lightbox) {
            closeLightbox();
        }
    });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeLightbox();
        }
    });
}

/* ========================================
   Utility Functions
   ======================================== */

// Debounce function for performance
function debounce(func, wait) {
    let timeout;
    return function executedFunction() {
        const context = this;
        const args = arguments;
        const later = function() {
            timeout = null;
            func.apply(context, args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// Throttle function for scroll events
function throttle(func, limit) {
    let inThrottle;
    return function() {
        const context = this;
        const args = arguments;
        if (!inThrottle) {
            func.apply(context, args);
            inThrottle = true;
            setTimeout(function() {
                inThrottle = false;
            }, limit);
        }
    };
}

/* ========================================
   News Modal
   ======================================== */
function closeNewsModal() {
    var modal = document.getElementById('newsModal');
    if (modal) {
        modal.classList.remove('active');
        document.body.style.overflow = '';
    }
}

(function() {
    document.querySelectorAll('.news-read-more').forEach(function(btn) {
        btn.addEventListener('click', function() {
            var modal    = document.getElementById('newsModal');
            var imgWrap  = document.getElementById('newsModalImage');
            var title    = document.getElementById('newsModalTitle');
            var date     = document.getElementById('newsModalDate');
            var category = document.getElementById('newsModalCategory');
            var badge    = document.getElementById('newsModalBadge');
            var excerpt  = document.getElementById('newsModalExcerpt');

            var imgSrc = btn.dataset.image;
            var icon   = btn.dataset.icon;

            if (imgSrc) {
                imgWrap.innerHTML = '<img src="' + imgSrc + '" alt="' + btn.dataset.title + '">';
            } else if (icon) {
                imgWrap.innerHTML = '<i class="' + icon + '"></i>';
            } else {
                imgWrap.innerHTML = '';
            }

            title.textContent    = btn.dataset.title;
            date.innerHTML       = '<i class="fas fa-calendar-alt"></i> ' + btn.dataset.date;
            category.textContent = btn.dataset.category;
            badge.textContent    = btn.dataset.badge;
            excerpt.textContent  = btn.dataset.excerpt;

            modal.classList.add('active');
            document.body.style.overflow = 'hidden';
        });
    });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeNewsModal();
    });
})();

/* ========================================
   Show More
   ======================================== */
function initShowMore() {
    document.querySelectorAll('.show-more-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            var target = document.querySelector(btn.dataset.target);
            if (!target) return;
            target.querySelectorAll('.extra-item').forEach(function(el) {
                el.style.display = '';
                el.classList.remove('extra-item');
            });
            btn.closest('.show-more-wrap').remove();
        });
    });
}
