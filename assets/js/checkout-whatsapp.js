/**
 * Arta Checkout WhatsApp Button JavaScript
 *
 * @package Arta_Consult_RX
 * @since 1.0.0
 */

(function($) {
    'use strict';

    // Storage key for checkout fields
    var STORAGE_KEY = 'arta_checkout_fields';

    /**
     * Save field value to localStorage
     */
    function saveFieldToStorage(fieldName, value) {
        try {
            var savedFields = JSON.parse(localStorage.getItem(STORAGE_KEY) || '{}');
            savedFields[fieldName] = value;
            localStorage.setItem(STORAGE_KEY, JSON.stringify(savedFields));
        } catch (e) {
            console.error('Error saving to localStorage:', e);
        }
    }

    /**
     * Get field value from localStorage
     */
    function getFieldFromStorage(fieldName) {
        try {
            var savedFields = JSON.parse(localStorage.getItem(STORAGE_KEY) || '{}');
            return savedFields[fieldName] || '';
        } catch (e) {
            console.error('Error reading from localStorage:', e);
            return '';
        }
    }

    /**
     * Save all form fields to localStorage
     */
    function saveAllFieldsToStorage() {
        var allFields = [
            'billing_first_name',
            'billing_last_name',
            'billing_company',
            'billing_country',
            'billing_address_1',
            'billing_address_2',
            'billing_city',
            'billing_state',
            'billing_postcode',
            'billing_phone',
            'billing_email',
            'arta_gender',
            'arta_birth_date',
            'arta_height',
            'arta_weight',
            'arta_chronic_diseases',
            'arta_current_medications',
            'arta_medical_history',
            'arta_program_goal',
            'arta_allergies'
        ];

        allFields.forEach(function(fieldName) {
            var $field = $('#' + fieldName);
            if ($field.length === 0) {
                $field = $('[name="' + fieldName + '"]');
            }

            if ($field.length > 0) {
                var value = $field.val();
                if ($field.attr('type') === 'checkbox') {
                    value = $field.is(':checked') ? 'yes' : '';
                }
                if (value) {
                    saveFieldToStorage(fieldName, value);
                }
            }
        });
    }

    /**
     * Restore all form fields from localStorage
     */
    function restoreFieldsFromStorage() {
        var allFields = [
            'billing_first_name',
            'billing_last_name',
            'billing_company',
            'billing_country',
            'billing_address_1',
            'billing_address_2',
            'billing_city',
            'billing_state',
            'billing_postcode',
            'billing_phone',
            'billing_email',
            'arta_gender',
            'arta_birth_date',
            'arta_height',
            'arta_weight',
            'arta_chronic_diseases',
            'arta_current_medications',
            'arta_medical_history',
            'arta_program_goal',
            'arta_allergies'
        ];

        allFields.forEach(function(fieldName) {
            var savedValue = getFieldFromStorage(fieldName);
            if (savedValue) {
                var $field = $('#' + fieldName);
                if ($field.length === 0) {
                    $field = $('[name="' + fieldName + '"]');
                }

                if ($field.length > 0) {
                    // Only restore if field is empty (don't overwrite existing values)
                    var currentValue = $field.val();
                    if (!currentValue || currentValue.trim() === '') {
                        $field.val(savedValue);
                        
                        // Trigger change event for WooCommerce to recognize the value
                        $field.trigger('change');
                        $field.trigger('blur');
                    }
                }
            }
        });
    }

    $(document).ready(function() {
        // Restore fields from localStorage on page load
        restoreFieldsFromStorage();

        // Save field values to localStorage when they change
        var allFields = [
            'billing_first_name',
            'billing_last_name',
            'billing_company',
            'billing_country',
            'billing_address_1',
            'billing_address_2',
            'billing_city',
            'billing_state',
            'billing_postcode',
            'billing_phone',
            'billing_email',
            'arta_gender',
            'arta_birth_date',
            'arta_height',
            'arta_weight',
            'arta_chronic_diseases',
            'arta_current_medications',
            'arta_medical_history',
            'arta_program_goal',
            'arta_allergies'
        ];

        // Attach change event listeners to all fields
        allFields.forEach(function(fieldName) {
            $(document).on('change blur input', '#' + fieldName + ', [name="' + fieldName + '"]', function() {
                var $field = $(this);
                var value = $field.val();
                if ($field.attr('type') === 'checkbox') {
                    value = $field.is(':checked') ? 'yes' : '';
                }
                saveFieldToStorage(fieldName, value);
            });
        });

        // Handle WhatsApp consultation button click
        $(document).on('click', '.arta-whatsapp-consultation-button', function(e) {
            e.preventDefault();
            
            var $button = $(this);
            var phone = $button.data('phone');
            var initialText = $button.data('initial-text');
            
            // Save all fields before opening WhatsApp
            saveAllFieldsToStorage();
            
            // Get required fields
            var requiredFields = [
                'billing_first_name',
                'billing_last_name',
                'billing_address_1',
                'billing_city',
                'billing_postcode',
                'billing_phone',
                'billing_email',
                'arta_gender',
                'arta_birth_date',
                'arta_height',
                'arta_weight'
            ];
            
            // Check if all required fields are filled
            var allFieldsFilled = true;
            var missingFields = [];
            
            requiredFields.forEach(function(fieldName) {
                var $field = $('#' + fieldName);
                if ($field.length === 0) {
                    // Try to find by name attribute
                    $field = $('[name="' + fieldName + '"]');
                }
                
                if ($field.length > 0) {
                    var value = $field.val();
                    if ($field.attr('type') === 'checkbox') {
                        value = $field.is(':checked') ? 'yes' : '';
                    }
                    
                    if (!value || value.trim() === '') {
                        allFieldsFilled = false;
                        missingFields.push(fieldName);
                    }
                }
            });
            
            if (!allFieldsFilled) {
                // Remove any existing error messages
                $('.arta-whatsapp-error-message').remove();
                
                // Create error message
                var errorMessage = arta_whatsapp.required_fields_error || 'لطفاً تمام فیلدهای اجباری را پر کنید';
                var $errorDiv = $('<div class="woocommerce-error arta-whatsapp-error-message" role="alert"></div>');
                $errorDiv.html(errorMessage);
                
                // Insert error message at the top of checkout form
                var $checkoutForm = $('form.checkout');
                if ($checkoutForm.length > 0) {
                    $checkoutForm.prepend($errorDiv);
                } else {
                    // Fallback: insert before the button
                    $button.before($errorDiv);
                }
                
                // Scroll to error message
                $('html, body').animate({
                    scrollTop: $errorDiv.offset().top - 100
                }, 500);
                
                // Scroll to first missing field after a short delay
                if (missingFields.length > 0) {
                    setTimeout(function() {
                        var firstMissingField = $('#' + missingFields[0]);
                        if (firstMissingField.length === 0) {
                            firstMissingField = $('[name="' + missingFields[0] + '"]');
                        }
                        if (firstMissingField.length > 0) {
                            $('html, body').animate({
                                scrollTop: firstMissingField.offset().top - 100
                            }, 500);
                            firstMissingField.focus();
                        }
                    }, 600);
                }
                
                // Auto-remove error message after 5 seconds
                setTimeout(function() {
                    $errorDiv.fadeOut(300, function() {
                        $(this).remove();
                    });
                }, 5000);
                
                return;
            }
            
            // Build WhatsApp message
            var message = initialText + '\n\n';
            
            // Field labels mapping
            var fieldLabels = {
                'billing_first_name': 'نام',
                'billing_last_name': 'نام خانوادگی',
                'billing_company': 'نام شرکت',
                'billing_country': 'کشور',
                'billing_address_1': 'آدرس',
                'billing_address_2': 'آدرس (خط دوم)',
                'billing_city': 'شهر',
                'billing_state': 'استان',
                'billing_postcode': 'کد پستی',
                'billing_phone': 'شماره تماس',
                'billing_email': 'ایمیل',
                'arta_gender': 'جنسیت',
                'arta_birth_date': 'تاریخ تولد',
                'arta_height': 'قد (سانتی‌متر)',
                'arta_weight': 'وزن (کیلوگرم)',
                'arta_chronic_diseases': 'بیماری‌های مزمن',
                'arta_current_medications': 'داروهای مصرفی فعلی',
                'arta_medical_history': 'سوابق پزشکی',
                'arta_program_goal': 'هدف از برنامه',
                'arta_allergies': 'آلرژی‌ها'
            };
            
            // Gender options mapping
            var genderOptions = {
                'male': 'مرد',
                'female': 'زن'
            };
            
            // Collect all form fields
            var allFields = [
                'billing_first_name',
                'billing_last_name',
                'billing_company',
                'billing_country',
                'billing_address_1',
                'billing_address_2',
                'billing_city',
                'billing_state',
                'billing_postcode',
                'billing_phone',
                'billing_email',
                'arta_gender',
                'arta_birth_date',
                'arta_height',
                'arta_weight',
                'arta_chronic_diseases',
                'arta_current_medications',
                'arta_medical_history',
                'arta_program_goal',
                'arta_allergies'
            ];
            
            allFields.forEach(function(fieldName) {
                var $field = $('#' + fieldName);
                if ($field.length === 0) {
                    $field = $('[name="' + fieldName + '"]');
                }
                
                if ($field.length > 0) {
                    var value = $field.val();
                    
                    // Handle select fields
                    if ($field.is('select')) {
                        var selectedOption = $field.find('option:selected');
                        if (selectedOption.length > 0) {
                            value = selectedOption.text();
                        }
                    }
                    
                    // Handle gender field
                    if (fieldName === 'arta_gender' && genderOptions[value]) {
                        value = genderOptions[value];
                    }
                    
                    // Only add field if it has a value
                    if (value && value.trim() !== '') {
                        var label = fieldLabels[fieldName] || fieldName;
                        message += label + ': ' + value + '\n';
                    }
                }
            });
            
            // Encode message for URL
            var encodedMessage = encodeURIComponent(message);
            
            // Build WhatsApp URL
            var whatsappUrl = 'https://web.whatsapp.com/send?phone=' + phone + '&text=' + encodedMessage;
            
            // Open in new tab
            window.open(whatsappUrl, '_blank');
        });

        // Clear localStorage when order is successfully placed
        $(document.body).on('checkout_place_order_success', function() {
            try {
                localStorage.removeItem(STORAGE_KEY);
            } catch (e) {
                console.error('Error clearing localStorage:', e);
            }
        });

        // Also listen for WooCommerce checkout update events
        $(document.body).on('updated_checkout', function() {
            // Restore fields after checkout update
            setTimeout(function() {
                restoreFieldsFromStorage();
            }, 100);
        });
    });
})(jQuery);



