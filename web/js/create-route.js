$(() => {

    $("#create-route-pjax").on('change', '.pause-item', function() {
        $.pjax.reload("#route-pause-pjax", {
            url: "/account/route/calc-pause",
            method: "POST",
            data: $("#form-create-route").serialize(),
            push: false,
            replace: false,
            timeout: 5000
        })
    })
})
