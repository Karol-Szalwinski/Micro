// change tables in DatataTable
$('.devices-table').DataTable({
    "language": {
        "lengthMenu": "Pokaż _MENU_ wierszy na stronie",
        "zeroRecords": "Niestety brak wyników",
        "info": "Stron _PAGE_ z _PAGES_",
        "infoEmpty": "Brak wierszy",
        "search": "Szukaj w każdej kolumnie:",
        "infoFiltered": "(znaleziono z _MAX_ wszystkich wierszy)"
    },
    "paging": false,
    "fixedHeader": {
        "header": true,
        "headerOffset": 70,
        "footer": true
    }
});


// activate input in info modal
$(document).on('click', '.edit-row-btn ', function () {
    //clear another open inpu
    $(".hidden-input ").attr('readonly', true).css("border", "none");
    $(".hidden-select ").attr('disabled', true).css("border", "none");

    // add to tr status active

    $(this).closest('tr').find(':input').removeAttr("readonly");
    $(this).closest('tr').find(':input').css("border", "solid").css("border-color", "DeepSkyBlue");
    $(this).closest('tr').find(':input').addClass('active');

    $(this).closest('tr').find('select').removeAttr("disabled");
    $(this).closest('tr').find('select').css("border", "solid").css("border-color", "DeepSkyBlue");
    $(this).closest('tr').find('select').addClass('active');

    window.getSelection().removeAllRanges();
});

// deactivate input in info modal
$(document).on('blur', '.active', function (e) {
    var id = this.closest('tr').id.substring( 4);
    setTimeout(function () {
        console.log(document.activeElement);
        if(!$(document.activeElement).hasClass('active')) {
            $(".hidden-input ").attr('readonly', true).css("border", "none");
            $(".hidden-select ").attr('disabled', true).css("border", "none");
            $(".hidden-op").removeClass('active');
            updateDevice(id);
        }

    }, 200)

});

// update fire protection device
 function updateDevice (id) {

     var device = {
         id: id,
         shortname: $('#i-shortcut-' + id).val(),
         serial:$('#i-serial-' + id).val(),
         address: $('#i-address-' + id).val(),
     };
     var jsonString = JSON.stringify(device);

    $.ajax({
        url: '../../../build-device/update/' + jsonString,
        type: 'POST',
        dataType: 'json',
        async: true,

        success: function (data) {

            // update row
            var device = JSON.parse(data['device']);
            $('#i-shortcut-' + id).val(device.shortname);
            $('#i-serial-' + id).val(device.serial);
            $('#i-address-' + id).val(device.address);

        },
        error: function () {
            alert('Błąd update device');
        }
    });
};