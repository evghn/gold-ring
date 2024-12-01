$(() => {

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