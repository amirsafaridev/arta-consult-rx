/**
 * Arta Prescription Admin JavaScript
 *
 * @package Arta_Consult_RX
 * @since 1.0.0
 */

(function($) {
    'use strict';

    $(document).ready(function() {
        var stampUploadButton = $('.arta-upload-stamp-button');
        var stampRemoveButton = $('.arta-remove-stamp-button');
        var stampInput = $('#_arta_doctor_stamp');
        var stampPreview = $('.arta-stamp-preview');

        // Handle stamp image upload
        stampUploadButton.on('click', function(e) {
            e.preventDefault();

            var frame = wp.media({
                title: artaPrescription.selectStamp || 'انتخاب مهر پزشک',
                button: {
                    text: artaPrescription.useThisImage || 'استفاده از این تصویر'
                },
                multiple: false,
                library: {
                    type: 'image'
                }
            });

            frame.on('select', function() {
                var attachment = frame.state().get('selection').first().toJSON();
                stampInput.val(attachment.id);
                
                // Update preview
                if (stampPreview.length) {
                    stampPreview.find('img').attr('src', attachment.url);
                } else {
                    var previewHtml = '<div class="arta-stamp-preview" style="margin-bottom: 10px;">';
                    previewHtml += '<img src="' + attachment.url + '" style="max-width: 200px; height: auto; border: 1px solid #ddd; border-radius: 4px; padding: 5px; background: #fff;" />';
                    previewHtml += '</div>';
                    stampUploadButton.before(previewHtml);
                }

                // Show remove button if not already visible
                if (!stampRemoveButton.length) {
                    var removeButtonText = artaPrescription.removeImage || 'حذف تصویر';
                    var removeButton = $('<button type="button" class="button arta-remove-stamp-button" style="margin-right: 5px;">' + removeButtonText + '</button>');
                    stampUploadButton.after(removeButton);
                    removeButton.on('click', function(e) {
                        e.preventDefault();
                        stampInput.val('');
                        stampPreview.remove();
                        $(this).remove();
                    });
                }
            });

            frame.open();
        });

        // Handle stamp image removal
        $(document).on('click', '.arta-remove-stamp-button', function(e) {
            e.preventDefault();
            stampInput.val('');
            stampPreview.remove();
            $(this).remove();
        });
    });
})(jQuery);

