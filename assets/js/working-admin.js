/**
 * Working Admin JavaScript for Arta Consult RX
 */

console.log('Working Admin JS loaded');

jQuery(document).ready(function($) {
    console.log('jQuery ready in working-admin.js');
    
    // Handle Create Slots button
    $('#bulk-create-slots').on('click', function(e) {
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
        
        console.log('Form data:', formData);
        
        // Validate form data
        if (!formData.doctor_id || !formData.start_date || !formData.end_date) {
            alert('Please fill in all required fields.');
            return;
        }
        
        // Show loading
        var $button = $(this);
        var originalText = $button.text();
        $button.prop('disabled', true).text('Creating...');
        
        // Make AJAX request
        $.post(arta_admin.ajax_url, formData, function(response) {
            console.log('AJAX response:', response);
            
            if (response.success) {
                alert('Success: ' + response.data.message);
                // Refresh the page
                setTimeout(function() {
                    location.reload();
                }, 1500);
            } else {
                alert('Error: ' + (response.data.message || 'Unknown error'));
            }
        }).fail(function(xhr, status, error) {
            console.log('AJAX error:', xhr, status, error);
            alert('AJAX Error: ' + error);
        }).always(function() {
            $button.prop('disabled', false).text(originalText);
        });
    });
    
    // Handle Delete Slots button
    $('#bulk-delete-slots').on('click', function(e) {
        e.preventDefault();
        console.log('Delete Slots button clicked!');
        
        if (!confirm('Are you sure you want to delete slots?')) {
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
        
        console.log('Delete form data:', formData);
        
        // Validate form data
        if (!formData.doctor_id || !formData.start_date || !formData.end_date) {
            alert('Please fill in all required fields.');
            return;
        }
        
        // Show loading
        var $button = $(this);
        var originalText = $button.text();
        $button.prop('disabled', true).text('Deleting...');
        
        // Make AJAX request
        $.post(arta_admin.ajax_url, formData, function(response) {
            console.log('Delete AJAX response:', response);
            
            if (response.success) {
                alert('Success: ' + response.data.message);
                // Refresh the page
                setTimeout(function() {
                    location.reload();
                }, 1500);
            } else {
                alert('Error: ' + (response.data.message || 'Unknown error'));
            }
        }).fail(function(xhr, status, error) {
            console.log('Delete AJAX error:', xhr, status, error);
            alert('AJAX Error: ' + error);
        }).always(function() {
            $button.prop('disabled', false).text(originalText);
        });
    });
    
    console.log('Working admin handlers set up');
});
