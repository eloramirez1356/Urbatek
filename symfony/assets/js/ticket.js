$(document).ready(function () {
    $('.js-type-select').on('click', function () {
        $('.ticket-tab').hide();
        $('#'+$(this).data('target')).show();
    })

    $provider_signed = $('#ticket_provider_signed');
    $('#ticket_file').attr('required', false);

    // Validación del campo comentarios cuando se selecciona "Otra" como obra
    $('#ticket_site').on('change', function() {
        var $comments = $('#ticket_comments');
        if ($(this).val() === '11') {
            $comments.attr('required', true);
            if (!$comments.val()) {
                $comments.addClass('is-invalid');
            } else {
                $comments.removeClass('is-invalid');
            }
        } else {
            $comments.attr('required', false);
            $comments.removeClass('is-invalid');
        }
    });

    // Validación del campo comentarios al escribir
    $('#ticket_comments').on('input', function() {
        if ($('#ticket_site').val() === '11') {
            if (!$(this).val()) {
                $(this).addClass('is-invalid');
            } else {
                $(this).removeClass('is-invalid');
            }
        }
    });

    // Validación del formulario antes de enviar
    $('form').on('submit', function(e) {
        if ($('#ticket_site').val() === '11' && !$('#ticket_comments').val()) {
            e.preventDefault();
            $('#ticket_comments').addClass('is-invalid');
            return false;
        }
    });

    var canvas = document.getElementById('signature-pad');

    // Adjust canvas coordinate space taking into account pixel ratio,
    // to make it look crisp on mobile devices.
    // This also causes canvas to be cleared.
    function resizeCanvas() {
        // When zoomed out to less than 100%, for some very strange reason,
        // some browsers report devicePixelRatio as less than 1
        // and only part of the canvas is cleared then.
        var ratio =  Math.max(window.devicePixelRatio || 1, 1);
        canvas.width = canvas.offsetWidth * ratio;
        canvas.height = canvas.offsetHeight * ratio;
        canvas.getContext("2d").scale(ratio, ratio);
    }

    window.onresize = resizeCanvas;
    resizeCanvas();

    var signaturePad = new SignaturePad(canvas, {
        backgroundColor: 'rgb(255, 255, 255)' // necessary for saving image as JPEG; can be removed is only saving as PNG or SVG
    });

    document.getElementById('save-png').addEventListener('click', function () {
        if (signaturePad.isEmpty()) {
            return alert("Please provide a signature first.");
        }

        var data = signaturePad.toDataURL('image/png');
        console.log(data);
        window.open(data);
    });

    document.getElementById('clear').addEventListener('click', function () {
        signaturePad.clear();
    });

    // $provider_signed.on('change', function (){
    //     if ($(this).val() == 1) {
    //         $file.attr('required', true);
    //     } else {
    //         $file.attr('required', false);
    //     }
    // });

});