/**
 * Test JavaScript file
 */

console.log('Arta Consult RX JavaScript loaded successfully!');

jQuery(document).ready(function($) {
    console.log('jQuery is ready!');
    
    // Test if our admin object exists
    if (typeof arta_admin !== 'undefined') {
        console.log('arta_admin object exists:', arta_admin);
    } else {
        console.log('arta_admin object is not defined');
    }
    
    // Test button click
    $('#bulk-create-slots').on('click', function(e) {
        e.preventDefault();
        console.log('Create Slots button clicked!');
        alert('Create Slots button clicked!');
    });
    
    $('#bulk-delete-slots').on('click', function(e) {
        e.preventDefault();
        console.log('Delete Slots button clicked!');
        alert('Delete Slots button clicked!');
    });
    
    // Test form validation
    $('#bulk-scheduler-form').on('submit', function(e) {
        e.preventDefault();
        console.log('Form submitted!');
        alert('Form submitted!');
    });
});
