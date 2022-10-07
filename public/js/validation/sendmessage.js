$(document).ready(function () {
    $("#frm_message").validate({
        rules: {
            subject: {
                required: '#via_email:checked',
            },
            message: {
                required: true,
            },

        },
        messages: {
            subject: {
                required: "Please provide subject field.",
            },
            message: {
                required: "Please provide message field.",
            },
        },
    });
});
