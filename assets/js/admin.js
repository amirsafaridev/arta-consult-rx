/**
 * Arta Consult RX Admin JavaScript
 */

jQuery(document).ready(function($) {
    'use strict';

    // Initialize date pickers
    if ($.fn.datepicker) {
        $('.arta-datepicker').datepicker({
            dateFormat: 'yy-mm-dd',
            changeMonth: true,
            changeYear: true,
            yearRange: '-1:+2'
        });
    }

    // Initialize Select2
    function initializeSelect2() {
        if ($.fn.select2) {
                    $('.arta-select2').each(function() {
                        if (!$(this).hasClass('select2-hidden-accessible')) {
                            var select2Strings = typeof arta_admin !== 'undefined' && arta_admin.strings ? arta_admin.strings : {};
                            $(this).select2({
                                placeholder: select2Strings.select_placeholder || 'Select...',
                                allowClear: true,
                                dir: 'rtl',
                                width: '100%',
                                language: {
                                    noResults: function() {
                                        return select2Strings.no_results || "No results found";
                                    },
                                    searching: function() {
                                        return select2Strings.searching || "Searching...";
                                    },
                                    loadingMore: function() {
                                        return select2Strings.loading_more || "Loading more...";
                                    }
                                }
                            });
                        }
                    });
        }
    }

    // Initialize on page load
    initializeSelect2();

    // Re-initialize when new content is added
    $(document).on('select2:open', function() {
        var select2Strings = typeof arta_admin !== 'undefined' && arta_admin.strings ? arta_admin.strings : {};
        $('.select2-search__field').attr('placeholder', select2Strings.search || 'Search...');
    });

    // Force re-initialization after a short delay to ensure DOM is ready
    setTimeout(function() {
        initializeSelect2();
    }, 500);

    // Simple Select with Add/Remove Functionality
    function initializeSelectWithAddRemove() {
        // Handle doctors select
        $('#arta_program_doctors').on('change', function() {
            var selectedValue = $(this).val();
            var selectedText = $(this).find('option:selected').text();
            var $hiddenInput = $('#arta_program_doctors_hidden');
            var $selectedContainer = $('#arta_selected_doctors');
            
            if (selectedValue && selectedValue !== '') {
                // Check if already selected
                if ($selectedContainer.find('[data-id="' + selectedValue + '"]').length === 0) {
                    // Add to selected items
                    var $newItem = $('<div class="arta-selected-item" data-id="' + selectedValue + '">');
                    $newItem.append('<span>' + selectedText + ' <span class="arta-item-id">#' + selectedValue + '</span></span>');
                    $newItem.append('<button type="button" class="arta-remove-item">×</button>');
                    $selectedContainer.append($newItem);
                    
                    // Update hidden input
                    updateHiddenInput($hiddenInput, $selectedContainer);
                }
                
                // Reset select
                $(this).val('');
            }
        });
        
        // Handle products select
        $('#arta_program_related_products').on('change', function() {
            var selectedValue = $(this).val();
            var selectedText = $(this).find('option:selected').text();
            var $hiddenInput = $('#arta_program_related_products_hidden');
            var $selectedContainer = $('#arta_selected_products');
            
            if (selectedValue && selectedValue !== '') {
                // Check if already selected
                if ($selectedContainer.find('[data-id="' + selectedValue + '"]').length === 0) {
                    // Add to selected items
                    var $newItem = $('<div class="arta-selected-item" data-id="' + selectedValue + '">');
                    $newItem.append('<span>' + selectedText + ' <span class="arta-item-id">#' + selectedValue + '</span></span>');
                    $newItem.append('<button type="button" class="arta-remove-item">×</button>');
                    $selectedContainer.append($newItem);
                    
                    // Update hidden input
                    updateHiddenInput($hiddenInput, $selectedContainer);
                }
                
                // Reset select
                $(this).val('');
            }
        });
        
        // Handle remove buttons
        $(document).on('click', '.arta-remove-item', function(e) {
            e.preventDefault();
            var $item = $(this).closest('.arta-selected-item');
            var $container = $item.closest('.arta-selected-items');
            var containerId = $container.attr('id');
            var $hiddenInput;
            
            
            if (containerId === 'arta_selected_doctors') {
                $hiddenInput = $('#arta_program_doctors_hidden');
            } else if (containerId === 'arta_selected_products') {
                $hiddenInput = $('#arta_program_related_products_hidden');
            } else {
                return;
            }
            
            $item.remove();
            updateHiddenInput($hiddenInput, $container);
        });
        
        // Function to update hidden input
        function updateHiddenInput($hiddenInput, $container) {
            var selectedIds = [];
            $container.find('.arta-selected-item').each(function() {
                var id = $(this).data('id');
                if (id && id !== '') {
                    selectedIds.push(id);
                }
            });
            $hiddenInput.val(selectedIds.join(','));
        }
    }

    // Initialize select with add/remove functionality
    initializeSelectWithAddRemove();
    
    // Re-initialize after a delay to ensure DOM is ready
    setTimeout(function() {
        initializeSelectWithAddRemove();
    }, 1000);
    
    // Also initialize when document is ready
    $(document).ready(function() {
        initializeSelectWithAddRemove();
        
        // Debug: Log current values (uncomment for debugging)
        // console.log('Doctors hidden input value:', $('#arta_program_doctors_hidden').val());
        // console.log('Products hidden input value:', $('#arta_program_related_products_hidden').val());
        // console.log('Doctors selected items count:', $('#arta_selected_doctors .arta-selected-item').length);
        // console.log('Products selected items count:', $('#arta_selected_products .arta-selected-item').length);
    });

    // Appointment deletion
    $(document).on('click', '.arta-delete-appointment', function(e) {
        e.preventDefault();
        
        if (!confirm(arta_admin.strings.confirm_delete)) {
            return;
        }

        var appointmentId = $(this).data('id');
        var row = $(this).closest('tr');
        
        $.ajax({
            url: arta_admin.ajax_url,
            type: 'POST',
            data: {
                action: 'arta_delete_appointment',
                appointment_id: appointmentId,
                nonce: arta_admin.nonce
            },
            beforeSend: function() {
                row.addClass('arta-loading');
            },
            success: function(response) {
                if (response.success) {
                    row.fadeOut(300, function() {
                        $(this).remove();
                    });
                    showNotice(response.data.message, 'success');
                } else {
                    showNotice(response.data.message || arta_admin.strings.error, 'error');
                }
            },
            error: function() {
                showNotice(arta_admin.strings.error, 'error');
            },
            complete: function() {
                row.removeClass('arta-loading');
            }
        });
    });

    // View appointment details
    $(document).on('click', '.arta-view-appointment', function(e) {
        e.preventDefault();
        
        var appointmentId = $(this).data('id');
        
        // Create modal or show details
        showAppointmentDetails(appointmentId);
    });

    // Edit appointment
    $(document).on('click', '.arta-edit-appointment', function(e) {
        e.preventDefault();
        
        var appointmentId = $(this).data('id');
        
        // Open edit form
        editAppointment(appointmentId);
    });

    // Calendar navigation
    $(document).on('change', '.arta-month-select', function() {
        var month = $(this).val();
        var doctor = $('.arta-doctor-select').val();
        
        loadCalendar(month, doctor);
    });

    $(document).on('change', '.arta-doctor-select', function() {
        var month = $('.arta-month-select').val();
        var doctor = $(this).val();
        
        loadCalendar(month, doctor);
    });

    // Bulk appointment creation
    $('#arta-bulk-appointment-form').on('submit', function(e) {
        e.preventDefault();
        
        var formData = $(this).serialize();
        var submitBtn = $(this).find('input[type="submit"]');
        
        $.ajax({
            url: arta_admin.ajax_url,
            type: 'POST',
            data: formData + '&action=arta_create_appointments&nonce=' + arta_admin.nonce,
            beforeSend: function() {
                submitBtn.prop('disabled', true).val(arta_admin.strings.loading);
            },
            success: function(response) {
                if (response.success) {
                    showNotice(response.data.message, 'success');
                    // Refresh calendar if on calendar page
                    if (typeof loadCalendar === 'function') {
                        loadCalendar();
                    }
                } else {
                    showNotice(response.data.message || arta_admin.strings.error, 'error');
                }
            },
            error: function() {
                showNotice(arta_admin.strings.error, 'error');
            },
            complete: function() {
                var strings = typeof arta_admin !== 'undefined' && arta_admin.strings ? arta_admin.strings : {};
                submitBtn.prop('disabled', false).val(strings.create_appointments || 'Create Appointments');
            }
        });
    });

    // Form validation
    $('.arta-form').on('submit', function(e) {
        var isValid = true;
        var requiredFields = $(this).find('[required]');
        
        requiredFields.each(function() {
            if (!$(this).val()) {
                $(this).addClass('error');
                isValid = false;
            } else {
                $(this).removeClass('error');
            }
        });
        
        if (!isValid) {
            e.preventDefault();
            var strings = typeof arta_admin !== 'undefined' && arta_admin.strings ? arta_admin.strings : {};
            showNotice(strings.fill_required_fields || 'Please fill in all required fields.', 'error');
        }
    });

    // Remove error class on input
    $('input, select, textarea').on('input change', function() {
        $(this).removeClass('error');
    });

    // Helper Functions
    function showNotice(message, type) {
        type = type || 'info';
        var noticeClass = 'arta-notice arta-notice-' + type;
        
        var notice = $('<div class="' + noticeClass + '"><p>' + message + '</p></div>');
        
        $('.wrap h1').after(notice);
        
        // Auto dismiss after 5 seconds
        setTimeout(function() {
            notice.fadeOut(300, function() {
                $(this).remove();
            });
        }, 5000);
    }

    function showAppointmentDetails(appointmentId) {
        // This would typically open a modal or redirect to a details page
        // For now, we'll just show an alert
        alert('نمایش جزئیات نوبت: ' + appointmentId);
    }

    function editAppointment(appointmentId) {
        // This would typically open an edit form
        // For now, we'll just show an alert
        alert('ویرایش نوبت: ' + appointmentId);
    }

    function loadCalendar(month, doctor) {
        if (typeof month === 'undefined') {
            month = $('.arta-month-select').val() || new Date().toISOString().slice(0, 7);
        }
        if (typeof doctor === 'undefined') {
            doctor = $('.arta-doctor-select').val() || 0;
        }
        
        $.ajax({
            url: arta_admin.ajax_url,
            type: 'POST',
            data: {
                action: 'arta_get_appointments',
                month: month,
                doctor_id: doctor,
                nonce: arta_admin.nonce
            },
            beforeSend: function() {
                $('#arta-calendar-container').addClass('arta-loading');
            },
            success: function(response) {
                if (response.success) {
                    // Update calendar display
                    updateCalendarDisplay(response.data);
                } else {
                    showNotice('خطا در بارگذاری تقویم', 'error');
                }
            },
            error: function() {
                showNotice('خطا در بارگذاری تقویم', 'error');
            },
            complete: function() {
                $('#arta-calendar-container').removeClass('arta-loading');
            }
        });
    }

    function updateCalendarDisplay(appointments) {
        // This would update the calendar display with new data
        // Implementation depends on the calendar structure
        console.log('Updating calendar with appointments:', appointments);
    }

    // Initialize tooltips if available
    if ($.fn.tooltip) {
        $('[data-tooltip]').tooltip();
    }

    // Calendar day click to filter appointments
    $(document).on('click', '.arta-calendar-day', function(e) {
        e.preventDefault();
        var selectedDate = $(this).data('date');
        
        console.log('Calendar day clicked:', selectedDate);
        
        // Remove active class from all days
        $('.arta-calendar-day').removeClass('arta-day-selected');
        
        // Add active class to clicked day
        $(this).addClass('arta-day-selected');
        
        // Filter appointments table by date
        filterAppointmentsByDate(selectedDate);
        
        // Show reset button
        $('#arta-reset-date-filter').show();
    });
    
    // Function to filter appointments by date
    function filterAppointmentsByDate(date) {
        console.log('Filtering appointments for date:', date);
        
        var $rows = $('#arta-appointments-list table tbody tr');
        var visibleCount = 0;
        
        if (!date) {
            // Show all appointments if no date selected
            $rows.show();
            visibleCount = $rows.length;
            
            // Hide reset button
            $('#arta-reset-date-filter').hide();
        } else {
            // Filter appointments by date
            $rows.each(function() {
                var $row = $(this);
                var rowDateText = $row.find('td:first strong').text().trim();
                
                // Convert date format from Y/m/d to Y-m-d for comparison
                var rowDate = rowDateText.replace(/\//g, '-');
                
                console.log('Comparing row date:', rowDate, 'with filter date:', date);
                
                if (rowDate === date) {
                    $row.show();
                    visibleCount++;
                } else {
                    $row.hide();
                }
            });
            
            // Show reset button
            $('#arta-reset-date-filter').show();
        }
        
        console.log('Visible appointments:', visibleCount);
        
        // Update list title to show filtered date or all
        var $listTitle = $('#arta-appointments-list').closest('.arta-section').find('h2');
        var resetBtn = $('#arta-reset-date-filter');
        
        if (date && visibleCount > 0) {
            var dateObj = new Date(date);
            var formattedDate = dateObj.toLocaleDateString('fa-IR');
            $listTitle.html('لیست نوبت‌ها - ' + date + ' (' + visibleCount + ' نوبت)' + resetBtn.prop('outerHTML'));
        } else if (date && visibleCount === 0) {
            $listTitle.html('لیست نوبت‌ها - ' + date + ' (هیچ نوبتی یافت نشد)' + resetBtn.prop('outerHTML'));
        } else {
            $listTitle.html('لیست نوبت‌ها' + resetBtn.prop('outerHTML'));
        }
    }
    
    // Reset date filter button click
    $(document).on('click', '#arta-reset-date-filter', function(e) {
        e.preventDefault();
        
        console.log('Reset button clicked - showing all appointments');
        
        // Remove active class from all days
        $('.arta-calendar-day').removeClass('arta-day-selected');
        
        // Show all appointments
        filterAppointmentsByDate(null);
    });
    
    // Double-click on calendar day to reset filter (show all appointments)
    $(document).on('dblclick', '.arta-calendar-day', function(e) {
        e.preventDefault();
        
        console.log('Calendar day double-clicked - resetting filter');
        
        // Remove active class from all days
        $('.arta-calendar-day').removeClass('arta-day-selected');
        
        // Show all appointments
        filterAppointmentsByDate(null);
    });

    // Auto-refresh appointments list every 30 seconds
    if ($('#arta-appointments-list').length) {
        setInterval(function() {
            if (document.visibilityState === 'visible') {
                // Only refresh if page is visible
                refreshAppointmentsList();
            }
        }, 30000);
    }

    function refreshAppointmentsList() {
        var currentMonth = $('.arta-month-select').val();
        var currentDoctor = $('.arta-doctor-select').val();
        
        if (currentMonth) {
            loadCalendar(currentMonth, currentDoctor);
        }
    }

    // Keyboard shortcuts
    $(document).on('keydown', function(e) {
        // Ctrl/Cmd + N for new appointment
        if ((e.ctrlKey || e.metaKey) && e.keyCode === 78) {
            e.preventDefault();
            window.location.href = 'admin.php?page=arta-appointment-settings';
        }
        
        // Ctrl/Cmd + R for refresh
        if ((e.ctrlKey || e.metaKey) && e.keyCode === 82) {
            e.preventDefault();
            location.reload();
        }
    });

    // Print functionality
    $('.arta-print').on('click', function(e) {
        e.preventDefault();
        window.print();
    });

    // Export functionality
    $('.arta-export').on('click', function(e) {
        e.preventDefault();
        var format = $(this).data('format') || 'csv';
        exportData(format);
    });

    function exportData(format) {
        var data = {
            action: 'arta_export_appointments',
            format: format,
            nonce: arta_admin.nonce
        };
        
        // Add current filters
        if ($('.arta-month-select').val()) {
            data.month = $('.arta-month-select').val();
        }
        if ($('.arta-doctor-select').val()) {
            data.doctor_id = $('.arta-doctor-select').val();
        }
        
        // Create form and submit
        var form = $('<form method="post" action="' + arta_admin.ajax_url + '"></form>');
        $.each(data, function(key, value) {
            form.append('<input type="hidden" name="' + key + '" value="' + value + '">');
        });
        
        $('body').append(form);
        form.submit();
        form.remove();
    }
});
