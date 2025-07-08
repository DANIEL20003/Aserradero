// JavaScript profesional para la página Quiénes Somos

    // Configuración de animaciones al scroll
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    // Observer para animaciones de entrada
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate__animated', 'animate__fadeInUp');
                
                // Animar contadores si es la sección de estadísticas
                if (entry.target.querySelector('.stat-number')) {
                    animateCounters();
                }
            }
        });
    }, observerOptions);

    // Observar elementos para animaciones
    document.querySelectorAll('.intro-card, .content-card, .valor-card').forEach(card => {
        observer.observe(card);
    });

    // Animación de contadores
    function animateCounters() {
        const counters = document.querySelectorAll('.stat-number');
        counters.forEach(counter => {
            const target = parseInt(counter.textContent.replace(/\D/g, ''));
            const duration = 2000;
            const increment = target / (duration / 16);
            let current = 0;
            
            const timer = setInterval(() => {
                current += increment;
                if (current >= target) {
                    current = target;
                    clearInterval(timer);
                }
                
                // Formatear el número según el contenido original
                if (counter.textContent.includes('+')) {
                    counter.textContent = Math.floor(current) + '+';
                } else if (counter.textContent.includes('%')) {
                    counter.textContent = Math.floor(current) + '%';
                } else if (counter.textContent.includes('K')) {
                    counter.textContent = Math.floor(current) + 'K+';
                } else {
                    counter.textContent = Math.floor(current);
                }
            }, 16);
        });
    }

    // Smooth scrolling para enlaces internos
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                const offsetTop = target.offsetTop - 80; // Ajuste para navbar fija
                window.scrollTo({
                    top: offsetTop,
                    behavior: 'smooth'
                });
            }
        });
    });

    // Efecto parallax sutil para el hero
    let ticking = false;
    function updateParallax() {
        const scrolled = window.pageYOffset;
        const hero = document.querySelector('.hero');
        if (hero) {
            const speed = scrolled * 0.2;
            hero.style.transform = `translateY(${speed}px)`;
        }
        ticking = false;
    }

    function requestParallaxTick() {
        if (!ticking) {
            requestAnimationFrame(updateParallax);
            ticking = true;
        }
    }

    window.addEventListener('scroll', requestParallaxTick);

    // Navbar transparente al hacer scroll
    const navbar = document.querySelector('.navbar');
    if (navbar) {
        window.addEventListener('scroll', () => {
            if (window.scrollY > 50) {
                navbar.style.background = 'linear-gradient(135deg, rgba(13, 110, 253, 0.95), rgba(25, 135, 84, 0.95))';
                navbar.style.backdropFilter = 'blur(15px)';
            } else {
                navbar.style.background = 'linear-gradient(135deg, var(--bs-primary), var(--bs-success))';
                navbar.style.backdropFilter = 'blur(10px)';
            }
        });
    }


    // Agregar efectos hover mejorados a las cards
    document.querySelectorAll('.valor-card').forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-10px) scale(1.02)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0) scale(1)';
        });
    });

    // Mejorar accesibilidad con navegación por teclado
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Tab') {
            document.body.classList.add('keyboard-navigation');
        }
    });

    document.addEventListener('mousedown', () => {
        document.body.classList.remove('keyboard-navigation');
    });

    // Preloader mejorado
    window.addEventListener('load', () => {
        document.body.classList.add('loaded');
        
        // Iniciar animaciones después de la carga
        setTimeout(() => {
            document.querySelector('.hero .animate__animated')?.classList.add('animate__fadeInUp');
        }, 200);
    });

    // Tooltip para iconos sociales
    const socialLinks = document.querySelectorAll('.footer-icons a');
    socialLinks.forEach(link => {
        link.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-5px) scale(1.1)';
        });
        
        link.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0) scale(1)';
        });
    });

    // Performance: debounce para eventos de scroll
    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }

    // Aplicar debounce al scroll
    const debouncedScroll = debounce(() => {
        // Lógica adicional de scroll si es necesaria
    }, 10);

    window.addEventListener('scroll', debouncedScroll);

// Función para animar elementos cuando entran en viewport
function animateOnScroll() {
    const elements = document.querySelectorAll('[data-animate]');
    elements.forEach(element => {
        const elementTop = element.getBoundingClientRect().top;
        const elementVisible = 150;
        
        if (elementTop < window.innerHeight - elementVisible) {
            element.classList.add('animate__animated', element.dataset.animate);
        }
    });
}

// Exportar funciones para uso global si es necesario
window.QuienesSomos = {
    animateCounters: function() {
        // Función pública para animar contadores
        const counters = document.querySelectorAll('.stat-number');
        counters.forEach(counter => {
            // Lógica de animación
        });
    }
};
