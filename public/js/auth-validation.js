/**
 * Auth Client-Side Form Validation (jQuery & Vanilla JS)
 * Smart Household & Home Services Management System Prototype
 */

$(document).ready(function () {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    // -------------------------------------------------------------
    // 1. Registration Form Validation
    // -------------------------------------------------------------
    $('#registerForm').on('submit', function (e) {
        let isValid = true;

        // Reset errors
        $('.form-control').removeClass('is-invalid');
        $('.invalid-feedback').text('');

        // Full Name Validation
        const name = $('#name').val().trim();
        if (name === '') {
            $('#name').addClass('is-invalid');
            $('#name_error').text('Full name is required.');
            isValid = false;
        }

        // Email Validation
        const email = $('#email').val().trim();
        if (email === '') {
            $('#email').addClass('is-invalid');
            $('#email_error').text('Email address is required.');
            isValid = false;
        } else if (!emailRegex.test(email)) {
            $('#email').addClass('is-invalid');
            $('#email_error').text('Please enter a valid email address.');
            isValid = false;
        }

        // Password Validation (min 8 chars)
        const password = $('#password').val();
        if (password === '') {
            $('#password').addClass('is-invalid');
            $('#password_error').text('Password is required.');
            isValid = false;
        } else if (password.length < 8) {
            $('#password').addClass('is-invalid');
            $('#password_error').text('Password must be at least 8 characters long.');
            isValid = false;
        }

        // Password Confirmation Validation
        const confirmPassword = $('#password_confirmation').val();
        if (confirmPassword === '') {
            $('#password_confirmation').addClass('is-invalid');
            $('#password_confirmation_error').text('Please confirm your password.');
            isValid = false;
        } else if (password !== confirmPassword) {
            $('#password_confirmation').addClass('is-invalid');
            $('#password_confirmation_error').text('Passwords do not match.');
            isValid = false;
        }

        // Role Validation
        if (!$('input[name="role"]:checked').val()) {
            $('#role_error').text('Please select a user role.');
            isValid = false;
        }

        if (!isValid) {
            e.preventDefault();
            e.stopPropagation();
        }
    });

    // -------------------------------------------------------------
    // 2. Login Form Validation
    // -------------------------------------------------------------
    $('#loginForm').on('submit', function (e) {
        let isValid = true;

        $('.form-control').removeClass('is-invalid');
        $('.invalid-feedback').text('');

        const email = $('#email').val().trim();
        if (email === '') {
            $('#email').addClass('is-invalid');
            $('#email_error').text('Email address is required.');
            isValid = false;
        } else if (!emailRegex.test(email)) {
            $('#email').addClass('is-invalid');
            $('#email_error').text('Please enter a valid email address format.');
            isValid = false;
        }

        const password = $('#password').val();
        if (password === '') {
            $('#password').addClass('is-invalid');
            $('#password_error').text('Password is required.');
            isValid = false;
        }

        if (!isValid) {
            e.preventDefault();
            e.stopPropagation();
        }
    });
});
