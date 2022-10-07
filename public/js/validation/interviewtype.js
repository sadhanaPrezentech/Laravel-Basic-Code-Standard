$(document).ready(function () {
    $("#frm_interviewType").validate({
        rules: {
            title: {
                required: true,
            },
        },
        messages: {
            title: {
                required: "Please provide interview type title.",
            },
        },
    });
});
