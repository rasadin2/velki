/**
 * WhatsApp Floating Button - Frontend JavaScript
 * Version: 1.0.0
 */

(function($) {
    'use strict';

    /**
     * Initialize WhatsApp Floating Button
     */
    function initWhatsAppButton() {
        const button = $('#wfb-button');
        const popup = $('#wfb-popup');
        const closeBtn = $('#wfb-close');

        if (!button.length || !popup.length) {
            return;
        }

        // Toggle popup on button click
        button.on('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            togglePopup();
        });

        // Close popup on close button click
        closeBtn.on('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            closePopup();
        });

        // Close popup when clicking outside
        $(document).on('click', function(e) {
            if (!$(e.target).closest('.wfb-container').length) {
                closePopup();
            }
        });

        // Prevent clicks inside popup from closing it
        popup.on('click', function(e) {
            e.stopPropagation();
        });

        // Close popup on ESC key
        $(document).on('keydown', function(e) {
            if (e.key === 'Escape' || e.keyCode === 27) {
                closePopup();
            }
        });

        // Add keyboard navigation support
        button.on('keydown', function(e) {
            if (e.key === 'Enter' || e.key === ' ' || e.keyCode === 13 || e.keyCode === 32) {
                e.preventDefault();
                togglePopup();
            }
        });

        closeBtn.on('keydown', function(e) {
            if (e.key === 'Enter' || e.key === ' ' || e.keyCode === 13 || e.keyCode === 32) {
                e.preventDefault();
                closePopup();
            }
        });
    }

    /**
     * Toggle popup visibility
     */
    function togglePopup() {
        const popup = $('#wfb-popup');
        const isActive = popup.hasClass('wfb-active');

        if (isActive) {
            closePopup();
        } else {
            openPopup();
        }
    }

    /**
     * Open popup
     */
    function openPopup() {
        const popup = $('#wfb-popup');
        const button = $('#wfb-button');

        popup.addClass('wfb-active');
        button.attr('aria-expanded', 'true');

        // Focus first contact link for accessibility
        setTimeout(function() {
            popup.find('.wfb-contact-item').first().focus();
        }, 100);

        // Trigger custom event
        $(document).trigger('wfb:popup:opened');
    }

    /**
     * Close popup
     */
    function closePopup() {
        const popup = $('#wfb-popup');
        const button = $('#wfb-button');

        popup.removeClass('wfb-active');
        button.attr('aria-expanded', 'false');

        // Trigger custom event
        $(document).trigger('wfb:popup:closed');
    }

    /**
     * Track WhatsApp link clicks (optional analytics)
     */
    function trackWhatsAppClick(label, number) {
        // Google Analytics tracking (if available)
        if (typeof gtag !== 'undefined') {
            gtag('event', 'whatsapp_click', {
                'event_category': 'WhatsApp',
                'event_label': label,
                'value': number
            });
        }

        // Google Tag Manager tracking (if available)
        if (typeof dataLayer !== 'undefined') {
            dataLayer.push({
                'event': 'whatsapp_click',
                'whatsapp_label': label,
                'whatsapp_number': number
            });
        }

        // Custom event for developers
        $(document).trigger('wfb:contact:clicked', {
            label: label,
            number: number
        });
    }

    /**
     * Add click tracking to WhatsApp links
     */
    function initClickTracking() {
        $('.wfb-contact-item').on('click', function() {
            const label = $(this).find('span').text();
            const href = $(this).attr('href');
            const number = href.replace('https://wa.me/', '');

            trackWhatsAppClick(label, number);
        });
    }

    /**
     * Initialize FAQ accordion
     */
    function initFAQAccordion() {
        $('.wfb-faq-question').on('click', function(e) {
            e.preventDefault();
            const $card = $(this).closest('.wfb-faq-card');
            const isActive = $card.hasClass('wfb-active');
            const $button = $(this);

            // Close all other FAQ items
            $('.wfb-faq-card').not($card).removeClass('wfb-active');
            $('.wfb-faq-question').not($button).attr('aria-expanded', 'false');

            // Toggle current item
            if (isActive) {
                $card.removeClass('wfb-active');
                $button.attr('aria-expanded', 'false');
            } else {
                $card.addClass('wfb-active');
                $button.attr('aria-expanded', 'true');
            }

            // Trigger custom event
            $(document).trigger('wfb:faq:toggled', {
                question: $(this).find('.wfb-faq-question-text').text(),
                isOpen: !isActive
            });
        });

        // Keyboard support for FAQ
        $('.wfb-faq-question').on('keydown', function(e) {
            if (e.key === 'Enter' || e.key === ' ' || e.keyCode === 13 || e.keyCode === 32) {
                e.preventDefault();
                $(this).click();
            }
        });
    }

    /**
     * Initialize on document ready
     */
    $(document).ready(function() {
        initWhatsAppButton();
        initClickTracking();
        initFAQAccordion();

        // Trigger custom event when plugin is ready
        $(document).trigger('wfb:ready');
    });

    /**
     * Expose public API for developers
     */
    window.WFB = {
        open: openPopup,
        close: closePopup,
        toggle: togglePopup
    };

})(jQuery);
