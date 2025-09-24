jQuery(document).ready(function($) {
    'use strict';

    // Appointment form modal
    var $modal = null;
    var currentStep = 1;
    var totalSteps = 3;
    var formData = {};

    // Initialize appointment form
    function initAppointmentForm() {
        // Create modal HTML
        createModal();
        
        // Bind events
        bindEvents();
    }

    // Create modal HTML
    function createModal() {
        var modalHTML = `
            <div id="arta-appointment-modal" class="arta-modal" style="display: none;">
                <div class="arta-modal-overlay"></div>
                <div class="arta-modal-content">
                    <div class="arta-modal-header">
                        <h2>رزرو نوبت مشاوره</h2>
                        <button type="button" class="arta-modal-close">&times;</button>
                    </div>
                    <div class="arta-modal-body">
                        <div class="arta-form-progress">
                            <div class="arta-progress-bar">
                                <div class="arta-progress-fill" style="width: 33.33%"></div>
                            </div>
                            <div class="arta-progress-steps">
                                <span class="arta-step active" data-step="1">اطلاعات شخصی</span>
                                <span class="arta-step" data-step="2">انتخاب پزشک</span>
                                <span class="arta-step" data-step="3">انتخاب زمان</span>
                            </div>
                        </div>
                        
                        <form id="arta-appointment-form" class="arta-appointment-form">
                            <!-- Step 1: Personal Information -->
                            <div class="arta-form-step" data-step="1">
                                <h3>اطلاعات شخصی</h3>
                                
                                <div class="arta-form-row">
                                    <div class="arta-form-group">
                                        <label for="full_name">نام و نام خانوادگی *</label>
                                        <input type="text" id="full_name" name="full_name" required>
                                    </div>
                                    <div class="arta-form-group">
                                        <label for="gender">جنسیت *</label>
                                        <select id="gender" name="gender" required>
                                            <option value="">انتخاب کنید</option>
                                            <option value="male">مرد</option>
                                            <option value="female">زن</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="arta-form-row">
                                    <div class="arta-form-group">
                                        <label for="birth_date">تاریخ تولد *</label>
                                        <input type="date" id="birth_date" name="birth_date" required>
                                    </div>
                                    <div class="arta-form-group">
                                        <label for="height">قد (سانتی‌متر)</label>
                                        <input type="number" id="height" name="height" min="100" max="250">
                                    </div>
                                    <div class="arta-form-group">
                                        <label for="weight">وزن (کیلوگرم)</label>
                                        <input type="number" id="weight" name="weight" min="30" max="200">
                                    </div>
                                </div>
                                
                                <div class="arta-form-row">
                                    <div class="arta-form-group">
                                        <label for="email">ایمیل *</label>
                                        <input type="email" id="email" name="email" required>
                                    </div>
                                    <div class="arta-form-group">
                                        <label for="phone">شماره تماس *</label>
                                        <input type="tel" id="phone" name="phone" required placeholder="09123456789">
                                    </div>
                                </div>
                                
                                <div class="arta-form-group">
                                    <label for="chronic_diseases">بیماری‌های مزمن</label>
                                    <textarea id="chronic_diseases" name="chronic_diseases" rows="3" placeholder="در صورت وجود بیماری مزمن، آن را ذکر کنید"></textarea>
                                </div>
                                
                                <div class="arta-form-group">
                                    <label for="medications">داروهای مصرفی</label>
                                    <textarea id="medications" name="medications" rows="3" placeholder="داروهایی که در حال حاضر مصرف می‌کنید"></textarea>
                                </div>
                                
                                <div class="arta-form-group">
                                    <label for="medical_history">سوابق درمانی</label>
                                    <textarea id="medical_history" name="medical_history" rows="3" placeholder="سوابق درمانی و جراحی‌های قبلی"></textarea>
                                </div>
                                
                                <div class="arta-form-group">
                                    <label for="program_goal">هدف از برنامه *</label>
                                    <textarea id="program_goal" name="program_goal" rows="4" required placeholder="هدف خود از شرکت در این برنامه را شرح دهید"></textarea>
                                </div>
                                
                                <div class="arta-form-group">
                                    <label class="arta-checkbox-label">
                                        <input type="checkbox" id="medical_consultation" name="medical_consultation" required>
                                        <span class="arta-checkbox-text">تایید مشاوره پزشکی *</span>
                                    </label>
                                </div>
                            </div>
                            
                            <!-- Step 2: Doctor Selection -->
                            <div class="arta-form-step" data-step="2" style="display: none;">
                                <h3>انتخاب پزشک</h3>
                                <div id="arta-doctors-list" class="arta-doctors-list">
                                    <!-- Doctors will be loaded here -->
                                </div>
                            </div>
                            
                            <!-- Step 3: Time Selection -->
                            <div class="arta-form-step" data-step="3" style="display: none;">
                                <h3>انتخاب زمان نوبت</h3>
                                <div class="arta-form-group">
                                    <label for="appointment_date">تاریخ نوبت *</label>
                                    <input type="date" id="appointment_date" name="appointment_date" required>
                                </div>
                                <div id="arta-time-slots" class="arta-time-slots">
                                    <!-- Time slots will be loaded here -->
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="arta-modal-footer">
                        <button type="button" class="arta-btn arta-btn-secondary" id="arta-prev-step" style="display: none;">قبلی</button>
                        <button type="button" class="arta-btn arta-btn-primary" id="arta-next-step">بعدی</button>
                        <button type="button" class="arta-btn arta-btn-success" id="arta-submit-form" style="display: none;">ثبت درخواست</button>
                    </div>
                </div>
            </div>
        `;
        
        $('body').append(modalHTML);
        $modal = $('#arta-appointment-modal');
    }

    // Bind events
    function bindEvents() {
        // Open modal
        $(document).on('click', '.arta-btn-reservation', function(e) {
            e.preventDefault();
            openModal();
        });

        // Close modal
        $(document).on('click', '.arta-modal-close, .arta-modal-overlay', function() {
            closeModal();
        });

        // Next step
        $(document).on('click', '#arta-next-step', function() {
            if (validateCurrentStep()) {
                nextStep();
            }
        });

        // Previous step
        $(document).on('click', '#arta-prev-step', function() {
            prevStep();
        });

        // Submit form
        $(document).on('click', '#arta-submit-form', function() {
            submitForm();
        });

        // Doctor selection
        $(document).on('click', '.arta-doctor-item', function() {
            $('.arta-doctor-item').removeClass('selected');
            $(this).addClass('selected');
            formData.doctor_id = $(this).data('doctor-id');
            console.log('Doctor selected:', formData.doctor_id); // Debug
        });

        // Date change
        $(document).on('change', '#appointment_date', function() {
            loadTimeSlots();
        });

        // Time slot selection
        $(document).on('click', '.arta-time-slot', function() {
            $('.arta-time-slot').removeClass('selected');
            $(this).addClass('selected');
            formData.appointment_id = $(this).data('appointment-id');
        });
    }

    // Open modal
    function openModal() {
        currentStep = 1;
        formData = {};
        formData.program_id = arta_ajax.program_id;
        
        // Load user data if logged in
        loadUserData();
        
        // Load doctors
        loadDoctors();
        
        // Reset form
        $('#arta-appointment-form')[0].reset();
        showStep(1);
        $modal.fadeIn();
    }

    // Close modal
    function closeModal() {
        $modal.fadeOut();
    }

    // Show step
    function showStep(step) {
        $('.arta-form-step').hide();
        $('.arta-form-step[data-step="' + step + '"]').show();
        
        // Update progress
        var progress = (step / totalSteps) * 100;
        $('.arta-progress-fill').css('width', progress + '%');
        
        // Update step indicators
        $('.arta-step').removeClass('active');
        $('.arta-step[data-step="' + step + '"]').addClass('active');
        
        // Update buttons
        $('#arta-prev-step').toggle(step > 1);
        $('#arta-next-step').toggle(step < totalSteps);
        $('#arta-submit-form').toggle(step === totalSteps);
    }

    // Next step
    function nextStep() {
        if (currentStep < totalSteps) {
            currentStep++;
            showStep(currentStep);
        }
    }

    // Previous step
    function prevStep() {
        if (currentStep > 1) {
            currentStep--;
            showStep(currentStep);
        }
    }

    // Validate current step
    function validateCurrentStep() {
        var isValid = true;
        var $currentStep = $('.arta-form-step[data-step="' + currentStep + '"]');
        
        // Clear previous errors
        $currentStep.find('.arta-error').remove();
        $currentStep.find('.arta-form-group').removeClass('error');
        
        if (currentStep === 1) {
            // Validate personal information
            var requiredFields = ['full_name', 'gender', 'birth_date', 'email', 'phone'];
            
            requiredFields.forEach(function(field) {
                var $field = $currentStep.find('[name="' + field + '"]');
                if (!$field.val().trim()) {
                    showFieldError($field, arta_ajax.strings.required_field);
                    isValid = false;
                }
            });
            
            // Validate email
            var email = $currentStep.find('[name="email"]').val();
            if (email && !isValidEmail(email)) {
                showFieldError($currentStep.find('[name="email"]'), arta_ajax.strings.invalid_email);
                isValid = false;
            }
            
            // Validate phone
            var phone = $currentStep.find('[name="phone"]').val();
            if (phone && !isValidPhone(phone)) {
                showFieldError($currentStep.find('[name="phone"]'), arta_ajax.strings.invalid_phone);
                isValid = false;
            }
            
            // Validate medical consultation checkbox
            if (!$currentStep.find('[name="medical_consultation"]').is(':checked')) {
                showFieldError($currentStep.find('[name="medical_consultation"]'), arta_ajax.strings.required_field);
                isValid = false;
            }
            
        } else if (currentStep === 2) {
            // Validate doctor selection
            if (!formData.doctor_id) {
                showStepError($currentStep, 'لطفاً یک پزشک انتخاب کنید');
                isValid = false;
            } else {
                console.log('Doctor validation passed:', formData.doctor_id); // Debug
            }
            
        } else if (currentStep === 3) {
            // Validate time selection
            if (!$('#appointment_date').val()) {
                showFieldError($('#appointment_date'), arta_ajax.strings.required_field);
                isValid = false;
            }
            
            if (!formData.appointment_id) {
                showStepError($currentStep, 'لطفاً یک زمان نوبت انتخاب کنید');
                isValid = false;
            }
        }
        
        return isValid;
    }

    // Show field error
    function showFieldError($field, message) {
        $field.closest('.arta-form-group').addClass('error');
        $field.after('<div class="arta-error">' + message + '</div>');
    }

    // Show step error
    function showStepError($step, message) {
        $step.prepend('<div class="arta-error arta-step-error">' + message + '</div>');
    }

    // Validate email
    function isValidEmail(email) {
        var re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(email);
    }

    // Validate phone
    function isValidPhone(phone) {
        var re = /^09[0-9]{9}$/;
        return re.test(phone);
    }

    // Load user data
    function loadUserData() {
        if (arta_ajax.user_logged_in) {
            $.ajax({
                url: arta_ajax.ajax_url,
                type: 'POST',
                data: {
                    action: 'arta_get_user_data',
                    nonce: arta_ajax.nonce
                },
                success: function(response) {
                    if (response.success && response.data) {
                        var userData = response.data;
                        
                        // Fill form fields with user data
                        if (userData.full_name) $('#full_name').val(userData.full_name);
                        if (userData.gender) $('#gender').val(userData.gender);
                        if (userData.birth_date) $('#birth_date').val(userData.birth_date);
                        if (userData.height) $('#height').val(userData.height);
                        if (userData.weight) $('#weight').val(userData.weight);
                        if (userData.phone) $('#phone').val(userData.phone);
                        if (userData.chronic_diseases) $('#chronic_diseases').val(userData.chronic_diseases);
                        if (userData.medications) $('#medications').val(userData.medications);
                        if (userData.medical_history) $('#medical_history').val(userData.medical_history);
                        if (userData.program_goal) $('#program_goal').val(userData.program_goal);
                        
                        // Handle email field
                        if (userData.has_profile_email) {
                            // User has email in profile, use it and make field readonly
                            $('#email').val(userData.profile_email).prop('readonly', true);
                            $('#email').after('<small style="color: #666; display: block; margin-top: 5px;">ایمیل از پروفایل شما استفاده می‌شود</small>');
                        } else if (userData.email) {
                            // User has custom email, use it
                            $('#email').val(userData.email);
                        }
                    }
                },
                error: function() {
                    console.log('خطا در بارگذاری اطلاعات کاربر');
                }
            });
        }
    }

    // Load doctors
    function loadDoctors() {
        // Get doctors from the current program via AJAX
        var programId = arta_ajax.program_id;
        
        $('#arta-doctors-list').html('<div class="arta-loading">' + arta_ajax.strings.loading + '</div>');
        
        $.ajax({
            url: arta_ajax.ajax_url,
            type: 'POST',
            data: {
                action: 'arta_get_program_doctors',
                nonce: arta_ajax.nonce,
                program_id: programId
            },
            success: function(response) {
                if (response.success) {
                    displayDoctors(response.data);
                } else {
                    $('#arta-doctors-list').html('<div class="arta-error">خطا در بارگذاری پزشکان</div>');
                }
            },
            error: function() {
                $('#arta-doctors-list').html('<div class="arta-error">خطا در بارگذاری پزشکان</div>');
            }
        });
    }

    // Display doctors
    function displayDoctors(doctors) {
        if (doctors.length === 0) {
            $('#arta-doctors-list').html('<div class="arta-no-doctors">هیچ پزشکی برای این برنامه تعریف نشده است</div>');
            return;
        }
        
        var doctorsHTML = '';
        doctors.forEach(function(doctor) {
            doctorsHTML += `
                <div class="arta-doctor-item" data-doctor-id="${doctor.id}">
                    <div class="arta-doctor-avatar">
                        <img src="${doctor.avatar}" alt="${doctor.name}">
                    </div>
                    <div class="arta-doctor-info">
                        <h4>${doctor.name}</h4>
                        <span>#${doctor.id}</span>
                    </div>
                </div>
            `;
        });
        
        $('#arta-doctors-list').html(doctorsHTML);
        
        // Re-bind click events for doctor items
        $('#arta-doctors-list .arta-doctor-item').off('click').on('click', function() {
            $('.arta-doctor-item').removeClass('selected');
            $(this).addClass('selected');
            formData.doctor_id = $(this).data('doctor-id');
            console.log('Doctor selected:', formData.doctor_id);
        });
    }

    // Load time slots
    function loadTimeSlots() {
        var doctorId = formData.doctor_id;
        var date = $('#appointment_date').val();
        
        if (!doctorId || !date) {
            return;
        }
        
        $('#arta-time-slots').html('<div class="arta-loading">' + arta_ajax.strings.loading + '</div>');
        
        $.ajax({
            url: arta_ajax.ajax_url,
            type: 'POST',
            data: {
                action: 'arta_get_available_slots',
                nonce: arta_ajax.nonce,
                doctor_id: doctorId,
                date: date
            },
            success: function(response) {
                if (response.success) {
                    displayTimeSlots(response.data);
                } else {
                    $('#arta-time-slots').html('<div class="arta-error">خطا در بارگذاری زمان‌های نوبت</div>');
                }
            },
            error: function() {
                $('#arta-time-slots').html('<div class="arta-error">خطا در بارگذاری زمان‌های نوبت</div>');
            }
        });
    }

    // Display time slots
    function displayTimeSlots(slots) {
        if (slots.length === 0) {
            $('#arta-time-slots').html('<div class="arta-no-slots">هیچ نوبت خالی برای این تاریخ وجود ندارد</div>');
            return;
        }
        
        var slotsHTML = '<div class="arta-slots-grid">';
        slots.forEach(function(slot) {
            slotsHTML += `
                <div class="arta-time-slot" data-appointment-id="${slot.id}">
                    <span class="arta-time">${slot.appointment_time}</span>
                </div>
            `;
        });
        slotsHTML += '</div>';
        
        $('#arta-time-slots').html(slotsHTML);
    }

    // Submit form
    function submitForm() {
        if (!validateCurrentStep()) {
            return;
        }
        
        // Collect all form data
        var formDataToSubmit = Object.assign({}, formData);
        
        // Add form fields
        $('#arta-appointment-form').find('input, select, textarea').each(function() {
            var $field = $(this);
            var name = $field.attr('name');
            var value = $field.val();
            
            if (name && value) {
                if ($field.attr('type') === 'checkbox') {
                    formDataToSubmit[name] = $field.is(':checked') ? 1 : 0;
                } else {
                    formDataToSubmit[name] = value;
                }
            }
        });
        
        // Add nonce
        formDataToSubmit.nonce = arta_ajax.nonce;
        formDataToSubmit.action = 'arta_submit_appointment';
        
        // Show loading
        $('#arta-submit-form').prop('disabled', true).text(arta_ajax.strings.loading);
        
        // Submit via AJAX
        $.ajax({
            url: arta_ajax.ajax_url,
            type: 'POST',
            data: formDataToSubmit,
            success: function(response) {
                if (response.success) {
                    // Show success message
                    $('.arta-modal-body').html(`
                        <div class="arta-success-message">
                            <div class="arta-success-icon">✓</div>
                            <h3>درخواست شما با موفقیت ثبت شد!</h3>
                            <p>${response.data.message}</p>
                            <p>ایمیل تاییدیه برای شما ارسال شد.</p>
                        </div>
                    `);
                    $('.arta-modal-footer').hide();
                    
                    // Close modal after 3 seconds
                    setTimeout(function() {
                        closeModal();
                        location.reload(); // Reload page to update appointment status
                    }, 3000);
                } else {
                    showValidationErrors(response.data.errors || [response.data.message]);
                }
            },
            error: function() {
                alert(arta_ajax.strings.error);
            },
            complete: function() {
                $('#arta-submit-form').prop('disabled', false).text('ثبت درخواست');
            }
        });
    }

    // Initialize
    // Show validation errors
    function showValidationErrors(errors) {
        // Remove existing error messages
        $('.arta-error-message').remove();
        
        if (errors && errors.length > 0) {
            var errorHtml = '<div class="arta-error-message">';
            errorHtml += '<div class="arta-error-icon">⚠️</div>';
            errorHtml += '<h4>لطفاً خطاهای زیر را برطرف کنید:</h4>';
            errorHtml += '<ul>';
            errors.forEach(function(error) {
                errorHtml += '<li>' + error + '</li>';
            });
            errorHtml += '</ul>';
            errorHtml += '</div>';
            
            // Insert error message at the top of modal body
            $('.arta-modal-body').prepend(errorHtml);
            
            // Scroll to top
            $('.arta-modal-content').scrollTop(0);
        }
    }

    initAppointmentForm();
});
