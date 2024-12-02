$(() => {

    $('#block-1, #block-2').on('click', '.btn-route-view-modal', function(e) {
        e.preventDefault();
        const href = $(this).attr('href');
        $.pjax.reload("#route-view-modal-pjax", {
            url: href,
            method: "POST",
            push: false,
            replace: false,
            timeout: 5000
        });
    })

    $('#view-route-modal').on('click', '.btn-modal-close', (e) => {
        e.preventDefault();
        $('#view-route-modal').modal('hide');      
    })
    $("#route-view-modal-pjax").on('pjax:end', 
        () => $('#view-route-modal').modal('show')
    );   
})