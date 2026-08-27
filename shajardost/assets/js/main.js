/**
 * ShajarDost - Main JavaScript
 * Version: 2.0
 */
(function() {
    'use strict';

    // --- Mobile Menu Toggle ---
    const menuBtn = document.getElementById('menuBtn');
    const navLinks = document.getElementById('navLinks');
    const icon = menuBtn ? menuBtn.querySelector('i') : null;

    if (menuBtn && navLinks) {
        menuBtn.addEventListener('click', () => {
            navLinks.classList.toggle('active');
            if (navLinks.classList.contains('active')) {
                icon.classList.remove('fa-bars');
                icon.classList.add('fa-times');
            } else {
                icon.classList.remove('fa-times');
                icon.classList.add('fa-bars');
            }
        });

        // Close menu on link click
        navLinks.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', () => {
                navLinks.classList.remove('active');
                if (icon) { icon.classList.remove('fa-times'); icon.classList.add('fa-bars'); }
            });
        });

        // Mobile dropdown toggle
        document.querySelectorAll('.dropdown > a').forEach(dropLink => {
            dropLink.addEventListener('click', function(e) {
                if (window.innerWidth <= 768) {
                    e.preventDefault();
                    this.parentElement.classList.toggle('active');
                }
            });
        });
    }

    // --- Navbar Scroll Shadow ---
    const header = document.getElementById('siteHeader');
    window.addEventListener('scroll', () => {
        if (header) {
            header.classList.toggle('scrolled', window.scrollY > 50);
        }
    });

    // --- Scroll to Top Button ---
    const scrollTopBtn = document.getElementById('scrollTopBtn');
    if (scrollTopBtn) {
        window.addEventListener('scroll', () => {
            scrollTopBtn.classList.toggle('visible', window.scrollY > 400);
        });
        scrollTopBtn.addEventListener('click', () => {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    }

    // --- Counter Animation ---
    const counters = document.querySelectorAll('.stat-number[data-target]');
    const counterObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const counter = entry.target;
                const target = parseInt(counter.getAttribute('data-target'));
                const suffix = counter.getAttribute('data-suffix') || '';
                const duration = 2000;
                const step = target / (duration / 16);
                let current = 0;

                const updateCounter = () => {
                    current += step;
                    if (current < target) {
                        counter.textContent = Math.floor(current).toLocaleString() + suffix;
                        requestAnimationFrame(updateCounter);
                    } else {
                        counter.textContent = target.toLocaleString() + suffix;
                    }
                };
                updateCounter();
                counterObserver.unobserve(counter);
            }
        });
    }, { threshold: 0.5 });

    counters.forEach(counter => counterObserver.observe(counter));

    // --- Scroll Animations ---
    const animatedElements = document.querySelectorAll('.fade-in, .slide-left, .slide-right');
    const animObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
                animObserver.unobserve(entry.target);
            }
        });
    }, { threshold: 0.1, rootMargin: '0px 0px -50px 0px' });

    animatedElements.forEach(el => animObserver.observe(el));

    // --- Smooth Scroll for Anchor Links ---
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                e.preventDefault();
                target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });
    });

    // --- Gallery Lightbox ---
    const galleryImages = document.querySelectorAll('.gallery-item img');
    if (galleryImages.length > 0) {
        const lightbox = document.createElement('div');
        lightbox.className = 'lightbox';
        lightbox.id = 'lightbox';
        lightbox.innerHTML = `
            <span class="lightbox-close">&times;</span>
            <span class="lightbox-nav lightbox-prev"><i class="fas fa-chevron-left"></i></span>
            <img src="" alt="Gallery Image" id="lightboxImg">
            <span class="lightbox-nav lightbox-next"><i class="fas fa-chevron-right"></i></span>
        `;
        document.body.appendChild(lightbox);

        let currentImgIndex = 0;
        const imgSrcs = Array.from(galleryImages).map(img => img.src);

        galleryImages.forEach((img, index) => {
            img.addEventListener('click', () => {
                currentImgIndex = index;
                document.getElementById('lightboxImg').src = imgSrcs[currentImgIndex];
                lightbox.classList.add('active');
                document.body.style.overflow = 'hidden';
            });
        });

        lightbox.querySelector('.lightbox-close').addEventListener('click', () => {
            lightbox.classList.remove('active');
            document.body.style.overflow = '';
        });

        lightbox.querySelector('.lightbox-prev').addEventListener('click', () => {
            currentImgIndex = (currentImgIndex - 1 + imgSrcs.length) % imgSrcs.length;
            document.getElementById('lightboxImg').src = imgSrcs[currentImgIndex];
        });

        lightbox.querySelector('.lightbox-next').addEventListener('click', () => {
            currentImgIndex = (currentImgIndex + 1) % imgSrcs.length;
            document.getElementById('lightboxImg').src = imgSrcs[currentImgIndex];
        });

        lightbox.addEventListener('click', (e) => {
            if (e.target === lightbox) {
                lightbox.classList.remove('active');
                document.body.style.overflow = '';
            }
        });

        document.addEventListener('keydown', (e) => {
            if (!lightbox.classList.contains('active')) return;
            if (e.key === 'Escape') { lightbox.classList.remove('active'); document.body.style.overflow = ''; }
            if (e.key === 'ArrowLeft') lightbox.querySelector('.lightbox-prev').click();
            if (e.key === 'ArrowRight') lightbox.querySelector('.lightbox-next').click();
        });
    }

    // --- Donation Plan Selector ---
    window.selectPlan = function(planName, price) {
        const input = document.getElementById('selected_plan');
        if (input) {
            input.value = planName + ' - ' + price + ' PKR';
            input.style.borderColor = 'var(--primary)';
            input.style.backgroundColor = '#f0fdf4';
            input.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    };

    // --- Active Nav Link ---
    const currentPage = window.location.pathname.split('/').pop() || 'index.php';
    document.querySelectorAll('.nav-links a').forEach(link => {
        const href = link.getAttribute('href');
        if (href === currentPage) {
            link.classList.add('active');
        }
    });

    // --- FAQ Toggle ---
    document.querySelectorAll('.faq-question').forEach(question => {
        question.addEventListener('click', () => {
            const item = question.parentElement;
            const isActive = item.classList.contains('active');
            document.querySelectorAll('.faq-item').forEach(i => i.classList.remove('active'));
            if (!isActive) item.classList.add('active');
        });
    });

    // --- Lazy Load Images ---
    if ('IntersectionObserver' in window) {
        const imgObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    if (img.dataset.src) { img.src = img.dataset.src; img.removeAttribute('data-src'); }
                    imgObserver.unobserve(img);
                }
            });
        });
        document.querySelectorAll('img[data-src]').forEach(img => imgObserver.observe(img));
    }

    // --- Update Copyright Year ---
    const yearEl = document.querySelector('.copyright .year');
    if (yearEl) yearEl.textContent = new Date().getFullYear();

})();
