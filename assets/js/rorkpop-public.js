/**
 * Public JavaScript for RORK POP
 */
(function ($) {
    'use strict';

    $(document).ready(function () {
        // Show popup immediately and don't allow closing
        $('#rorkpop-overlay').addClass('show');

        // Create login form once on page load
        const createLoginForm = function () {
            // Get the login URL from the first login toggle link
            const loginUrl = $('.login-toggle').first().attr('href') || '#';

            const loginForm = `
                <form id="rorkpop-login-form" class="rorkpop-form" style="display: none;">
                    <h4>LOG IN TO YOUR ACCOUNT</h4>
                    <input type="hidden" name="action" value="rorkpop_login_user">
                    <input type="hidden" name="rorkpop_login_nonce" id="rorkpop_login_nonce" value="${rorkpop_ajax.nonce}">
                    
                    <div class="rorkpop-form-field">
                        <label for="rorkpop-login-username">USERNAME OR EMAIL</label>
                        <input type="text" id="rorkpop-login-username" name="username" required>
                    </div>
                    
                    <div class="rorkpop-form-field">
                        <label for="rorkpop-login-password">PASSWORD</label>
                        <input type="password" id="rorkpop-login-password" name="password" required>
                    </div>
                    
                    <div class="rorkpop-form-field checkbox">
                        <input type="checkbox" id="rorkpop-login-remember" name="remember">
                        <label for="rorkpop-login-remember">Remember me</label>
                    </div>
                    
                    <div class="rorkpop-form-submit">
                        <button type="submit" id="rorkpop-login-submit">LOG IN</button>
                    </div>
                    
                    <div id="rorkpop-login-message" class="rorkpop-response-message"></div>
                    
                    <div class="rorkpop-login-link">
                        <p><a href="#" class="register-toggle">Need an account? Register here</a></p>
                        <p><a href="${loginUrl}?action=lostpassword" target="_blank">Forgot your password?</a></p>
                    </div>
                </form>
            `;

            $('.rorkpop-form-container').append(loginForm);
        };

        // Create the login form once the page is loaded
        createLoginForm();

        // Handle form submission for registration
        $('#rorkpop-form').on('submit', function (e) {
            e.preventDefault();

            const $form = $(this);
            const $submitButton = $('#rorkpop-submit');
            const $message = $('#rorkpop-message');

            // Validate password
            const password = $('#rorkpop-password').val();
            if (password.length < 6) {
                $message.removeClass('success').addClass('error').text('Password must be at least 6 characters long.').show();
                return;
            }

            // Validate terms checkbox
            if (!$('#rorkpop-terms').is(':checked')) {
                $message.removeClass('success').addClass('error').text('You must agree to the Terms and Conditions.').show();
                return;
            }

            // Collect form data
            const formData = {
                name: $('#rorkpop-name').val(),
                email: $('#rorkpop-email').val(),
                password: password,
                country: $('#rorkpop-country').val(),
                terms: $('#rorkpop-terms').is(':checked') ? 1 : 0,
                rorkpop_nonce: $('#rorkpop_nonce').val(),
                action: 'rorkpop_register_user'
            };

            // Store email for success message
            const userEmail = formData.email;

            // Disable submit button and show loading state
            $submitButton.prop('disabled', true).text('Registering...');

            // Send AJAX request
            $.ajax({
                url: rorkpop_ajax.ajax_url,
                type: 'POST',
                data: formData,
                success: function (response) {
                    $submitButton.prop('disabled', false).text('Register');

                    if (response.success) {
                        // Show verification message with user's email
                        $form.hide();
                        $message.removeClass('error').addClass('success').html(
                            '<h4>Successful Registration!</h4>' +
                            '<p>Check your email <strong>' + userEmail + '</strong> to verify your registration.</p>' +
                            '<p>A verification link has been sent to your inbox. Please click the link to complete your registration.</p>'
                        ).show();
                    } else {
                        // Show error message
                        $message.removeClass('success').addClass('error').text(response.data.message).show();
                    }
                },
                error: function () {
                    $submitButton.prop('disabled', false).text('Register');
                    $message.removeClass('success').addClass('error').text('An error occurred. Please try again.').show();
                }
            });
        });

        // Handle login form submission
        $(document).on('submit', '#rorkpop-login-form', function (e) {
            e.preventDefault();

            const $form = $(this);
            const $submitButton = $('#rorkpop-login-submit');
            const $message = $('#rorkpop-login-message');

            // Collect form data
            const formData = {
                username: $('#rorkpop-login-username').val(),
                password: $('#rorkpop-login-password').val(),
                rorkpop_login_nonce: $('#rorkpop_login_nonce').val(),
                remember: $('#rorkpop-login-remember').is(':checked') ? 1 : 0,
                action: 'rorkpop_login_user'
            };

            // Disable submit button and show loading state
            $submitButton.prop('disabled', true).text('Logging in...');

            // Send AJAX request
            $.ajax({
                url: rorkpop_ajax.ajax_url,
                type: 'POST',
                data: formData,
                success: function (response) {
                    if (response.success) {
                        // Show success message and reload page
                        $message.removeClass('error').addClass('success').text('Login successful! Redirecting...').show();

                        setTimeout(function () {
                            window.location.reload();
                        }, 1000);
                    } else {
                        // Show error message
                        $submitButton.prop('disabled', false).text('Log In');
                        $message.removeClass('success').addClass('error').text(response.data.message).show();
                    }
                },
                error: function () {
                    $submitButton.prop('disabled', false).text('Log In');
                    $message.removeClass('success').addClass('error').text('An error occurred. Please try again.').show();
                }
            });
        });

        // Handle switching to login form
        $(document).on('click', '.login-toggle', function (e) {
            e.preventDefault();

            // Hide registration form and show login form
            $('#rorkpop-form').hide();
            $('#rorkpop-message').hide();
            $('#rorkpop-login-form').show();
        });

        // Handle switching back to registration form
        $(document).on('click', '.register-toggle', function (e) {
            e.preventDefault();

            $('#rorkpop-login-form').hide();
            $('#rorkpop-login-message').hide();
            $('#rorkpop-form').show();
        });

        // Check for email verification success
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.has('email_verified') && urlParams.get('email_verified') === 'true') {
            // Show verification success message
            $('<div class="rorkpop-verification-success">' +
                '<div class="rorkpop-verification-content">' +
                '<h3>Email Verified Successfully!</h3>' +
                '<p>Thank you for verifying your email. You now have full access to our content.</p>' +
                '<button id="rorkpop-verification-close">Continue to Website</button>' +
                '</div>' +
                '</div>').appendTo('body');

            // Handle close button click
            $('#rorkpop-verification-close').on('click', function () {
                $('.rorkpop-verification-success').fadeOut(function () {
                    $(this).remove();
                });

                // Remove the query parameter from URL
                const url = new URL(window.location);
                url.searchParams.delete('email_verified');
                window.history.replaceState({}, '', url);
            });
        }
    });

})(jQuery); 