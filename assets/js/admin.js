/**
 * Admin JavaScript for Arta Consult RX
 *
 * @package Arta_Consult_RX
 * @since 1.0.0
 */

console.log('Admin JS file loaded');

jQuery(document).ready(function($) {
    console.log('jQuery ready in admin.js');
    
    // Test if we can access the page
    if ($('#bulk-create-slots').length > 0) {
        console.log('Bulk create button found');
    } else {
        console.log('Bulk create button NOT found');
    }
    
    // Initialize admin functionality
    if (typeof ArtaAdmin !== 'undefined') {
        ArtaAdmin.init();
    } else {
        console.log('ArtaAdmin object not defined yet');
    }
});

});

// Admin object
var ArtaAdmin = {
        
        /**
         * Initialize admin functionality
         */
        init: function() {
            this.initBulkScheduler();
            this.initOrderManagement();
            this.initCalendar();
            this.initFormValidation();
            this.initLoadingSystem();
        },

        /**
         * Initialize bulk scheduler
         */
        initBulkScheduler: function() {
            var $bulkScheduler = $('#arta-bulk-scheduler');
            if ($bulkScheduler.length === 0) return;

            // Date range validation
            $('#start_date, #end_date').on('change', function() {
                var startDate = new Date($('#start_date').val());
                var endDate = new Date($('#end_date').val());
                
                if (startDate > endDate) {
                    alert(arta_admin.strings.error);
                    $(this).val('');
                }
            });

            // Time range validation
            $('#start_time, #end_time').on('change', function() {
                var startTime = $('#start_time').val();
                var endTime = $('#end_time').val();
                
                if (startTime && endTime && startTime >= endTime) {
                    alert(arta_admin.strings.error);
                    $(this).val('');
                }
            });

            // Bulk create slots
            $('#bulk-create-slots').on('click', function(e) {
                e.preventDefault();
                ArtaAdmin.bulkCreateSlots();
            });

            // Bulk delete slots
            $('#bulk-delete-slots').on('click', function(e) {
                e.preventDefault();
                ArtaAdmin.bulkDeleteSlots();
            });
        },

        /**
         * Initialize order management
         */
        initOrderManagement: function() {
            // Update order status
            $('.arta-update-status').on('click', function(e) {
                e.preventDefault();
                var orderId = $(this).data('order-id');
                var newStatus = $(this).data('status');
                ArtaAdmin.updateOrderStatus(orderId, newStatus);
            });

            // View order details
            $('.arta-view-details').on('click', function(e) {
                e.preventDefault();
                var orderId = $(this).data('order-id');
                ArtaAdmin.viewOrderDetails(orderId);
            });
        },

        /**
         * Initialize calendar
         */
        initCalendar: function() {
            var $calendar = $('#arta-calendar');
            if ($calendar.length === 0) return;

            // Calendar navigation
            $('.arta-calendar-prev').on('click', function(e) {
                e.preventDefault();
                ArtaAdmin.navigateCalendar('prev');
            });

            $('.arta-calendar-next').on('click', function(e) {
                e.preventDefault();
                ArtaAdmin.navigateCalendar('next');
            });

            // Appointment click
            $('.arta-calendar-appointment').on('click', function() {
                var appointmentId = $(this).data('appointment-id');
                ArtaAdmin.viewAppointmentDetails(appointmentId);
            });
        },

        /**
         * Initialize form validation
         */
        initFormValidation: function() {
            // Real-time validation
            $('input[required], select[required], textarea[required]').on('blur', function() {
                ArtaAdmin.validateField($(this));
            });

            // Form submission
            $('.arta-form').on('submit', function(e) {
                if (!ArtaAdmin.validateForm($(this))) {
                    e.preventDefault();
                }
            });
        },

        /**
         * Bulk create slots
         */
        bulkCreateSlots: function() {
            var formData = {
                action: 'arta_bulk_create_slots',
                nonce: arta_admin.nonce,
                doctor_id: $('#doctor_id').val(),
                start_date: $('#start_date').val(),
                end_date: $('#end_date').val(),
                start_time: $('#start_time').val(),
                end_time: $('#end_time').val(),
                interval: $('#interval').val(),
                days_of_week: $('input[name="days_of_week[]"]:checked').map(function() {
                    return this.value;
                }).get()
            };

            // Validate form data
            if (!ArtaAdmin.validateBulkSlotData(formData)) {
                return;
            }

            // Show loading
            var $button = $('#bulk-create-slots');
            ArtaAdmin.setButtonLoading($button, true);
            ArtaAdmin.showLoading('Creating appointment slots...');

            // Make AJAX request
            $.post(arta_admin.ajax_url, formData, function(response) {
                if (response.success) {
                    ArtaAdmin.showSuccessMessage(response.data.message);
                    // Refresh the page after a short delay
                    setTimeout(function() {
                        location.reload();
                    }, 1500);
                } else {
                    ArtaAdmin.showErrorMessage(response.data.message || arta_admin.strings.error);
                }
            }).fail(function() {
                ArtaAdmin.showErrorMessage(arta_admin.strings.error);
            }).always(function() {
                ArtaAdmin.setButtonLoading($button, false);
                ArtaAdmin.hideLoading();
            });
        },

        /**
         * Bulk delete slots
         */
        bulkDeleteSlots: function() {
            if (!confirm(arta_admin.strings.confirm_delete)) {
                return;
            }

            var formData = {
                action: 'arta_bulk_delete_slots',
                nonce: arta_admin.nonce,
                doctor_id: $('#doctor_id').val(),
                start_date: $('#start_date').val(),
                end_date: $('#end_date').val()
            };

            // Show loading
            var $button = $('#bulk-delete-slots');
            ArtaAdmin.setButtonLoading($button, true);
            ArtaAdmin.showLoading('Deleting appointment slots...');

            // Make AJAX request
            $.post(arta_admin.ajax_url, formData, function(response) {
                if (response.success) {
                    ArtaAdmin.showSuccessMessage(response.data.message);
                    // Refresh the page after a short delay
                    setTimeout(function() {
                        location.reload();
                    }, 1500);
                } else {
                    ArtaAdmin.showErrorMessage(response.data.message || arta_admin.strings.error);
                }
            }).fail(function() {
                ArtaAdmin.showErrorMessage(arta_admin.strings.error);
            }).always(function() {
                ArtaAdmin.setButtonLoading($button, false);
                ArtaAdmin.hideLoading();
            });
        },

        /**
         * Update order status
         */
        updateOrderStatus: function(orderId, newStatus) {
            var formData = {
                action: 'arta_update_order_status',
                nonce: arta_admin.nonce,
                order_id: orderId,
                new_status: newStatus
            };

            // Show loading
            ArtaAdmin.showLoading('Updating order status...');

            // Make AJAX request
            $.post(arta_admin.ajax_url, formData, function(response) {
                if (response.success) {
                    ArtaAdmin.showSuccessMessage(response.data.message);
                    // Update UI
                    $('.arta-order-status-' + orderId).text(newStatus);
                } else {
                    ArtaAdmin.showErrorMessage(response.data.message || arta_admin.strings.error);
                }
            }).fail(function() {
                ArtaAdmin.showErrorMessage(arta_admin.strings.error);
            }).always(function() {
                ArtaAdmin.hideLoading();
            });
        },

        /**
         * View order details
         */
        viewOrderDetails: function(orderId) {
            var formData = {
                action: 'arta_get_order_details',
                nonce: arta_admin.nonce,
                order_id: orderId
            };

            // Show loading
            ArtaAdmin.showLoading('Loading order details...');

            // Make AJAX request
            $.post(arta_admin.ajax_url, formData, function(response) {
                if (response.success) {
                    ArtaAdmin.showOrderDetailsModal(response.data.order_details);
                } else {
                    ArtaAdmin.showErrorMessage(response.data.message || arta_admin.strings.error);
                }
            }).fail(function() {
                ArtaAdmin.showErrorMessage(arta_admin.strings.error);
            }).always(function() {
                ArtaAdmin.hideLoading();
            });
        },

        /**
         * Show order details modal
         */
        showOrderDetailsModal: function(orderDetails) {
            var modalHtml = '<div id="arta-order-modal" class="arta-modal">';
            modalHtml += '<div class="arta-modal-content">';
            modalHtml += '<span class="arta-modal-close">&times;</span>';
            modalHtml += '<h2>Order Details #' + orderDetails.id + '</h2>';
            modalHtml += '<div class="arta-order-details">';
            modalHtml += '<p><strong>Status:</strong> ' + orderDetails.status + '</p>';
            modalHtml += '<p><strong>Date:</strong> ' + orderDetails.date + '</p>';
            modalHtml += '<p><strong>Total:</strong> $' + orderDetails.total + '</p>';
            modalHtml += '<p><strong>Customer:</strong> ' + orderDetails.customer.name + '</p>';
            modalHtml += '<p><strong>Email:</strong> ' + orderDetails.customer.email + '</p>';
            modalHtml += '<p><strong>Phone:</strong> ' + orderDetails.customer.phone + '</p>';
            modalHtml += '</div>';
            modalHtml += '</div>';
            modalHtml += '</div>';

            $('body').append(modalHtml);
            $('#arta-order-modal').fadeIn();

            // Close modal
            $('.arta-modal-close').on('click', function() {
                $('#arta-order-modal').fadeOut(function() {
                    $(this).remove();
                });
            });

            // Close on outside click
            $(document).on('click', function(e) {
                if ($(e.target).hasClass('arta-modal')) {
                    $('#arta-order-modal').fadeOut(function() {
                        $(this).remove();
                    });
                }
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
            location.href = '?page=arta-appointments&month=' + (date.getMonth() + 1) + '&year=' + date.getFullYear();
        },

        /**
         * View appointment details
         */
        viewAppointmentDetails: function(appointmentId) {
            // Implementation for viewing appointment details
            console.log('View appointment:', appointmentId);
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

            if ($field.attr('type') === 'email' && value && !ArtaAdmin.isValidEmail(value)) {
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
                if (!ArtaAdmin.validateField($(this))) {
                    isValid = false;
                }
            });

            return isValid;
        },

        /**
         * Validate bulk slot data
         */
        validateBulkSlotData: function(data) {
            if (!data.doctor_id || !data.start_date || !data.end_date) {
                alert('Please fill in all required fields.');
                return false;
            }

            if (data.start_date > data.end_date) {
                alert('Start date must be before end date.');
                return false;
            }

            if (!data.start_time || !data.end_time) {
                alert('Please select time range.');
                return false;
            }

            if (data.start_time >= data.end_time) {
                alert('Start time must be before end time.');
                return false;
            }

            if (!data.interval || data.interval < 15) {
                alert('Interval must be at least 15 minutes.');
                return false;
            }

            if (!data.days_of_week || data.days_of_week.length === 0) {
                alert('Please select at least one day of the week.');
                return false;
            }

            return true;
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
            if (!$('#arta-loading-styles').length) {
                $('head').append(`
                    <style id="arta-loading-styles">
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
                        
                        .arta-admin-message {
                            position: fixed;
                            top: 32px;
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
                        
                        .arta-admin-message-success {
                            background: #d4edda;
                            color: #155724;
                            border: 1px solid #c3e6cb;
                        }
                        
                        .arta-admin-message-error {
                            background: #f8d7da;
                            color: #721c24;
                            border: 1px solid #f5c6cb;
                        }
                        
                        .arta-admin-message-info {
                            background: #cce5ff;
                            color: #004085;
                            border: 1px solid #99d3ff;
                        }
                        
                        .arta-admin-message-warning {
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
            message = message || arta_admin.strings.loading;
            
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
            $('.arta-admin-message').remove();
            
            var messageClass = 'arta-admin-message arta-admin-message-' + type;
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
                $('.arta-admin-message').fadeOut(function() {
                    $(this).remove();
                });
            }, 5000);
            
            // Close button
            $('.arta-admin-message .message-close').on('click', function() {
                $(this).parent().fadeOut(function() {
                    $(this).remove();
                });
            });
        }
    };

})(jQuery);
