/**
 * Frontend JavaScript for Arta Consult RX
 *
 * @package Arta_Consult_RX
 * @since 1.0.0
 */

(function($) {
    'use strict';

    // Initialize when document is ready
    $(document).ready(function() {
        ArtaFrontend.init();
    });

    // Frontend object
    var ArtaFrontend = {
        
        /**
         * Initialize frontend functionality
         */
        init: function() {
            this.initConsultationForm();
            this.initAppointmentBooking();
            this.initProductRequests();
            this.initCalendar();
            this.initFormValidation();
            this.initUserDashboard();
            this.initLoadingSystem();
        },

        /**
         * Initialize consultation form
         */
        initConsultationForm: function() {
            var $form = $('#arta-consultation-form');
            if ($form.length === 0) return;

            // Form submission
            $form.on('submit', function(e) {
                e.preventDefault();
                ArtaFrontend.submitConsultationForm($(this));
            });

            // Real-time validation
            $form.find('input, select, textarea').on('blur', function() {
                ArtaFrontend.validateField($(this));
            });
        },

        /**
         * Initialize appointment booking
         */
        initAppointmentBooking: function() {
            var $booking = $('#arta-appointment-booking');
            if ($booking.length === 0) return;

            // Doctor selection
            $('#doctor-select').on('change', function() {
                var doctorId = $(this).val();
                if (doctorId) {
                    ArtaFrontend.loadDoctorAvailability(doctorId);
                }
            });

            // Date selection
            $('#appointment-date').on('change', function() {
                var doctorId = $('#doctor-select').val();
                var date = $(this).val();
                if (doctorId && date) {
                    ArtaFrontend.loadAvailableSlots(doctorId, date);
                }
            });

            // Time slot selection
            $(document).on('click', '.arta-time-slot.available', function() {
                $('.arta-time-slot').removeClass('selected');
                $(this).addClass('selected');
                $('#selected-time').val($(this).data('time'));
            });

            // Booking form submission
            $('#appointment-booking-form').on('submit', function(e) {
                e.preventDefault();
                ArtaFrontend.submitAppointmentBooking($(this));
            });
        },

        /**
         * Initialize product requests
         */
        initProductRequests: function() {
            // Product request buttons
            $('.arta-request-product').on('click', function(e) {
                e.preventDefault();
                var productId = $(this).data('product-id');
                ArtaFrontend.showProductRequestModal(productId);
            });

            // Product request form submission
            $(document).on('submit', '#product-request-form', function(e) {
                e.preventDefault();
                ArtaFrontend.submitProductRequest($(this));
            });
        },

        /**
         * Initialize calendar
         */
        initCalendar: function() {
            // Calendar navigation
            $('.arta-calendar-prev').on('click', function(e) {
                e.preventDefault();
                ArtaFrontend.navigateCalendar('prev');
            });

            $('.arta-calendar-next').on('click', function(e) {
                e.preventDefault();
                ArtaFrontend.navigateCalendar('next');
            });

            // Date selection
            $('.arta-calendar-day').on('click', function() {
                if ($(this).hasClass('available')) {
                    $('.arta-calendar-day').removeClass('selected');
                    $(this).addClass('selected');
                    var date = $(this).data('date');
                    $('#selected-date').val(date);
                }
            });
        },

        /**
         * Initialize form validation
         */
        initFormValidation: function() {
            // Real-time validation
            $('input[required], select[required], textarea[required]').on('blur', function() {
                ArtaFrontend.validateField($(this));
            });

            // Form submission validation
            $('.arta-form').on('submit', function(e) {
                if (!ArtaFrontend.validateForm($(this))) {
                    e.preventDefault();
                }
            });
        },

        /**
         * Initialize user dashboard
         */
        initUserDashboard: function() {
            // Tab switching
            $('.arta-dashboard-tab').on('click', function(e) {
                e.preventDefault();
                var tab = $(this).data('tab');
                ArtaFrontend.switchDashboardTab(tab);
            });

            // Refresh data
            $('.arta-refresh-data').on('click', function(e) {
                e.preventDefault();
                var section = $(this).data('section');
                ArtaFrontend.refreshDashboardSection(section);
            });
        },

        /**
         * Submit consultation form
         */
        submitConsultationForm: function($form) {
            if (!ArtaFrontend.validateForm($form)) {
                return;
            }

            var formData = $form.serialize();
            formData += '&action=arta_submit_consultation&nonce=' + arta_frontend.nonce;

            // Show loading
            var $submitBtn = $form.find('button[type="submit"]');
            ArtaFrontend.setButtonLoading($submitBtn, true);
            ArtaFrontend.showLoading('Submitting consultation request...');

            // Make AJAX request
            $.post(arta_frontend.ajax_url, formData, function(response) {
                if (response.success) {
                    ArtaFrontend.showSuccessMessage(response.data.message);
                    $form[0].reset();
                } else {
                    ArtaFrontend.showErrorMessage(response.data.message || arta_frontend.strings.error);
                }
            }).fail(function() {
                ArtaFrontend.showErrorMessage(arta_frontend.strings.error);
            }).always(function() {
                ArtaFrontend.setButtonLoading($submitBtn, false);
                ArtaFrontend.hideLoading();
            });
        },

        /**
         * Load doctor availability
         */
        loadDoctorAvailability: function(doctorId) {
            var formData = {
                action: 'arta_get_doctor_availability',
                nonce: arta_frontend.nonce,
                doctor_id: doctorId,
                start_date: moment().format('YYYY-MM-DD'),
                end_date: moment().add(30, 'days').format('YYYY-MM-DD')
            };

            // Show loading
            $('#availability-calendar').html('<div class="arta-loading"></div>');

            // Make AJAX request
            $.post(arta_frontend.ajax_url, formData, function(response) {
                if (response.success) {
                    ArtaFrontend.renderAvailabilityCalendar(response.data.availability);
                } else {
                    $('#availability-calendar').html('<p>' + (response.data.message || arta_frontend.strings.error) + '</p>');
                }
            }).fail(function() {
                $('#availability-calendar').html('<p>' + arta_frontend.strings.error + '</p>');
            });
        },

        /**
         * Load available slots
         */
        loadAvailableSlots: function(doctorId, date) {
            var formData = {
                action: 'arta_get_available_slots',
                nonce: arta_frontend.nonce,
                doctor_id: doctorId,
                date: date
            };

            // Show loading
            $('#time-slots').html('<div class="arta-loading"></div>');

            // Make AJAX request
            $.post(arta_frontend.ajax_url, formData, function(response) {
                if (response.success) {
                    ArtaFrontend.renderTimeSlots(response.data.slots);
                } else {
                    $('#time-slots').html('<p>' + (response.data.message || arta_frontend.strings.error) + '</p>');
                }
            }).fail(function() {
                $('#time-slots').html('<p>' + arta_frontend.strings.error + '</p>');
            });
        },

        /**
         * Submit appointment booking
         */
        submitAppointmentBooking: function($form) {
            if (!ArtaFrontend.validateForm($form)) {
                return;
            }

            var formData = $form.serialize();
            formData += '&action=arta_book_appointment&nonce=' + arta_frontend.nonce;

            // Show loading
            var $submitBtn = $form.find('button[type="submit"]');
            ArtaFrontend.setButtonLoading($submitBtn, true);
            ArtaFrontend.showLoading('Booking appointment...');

            // Make AJAX request
            $.post(arta_frontend.ajax_url, formData, function(response) {
                if (response.success) {
                    ArtaFrontend.showSuccessMessage(response.data.message);
                    $form[0].reset();
                    $('.arta-time-slot').removeClass('selected');
                } else {
                    ArtaFrontend.showErrorMessage(response.data.message || arta_frontend.strings.error);
                }
            }).fail(function() {
                ArtaFrontend.showErrorMessage(arta_frontend.strings.error);
            }).always(function() {
                ArtaFrontend.setButtonLoading($submitBtn, false);
                ArtaFrontend.hideLoading();
            });
        },

        /**
         * Show product request modal
         */
        showProductRequestModal: function(productId) {
            var modalHtml = '<div id="arta-product-modal" class="arta-modal">';
            modalHtml += '<div class="arta-modal-content">';
            modalHtml += '<span class="arta-modal-close">&times;</span>';
            modalHtml += '<h2>Request Product</h2>';
            modalHtml += '<form id="product-request-form">';
            modalHtml += '<input type="hidden" name="product_id" value="' + productId + '">';
            modalHtml += '<div class="arta-form-group">';
            modalHtml += '<label for="quantity">Quantity <span class="required">*</span></label>';
            modalHtml += '<input type="number" id="quantity" name="quantity" required min="1" value="1">';
            modalHtml += '</div>';
            modalHtml += '<div class="arta-form-group">';
            modalHtml += '<label for="notes">Notes</label>';
            modalHtml += '<textarea id="notes" name="notes" rows="3"></textarea>';
            modalHtml += '</div>';
            modalHtml += '<div class="arta-form-submit">';
            modalHtml += '<button type="submit" class="arta-btn arta-btn-primary">Submit Request</button>';
            modalHtml += '</div>';
            modalHtml += '</form>';
            modalHtml += '</div>';
            modalHtml += '</div>';

            $('body').append(modalHtml);
            $('#arta-product-modal').fadeIn();

            // Close modal
            $('.arta-modal-close').on('click', function() {
                $('#arta-product-modal').fadeOut(function() {
                    $(this).remove();
                });
            });

            // Close on outside click
            $(document).on('click', function(e) {
                if ($(e.target).hasClass('arta-modal')) {
                    $('#arta-product-modal').fadeOut(function() {
                        $(this).remove();
                    });
                }
            });
        },

        /**
         * Submit product request
         */
        submitProductRequest: function($form) {
            if (!ArtaFrontend.validateForm($form)) {
                return;
            }

            var formData = $form.serialize();
            formData += '&action=arta_request_product&nonce=' + arta_frontend.nonce;

            // Show loading
            var $submitBtn = $form.find('button[type="submit"]');
            ArtaFrontend.setButtonLoading($submitBtn, true);
            ArtaFrontend.showLoading('Submitting product request...');

            // Make AJAX request
            $.post(arta_frontend.ajax_url, formData, function(response) {
                if (response.success) {
                    ArtaFrontend.showSuccessMessage(response.data.message);
                    $('#arta-product-modal').fadeOut(function() {
                        $(this).remove();
                    });
                } else {
                    ArtaFrontend.showErrorMessage(response.data.message || arta_frontend.strings.error);
                }
            }).fail(function() {
                ArtaFrontend.showErrorMessage(arta_frontend.strings.error);
            }).always(function() {
                ArtaFrontend.setButtonLoading($submitBtn, false);
                ArtaFrontend.hideLoading();
            });
        },

        /**
         * Navigate calendar
         */
        navigateCalendar: function(direction) {
            var currentMonth = $('#current-month').data('month');
            var currentYear = $('#current-year').data('year');
            
            var date = new Date(currentYear, currentMonth - 1);
            if (direction === 'prev') {
                date.setMonth(date.getMonth() - 1);
            } else {
                date.setMonth(date.getMonth() + 1);
            }

            // Reload calendar with new month
            location.href = '?month=' + (date.getMonth() + 1) + '&year=' + date.getFullYear();
        },

        /**
         * Switch dashboard tab
         */
        switchDashboardTab: function(tab) {
            $('.arta-dashboard-tab').removeClass('active');
            $('.arta-dashboard-tab[data-tab="' + tab + '"]').addClass('active');
            
            $('.arta-dashboard-section').hide();
            $('.arta-dashboard-section[data-section="' + tab + '"]').show();
        },

        /**
         * Refresh dashboard section
         */
        refreshDashboardSection: function(section) {
            // Implementation for refreshing dashboard data
            console.log('Refresh section:', section);
        },

        /**
         * Render availability calendar
         */
        renderAvailabilityCalendar: function(availability) {
            var html = '<div class="arta-availability-calendar">';
            html += '<div class="arta-calendar-grid">';
            
            // Calendar implementation
            html += '</div>';
            html += '</div>';
            
            $('#availability-calendar').html(html);
        },

        /**
         * Render time slots
         */
        renderTimeSlots: function(slots) {
            var html = '<div class="arta-time-slots">';
            
            if (slots.length === 0) {
                html += '<p>No available time slots for this date.</p>';
            } else {
                slots.forEach(function(slot) {
                    html += '<div class="arta-time-slot available" data-time="' + slot.slot_time + '">';
                    html += moment(slot.slot_time, 'HH:mm:ss').format('h:mm A');
                    html += '</div>';
                });
            }
            
            html += '</div>';
            $('#time-slots').html(html);
        },

        /**
         * Validate field
         */
        validateField: function($field) {
            var value = $field.val().trim();
            var isValid = true;

            if ($field.prop('required') && !value) {
                isValid = false;
            }

            if ($field.attr('type') === 'email' && value && !ArtaFrontend.isValidEmail(value)) {
                isValid = false;
            }

            if ($field.attr('type') === 'number' && value && isNaN(value)) {
                isValid = false;
            }

            // Update field appearance
            if (isValid) {
                $field.removeClass('arta-error').addClass('arta-valid');
            } else {
                $field.removeClass('arta-valid').addClass('arta-error');
            }

            return isValid;
        },

        /**
         * Validate form
         */
        validateForm: function($form) {
            var isValid = true;
            var $requiredFields = $form.find('input[required], select[required], textarea[required]');

            $requiredFields.each(function() {
                if (!ArtaFrontend.validateField($(this))) {
                    isValid = false;
                }
            });

            return isValid;
        },

        /**
         * Show message
         */
        showMessage: function(message, type) {
            var messageHtml = '<div class="arta-message arta-message-' + type + '">' + message + '</div>';
            
            $('.arta-message').remove();
            $('body').prepend(messageHtml);
            
            // Auto-hide after 5 seconds
            setTimeout(function() {
                $('.arta-message').fadeOut(function() {
                    $(this).remove();
                });
            }, 5000);
        },

        /**
         * Check if email is valid
         */
        isValidEmail: function(email) {
            var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return emailRegex.test(email);
        },

        /**
         * Initialize loading system
         */
        initLoadingSystem: function() {
            // Add loading styles to head
            if (!$('#arta-frontend-loading-styles').length) {
                $('head').append(`
                    <style id="arta-frontend-loading-styles">
                        .arta-loading-overlay {
                            position: fixed;
                            top: 0;
                            left: 0;
                            width: 100%;
                            height: 100%;
                            background: rgba(255, 255, 255, 0.9);
                            display: flex;
                            justify-content: center;
                            align-items: center;
                            z-index: 999999;
                            backdrop-filter: blur(2px);
                        }
                        
                        .arta-loading-spinner {
                            width: 50px;
                            height: 50px;
                            border: 4px solid #f3f3f3;
                            border-top: 4px solid #0073aa;
                            border-radius: 50%;
                            animation: arta-spin 1s linear infinite;
                        }
                        
                        .arta-loading-text {
                            margin-top: 15px;
                            color: #333;
                            font-weight: 500;
                            text-align: center;
                        }
                        
                        .arta-loading-content {
                            text-align: center;
                            background: #fff;
                            padding: 30px;
                            border-radius: 8px;
                            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
                        }
                        
                        @keyframes arta-spin {
                            0% { transform: rotate(0deg); }
                            100% { transform: rotate(360deg); }
                        }
                        
                        .arta-button-loading {
                            position: relative;
                            pointer-events: none;
                            opacity: 0.7;
                        }
                        
                        .arta-button-loading::after {
                            content: '';
                            position: absolute;
                            top: 50%;
                            left: 50%;
                            width: 16px;
                            height: 16px;
                            margin: -8px 0 0 -8px;
                            border: 2px solid transparent;
                            border-top: 2px solid #fff;
                            border-radius: 50%;
                            animation: arta-spin 1s linear infinite;
                        }
                        
                        .arta-button-loading .button-text {
                            opacity: 0;
                        }
                        
                        .arta-frontend-message {
                            position: fixed;
                            top: 20px;
                            right: 20px;
                            z-index: 999999;
                            padding: 15px 20px;
                            border-radius: 4px;
                            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
                            display: flex;
                            align-items: center;
                            gap: 10px;
                            min-width: 300px;
                            max-width: 500px;
                            animation: slideInRight 0.3s ease;
                        }
                        
                        .arta-frontend-message-success {
                            background: #d4edda;
                            color: #155724;
                            border: 1px solid #c3e6cb;
                        }
                        
                        .arta-frontend-message-error {
                            background: #f8d7da;
                            color: #721c24;
                            border: 1px solid #f5c6cb;
                        }
                        
                        .arta-frontend-message-info {
                            background: #cce5ff;
                            color: #004085;
                            border: 1px solid #99d3ff;
                        }
                        
                        .arta-frontend-message-warning {
                            background: #fff3cd;
                            color: #856404;
                            border: 1px solid #ffeaa7;
                        }
                        
                        .message-icon {
                            font-weight: bold;
                            font-size: 16px;
                        }
                        
                        .message-text {
                            flex: 1;
                            font-weight: 500;
                        }
                        
                        .message-close {
                            cursor: pointer;
                            font-size: 18px;
                            font-weight: bold;
                            opacity: 0.7;
                            transition: opacity 0.3s ease;
                        }
                        
                        .message-close:hover {
                            opacity: 1;
                        }
                        
                        @keyframes slideInRight {
                            from {
                                transform: translateX(100%);
                                opacity: 0;
                            }
                            to {
                                transform: translateX(0);
                                opacity: 1;
                            }
                        }
                    </style>
                `);
            }
        },

        /**
         * Show loading overlay
         */
        showLoading: function(message) {
            message = message || arta_frontend.strings.loading;
            
            if ($('#arta-loading-overlay').length) {
                this.hideLoading();
            }
            
            var loadingHtml = `
                <div id="arta-loading-overlay" class="arta-loading-overlay">
                    <div class="arta-loading-content">
                        <div class="arta-loading-spinner"></div>
                        <div class="arta-loading-text">${message}</div>
                    </div>
                </div>
            `;
            
            $('body').append(loadingHtml);
        },

        /**
         * Hide loading overlay
         */
        hideLoading: function() {
            $('#arta-loading-overlay').fadeOut(300, function() {
                $(this).remove();
            });
        },

        /**
         * Set button loading state
         */
        setButtonLoading: function($button, loading) {
            if (loading) {
                var originalText = $button.text();
                $button.data('original-text', originalText);
                $button.addClass('arta-button-loading');
                $button.html('<span class="button-text">' + originalText + '</span>');
                $button.prop('disabled', true);
            } else {
                var originalText = $button.data('original-text');
                $button.removeClass('arta-button-loading');
                $button.html(originalText);
                $button.prop('disabled', false);
            }
        },

        /**
         * Show success message
         */
        showSuccessMessage: function(message) {
            this.showMessage(message, 'success');
        },

        /**
         * Show error message
         */
        showErrorMessage: function(message) {
            this.showMessage(message, 'error');
        },

        /**
         * Show info message
         */
        showInfoMessage: function(message) {
            this.showMessage(message, 'info');
        },

        /**
         * Show message
         */
        showMessage: function(message, type) {
            // Remove existing messages
            $('.arta-frontend-message').remove();
            
            var messageClass = 'arta-frontend-message arta-frontend-message-' + type;
            var icon = '';
            
            switch(type) {
                case 'success':
                    icon = '✓';
                    break;
                case 'error':
                    icon = '✗';
                    break;
                case 'info':
                    icon = 'ℹ';
                    break;
                case 'warning':
                    icon = '⚠';
                    break;
            }
            
            var messageHtml = `
                <div class="${messageClass}">
                    <span class="message-icon">${icon}</span>
                    <span class="message-text">${message}</span>
                    <span class="message-close">&times;</span>
                </div>
            `;
            
            $('body').prepend(messageHtml);
            
            // Auto-hide after 5 seconds
            setTimeout(function() {
                $('.arta-frontend-message').fadeOut(function() {
                    $(this).remove();
                });
            }, 5000);
            
            // Close button
            $('.arta-frontend-message .message-close').on('click', function() {
                $(this).parent().fadeOut(function() {
                    $(this).remove();
                });
            });
        }
    };

})(jQuery);
