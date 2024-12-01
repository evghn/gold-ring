$(() => {
    // $("#route-point_start_id").on('change', function() {
    //         $.post('/account/route/end-points?id=' + $(this).val(),
    //             res => $("#route-point_end_id").html(res)
    //         )
    //     }
    // )
    $("#create-route-pjax").on('change', '.pause-item', function() {
        $.pjax.reload("#route-pause-pjax", {
            url: "/account/route/calc-pause",
            method: "POST",
            data: $("#form-create-route").serialize(),
            pushState: false,
            replaceState: false,
            timeout: 5000
        })
    })
})