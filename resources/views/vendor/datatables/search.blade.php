<script>
    $("form#{{$form_id}}").find('input').on('keyup', function() {
        $("form#{{$form_id}}").submit();
    })

    $("form#{{$form_id}}").find('select').on('change', function() {
        $("form#{{$form_id}}").submit();
    });

    $("form#{{$form_id}}").find('input[type="checkbox"]').on('click', function() {
        if($(this).is(':checked')){
            $("form#{{$form_id}}").submit();
        }
        else{
            $("form#{{$form_id}}").submit();
        }

    });
    $(document).on("submit", "form#{{$form_id}}", function(e) {
        e.preventDefault();
        window.LaravelDataTables["{{ $id }}"].draw();
    })
</script>
