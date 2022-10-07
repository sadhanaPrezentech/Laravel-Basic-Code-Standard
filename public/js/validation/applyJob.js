$(document).ready(function () {
    $("#frm_applyJob").validate({
        rules: {
            title: {
                required: true,
            },
        },
        messages: {
            title: {
                required: "Please provide applyJob title.",
            },
        },
    });
});
