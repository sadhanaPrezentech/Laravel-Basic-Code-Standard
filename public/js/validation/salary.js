$(document).ready(function () {
    $("#frm_salary").validate({
        rules: {
            title: {
                required: true,
            },
            start: {
                required: true,
            },
            end: {
                required: true,
            },
        },
        messages: {
            title: {
                required: "Please provide salary title.",
            },
            start: {
                required: "Please provide salary start range.",
            },
            end: {
                required: "Please provide salary end range.",
            },
        },
    });
});
