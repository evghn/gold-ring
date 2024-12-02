$(() => {

    $("#route-pause-pjax").on('change', '.pause-item', function() {
        $.pjax.reload("#route-pause-pjax", {
            url: "/manager/route/calc-pause?id=" + $(this).data('route-id'),
            method: "POST",
            data: $("#form-update-route").serialize(),
            push: false,
            replace: false,
            timeout: 5000
        })
    })
})