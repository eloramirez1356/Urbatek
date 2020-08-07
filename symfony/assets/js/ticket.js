$(document).ready(function () {
    // $material_element = $('#ticket_material');
    // $material_element.ready(function () {
    //     setMaterialSelector($('option:selected', this).attr('js-type'));
    // });
    //
    // setMaterialSelector($('option:selected', $material_element).attr('js-type'));
    // $material_element.on('change', function () {
    //     let type = $('option:selected', this).attr('js-type');
    //     setMaterialSelector(type);
    // });

    $('.js-type-select').on('click', function () {
        $('.ticket-tab').hide();
        $('#'+$(this).data('target')).show();
    })
});

function setMaterialSelector(type) {
    let $tons_selector = $('#ticket_tons');
    let $num_travels_selector = $('#ticket_num_travels');
    if (type === 'withdrawal') {
        // $num_travels_selector.parent().show();
        // $num_travels_selector.attr('required', true);
        // $num_travels_selector.attr('disabled', false);
        //
        // $tons_selector.parent().hide();
        // $tons_selector.attr('required', false);
        // $tons_selector.attr('disabled', true);

    }

    if (type === 'supply') {
        // $tons_selector.parent().show();
        // $tons_selector.attr('required', true);
        // $tons_selector.attr('disabled', false);
        //
        // $num_travels_selector.parent().hide();
        // $num_travels_selector.attr('required', false);
        // $num_travels_selector.attr('disabled', true);
    }
}