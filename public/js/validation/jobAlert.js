$(document).ready(function () {
    $("#frm_jobAlert").validate({
        rules: {
            search_term: {
                required: true,
            },
            location_id: {
                required: true,
            },
            state_id: {
                required: true,
            },

        },
        messages: {
            search_term: {
                required: "Please provide Search Term.",
            },

            location_id: {
                required: "Please provide Location.",
            },

            state_id: {
                required: "Please provide State.",
            },
        },
    });
});
