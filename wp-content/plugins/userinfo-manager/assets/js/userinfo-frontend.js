/**
 * UserInfo Manager - Frontend JavaScript
 * Isolated in IIFE to prevent conflicts
 * Version: 1.6.0
 *
 * Changelog v1.6.0:
 * - Added client-side form validation on submit
 * - Short, red error messages displayed below fields when validation fails
 * - Auto-clear errors when user starts typing
 * - Auto-scroll to first error field
 *
 * Changelog v1.5.2:
 * - Removed auto-hide from status check verification results (users need to review results)
 * - Kept auto-hide only for registration form success/error messages
 * - Verification results now stay visible until tab switch or manual clear
 *
 * Changelog v1.5.1:
 * - Fixed auto-remove timer for verification results (now properly waits for fadeIn completion)
 * - Ensures 5-second timer starts after animation completes
 *
 * Changelog v1.5.0:
 * - Added auto-remove for success/error messages after 5 seconds
 * - Added form reset functionality when switching tabs
 * - Enhanced user experience with automatic cleanup
 */

(function($) {
    'use strict';

    // Prevent multiple initializations
    if (window.UserinfoManager) {
        return;
    }

    // Namespace for the plugin
    window.UserinfoManager = {
        init: function() {
            this.initRippleEffect();
            this.initVerificationForm();
            this.initTabSwitching();
            this.initMessageAutoRemove();
            this.initFormValidation();
            this.initRegistrationForm();
            this.initModal();
            this.initCountdownTimer();
            this.initFestiveEffects();
        },

        /**
         * Festive Confetti Effect
         */
        initFestiveEffects: function() {
            var self = this;

            // Create confetti on form submission
            $('.userinfo-form').on('submit', function() {
                self.createConfettiBurst($(this));
            });

            // Create confetti when success message appears
            if ($('.userinfo-success').length > 0) {
                setTimeout(function() {
                    self.createConfettiBurst($('.userinfo-success'));
                }, 100);
            }

            // Add floating particles on page load
            self.createFloatingParticles();
        },

        /**
         * Create Confetti Burst Effect
         */
        createConfettiBurst: function($element) {
            if (!$element || $element.length === 0) return;

            var colors = ['#667eea', '#764ba2', '#f093fb', '#4facfe', '#43e97b'];
            var confettiCount = 30;
            var container = $('<div class="festive-confetti-container"></div>');

            $('body').append(container);

            for (var i = 0; i < confettiCount; i++) {
                var confetti = $('<div class="festive-confetti"></div>');
                var color = colors[Math.floor(Math.random() * colors.length)];
                var left = Math.random() * 100;
                var animationDelay = Math.random() * 0.5;
                var animationDuration = 2 + Math.random() * 2;
                var size = 5 + Math.random() * 5;

                confetti.css({
                    left: left + '%',
                    backgroundColor: color,
                    width: size + 'px',
                    height: size + 'px',
                    animationDelay: animationDelay + 's',
                    animationDuration: animationDuration + 's'
                });

                container.append(confetti);
            }

            // Remove confetti after animation
            setTimeout(function() {
                container.fadeOut(500, function() {
                    $(this).remove();
                });
            }, 4000);
        },

        /**
         * Create Floating Particles
         */
        createFloatingParticles: function() {
            var particles = ['✨', '⭐', '💫', '🌟'];
            var particleCount = 8;
            var container = $('.userinfo-form-container');

            if (container.length === 0) return;

            // Check if already initialized
            if (container.find('.festive-floating-particle').length > 0) return;

            for (var i = 0; i < particleCount; i++) {
                var particle = $('<span class="festive-floating-particle"></span>');
                var symbol = particles[Math.floor(Math.random() * particles.length)];
                var left = 5 + Math.random() * 90;
                var animationDelay = Math.random() * 4;
                var animationDuration = 3 + Math.random() * 3;

                particle.text(symbol).css({
                    left: left + '%',
                    animationDelay: animationDelay + 's',
                    animationDuration: animationDuration + 's'
                });

                container.append(particle);
            }
        },


        /**
         * Ripple Effect on Submit Button
         */
        initRippleEffect: function() {
            var $submitBtn = $('.userinfo-form button[type="submit"], .userinfo-check-form button[type="submit"]');

            if ($submitBtn.length === 0) return;

            // Remove previous event handlers to avoid duplicates
            $submitBtn.off('click.ripple');

            $submitBtn.on('click.ripple', function(e) {
                var $btn = $(this);
                var $ripple = $('<span class="ripple"></span>');
                var btnOffset = $btn.offset();
                var x = e.pageX - btnOffset.left;
                var y = e.pageY - btnOffset.top;

                $ripple.css({
                    top: y + 'px',
                    left: x + 'px'
                });

                $btn.append($ripple);

                setTimeout(function() {
                    $ripple.remove();
                }, 600);
            });
        },

        /**
         * Verification Form AJAX Handler
         */
        initVerificationForm: function() {
            var $form = $('#userinfo-check-form');

            if ($form.length === 0) return;

            // Remove previous event handlers to avoid duplicates
            $form.off('submit');

            $form.on('submit', function(e) {
                e.preventDefault();

                var $btn = $('#verify-btn');
                var $result = $('#verification-result');

                // Clear previous results and hide
                $result.hide().empty();

                // Disable button and show loading
                $btn.prop('disabled', true).html('<span>' + userinfo_l10n.verifying + ' <span class="loading-spinner"></span></span>');

                // Get form data
                var username = $('#check_username').val();
                var phoneNumber = $('#check_phone_number').val();

                // Make AJAX request
                $.ajax({
                    url: userinfo_l10n.ajax_url,
                    type: 'POST',
                    data: {
                        action: 'userinfo_verify_user',
                        username: username,
                        phone_number: phoneNumber,
                        nonce: $('#userinfo_verify_nonce').val()
                    },
                    success: function(response) {
                        if (response.success && response.data.found) {
                            // User found and valid
                            var data = response.data;
                            var statusClass = data.is_valid == '1' ? 'status-valid' : 'status-invalid';
                            var resultClass = 'verification-success';

                            var html = '<div class="' + resultClass + '">';
                            html += '<h3>✓ ' + userinfo_l10n.user_found + '</h3>';

                            // Display Registration ID prominently at the top
                            html += '<div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 20px; border-radius: 8px; margin-bottom: 20px; text-align: center; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">';
                            html += '<div style="font-size: 14px; font-weight: 600; text-transform: uppercase; letter-spacing: 1px; opacity: 0.9; margin-bottom: 8px;">' + userinfo_l10n.registration_id + '</div>';
                            html += '<div style="font-size: 32px; font-weight: 700; letter-spacing: 2px;">' + data.registration_id + '</div>';
                            html += '</div>';

                            html += '<div class="user-detail">';
                            html += '<span class="user-detail-label">' + userinfo_l10n.full_name + ':</span>';
                            html += '<span class="user-detail-value">' + data.full_name + '</span>';
                            html += '</div>';
                            html += '<div class="user-detail">';
                            html += '<span class="user-detail-label">' + userinfo_l10n.username + ':</span>';
                            html += '<span class="user-detail-value">' + data.username + '</span>';
                            html += '</div>';
                            html += '<div class="user-detail">';
                            html += '<span class="user-detail-label">' + userinfo_l10n.agent_id + ':</span>';
                            html += '<span class="user-detail-value">' + data.agent_id + '</span>';
                            html += '</div>';
                            html += '<div class="user-detail">';
                            html += '<span class="user-detail-label">' + userinfo_l10n.phone + ':</span>';
                            html += '<span class="user-detail-value">' + data.phone_number + '</span>';
                            html += '</div>';
                            html += '<div class="user-detail">';
                            html += '<span class="user-detail-label">' + userinfo_l10n.email + ':</span>';
                            html += '<span class="user-detail-value">' + data.email + '</span>';
                            html += '</div>';
                            html += '<div style="text-align: center; margin-top: 20px;">';
                            html += '<span class="user-detail-label">' + userinfo_l10n.status + ':</span><br>';

                            // Replace "Valid" text with "রেজিস্ট্রেশন সম্পন্ন" for valid status
                            var statusText = data.is_valid == '1' ? 'রেজিস্ট্রেশন সম্পন্ন' : data.valid_text;
                            html += '<span class="status-badge ' + statusClass + '">' + statusText + '</span>';

                            html += '</div>';

                            html += '</div>';

                            $result.html(html).css('display', 'block').hide().fadeIn(300);
                        } else {
                            // User not found
                            var html = '<div class="verification-error">';
                            html += '<strong>✗ ' + response.data.message + '</strong>';
                            html += '</div>';
                            $result.html(html).css('display', 'block').hide().fadeIn(300);
                        }
                    },
                    error: function() {
                        var html = '<div class="verification-error">';
                        html += '<strong>✗ ' + userinfo_l10n.error_occurred + '</strong>';
                        html += '</div>';
                        $result.html(html).css('display', 'block').hide().fadeIn(300);
                    },
                    complete: function() {
                        // Re-enable button
                        $btn.prop('disabled', false).html('<span>' + userinfo_l10n.verify_user + '</span>');
                    }
                });
            });
        },

        /**
         * Tab Switching Functionality for Tabs Shortcode
         */
        initTabSwitching: function() {
            var $tabButtons = $('.userinfo-tab-btn');

            if ($tabButtons.length === 0) return;

            var self = this;

            // Tab switching with advanced animations
            $tabButtons.on('click', function() {
                var $clickedBtn = $(this);
                var tabName = $clickedBtn.data('tab');
                var $currentContent = $('.userinfo-tab-content.active');
                var $targetContent = $('#' + tabName + '-tab');

                // Don't do anything if clicking on already active tab
                if ($clickedBtn.hasClass('active')) {
                    return;
                }

                // Add slide-out animation to current content
                $currentContent.addClass('slide-out');

                // Wait for slide-out animation to complete
                setTimeout(function() {
                    // Remove active and slide-out class from all content
                    $('.userinfo-tab-content').removeClass('active slide-out');

                    // Remove active class from all buttons
                    $tabButtons.removeClass('active');

                    // Add active class to clicked button
                    $clickedBtn.addClass('active');

                    // Show target content with slide-in animation
                    $targetContent.addClass('active');

                    // Reset forms when switching tabs
                    var $registrationForm = $('#userinfo-form');
                    var $checkForm = $('#userinfo-check-form');

                    if (tabName === 'register' && $registrationForm.length > 0) {
                        self.resetForm($registrationForm);
                    } else if (tabName === 'check' && $checkForm.length > 0) {
                        self.resetForm($checkForm);
                    }

                    // Re-initialize form handlers for the newly shown tab
                    self.initRippleEffect();
                    self.initVerificationForm();

                    // Scroll to top of tabs on mobile
                    if ($(window).width() <= 768) {
                        $('html, body').animate({
                            scrollTop: $('.userinfo-tabs-container').offset().top - 20
                        }, 300);
                    }
                }, 400); // Match the slideOutScale animation duration
            });

            // Add ripple effect on tab click
            $tabButtons.on('mousedown', function(e) {
                var $btn = $(this);
                var x = e.pageX - $btn.offset().left;
                var y = e.pageY - $btn.offset().top;

                var $ripple = $('<span class="ripple"></span>');
                $ripple.css({
                    left: x + 'px',
                    top: y + 'px'
                });

                $btn.append($ripple);

                setTimeout(function() {
                    $ripple.remove();
                }, 600);
            });
        },

        /**
         * Auto-remove Success/Error Messages After 5 Seconds
         * Note: Only applies to registration form messages, not verification results
         */
        initMessageAutoRemove: function() {
            var $successMessage = $('.userinfo-success');
            var $errorMessage = $('.userinfo-errors');

            // Auto-remove registration form success messages after 5 seconds
            if ($successMessage.length > 0 && $successMessage.is(':visible')) {
                setTimeout(function() {
                    $successMessage.fadeOut(500, function() {
                        $(this).remove();
                    });
                }, 5000);
            }

            // Auto-remove registration form error messages after 5 seconds
            if ($errorMessage.length > 0 && $errorMessage.is(':visible')) {
                setTimeout(function() {
                    $errorMessage.fadeOut(500, function() {
                        $(this).remove();
                    });
                }, 5000);
            }
        },

        /**
         * Form Validation on Submit
         */
        initFormValidation: function() {
            var self = this;

            // Registration Form Validation - Handled by AJAX now, keep only status check form validation

            // Status Check Form Validation
            var $checkForm = $('#userinfo-check-form');
            if ($checkForm.length > 0) {
                $checkForm.off('submit.validation');
                $checkForm.on('submit.validation', function(e) {
                    // Clear all previous errors
                    $('.field-error').text('').hide();
                    $('.form-group').removeClass('has-error');

                    var isValid = true;

                    // Validate Username
                    var username = $('#check_username').val().trim();
                    if (!username) {
                        self.showError('check_username', 'Required');
                        isValid = false;
                    }

                    // Validate Phone Number
                    var phone = $('#check_phone_number').val().trim();
                    if (!phone) {
                        self.showError('check_phone_number', 'Required');
                        isValid = false;
                    } else if (!/^[0-9]{10,50}$/.test(phone)) {
                        self.showError('check_phone_number', 'Must be 10-50 digits');
                        isValid = false;
                    }

                    if (!isValid) {
                        e.preventDefault();
                        // Scroll to first error
                        var $firstError = $('.has-error').first();
                        if ($firstError.length > 0) {
                            $('html, body').animate({
                                scrollTop: $firstError.offset().top - 100
                            }, 300);
                        }
                    }
                });

                // Clear error on input
                $checkForm.find('input').on('input', function() {
                    var fieldId = $(this).attr('id');
                    self.clearError(fieldId);
                });
            }
        },

        /**
         * Show Error Message
         */
        showError: function(fieldId, message) {
            var $errorSpan = $('.field-error[data-error-for="' + fieldId + '"]');
            var $formGroup = $('#' + fieldId).closest('.form-group');

            $errorSpan.text(message).fadeIn(200);
            $formGroup.addClass('has-error');
        },

        /**
         * Clear Error Message
         */
        clearError: function(fieldId) {
            var $errorSpan = $('.field-error[data-error-for="' + fieldId + '"]');
            var $formGroup = $('#' + fieldId).closest('.form-group');

            $errorSpan.text('').hide();
            $formGroup.removeClass('has-error');
        },

        /**
         * Reset Form to Initial State
         */
        resetForm: function($form) {
            if ($form.length === 0) return;

            // Reset form fields
            $form[0].reset();

            // Clear verification results
            var $verificationResult = $('#verification-result');
            if ($verificationResult.length > 0) {
                $verificationResult.empty().hide();
            }

            // Remove any success/error messages
            $('.userinfo-success').remove();
            $('.userinfo-errors').remove();
        },

        /**
         * Registration Form AJAX Handler
         */
        initRegistrationForm: function() {
            var self = this;
            var $form = $('#userinfo-form');

            if ($form.length === 0) return;

            // Remove previous event handlers
            $form.off('submit.ajax');

            $form.on('submit.ajax', function(e) {
                e.preventDefault();

                // Clear previous inline errors
                $('.field-error').text('').hide();
                $('.form-group').removeClass('has-error');

                var $submitBtn = $form.find('button[type="submit"]');
                var originalText = $submitBtn.find('span').text();

                // Disable button and show loading
                $submitBtn.prop('disabled', true);
                $submitBtn.find('span').html(userinfo_l10n.submitting || 'Submitting...');

                // Prepare form data
                var formData = new FormData(this);
                formData.append('action', 'userinfo_ajax_submit');

                // Make AJAX request
                $.ajax({
                    url: userinfo_l10n.ajax_url,
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        console.log('AJAX Response:', response);

                        if (response.success && response.data) {
                            // Show success modal with registration ID
                            self.showModal('success', response.data.message || 'Success!', response.data.registration_id);
                            // Reset form
                            $form[0].reset();
                        } else if (response.data && response.data.message) {
                            // Show error modal with server message
                            self.showModal('error', response.data.message);
                        } else {
                            // Show generic error
                            console.log('Unknown response format:', response);
                            self.showModal('error', userinfo_l10n.error_occurred || 'An error occurred. Please try again.');
                        }
                    },
                    error: function(xhr, status, error) {
                        // Show error modal
                        console.log('AJAX Error:', xhr.responseText, status, error);
                        self.showModal('error', userinfo_l10n.error_occurred || 'An error occurred. Please try again.');
                    },
                    complete: function() {
                        // Re-enable button
                        $submitBtn.prop('disabled', false);
                        $submitBtn.find('span').text(originalText);
                    }
                });
            });
        },

        /**
         * Modal Functions
         */
        initModal: function() {
            var self = this;
            var $modal = $('#userinfo-modal');

            // Close modal on button click
            $('.userinfo-modal-btn, .userinfo-modal-close', $modal).on('click', function() {
                self.hideModal();
            });

            // Close modal on overlay click
            $('.userinfo-modal-overlay', $modal).on('click', function() {
                self.hideModal();
            });

            // Close modal on ESC key
            $(document).on('keydown', function(e) {
                if (e.key === 'Escape' && $modal.hasClass('show')) {
                    self.hideModal();
                }
            });
        },

        /**
         * Show Modal
         */
        showModal: function(type, message, registrationId) {
            var $modal = $('#userinfo-modal');
            var $title = $('.userinfo-modal-title', $modal);
            var $body = $('.userinfo-modal-body', $modal);

            // Remove previous state classes
            $modal.removeClass('success error');

            if (type === 'success') {
                $modal.addClass('success');
                $title.text(userinfo_l10n.success_title || 'Success!');

                var bodyHtml = '<p>' + message + '</p>';

                if (registrationId) {
                    bodyHtml += '<div class="userinfo-registration-id-box">';
                    bodyHtml += '<div class="userinfo-registration-id-label">' + (userinfo_l10n.registration_id || 'Registration ID') + '</div>';
                    bodyHtml += '<div class="userinfo-registration-id-value">' + registrationId + '</div>';
                    bodyHtml += '</div>';
                    bodyHtml += '<p><small>' + (userinfo_l10n.save_id_message || 'Please save this ID for future reference.') + '</small></p>';
                }

                $body.html(bodyHtml);
            } else {
                $modal.addClass('error');
                $title.text(userinfo_l10n.error_title || 'Error');
                $body.html('<p>' + message + '</p>');
            }

            // Show modal
            $modal.addClass('show');

            // Prevent body scrolling while modal is open
            $('body').css({
                'overflow': 'hidden'
            });
        },

        /**
         * Hide Modal
         */
        hideModal: function() {
            var $modal = $('#userinfo-modal');

            $modal.removeClass('show');

            // Restore body scrolling
            $('body').css({
                'overflow': ''
            });
        },

        /**
         * Convert English numerals to Bangla numerals
         */
        toBanglaNumber: function(num) {
            var banglaDigits = {
                '0': '০',
                '1': '১',
                '2': '২',
                '3': '৩',
                '4': '৪',
                '5': '৫',
                '6': '৬',
                '7': '৭',
                '8': '৮',
                '9': '৯'
            };

            return String(num).split('').map(function(digit) {
                return banglaDigits[digit] || digit;
            }).join('');
        },

        /**
         * Countdown Timer (Monthly or Custom Date)
         */
        initCountdownTimer: function() {
            var $countdown = $('#userinfo-countdown-inline, #userinfo-countdown');

            if ($countdown.length === 0) return;

            var self = this;
            var countdownType = $countdown.first().data('countdown-type');
            var customDate = $countdown.first().data('custom-date');

            function updateCountdown() {
                // Get current date and time
                var now = new Date();
                var targetDate;

                // TEST MODE: Force H:M:S display (set to true to test)
                var testMode = false;
                if (testMode) {
                    // Set target to 12 hours from now for testing
                    targetDate = new Date(now.getTime() + (12 * 60 * 60 * 1000));
                } else if (countdownType === 'monthly') {
                    // Monthly countdown - end of current month
                    var year = now.getFullYear();
                    var month = now.getMonth();
                    var lastDay = new Date(year, month + 1, 0).getDate();
                    targetDate = new Date(year, month, lastDay, 23, 59, 59);
                } else if (countdownType === 'custom' && customDate) {
                    // Custom countdown - specific date at 23:59:59
                    targetDate = new Date(customDate + 'T23:59:59');
                } else {
                    // No valid countdown configuration
                    return;
                }

                // Calculate difference
                var diff = targetDate - now;

                // If time has expired, hide countdown and reload page to show closed message
                if (diff <= 0) {
                    // Prevent infinite reload loop using sessionStorage
                    if (!sessionStorage.getItem('countdown_expired_reload')) {
                        sessionStorage.setItem('countdown_expired_reload', 'true');
                        $countdown.fadeOut(300);
                        // Reload page after 1 second to show form blocked message
                        setTimeout(function() {
                            window.location.reload();
                        }, 1000);
                    } else {
                        // Already reloaded once, just hide countdown
                        $countdown.fadeOut(300);
                    }
                    return;
                }

                // Calculate time units
                var days = Math.floor(diff / (1000 * 60 * 60 * 24));
                var hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                var minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
                var seconds = Math.floor((diff % (1000 * 60)) / 1000);

                // Update all countdown components with Bangla numerals and zero-padding
                $('#countdown-days').text(self.toBanglaNumber(String(days).padStart(2, '0')));
                $('#countdown-hours').text(self.toBanglaNumber(String(hours).padStart(2, '0')));
                $('#countdown-minutes').text(self.toBanglaNumber(String(minutes).padStart(2, '0')));
                $('#countdown-seconds').text(self.toBanglaNumber(String(seconds).padStart(2, '0')));

                // DEBUG: Log current days value
                console.log('Countdown - Days:', days, 'Hours:', hours, 'Minutes:', minutes, 'Seconds:', seconds);

                // Conditional visibility based on remaining time
                if (days === 0) {
                    // Days is 0: Hide Days group, show H:M:S groups
                    console.log('Showing H:M:S groups (days === 0)');
                    $('#countdown-group-days').css('display', 'none');
                    $('#separator-1').css('display', 'none');
                    $('#countdown-group-hours').css('display', 'flex');
                    $('#separator-2').css('display', 'inline');
                    $('#countdown-group-minutes').css('display', 'flex');
                    $('#separator-3').css('display', 'inline');
                    $('#countdown-group-seconds').css('display', 'flex');
                } else {
                    // Days > 0: Show ONLY Days group, hide H:M:S groups
                    console.log('Showing Days group only (days > 0)');
                    $('#countdown-group-days').css('display', 'flex');
                    $('#separator-1').css('display', 'none');
                    $('#countdown-group-hours').css('display', 'none');
                    $('#countdown-group-minutes').css('display', 'none');
                    $('#separator-2').css('display', 'none');
                    $('#separator-3').css('display', 'none');
                    $('#countdown-group-seconds').css('display', 'none');
                }
            }

            // Initial update
            updateCountdown();

            // Update every second
            setInterval(updateCountdown, 1000);
        }
    };

    // Initialize when DOM is ready
    $(document).ready(function() {
        UserinfoManager.init();
    });

})(jQuery);
