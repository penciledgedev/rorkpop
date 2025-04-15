/**
 * Public JavaScript for RORK POP
 */
(function ($) {
    'use strict';

    $(document).ready(function () {
        // Show popup with a slight delay
        setTimeout(function () {
            $('#rorkpop-overlay').addClass('show');
        }, 2000);

        // Close popup when clicking the close button
        $('.rorkpop-close').on('click', function () {
            $('#rorkpop-overlay').removeClass('show');
        });

        // Close popup when clicking outside the popup
        $('#rorkpop-overlay').on('click', function (e) {
            if ($(e.target).closest('.rorkpop-container').length === 0) {
                $('#rorkpop-overlay').removeClass('show');
            }
        });

        // Handle form submission
        $('#rorkpop-form').on('submit', function (e) {
            e.preventDefault();

            const $form = $(this);
            const $submitButton = $('#rorkpop-submit');
            const $message = $('#rorkpop-message');

            // Collect form data
            const formData = {
                name: $('#rorkpop-name').val(),
                email: $('#rorkpop-email').val(),
                country: $('#rorkpop-country').val(),
                nonce: rorkpop_ajax.nonce,
                action: 'rorkpop_submit_form'
            };

            // Disable submit button and show loading state
            $submitButton.prop('disabled', true).text('Submitting...');

            // Send AJAX request
            $.ajax({
                url: rorkpop_ajax.ajax_url,
                type: 'POST',
                data: formData,
                success: function (response) {
                    $submitButton.prop('disabled', false).text('Submit');

                    if (response.success) {
                        // Show success message
                        $message.removeClass('error').addClass('success').text(response.data.message).show();

                        // Clear the form
                        $form[0].reset();

                        // Close popup after a delay
                        setTimeout(function () {
                            $('#rorkpop-overlay').removeClass('show');
                        }, 3000);
                    } else {
                        // Show error message
                        $message.removeClass('success').addClass('error').text(response.data.message).show();
                    }
                },
                error: function () {
                    $submitButton.prop('disabled', false).text('Submit');
                    $message.removeClass('success').addClass('error').text('An error occurred. Please try again.').show();
                }
            });
        });
    });

})(jQuery); 