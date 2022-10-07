$(document).ready(function () {
    $("#frm_experience").validate({
        rules: {
            title: {
                required: true,
            },
        },
        messages: {
            title: {
                required: "Please provide experience title.",
            },
        },
    });
});
