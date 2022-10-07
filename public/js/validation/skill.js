$(document).ready(function () {
    $("#frm_skill").validate({
        rules: {
            title: {
                required: true,
            },
        },
        messages: {
            title: {
                required: "Please provide skill title.",
            },
        },
    });
});
