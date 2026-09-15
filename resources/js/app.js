import * as bootstrap from 'bootstrap';
import $ from 'jquery';

window.bootstrap = bootstrap;
window.$ = window.jQuery = $;

const reducedMotionPreference = window.matchMedia('(prefers-reduced-motion: reduce)');
if (!reducedMotionPreference.matches) {
    document.documentElement.classList.add('has-reveal');
}

$(function () {
    const $navbar = $('.site-navbar');
    const updateNavbar = () => $navbar.toggleClass('is-scrolled', window.scrollY > 30);
    updateNavbar();
    $(window).on('scroll', updateNavbar);

    const $megaItems = $('.nav-mega-item');
    const isDesktopNavigation = () => window.matchMedia('(min-width: 992px)').matches;
    let megaCloseTimer = null;
    const cancelScheduledMegaClose = () => {
        if (megaCloseTimer !== null) {
            window.clearTimeout(megaCloseTimer);
            megaCloseTimer = null;
        }
    };
    const closeMegaMenus = (except = null) => {
        cancelScheduledMegaClose();
        $megaItems.each(function () {
            if (this === except) return;
            $(this).removeClass('is-open').children('.nav-mega-trigger').attr('aria-expanded', 'false');
        });
    };
    const openMegaMenu = (item) => {
        closeMegaMenus(item);
        $(item).addClass('is-open').children('.nav-mega-trigger').attr('aria-expanded', 'true');
    };

    $megaItems.on('mouseenter focusin', function () {
        if (isDesktopNavigation()) {
            cancelScheduledMegaClose();
            openMegaMenu(this);
        }
    }).on('mouseleave', function () {
        if (isDesktopNavigation()) {
            const item = this;
            cancelScheduledMegaClose();
            megaCloseTimer = window.setTimeout(() => {
                if (!item.matches(':hover') && !item.contains(document.activeElement)) {
                    $(item).removeClass('is-open').children('.nav-mega-trigger').attr('aria-expanded', 'false');
                }
                megaCloseTimer = null;
            }, 320);
        }
    }).on('focusout', function () {
        if (!isDesktopNavigation()) return;
        const item = this;
        window.setTimeout(() => {
            if (!item.contains(document.activeElement)) {
                $(item).removeClass('is-open').children('.nav-mega-trigger').attr('aria-expanded', 'false');
            }
        }, 0);
    });

    $('.nav-mega-trigger').on('click', function (event) {
        if (isDesktopNavigation()) return;
        event.preventDefault();
        const item = this.parentElement;
        const willOpen = !item.classList.contains('is-open');
        closeMegaMenus();
        if (willOpen) openMegaMenu(item);
    });

    $(document).on('keydown', function (event) {
        if (event.key === 'Escape') {
            closeMegaMenus();
            $('.site-navbar .navbar-toggler:visible').trigger('focus');
        }
    });

    $('#mainNavigation').on('hidden.bs.collapse', () => closeMegaMenus());
    $('.mega-menu a').on('click', function () {
        if (!isDesktopNavigation()) {
            bootstrap.Collapse.getOrCreateInstance(document.getElementById('mainNavigation'), { toggle: false }).hide();
        }
    });

    const heroSliderElement = document.getElementById('aconsHeroSlider');
    if (heroSliderElement) {
        const heroSlides = [...heroSliderElement.querySelectorAll('.home-hero-slide')];
        const heroCurrent = heroSliderElement.querySelector('[data-hero-current]');

        const restartHeroProgress = () => {
            heroSliderElement.classList.remove('is-progressing');
            void heroSliderElement.offsetWidth;

            if (!reducedMotionPreference.matches) {
                heroSliderElement.classList.add('is-progressing');
            }
        };

        const animateHeroSlide = (slide) => {
            heroSlides.forEach((heroSlide) => heroSlide.classList.remove('is-animated'));
            void slide.offsetWidth;
            slide.classList.add('is-animated');
        };

        heroSliderElement.addEventListener('slide.bs.carousel', (event) => {
            animateHeroSlide(event.relatedTarget);
            restartHeroProgress();
        });

        heroSliderElement.addEventListener('slid.bs.carousel', (event) => {
            if (heroCurrent) {
                heroCurrent.textContent = String(event.to + 1).padStart(2, '0');
            }
        });

        if (reducedMotionPreference.matches) {
            bootstrap.Carousel.getOrCreateInstance(heroSliderElement).pause();
            heroSliderElement.classList.remove('is-progressing');
        }
    }

    const revealElements = [...document.querySelectorAll('[data-reveal]')];
    if (revealElements.length) {
        const showRevealElement = (element) => element.classList.add('is-visible');

        if (reducedMotionPreference.matches || !('IntersectionObserver' in window)) {
            revealElements.forEach(showRevealElement);
        } else {
            const revealObserver = new IntersectionObserver((entries) => {
                entries.forEach((entry) => {
                    if (!entry.isIntersecting) {
                        return;
                    }

                    showRevealElement(entry.target);
                    revealObserver.unobserve(entry.target);
                });
            }, {
                threshold: 0.12,
                rootMargin: '0px 0px -8% 0px',
            });

            revealElements.forEach((element) => revealObserver.observe(element));
        }
    }

    $('.filter-btn').on('click', function () {
        const filter = $(this).data('filter');
        const $grid = $('#featuredProjectsGrid');
        $('.filter-btn').removeClass('active');
        $(this).addClass('active');

        $('.project-filter-item').each(function () {
            const visible = filter === '*' || $(this).data('category') === filter;
            $(this).toggleClass('is-hidden', !visible);
        });

        $grid.toggleClass('is-filtered', filter !== '*');
    });

    const $contactService = $('#contact-service');
    const $contactServiceOptions = $('.contact-service-option');
    const syncContactServiceOptions = () => {
        const selectedService = String($contactService.val() || '');
        $contactServiceOptions.each(function () {
            const isSelected = String($(this).data('contact-service')) === selectedService;
            $(this).toggleClass('is-selected', isSelected).attr('aria-pressed', isSelected);
        });
    };

    if ($contactService.length && $contactServiceOptions.length) {
        syncContactServiceOptions();
        $contactService.on('change', syncContactServiceOptions);
        $contactServiceOptions.on('click', function () {
            $contactService.val(String($(this).data('contact-service'))).trigger('change');
            document.getElementById('contact-form')?.scrollIntoView({ behavior: 'smooth', block: 'start' });
        });
    }

    const galleryModalElement = document.getElementById('galleryModal');
    if (galleryModalElement) {
        const galleryModal = new bootstrap.Modal(galleryModalElement);
        $('[data-gallery-src]').on('click', function () {
            $('#galleryModalImage').attr({
                src: $(this).data('gallery-src'),
                alt: $(this).data('gallery-alt') || '',
            });
            $('#galleryModalCaption').text($(this).data('gallery-caption') || '');
            galleryModal.show();
        });
    }
});
