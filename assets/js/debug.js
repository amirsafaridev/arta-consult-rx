// Debug JavaScript file
console.log('Debug JS file loaded');

// Test if jQuery is available
if (typeof jQuery !== 'undefined') {
    console.log('jQuery is available');
    
    jQuery(document).ready(function($) {
        console.log('jQuery document ready');
        
        // Test button click after 2 seconds
        setTimeout(function() {
            console.log('Setting up button handlers...');
            
            // Create Slots button
            $('#bulk-create-slots').on('click', function(e) {
                e.preventDefault();
                console.log('CREATE SLOTS BUTTON CLICKED!');
                alert('CREATE SLOTS BUTTON WORKS!');
            });
            
            // Delete Slots button
            $('#bulk-delete-slots').on('click', function(e) {
                e.preventDefault();
                console.log('DELETE SLOTS BUTTON CLICKED!');
                alert('DELETE SLOTS BUTTON WORKS!');
            });
            
            console.log('Button handlers set up');
        }, 2000);
    });
} else {
    console.log('jQuery is NOT available');
}
