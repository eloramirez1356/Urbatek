$(document).ready(function () {
    $('.js-type-select').on('click', function () {
        $('.ticket-tab').hide();
        $('#'+$(this).data('target')).show();
    })

    $file = $('#ticket_file');
    $provider_signed = $('#ticket_provider_signed');
    $file.attr('required', $provider_signed.val());

    $provider_signed.on('change', function (){
        if ($(this).val() === 1) {
            $file.attr('required', true);
        } else {
            $file.attr('required', false);
        }
    });

});