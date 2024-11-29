$(() => {
    $("#route-point_start_id").on('change', function() {
            $.post('/account/route/end-points?id=' + $(this).val(),
                res => $("#route-point_end_id").html(res)
            )
        }
    )
})