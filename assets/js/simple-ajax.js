/**
 * Simple AJAX JavaScript for Arta Consult RX
 */

console.log('Simple AJAX JS loaded');

jQuery(document).ready(function($) {
    console.log('jQuery ready in simple-ajax.js');
    
    // Test if arta_admin object exists
    if (typeof arta_admin === 'undefined') {
        console.log('arta_admin object is not defined!');
        return;
    }
    
    console.log('arta_admin object:', arta_admin);
    
    // Handle Create Slots button
    $(document).on('click', '#bulk-create-slots', function(e) {
        e.preventDefault();
        console.log('Create Slots button clicked!');
        
        // Get form data
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
        
        console.log('Form data to send:', formData);
        
        // Validate required fields
        if (!formData.doctor_id || !formData.start_date || !formData.end_date) {
            alert('Please fill in all required fields (Doctor, Start Date, End Date).');
            return;
        }
        
        // Show loading
        var $button = $(this);
        var originalText = $button.text();
        $button.prop('disabled', true).text('Creating...');
        
        // Make AJAX request
        $.ajax({
            url: arta_admin.ajax_url,
            type: 'POST',
            data: formData,
            success: function(response) {
                console.log('AJAX Success Response:', response);
                
                if (response.success) {
                    alert('Success: ' + response.data.message);
                    // Refresh the page to show new slots
                    setTimeout(function() {
                        location.reload();
                    }, 1500);
                } else {
                    alert('Error: ' + (response.data.message || 'Unknown error occurred'));
                }
            },
            error: function(xhr, status, error) {
                console.log('AJAX Error:', xhr, status, error);
                console.log('Response Text:', xhr.responseText);
                alert('AJAX Error: ' + error + '\nStatus: ' + status);
            },
            complete: function() {
                $button.prop('disabled', false).text(originalText);
            }
        });
    });
    
    // Handle Delete Slots button
    $(document).on('click', '#bulk-delete-slots', function(e) {
        e.preventDefault();
        console.log('Delete Slots button clicked!');
        
        if (!confirm('Are you sure you want to delete slots for the selected date range?')) {
            return;
        }
        
        // Get form data
        var formData = {
            action: 'arta_bulk_delete_slots',
            nonce: arta_admin.nonce,
            doctor_id: $('#doctor_id').val(),
            start_date: $('#start_date').val(),
            end_date: $('#end_date').val()
        };
        
        console.log('Delete form data to send:', formData);
        
        // Validate required fields
        if (!formData.doctor_id || !formData.start_date || !formData.end_date) {
            alert('Please fill in all required fields (Doctor, Start Date, End Date).');
            return;
        }
        
        // Show loading
        var $button = $(this);
        var originalText = $button.text();
        $button.prop('disabled', true).text('Deleting...');
        
        // Make AJAX request
        $.ajax({
            url: arta_admin.ajax_url,
            type: 'POST',
            data: formData,
            success: function(response) {
                console.log('Delete AJAX Success Response:', response);
                
                if (response.success) {
                    alert('Success: ' + response.data.message);
                    // Refresh the page to show updated slots
                    setTimeout(function() {
                        location.reload();
                    }, 1500);
                } else {
                    alert('Error: ' + (response.data.message || 'Unknown error occurred'));
                }
            },
            error: function(xhr, status, error) {
                console.log('Delete AJAX Error:', xhr, status, error);
                console.log('Response Text:', xhr.responseText);
                alert('AJAX Error: ' + error + '\nStatus: ' + status);
            },
            complete: function() {
                $button.prop('disabled', false).text(originalText);
            }
        });
    });
    
    console.log('Simple AJAX handlers set up successfully');
});
