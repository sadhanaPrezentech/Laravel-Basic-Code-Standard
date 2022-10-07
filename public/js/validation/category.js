$(document).ready(function () {
    $("#frm_category").validate({
        rules: {
            title: {
                required: true,
            },
        },
        messages: {
            title: {
                required: "Please provide category title.",
            },
        },
    });
});
