/**
 * Appointment Form Widget JavaScript
 *
 * @package Arta_Consult_RX
 * @since 1.0.0
 */

(function($) {
    'use strict';

    // Global appointment form object
    window.artaAppointmentForm = {
        settings: {},
        currentStep: 1,
        totalSteps: 3,
        formData: {},
        modal: null,
        isInitialized: false,

        /**
         * Initialize the appointment form
         */
        init: function(settings) {
            console.log('init فراخوانی شد');
            // if (this.isInitialized) {
            //     console.log('فرم قبلاً initialize شده است');
            //     return;
            // }
            this.settings = settings;
            this.createModal();
            this.bindEvents();
            this.showStep(1);
            this.isInitialized = true;
        },

        /**
         * Initialize the appointment form directly (without modal)
         */
        initDirect: function(settings) {
            console.log('initDirect فراخوانی شد با settings:', settings);
            console.log('medicalConsultationText در initDirect:', settings.medicalConsultationText);
            
            // استفاده از container ID یونیک
            var containerId = settings.containerId || 'arta-appointment-form-container';
            var container = $('#' + containerId);
            
            // ذخیره settings در DOM element
            container.data('artaSettings', settings);
            
            // if (this.isInitialized) {
            //     console.log('فرم قبلاً initialize شده است');
            //     return;
            // }
            this.settings = settings;
            this.currentContainer = container;
            this.createDirectForm();
            this.bindEvents();
            this.showStep(1);
            this.isInitialized = true;
        },


        /**
         * Create the modal HTML
         */
        createModal: function() {
            var self = this;
           
            // Remove existing modal if any
            $('#arta-appointment-modal').remove();
            
            // Get direction class and attribute
            var directionClass = this.settings.direction || (this.settings.isRTL ? 'arta-rtl' : 'arta-ltr');
            var directionAttr = this.settings.directionAttr || (this.settings.isRTL ? 'rtl' : 'ltr');
            
            var modalHTML = `
                <div id="arta-appointment-modal" class="arta-modal" style="display: none;">
                    <div class="arta-modal-overlay"></div>
                    <div class="arta-modal-content ${directionClass}" dir="${directionAttr}">
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
                                    <span class="arta-step active" data-step="1">${this.settings.strings?.step1 || 'Personal Information'}</span>
                                    <span class="arta-step" data-step="2">${this.settings.strings?.step2 || 'Select Doctor'}</span>
                                    <span class="arta-step" data-step="3">${this.settings.strings?.step3 || 'Select Time'}</span>
                                </div>
                            </div>
                            
                            <form id="arta-appointment-form" class="arta-appointment-form ${directionClass}" dir="${directionAttr}">
                                ${this.renderStep1()}
                                ${this.renderStep2()}
                                ${this.renderStep3()}
                            </form>
                        </div>
                        <div class="arta-modal-footer">
                            <button type="button" class="arta-btn arta-btn-secondary" id="arta-prev-step" style="display: none;">${this.settings.buttonTexts.prev}</button>
                            <button type="button" class="arta-btn arta-btn-primary" id="arta-next-step">${this.settings.buttonTexts.next}</button>
                            <button type="button" class="arta-btn arta-btn-success" id="arta-submit-form" style="display: none;">${this.settings.buttonTexts.submit}</button>
                        </div>
                    </div>
                </div>
            `;
            
            $('body').append(modalHTML);
            this.modal = $('#arta-appointment-modal');
        },

        /**
         * Create the direct form HTML (without modal)
         */
        createDirectForm: function() {
            console.log('createDirectForm فراخوانی شد');
            console.log('this.settings.medicalConsultationText:', this.settings.medicalConsultationText);
            var self = this;
            
            var containerId = this.settings.containerId || 'arta-appointment-form-container';
            var container = $('#' + containerId);
            
            // Get direction class and attribute
            var directionClass = this.settings.direction || (this.settings.isRTL ? 'arta-rtl' : 'arta-ltr');
            var directionAttr = this.settings.directionAttr || (this.settings.isRTL ? 'rtl' : 'ltr');
            
            // Debug: Log direction settings
            console.log('Form Direction Debug:', {
                isRTL: this.settings.isRTL,
                direction: this.settings.direction,
                directionAttr: this.settings.directionAttr,
                finalClass: directionClass,
                finalAttr: directionAttr
            });
            
            var formHTML = `
                <div class="arta-appointment-form-direct ${directionClass}" dir="${directionAttr}">
                    <div class="arta-form-progress">
                        <div class="arta-progress-bar">
                            <div class="arta-progress-fill" style="width: 33.33%"></div>
                        </div>
                        <div class="arta-progress-steps">
                            <span class="arta-step active" data-step="1">${this.settings.strings?.step1 || 'Personal Information'}</span>
                            <span class="arta-step" data-step="2">${this.settings.strings?.step2 || 'Select Doctor'}</span>
                            <span class="arta-step" data-step="3">${this.settings.strings?.step3 || 'Select Time'}</span>
                        </div>
                    </div>
                    
                    <form id="arta-appointment-form" class="arta-appointment-form ${directionClass}" dir="${directionAttr}">
                        ${this.renderStep1()}
                        ${this.renderStep2()}
                        ${this.renderStep3()}
                    </form>
                    
                    <div class="arta-form-footer">
                        <button type="button" class="arta-btn arta-btn-secondary" id="arta-prev-step" style="display: none;">${this.settings.buttonTexts.prev}</button>
                        <button type="button" class="arta-btn arta-btn-primary" id="arta-next-step">${this.settings.buttonTexts.next}</button>
                        <button type="button" class="arta-btn arta-btn-success" id="arta-submit-form" style="display: none;">${this.settings.buttonTexts.submit}</button>
                    </div>
                </div>
            `;
            
            container.html(formHTML);
        },

        /**
         * Render step 1 - Personal Information
         */
        renderStep1: function() {
            // خواندن settings از DOM اگر موجود باشد
            var containerId = this.settings.containerId || 'arta-appointment-form-container';
            var container = $('#' + containerId);
            var savedSettings = container.data('artaSettings');
            if (savedSettings && savedSettings.medicalConsultationText) {
                this.settings.medicalConsultationText = savedSettings.medicalConsultationText;
            }
            
            console.log('renderStep1 فراخوانی شد');
            console.log('this.settings.medicalConsultationText در renderStep1:', this.settings.medicalConsultationText);
            if (!this.settings.showPersonalInfo) {
                return '';
            }
            
            var requiredFields = this.settings.requiredFields || [];
            var isRequired = function(field) {
                return requiredFields.includes(field) ? 'required' : '';
            };
            console.log("medicalConsultationText 2", this.settings.medicalConsultationText);
            var strings = this.settings.strings || {};
            var artaStrings = typeof arta_ajax !== 'undefined' && arta_ajax.strings ? arta_ajax.strings : {};
            var labels = {
                personal_info: strings.personal_info || artaStrings.personal_info || 'Personal Information',
                full_name: strings.full_name || artaStrings.full_name || 'Full Name',
                gender: strings.gender || artaStrings.gender || 'Gender',
                select_option: strings.select_option || artaStrings.select_option || 'Select...',
                male: strings.male || artaStrings.male || 'Male',
                female: strings.female || artaStrings.female || 'Female',
                birth_date: strings.birth_date || artaStrings.birth_date || 'Birth Date',
                height: strings.height || artaStrings.height || 'Height (cm)',
                weight: strings.weight || artaStrings.weight || 'Weight (kg)',
                email: strings.email || artaStrings.email || 'Email',
                phone: strings.phone || artaStrings.phone || 'Phone Number',
                chronic_diseases: strings.chronic_diseases || artaStrings.chronic_diseases || 'Chronic Diseases',
                medications: strings.medications || artaStrings.medications || 'Current Medications',
                medical_history: strings.medical_history || artaStrings.medical_history || 'Medical History',
                program_goal: strings.program_goal || artaStrings.program_goal || 'Program Goal'
            };
            
            return `
                <div class="arta-form-step active" data-step="1">
                    <h3>${labels.personal_info}</h3>
                    
                    <div class="arta-form-row">
                        <div class="arta-form-group">
                            <label for="full_name">${labels.full_name} ${requiredFields.includes('full_name') ? '*' : ''}</label>
                            <input type="text" id="full_name" name="full_name" ${isRequired('full_name')} >
                        </div>
                        <div class="arta-form-group">
                            <label for="gender">${labels.gender} ${requiredFields.includes('gender') ? '*' : ''}</label>
                            <select id="gender" name="gender" ${isRequired('gender')}>
                                <option value="">${labels.select_option}</option>
                                <option value="male">${labels.male}</option>
                                <option value="female">${labels.female}</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="arta-form-row">
                        <div class="arta-form-group">
                            <label for="birth_date">${labels.birth_date} ${requiredFields.includes('birth_date') ? '*' : ''}</label>
                            <input type="date" id="birth_date" name="birth_date" ${isRequired('birth_date')}>
                        </div>
                        <div class="arta-form-group">
                            <label for="height">${labels.height}</label>
                            <input type="number" id="height" name="height" min="100" max="250">
                        </div>
                        <div class="arta-form-group">
                            <label for="weight">${labels.weight}</label>
                            <input type="number" id="weight" name="weight" min="30" max="200">
                        </div>
                    </div>
                    
                    <div class="arta-form-row">
                        <div class="arta-form-group">
                            <label for="email">${labels.email} ${requiredFields.includes('email') ? '*' : ''}</label>
                            <input type="email" id="email" name="email" ${isRequired('email')}>
                        </div>
                        <div class="arta-form-group">
                            <label for="phone">${labels.phone} ${requiredFields.includes('phone') ? '*' : ''}</label>
                            <input type="tel" id="phone" name="phone" ${isRequired('phone')} placeholder="09123456789">
                        </div>
                    </div>
                    
                    ${this.settings.showMedicalInfo ? `
                        <div class="arta-form-group">
                            <label for="chronic_diseases">${labels.chronic_diseases}</label>
                            <textarea id="chronic_diseases" name="chronic_diseases" rows="3"></textarea>
                        </div>
                        
                        <div class="arta-form-group">
                            <label for="medications">${labels.medications}</label>
                            <textarea id="medications" name="medications" rows="3"></textarea>
                        </div>
                        
                        <div class="arta-form-group">
                            <label for="medical_history">${labels.medical_history}</label>
                            <textarea id="medical_history" name="medical_history" rows="3"></textarea>
                        </div>
                    ` : ''}
                    
                    <div class="arta-form-group">
                        <label for="program_goal">${labels.program_goal} ${requiredFields.includes('program_goal') ? '*' : ''}</label>
                        <textarea id="program_goal" name="program_goal" rows="4" ${isRequired('program_goal')}></textarea>
                    </div>
                    
                    ${requiredFields.includes('medical_consultation') ? `
                        <div class="arta-form-group">
                            <label class="arta-checkbox-label">
                                <input type="checkbox" id="medical_consultation" name="medical_consultation" required>
                                <span class="arta-checkbox-text">${this.settings.medicalConsultationText || 'تایید مشاوره پزشکی'} </span>
                            </label>
                        </div>
                    ` : ''}
                </div>
            `;
        },

        /**
         * Render step 2 - Doctor Selection
         */
        renderStep2: function() {
            if (!this.settings.showDoctorSelection) {
                return '';
            }
            
            var strings = this.settings.strings || {};
            var artaStrings = typeof arta_ajax !== 'undefined' && arta_ajax.strings ? arta_ajax.strings : {};
            var selectDoctor = strings.select_doctor || artaStrings.select_doctor || 'Select Doctor';
            var loadingDoctors = strings.loading_doctors || artaStrings.loading_doctors || 'Loading doctors list...';

            return `
                <div class="arta-form-step" data-step="2" style="display: none;">
                    <h3>${selectDoctor}</h3>
                    <div id="arta-doctors-list" class="arta-doctors-list">
                        <p class="arta-no-doctors">${loadingDoctors}</p>
                    </div>
                </div>
            `;
        },

        /**
         * Render step 3 - Time Selection
         */
        renderStep3: function() {
            if (!this.settings.showTimeSelection) {
                return '';
            }
            
            var strings = this.settings.strings || {};
            var artaStrings = typeof arta_ajax !== 'undefined' && arta_ajax.strings ? arta_ajax.strings : {};
            var selectTime = strings.select_time || artaStrings.select_time || 'Select Appointment Time';
            var appointmentDate = strings.appointment_date || artaStrings.appointment_date || 'Appointment Date';
            var loadingSlots = strings.loading_slots || artaStrings.loading_slots || 'Loading...';

            return `
                <div class="arta-form-step" data-step="3" style="display: none;">
                    <h3>${selectTime}</h3>
                    <div class="arta-form-group">
                        <label for="appointment_date">${appointmentDate} *</label>
                        <input type="date" id="appointment_date" name="appointment_date" required>
                    </div>
                    <div id="arta-time-slots" class="arta-time-slots">
                        <div class="arta-loading-container">
                            <div class="arta-loading"></div>
                            <span>${loadingSlots}</span>
                        </div>
                    </div>
                </div>
            `;
        },

        /**
         * Bind events
         */
        bindEvents: function() {
            var self = this;

            // Unbind previous events to prevent duplicate handlers
            $(document).off('click.artaAppointment');
            $(document).off('change.artaAppointment');

            // Modal events
            $(document).on('click.artaAppointment', '.arta-modal-close, .arta-modal-overlay', function() {
                self.closeModal();
            });

            // Form navigation
            $(document).on('click.artaAppointment', '#arta-next-step', function() {
                self.nextStep();
            });

            $(document).on('click.artaAppointment', '#arta-prev-step', function() {
                self.prevStep();
            });

            $(document).on('click.artaAppointment', '#arta-submit-form', function() {
                self.submitForm();
            });

            // Doctor selection
            $(document).on('click.artaAppointment', '.arta-doctor-item', function() {
                $('.arta-doctor-item').removeClass('selected');
                $(this).addClass('selected');
                self.formData.doctor_id = $(this).data('doctor-id');
            });

            // Time slot selection
            $(document).on('click.artaAppointment', '.arta-time-slot:not(.unavailable)', function() {
                $('.arta-time-slot').removeClass('selected');
                $(this).addClass('selected');
                self.formData.time_slot = $(this).data('time-slot');
            });

            // Date change
            $(document).on('change.artaAppointment', '#appointment_date', function() {
                // نمایش لودینگ
                var strings = self.settings.strings || {};
                var artaStrings = typeof arta_ajax !== 'undefined' && arta_ajax.strings ? arta_ajax.strings : {};
                var searching = strings.searching || artaStrings.searching || 'Searching...';
                $('#arta-time-slots').html(`
                    <div class="arta-loading-container">
                        <div class="arta-loading"></div>
                        <span>${searching}</span>
                    </div>
                `);
                self.loadTimeSlots();
            });

            // Prevent modal close on content click
            $(document).on('click.artaAppointment', '.arta-modal-content', function(e) {
                e.stopPropagation();
            });

            // Auto-open datepicker on date input click
            $(document).on('click.artaAppointment', 'input[type="date"]', function() {
                try {
                    this.showPicker();
                } catch (e) {
                    // Fallback for browsers that don't support showPicker()
                    this.focus();
                }
            });
        },

        /**
         * Show modal
         */
        showModal: function() {
            this.modal.show();
            $('body').addClass('modal-open');
           
        },

        /**
         * Close modal
         */
        closeModal: function() {
            this.modal.hide();
            $('body').removeClass('modal-open');
            this.resetForm();
        },

        /**
         * Load user data if logged in
         */
        loadUserData: function() {
            var self = this;
           
            
            // Add loading state to form elements
            this.setFormLoadingState(true);
            
            // Check if user is logged in
            $.ajax({
                url: arta_ajax.ajax_url,
                type: 'POST',
                data: {
                    action: 'arta_get_user_data'
                },
                success: function(response) {
                    console.log('loadUserData response', response);
                    if (response.success && response.data) {
                        self.populateFormWithUserData(response.data);
                    }
                    // Remove loading state regardless of success/failure
                    self.setFormLoadingState(false);
                },
                error: function(xhr, status, error) {
                    // User not logged in or error loading user data
                    console.log('loadUserData error', error);
                    // Remove loading state on error
                    self.setFormLoadingState(false);
                }
            });
        },

        /**
         * Populate form with user data
         */
        populateFormWithUserData: function(userData) {
           
            
            // Populate personal information fields
            if (userData.full_name) {
                $('#full_name').val(userData.full_name);
            }
            if (userData.gender) {
                $('#gender').val(userData.gender);
            }
            if (userData.birth_date) {
                $('#birth_date').val(userData.birth_date);
            }
            if (userData.height) {
                $('#height').val(userData.height);
            }
            if (userData.weight) {
                $('#weight').val(userData.weight);
            }
            if (userData.email) {
                $('#email').val(userData.email);
            }
            if (userData.phone) {
                $('#phone').val(userData.phone);
            }
            if (userData.chronic_diseases) {
                $('#chronic_diseases').val(userData.chronic_diseases);
            }
            if (userData.medications) {
                $('#medications').val(userData.medications);
            }
            if (userData.medical_history) {
                $('#medical_history').val(userData.medical_history);
            }
            if (userData.program_goal) {
                $('#program_goal').val(userData.program_goal);
            }
        },

        /**
         * Set form loading state
         */
        setFormLoadingState: function(isLoading) {
            var step1Element = $('.arta-form-step[data-step="1"]');
            var formStepElements = $('.arta-form-step');
            
            if (isLoading) {
                // Add loading class to step 1 and all form steps
                step1Element.addClass('arta-form-loading');
                formStepElements.addClass('arta-form-loading');
            } else {
                // Remove loading class from step 1 and all form steps
                step1Element.removeClass('arta-form-loading');
                formStepElements.removeClass('arta-form-loading');
            }
        },

        /**
         * Show specific step
         */
        showStep: function(step) {
            this.currentStep = step;
            
            // Hide all steps
            $('.arta-form-step').removeClass('active').hide();
            
            // Show current step - use first() to ensure we only get one element
            var currentStepElement = $(`.arta-form-step[data-step="${step}"]`).first();
            currentStepElement.addClass('active').show();
            
            // Update progress bar
            var progress = (step / this.totalSteps) * 100;
            $('.arta-progress-fill').css('width', progress + '%');
            
            // Update step indicators
            $('.arta-step').removeClass('active completed');
            for (var i = 1; i <= step; i++) {
                if (i < step) {
                    $(`.arta-step[data-step="${i}"]`).addClass('completed');
                } else {
                    $(`.arta-step[data-step="${i}"]`).addClass('active');
                }
            }
            
            // Update buttons
            this.updateButtons();
            
            // Load step-specific data
            this.loadStepData(step);
        },

        /**
         * Load step-specific data
         */
        loadStepData: function(step) {
            console.log('loadStepData فراخوانی شد برای step:', step);
            if (step === 1 && this.settings.showPersonalInfo) {
                // Load user data for step 1
                this.loadUserData();
            } else if (step === 2 && this.settings.showDoctorSelection) {
                this.loadDoctors();
            } else if (step === 3 && this.settings.showTimeSelection) {
                // Set minimum date to today for appointment_date input
                var today = new Date().toISOString().split('T')[0];
                $('#appointment_date').attr('min', today);
                
                // Clear any existing loading state
                var strings = this.settings.strings || {};
                var artaStrings = typeof arta_ajax !== 'undefined' && arta_ajax.strings ? arta_ajax.strings : {};
                var loadingSlots = strings.loading_slots || artaStrings.loading_slots || 'Loading...';
                $('#arta-time-slots').html(`
                    <div class="arta-loading-container">
                        <div class="arta-loading"></div>
                        <span>${loadingSlots}</span>
                    </div>
                `);
                this.loadTimeSlots();
            }
        },

        /**
         * Load doctors list for the program
         */
        loadDoctors: function() {
            var self = this;
            var doctorsContainer = $('#arta-doctors-list');
            
            if (!this.settings.programId) {
                doctorsContainer.html('<div class="arta-no-doctors">برنامه مشخص نشده است.</div>');
                return;
            }
            
            var strings = this.settings.strings || {};
            var artaStrings = typeof arta_ajax !== 'undefined' && arta_ajax.strings ? arta_ajax.strings : {};
            var loadingDoctors = strings.loading_doctors || artaStrings.loading_doctors || 'Loading doctors list...';
            doctorsContainer.html('<div class="arta-loading-container"><span class="arta-loading"></span> ' + loadingDoctors + '</div>');
            
            $.ajax({
                url: arta_ajax.ajax_url,
                type: 'POST',
                data: {
                    action: 'arta_get_program_doctors',
                    program_id: this.settings.programId
                },
                success: function(response) {
                    if (response.success && response.data && response.data.length > 0) {
                        var doctorsHtml = '<div class="arta-doctors-grid">';
                        response.data.forEach(function(doctor) {
                            doctorsHtml += `
                                <div class="arta-doctor-item" data-doctor-id="${doctor.id}">
                                    <div class="arta-doctor-image">
                                        <img src="${doctor.image}" alt="${doctor.name}">
                                    </div>
                                    <div class="arta-doctor-info">
                                        <h4>${doctor.name}</h4>
                                       
                                    </div>
                                </div>
                            `;
                        });
                        doctorsHtml += '</div>';
                        doctorsContainer.html(doctorsHtml);
                    } else {
                        doctorsContainer.html('<div class="arta-no-doctors">پزشکی برای این برنامه یافت نشد.</div>');
                    }
                },
                error: function(xhr, status, error) {
                    doctorsContainer.html('<div class="arta-error">خطا در بارگذاری پزشکان. لطفا دوباره تلاش کنید.</div>');
                }
            });
        },



        /**
         * Load time slots
         */
        loadTimeSlots: function() {
            var self = this;
            var date = $('#appointment_date').val();
            var doctorId = this.formData.doctor_id;
            
            if (!date || !doctorId) {
                var strings = this.settings.strings || {};
                var artaStrings = typeof arta_ajax !== 'undefined' && arta_ajax.strings ? arta_ajax.strings : {};
                var message = this.settings.noDataMessages && this.settings.noDataMessages.selectDoctorDate 
                    ? this.settings.noDataMessages.selectDoctorDate 
                    : (strings.select_doctor_date_first || artaStrings.select_doctor_date_first || 'Please first select doctor and date');
                $('#arta-time-slots').html('<p class="arta-no-data">' + message + '</p>');
                return;
            }
            
            $.ajax({
                url: arta_ajax.ajax_url,
                type: 'POST',
                data: {
                    action: 'arta_get_available_slots',
                    doctor_id: doctorId,
                    date: date
                },
                success: function(response) {
                    if (response.success) {
                        self.renderTimeSlots(response.data);
                    } else {
                        $('#arta-time-slots').html('<p class="arta-error">خطا در بارگذاری زمان‌های موجود: ' + (response.data || 'خطای نامشخص') + '</p>');
                    }
                },
                error: function(xhr, status, error) {
                    $('#arta-time-slots').html('<p class="arta-error">خطا در ارتباط با سرور: ' + error + '</p>');
                },
                timeout: 10000 // 10 second timeout
            });
        },

        /**
         * Render time slots
         */
        renderTimeSlots: function(timeSlots) {
            var html = '';
            
            if (!timeSlots || timeSlots.length === 0) {
                var strings = this.settings.strings || {};
                var artaStrings = typeof arta_ajax !== 'undefined' && arta_ajax.strings ? arta_ajax.strings : {};
                var message = this.settings.noDataMessages && this.settings.noDataMessages.noSlots 
                    ? this.settings.noDataMessages.noSlots 
                    : (strings.no_slots_available || artaStrings.no_slots_available || 'No available time slots for this date');
                html = '<p class="arta-no-data">' + message + '</p>';
            } else {
                timeSlots.forEach(function(slot) {
                    var isAvailable = slot.status === 'available';
                    var timeValue = slot.appointment_time || slot.time;
                    
                    // Format time to show only hour:minute (remove seconds)
                    if (timeValue && timeValue.includes(':')) {
                        var timeParts = timeValue.split(':');
                        if (timeParts.length >= 2) {
                            timeValue = timeParts[0] + ':' + timeParts[1];
                        }
                    }
                    
                    var className = isAvailable ? 'arta-time-slot' : 'arta-time-slot unavailable';
                    
                    html += `
                        <div class="${className}" data-appointment-id="${slot.id}" data-time-slot="${timeValue}" ${!isAvailable ? 'style="cursor: not-allowed;"' : ''}>
                            ${timeValue}
                        </div>
                    `;
                });
            }
            
            $('#arta-time-slots').html(html);
        },

        /**
         * Update buttons
         */
        updateButtons: function() {
            var prevBtn = $('#arta-prev-step');
            var nextBtn = $('#arta-next-step');
            var submitBtn = $('#arta-submit-form');
            
            // Show/hide previous button
            if (this.currentStep > 1) {
                prevBtn.show();
            } else {
                prevBtn.hide();
            }
            
            // Show/hide next and submit buttons
            if (this.currentStep === this.totalSteps) {
                nextBtn.hide();
                submitBtn.show();
            } else {
                nextBtn.show();
                submitBtn.hide();
            }
        },

        /**
         * Next step
         */
        nextStep: function() {
            if (this.validateCurrentStep()) {
                this.collectFormData();
                this.showStep(this.currentStep + 1);
            }
        },

        /**
         * Previous step
         */
        prevStep: function() {
            this.showStep(this.currentStep - 1);
        },

        /**
         * Validate current step
         */
        validateCurrentStep: function() {
            var isValid = true;
            var currentStepElement = $(`.arta-form-step[data-step="${this.currentStep}"]:visible`);
            
            // Clear previous errors
            currentStepElement.find('.has-error').removeClass('has-error');
            currentStepElement.find('.arta-error').remove();
            
            // Also clear errors from all steps to be safe
            $('.arta-form-step .has-error').removeClass('has-error');
            $('.arta-form-step .arta-error').remove();
            
            // Validate required fields - only validate visible fields
            var strings = this.settings.strings || {};
            var artaStrings = typeof arta_ajax !== 'undefined' && arta_ajax.strings ? arta_ajax.strings : {};
            var fieldRequired = strings.field_required || artaStrings.field_required || 'This field is required';
            currentStepElement.find('input[required]:visible, select[required]:visible, textarea[required]:visible').each(function() {
                var field = $(this);
                var value = field.val().trim();
                
                if (!value) {
                    isValid = false;
                    field.closest('.arta-form-group').addClass('has-error');
                    field.after('<span class="arta-error">' + fieldRequired + '</span>');
                }
            });
            
            // Validate email - only validate visible fields
            var emailField = currentStepElement.find('input[type="email"]:visible');
            if (emailField.length && emailField.val()) {
                var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(emailField.val())) {
                    isValid = false;
                    var strings = this.settings.strings || {};
                    var artaStrings = typeof arta_ajax !== 'undefined' && arta_ajax.strings ? arta_ajax.strings : {};
                    var invalidEmail = strings.invalid_email || artaStrings.invalid_email || 'Invalid email';
                    emailField.closest('.arta-form-group').addClass('has-error');
                    emailField.after('<span class="arta-error">' + invalidEmail + '</span>');
                }
            }
            
            // Validate phone - only validate visible fields
            var phoneField = currentStepElement.find('input[type="tel"]:visible');
            if (phoneField.length && phoneField.val()) {
                var phoneRegex = /^09\d{9}$/;
                if (!phoneRegex.test(phoneField.val())) {
                    isValid = false;
                    var strings = this.settings.strings || {};
                    var artaStrings = typeof arta_ajax !== 'undefined' && arta_ajax.strings ? arta_ajax.strings : {};
                    var invalidPhone = strings.invalid_phone || artaStrings.invalid_phone || 'Invalid phone number';
                    phoneField.closest('.arta-form-group').addClass('has-error');
                    phoneField.after('<span class="arta-error">' + invalidPhone + '</span>');
                }
            }
            
            // Validate medical consultation checkbox
            var medicalConsultationCheckbox = currentStepElement.find('input[type="checkbox"]#medical_consultation:visible');
            if (medicalConsultationCheckbox.length && medicalConsultationCheckbox.prop('required') && !medicalConsultationCheckbox.is(':checked')) {
                isValid = false;
                medicalConsultationCheckbox.closest('.arta-form-group').addClass('has-error');
                var strings = this.settings.strings || {};
                var artaStrings = typeof arta_ajax !== 'undefined' && arta_ajax.strings ? arta_ajax.strings : {};
                var medicalConsultationRequired = strings.medical_consultation_required || artaStrings.medical_consultation_required || 'Medical consultation confirmation is required';
                medicalConsultationCheckbox.closest('.arta-checkbox-label').after('<span class="arta-error">' + medicalConsultationRequired + '</span>');
            }
            
            // Validate step-specific requirements
            var strings = this.settings.strings || {};
            var artaStrings = typeof arta_ajax !== 'undefined' && arta_ajax.strings ? arta_ajax.strings : {};
            if (this.currentStep === 2 && this.settings.showDoctorSelection) {
                if (!this.formData.doctor_id) {
                    isValid = false;
                    var selectDoctorFirst = strings.select_doctor_first || artaStrings.select_doctor_first || 'Please select a doctor';
                    currentStepElement.find('#arta-doctors-list').after('<span class="arta-error">' + selectDoctorFirst + '</span>');
                }
            }
            
            if (this.currentStep === 3 && this.settings.showTimeSelection) {
                if (!this.formData.time_slot) {
                    isValid = false;
                    var selectTimeFirst = strings.select_time_first || artaStrings.select_time_first || 'Please select a time';
                    currentStepElement.find('#arta-time-slots').after('<span class="arta-error">' + selectTimeFirst + '</span>');
                }
            }
            
            return isValid;
        },

        /**
         * Collect form data
         */
        collectFormData: function() {
            var form = $('#arta-appointment-form');
            var formData = {};
            
            
            
            // Collect all form fields (شامل فیلدهای hidden هم)
            form.find('input, select, textarea').each(function() {
                var field = $(this);
                var name = field.attr('name');
                var type = field.attr('type');
                var value = field.val();
                
                if (name) {
                    
                    if (type === 'checkbox') {
                        formData[name] = field.is(':checked') ? '1' : '0';
                    } else {
                        // همه مقادیر رو ذخیره می‌کنیم (حتی خالی)
                        formData[name] = value || '';
                    }
                }
            });
            var timeslotsselected = $('#arta-time-slots .selected').data('appointment-id');
            formData['timeslotsselected'] = timeslotsselected;
           
            
            // Merge with existing form data (داده‌های جدید روی قدیمی override میشن)
            this.formData = $.extend({}, this.formData, formData);
            
        },

        /**
         * Submit form
         */
        submitForm: function() {
            if (this.validateCurrentStep()) {
                this.collectFormData();
                this.sendFormData();
            }
        },

        /**
         * Send form data
         */
        sendFormData: function() {
            var self = this;
            var submitBtn = $('#arta-submit-form');
            var originalText = submitBtn.text();
            
            // Show loading state
            var strings = this.settings.strings || {};
            var artaStrings = typeof arta_ajax !== 'undefined' && arta_ajax.strings ? arta_ajax.strings : {};
            var sending = strings.sending || artaStrings.sending || 'Sending...';
            submitBtn.prop('disabled', true).html('<span class="arta-loading"></span> ' + sending);
            
            // Add program ID to form data
            this.formData.program_id = $('#arta-program-id').val();
            this.formData.product_id = $('#arta-product-id').val();
            this.formData.consultation_id = $('#arta-consultation-id').val();
            
            console.log('داده‌های فرم قبل از ارسال:', this.formData);
            
            $.ajax({
                url: arta_ajax.ajax_url,
                type: 'POST',
                data: {
                    action: 'arta_submit_appointment',
                    form_data: this.formData
                },
                success: function(response) {
                    console.log(response)
                    if (response.success) {
                        self.showSuccessMessage(response.data.message);
                    } else {
                        self.showErrorMessage(response.data.message || 'خطا در ارسال فرم');
                    }
                },
                error: function(xhr, status, error) {
                    self.showErrorMessage('خطا در ارتباط با سرور');
                },
                complete: function() {
                    // Reset button
                    submitBtn.prop('disabled', false).text(originalText);
                }
            });
        },

        /**
         * Show success message
         */
        showSuccessMessage: function(message) {
            
            // دریافت تنظیمات از Elementor
            var successSettings = this.settings.successMessages || {};
            var title = successSettings.title || 'درخواست شما با موفقیت ثبت شد!';
            var successMessage = successSettings.message || message;
            
          
            
            // ساخت HTML آیکون
            var iconHtml = '';
            
            
            
            var html = `
                <div class="arta-success-container">
                    <h3 class="arta-success-title">${title}</h3>
                    <p class="arta-success-message">${successMessage}</p>
                </div>
            `;
            
            // مخفی کردن فرم و نمایش پیام موفقیت
            $('.arta-appointment-form-direct').html(html);
            
            // اسکرول به بالا
            $('html, body').animate({
                scrollTop: $('.arta-appointment-form-wrapper').offset().top - 50
            }, 500);
        },

        /**
         * Show error message
         */
        showErrorMessage: function(message) {
            
            // حذف پیام خطای قبلی
            $('.arta-form-notice').remove();
            
            // ساخت پیام خطا
            var errorNotice = `
                <div class="arta-form-notice arta-error">
                    <span class="arta-notice-icon">⚠️</span>
                    <span class="arta-notice-text">${message}</span>
                    <button type="button" class="arta-notice-close">&times;</button>
                </div>
            `;
            
            // اضافه کردن به بالای فرم
            $('.arta-form-progress').after(errorNotice);
            
            // اسکرول به بالای فرم
            $('html, body').animate({
                scrollTop: $('.arta-appointment-form-direct').offset().top - 20
            }, 300);
            
            // بستن با کلیک روی دکمه close
            $('.arta-notice-close').on('click', function() {
                $(this).closest('.arta-form-notice').fadeOut(300, function() {
                    $(this).remove();
                });
            });
            
            // حذف خودکار بعد از 5 ثانیه
            setTimeout(function() {
                $('.arta-form-notice').fadeOut(300, function() {
                    $(this).remove();
                });
            }, 5000);
        },

        /**
         * Reset form
         */
        resetForm: function() {
            this.currentStep = 1;
            this.formData = {};
            if ($('#arta-appointment-form').length > 0) {
                $('#arta-appointment-form')[0].reset();
            }
            this.showStep(1);
            // Note: We don't reset isInitialized here because we want to keep the event handlers
        }
    };



})(jQuery);
