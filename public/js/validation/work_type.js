$(document).ready(function () {
    $("#frm_workType").validate({
        rules: {
            title: {
                required: true,
            },
        },
        messages: {
            title: {
                required: "Please provide work type title.",
            },
        },
    });
});
