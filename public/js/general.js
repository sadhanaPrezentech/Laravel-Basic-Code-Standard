
    function submitFormByaction(action, id, msg = "") {

        $("form#" + id)
            .find('input[name="process"]')
            .val(action);

        if (action == "delete") {
            var message =
                "This will delete the record permanently, do you want to delete?";
            if (msg != "") {
                message = msg;
            }
            if (confirm(message)) {
                rawformSubmit("DELETE", id, action);
            }
        }
    }

    function rawformSubmit(method, form_id, process) {
        $("form#" + form_id)
            .find('input[name="_method"]')
            .val(method);
        var url = $("form#" + form_id).attr("action");
        var data = JSON.stringify({ process: process, _method: method });
        var model = $("form#" + form_id).data("model");
        processAjaxOperation(
            url,
            method,
            data,
            "application/json",
            model,
            null,
            false
        );
    }
    function processAjaxOperation(
        url,
        type,
        data,
        contentType = false,
        model,
        processData = false
    ) {

        var options = {
            url: url,
            type: type,
            data: data,
            success: function (response) {
                if (response.success === true) {
                        reloadDataTable(model);
                } else {
                    // $("#errorMessage").removeClass("d-none").html(response.message);
                    console.log(response.message);
                }
            },
            error: function (reject) {
                console.log(reject);
                console.log('Something went wrong. Please try again.');
            },
            contentType: contentType,
            processData: processData,
        };

        $.ajax(options);
    }
function reloadDataTable(model) {
    if (
        window.hasOwnProperty("LaravelDataTables") &&
        window.LaravelDataTables.hasOwnProperty(model + "-datatable")
    )
        window.LaravelDataTables[model + "-datatable"].draw();
}
