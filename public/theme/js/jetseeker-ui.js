/**
 * Jetseeker UI — lightweight interactions
 */
(function () {
    'use strict';

    document.addEventListener('DOMContentLoaded', function () {
        initMobileNav();
        initMegaMenu();
        initFaqAccordion();
        initFilterDrawer();
        initActiveNav();
        initScrollReveal();
        initSupportFormValidation();
        initManageFormValidation();
    });

    function initMobileNav() {
        var toggle = document.querySelector('.js-nav-toggle');
        var mobileNav = document.querySelector('.js-mobile-nav-inline');
        if (!toggle || !mobileNav) return;

        function openNav() {
            mobileNav.classList.add('is-open');
            mobileNav.setAttribute('aria-hidden', 'false');
            toggle.setAttribute('aria-expanded', 'true');
            toggle.setAttribute('aria-label', 'Close menu');
        }

        function closeNav() {
            mobileNav.classList.remove('is-open');
            mobileNav.setAttribute('aria-hidden', 'true');
            toggle.setAttribute('aria-expanded', 'false');
            toggle.setAttribute('aria-label', 'Open menu');
        }

        toggle.addEventListener('click', function () {
            if (mobileNav.classList.contains('is-open')) {
                closeNav();
            } else {
                openNav();
            }
        });

        document.addEventListener('click', function (e) {
            if (!mobileNav.classList.contains('is-open')) return;
            if (mobileNav.contains(e.target) || toggle.contains(e.target)) return;
            closeNav();
        });

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && mobileNav.classList.contains('is-open')) {
                closeNav();
            }
        });

        mobileNav.querySelectorAll('.js-mobile-nav__accordion-btn').forEach(function (btn) {
            btn.addEventListener('click', function () {
                var expanded = btn.getAttribute('aria-expanded') === 'true';
                var submenu = btn.nextElementSibling;
                btn.setAttribute('aria-expanded', expanded ? 'false' : 'true');
                if (submenu) submenu.classList.toggle('is-open', !expanded);
            });
        });

        mobileNav.querySelectorAll('.js-mobile-nav__link, .js-mobile-nav__submenu a').forEach(function (link) {
            link.addEventListener('click', function () {
                closeNav();
            });
        });
    }

    function initMegaMenu() {
        document.querySelectorAll('.js-nav__item--dropdown').forEach(function (item) {
            var link = item.querySelector('.js-nav__link');
            if (!link) return;

            link.addEventListener('keydown', function (e) {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    item.classList.toggle('is-open');
                }
            });

            document.addEventListener('click', function (e) {
                if (!item.contains(e.target)) {
                    item.classList.remove('is-open');
                }
            });
        });
    }

    function initFaqAccordion() {
        document.querySelectorAll('.js-faq-question').forEach(function (btn) {
            var answerId = btn.getAttribute('aria-controls');
            var answer = answerId ? document.getElementById(answerId) : btn.nextElementSibling;
            if (!answer) return;

            btn.addEventListener('click', function () {
                var expanded = btn.getAttribute('aria-expanded') === 'true';
                btn.setAttribute('aria-expanded', expanded ? 'false' : 'true');
                answer.classList.toggle('is-open', !expanded);
            });
        });
    }

    function initFilterDrawer() {
        var toggle = document.querySelector('.js-filter-toggle');
        var filters = document.querySelector('.js-filters');
        if (!toggle || !filters) return;

        toggle.addEventListener('click', function () {
            filters.classList.add('is-open');
            document.body.style.overflow = 'hidden';
        });

        var closeBtn = filters.querySelector('.js-filters__close');
        if (closeBtn) {
            closeBtn.addEventListener('click', function () {
                filters.classList.remove('is-open');
                document.body.style.overflow = '';
            });
        }
    }

    function initActiveNav() {
        var path = window.location.pathname;
        document.querySelectorAll('.js-nav__link, .js-mobile-nav__link').forEach(function (link) {
            var href = link.getAttribute('href');
            if (!href || href === '#') return;
            try {
                var linkPath = new URL(link.href, window.location.origin).pathname;
                if (linkPath === path || (path !== '/' && linkPath !== '/' && path.indexOf(linkPath) === 0)) {
                    link.classList.add('is-active');
                }
            } catch (e) { /* ignore */ }
        });
    }

    function initScrollReveal() {
        var elements = document.querySelectorAll('.js-reveal');
        if (!elements.length) return;

        if (!('IntersectionObserver' in window)) {
            elements.forEach(function (el) {
                el.classList.add('is-visible');
            });
            return;
        }

        var observer = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
                    observer.unobserve(entry.target);
                }
            });
        }, {
            threshold: 0.12,
            rootMargin: '0px 0px -32px 0px'
        });

        elements.forEach(function (el) {
            observer.observe(el);
        });
    }

    function initManageFormValidation() {
        var forms = document.querySelectorAll('.js-manage-form');
        if (!forms.length) return;

        var messages = {
            ref_no: 'Booking reference number is required.',
            last_name: 'Last name is required.',
            email: 'Enter a valid email address.'
        };

        bindFormValidation(forms, messages, 'manage');
    }

    function bindFormValidation(forms, messages, formType) {
        forms.forEach(function (form) {
            form.addEventListener('submit', function (e) {
                clearClientFormErrors(form, formType);
                if (!validateForm(form, messages, formType)) {
                    e.preventDefault();
                }
            });

            form.querySelectorAll('input, select, textarea').forEach(function (field) {
                field.addEventListener('input', function () {
                    clearClientFieldError(field, formType);
                });
                field.addEventListener('change', function () {
                    clearClientFieldError(field, formType);
                });
                field.addEventListener('blur', function () {
                    if (field.value || field.type === 'checkbox') {
                        validateField(form, field, messages, formType);
                    }
                });
            });
        });
    }

    function initSupportFormValidation() {
        var forms = document.querySelectorAll('.js-support-form');
        if (!forms.length) return;

        var messages = {
            ref_no: {
                create: 'Booking reference number is required.',
                search: 'Ticket reference is required.'
            },
            full_name: 'Last name is required.',
            email: 'Enter a valid email address.',
            contact: 'Contact number is required.',
            department: 'Please select a support department.',
            priority: 'Please select a ticket priority.',
            subject: 'Ticket subject is required.',
            message: 'Ticket message is required.',
            supportdeskpolicy: 'You must agree to the Support Policy and Terms of Service.'
        };

        bindFormValidation(forms, messages, 'support');
    }

    function validateForm(form, messages, formType) {
        var valid = true;
        var firstInvalid = null;

        form.querySelectorAll('[required]').forEach(function (field) {
            if (!validateField(form, field, messages, formType)) {
                valid = false;
                if (!firstInvalid) firstInvalid = field;
            }
        });

        if (!valid && firstInvalid) {
            firstInvalid.focus();
            showFormAlert(form, formType);
        }

        return valid;
    }

    function validateField(form, field, messages, formType) {
        var name = field.name;
        var isSearchForm = form.classList.contains('support-form');
        var message = messages[name];

        if (name === 'ref_no' && formType === 'support' && typeof messages.ref_no === 'object') {
            message = isSearchForm ? messages.ref_no.search : messages.ref_no.create;
        }

        if (field.type === 'checkbox') {
            if (!field.checked) {
                showClientFieldError(field, message || 'This field is required.', formType);
                return false;
            }
            clearClientFieldError(field, formType);
            return true;
        }

        if (field.tagName === 'SELECT' && !field.value) {
            showClientFieldError(field, message || 'Please select an option.', formType);
            return false;
        }

        if (!field.value.trim()) {
            showClientFieldError(field, message || 'This field is required.', formType);
            return false;
        }

        if (field.type === 'email' && !field.checkValidity()) {
            showClientFieldError(field, messages.email, formType);
            return false;
        }

        clearClientFieldError(field, formType);
        return true;
    }

    function getClientErrorClass(formType) {
        return formType === 'manage' ? 'js-manage-field-error' : 'js-support-field-error';
    }

    function showClientFieldError(field, message, formType) {
        field.classList.add('is-invalid');
        field.setAttribute('aria-invalid', 'true');

        var group = field.closest('.form-group') || field.closest('.js-support-form__checkbox') || field.parentElement;
        if (!group) return;

        var baseClass = getClientErrorClass(formType);
        var existing = group.querySelector('.' + baseClass + '--client');
        if (existing) {
            existing.textContent = message;
            return;
        }

        var error = document.createElement('span');
        error.className = baseClass + ' ' + baseClass + '--client';
        error.setAttribute('role', 'alert');
        error.textContent = message;
        group.appendChild(error);
    }

    function clearClientFieldError(field, formType) {
        field.classList.remove('is-invalid');
        field.removeAttribute('aria-invalid');

        var group = field.closest('.form-group') || field.closest('.js-support-form__checkbox') || field.parentElement;
        if (!group) return;

        var baseClass = getClientErrorClass(formType);
        var clientError = group.querySelector('.' + baseClass + '--client');
        if (clientError) clientError.remove();
    }

    function clearClientFormErrors(form, formType) {
        var baseClass = getClientErrorClass(formType);
        form.querySelectorAll('.' + baseClass + '--client').forEach(function (error) {
            error.remove();
        });
    }

    function showFormAlert(form, formType) {
        var selector = formType === 'manage'
            ? '.js-manage-form__alert--error'
            : '.js-support-form__alert--error';
        var alert = form.querySelector(selector);
        if (alert) {
            alert.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        }
    }
})();
