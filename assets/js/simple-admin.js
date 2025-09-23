/**
 * Simple Admin JavaScript for Arta Consult RX
 */

console.log('Simple Admin JS loaded');

jQuery(document).ready(function($) {
    console.log('jQuery ready in simple-admin.js');
    
    // Wait a bit for the page to fully load
    setTimeout(function() {
        console.log('Setting up button handlers...');
        
        // Handle Create Slots button
        $(document).on('click', '#bulk-create-slots', function(e) {
            e.preventDefault();
            console.log('Create Slots button clicked!');
            
            // Show loading
            var $button = $(this);
            var originalText = $button.text();
            $button.prop('disabled', true).text('Creating...');
            
            // Simulate AJAX call
            setTimeout(function() {
                alert('Create Slots functionality would work here!');
                $button.prop('disabled', false).text(originalText);
            }, 2000);
        });
        
        // Handle Delete Slots button
        $(document).on('click', '#bulk-delete-slots', function(e) {
            e.preventDefault();
            console.log('Delete Slots button clicked!');
            
            if (confirm('Are you sure you want to delete slots?')) {
                // Show loading
                var $button = $(this);
                var originalText = $button.text();
                $button.prop('disabled', true).text('Deleting...');
                
                // Simulate AJAX call
                setTimeout(function() {
                    alert('Delete Slots functionality would work here!');
                    $button.prop('disabled', false).text(originalText);
                }, 2000);
            }
        });
        
        // Handle form submission
        $(document).on('submit', '#bulk-scheduler-form', function(e) {
            e.preventDefault();
            console.log('Form submitted!');
            alert('Form submission prevented - would process here!');
        });
        
        console.log('Button handlers set up successfully');
        
    }, 1000);
});
