$(document).ready(function () {
    $('.js-type-select').on('click', function () {
        $('.ticket-tab').hide();
        $('#'+$(this).data('target')).show();
    })
});