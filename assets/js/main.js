"use strict";

$(function () {
    $('#add_attendee').submit(function (e) {
        e.preventDefault();

        var form = $(this);

        $('#submit_button').attr('disabled', true);

        $.ajax({
            type: 'POST',
            url: '/add_attendee.php',
            data: form.serialize(),
            success: function (data) {
                $('.error').hide();

                var response = JSON.parse(data);

                if (response.status === 'success') {
                    $('#add_attendee').hide();
                    $('#title').text(response.message);
                    $('#success').show();
                    form[0].reset();
                }
            },
            error: function (error) {
                $('.error').hide();

                $('#submit_button').removeAttr('disabled');

                var response = JSON.parse(error.responseText);

                if (response.status === 'error') {
                    for (var field in response.errors) {
                        var errorText = response.errors[field];
                        var errorElement = $('input[name="'+ field +'"]').next('.error');
                        errorElement.text(errorText).show();
                    }
                }
            }
        });
    });
})
