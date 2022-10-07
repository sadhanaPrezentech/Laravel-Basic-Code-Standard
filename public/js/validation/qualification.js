$(document).ready(function () {
    $("#frm_qualification").validate({
        rules: {
            title: {
                required: true,
            },
        },
        messages: {
            title: {
                required: "Please provide qualification title.",
            },
        },
    });
});
