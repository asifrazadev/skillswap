// Initial reveal of hero elements
document.addEventListener('DOMContentLoaded', () => {
    // Hero entry animations
    const heroElements = document.querySelectorAll('.fade-in-up');
    setTimeout(() => {
        heroElements.forEach(el => el.classList.add('visible'));
    }, 100);

    // Scroll Reveal implementation (Intersection Observer)
    const revealElements = document.querySelectorAll('.reveal');
    
    const revealOptions = {
        threshold: 0.15,
        rootMargin: "0px 0px -50px 0px"
    };

    const revealOnScroll = new IntersectionObserver(function(entries, observer) {
        entries.forEach(entry => {
            if (!entry.isIntersecting) {
                return;
            } else {
                entry.target.classList.add('active');
                observer.unobserve(entry.target);
            }
        });
    }, revealOptions);

    revealElements.forEach(el => {
        revealOnScroll.observe(el);
    });

    // Mobile Menu Toggle logic
    const mobileToggle = document.querySelector('.mobile-menu-toggle');
    const header = document.querySelector('.navbar');
    
    if (mobileToggle) {
        mobileToggle.addEventListener('click', () => {
            header.classList.toggle('mobile-menu-active');
            
            // Animate hamburger to X
            const spans = mobileToggle.querySelectorAll('span');
            if(header.classList.contains('mobile-menu-active')) {
                spans[0].style.transform = 'rotate(45deg) translate(5px, 5px)';
                spans[1].style.opacity = '0';
                spans[2].style.transform = 'rotate(-45deg) translate(7px, -7px)';
            } else {
                spans[0].style.transform = 'none';
                spans[1].style.opacity = '1';
                spans[2].style.transform = 'none';
            }
        });
    }

    // Parallax effect on Hero
    window.addEventListener('scroll', () => {
        const scrolled = window.scrollY;
        const hero = document.querySelector('.hero');
        if (hero && scrolled < window.innerHeight) {
            hero.style.backgroundPositionY = `${scrolled * 0.5}px`;
        }
    });
});
