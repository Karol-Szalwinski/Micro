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


// activate input in device row
$(document).on('click', '.edit-row-btn ', function () {
    // add to tr status active

    $(this).closest('tr').find(':input').removeAttr("readonly");
    $(this).closest('tr').find(':input').css("border", "2px solid #00bfff");
    $(this).closest('tr').find(':input').addClass('active');

    $(this).closest('tr').find('select').removeAttr("disabled");
    $(this).closest('tr').find('select').css("border", "2px solid #00bfff");
    $(this).closest('tr').find('select').addClass('active');

    window.getSelection().removeAllRanges();
});

// deactivate input in info modal except another input or select in this row
$(document).on('blur', '.active', function (e) {
    var activeRow = this.closest('tr');
    var id = activeRow.id.substring(4);

    setTimeout(function () {
        var oldActiveRow = activeRow;
        var newActiveRow = document.activeElement.closest('tr');
        if (oldActiveRow !== newActiveRow) {
            var bgcolor = $(oldActiveRow).find('input').css("background-color");

            $(oldActiveRow).find('input').attr('readonly', true).css("border-color", bgcolor);
            $(oldActiveRow).find('select').attr('disabled', true).css("border-color", bgcolor);
            $(".hidden-op").removeClass('active');
            updateDevice(id);
        }

    }, 200)

});




///////////////////////////////////////new///////////////////////////////

$(document).on('click', '#add-row-btn ', function () {
    addDevice($(this).data("building"), $(this).data("loopno"));
});


// add build-device
function addDevice(building, loop) {

    $.ajax({
        url: '../../../build-device/add/' + building + '/' + loop,
        type: 'POST',
        dataType: 'json',
        async: true,

        success: function (data) {
            var options = "";
            var device = JSON.parse(data['device']);
            var next = data['next'];
            var shortnames = data['shortnames'];
            var tdOrder = "<td class='text-center order'>" + next + "</td>";
            var tdNumber = "<td class='text-right'>" + device.loopNo + " / <input type='number' min='1' max='255' " +
                    "class='hidden-op hidden-input unique-input' id='i-number-" + device.id + "' value='" + device.number + "'readonly></td>";
            var tdName = "<td class='text-center'><a class='text-center' id='i-name-" + device.id + "'></a></td>";

            shortnames.forEach(function(item){
                options +="<option value='" + item + "'>" + item + "</option>";
            });


            var tdShortname = "<td class='text-center'><select class=' hidden-op hidden-select' id='i-shortname-" + device.id + "' disabled>" +
                "<option value='' selected></option>" +
                options + "</select></td>";
            var tdSerial = "<td class='text-center'><input class='hidden-op hidden-input' id='i-serial-" + device.id + "' value='' readonly></td>";
            var tdAddress = "<td class='text-center'><input class='hidden-op hidden-input' id='i-address-" + device.id + "' value='' readonly></td>";
            var tdDate = "<td class='text-center'>Brak</td>";
            var tdActions = "<td><a id='info-" + device.id + "' data-toggle='modal' data-target='#info-modal' title='Pokaż info'" +
                "class=' info mr-1 info-modal-btn'><i class='la la-info'></i></a>" +
                "<a id='edit-row-btn-" + device.id + "' class='primary edit-row-btn mr-1'><i class='la la-pencil'></i></a>" +
                "<a data-device='" + device.id + "' class='danger delete-row-btn mr-1'><i class='la la-trash-o'></i></a></td>";

            var row =
                "<tr id='row-" + device.id + "'>" +
                tdOrder + tdNumber + tdName + tdShortname + tdSerial + tdAddress + tdDate + tdActions +
                "</tr>";

            $("#tbody-devices").append(row);
            repairRowOrderWithStripes();



        },
        error: function () {
            alert('Błąd dodawania device');
        }
    });
};


// update fire protection device
function updateDevice(id) {

    var device = {
        id: id,
        shortname: $('#i-shortname-' + id).val(),
        number: $('#i-number-' + id).val(),
        serial: $('#i-serial-' + id).val(),
        address: $('#i-address-' + id).val(),
    };
    var jsonString = JSON.stringify(device);

    $.ajax({
        url: '../../../build-device/update/' + encodeURIComponent(jsonString),
        type: 'POST',
        dataType: 'json',
        async: true,

        success: function (data) {

            // update row
            var device = JSON.parse(data['device']);
            $('#i-shortname-' + id).val(device.shortname);
            $('#i-name-' + id).text(device.name);
            $('#i-number-' + id).val(device.number);
            $('#i-serial-' + id).val(device.serial);
            $('#i-address-' + id).val(device.address);

        },
        error: function () {
            alert('Błąd update device');
        }
    });
};


$(document).on('click', '.delete-row-btn ', function () {

    var id = $(this).data("device");
    swal({
        title: "Jesteś pewny?",
        text: "Usunięcie tego urządzenia jest nieodwracalne!",
        icon: "warning",
        buttons: {
            cancel: {
                text: "Nie, rozmyśliłem się",
                value: null,
                visible: true,
                className: "",
                closeModal: false,
            },
            confirm: {
                text: "Tak, usuń to urządzenie!",
                value: true,
                visible: true,
                className: "",
                closeModal: false,
            }
        }
    })
        .then((isConfirm) => {
            if (isConfirm) {
                deleteDevice(id);
                swal("Usunięte!", "Tego urządzenia już nie zobaczysz.", "success");
            } else {
                swal("Anulowano", "Twoje urządzenie pozostało", "error");
            }
        })
});

function deleteDevice(id) {
    $.ajax({
        url: '../../../build-device/delete/' + id,
        type: 'POST',
        dataType: 'json',
        async: true,

        success: function (data) {
            var id = data['id'];
            $('#row-'+ id).remove();
            //repair first column after remover row
            repairRowOrderWithStripes();
        },
        error: function () {
            alert('Błąd delete device');
        }
    });
};

function repairRowOrderWithStripes() {
    var row = 1;
    $('.order').each(function () {
        if($(this).is(':visible')) {
            $(this).text(row++);
            if (row % 2 === 1){ //odd row
                $(this).parent().addClass('grey');
            } else
            {
                $(this).parent().removeClass('grey');
            }
        }
    })
}

//compare number inputs
$(document).on('change', '.unique-input', function () {
    $(this).removeClass('unique-input');
    var value = this.value;
    var unique = true;
    $('.unique-input').each(function () {

        if (this.value == value) {
            unique = false;
        }
    })

    if (unique == false) {
        $(this).addClass('danger');
        $(this).parent().addClass('danger');
    } else {
        $(this).removeClass('danger');
        $(this).parent().removeClass('danger');
    }
    $(this).addClass('unique-input');
})
;

// fill info modal
$(document).on('click', '.info-modal-btn', function () {
    var id = this.id.substring(5);
    $.ajax({
        url: '../../../build-device/get/' + id,
        type: 'POST',
        dataType: 'json',
        async: true,

        success: function (data) {

            var device = JSON.parse(data['device']);

            $('#dialog-name').text(device.name);
            $('#dialog-id').text(device.id);
            $('#dialog-serial').val(device.serial);
            $('#dialog-address').val(device.address);
            $('#dialog-description').val(device.desc);
            $('#dialog-number').text(device.loopNo + ' / ' + device.number);
            $('#dialog-temp-date').text(device.lastServiceDate);


            $('#info-modal').modal('show');

        },
        error: function () {
            alert('Błąd przesyłania');
        }
    })

});

// animation dialog
// $("#animation-dialog").dialog({
//     autoOpen: false,
//     width: 400,
//     show: {
//         effect: "fade",
//         duration: 400
//     },
//     hide: {
//         effect: "explode",
//         duration: 1000
//     },
//     modal: true
// });

// activate input in info modal
$(document).on('click', '.edit-info-btn ', function () {

    $(this).parent().prev().children().removeAttr("readonly");
    $(this).parent().prev().children().focus();
    $(this).parent().prev().children().css("border", "2px solid #00bfff");
    window.getSelection().removeAllRanges();
});

// deactivate input in info modal
$(document).on('blur', '.info-input ', function () {

    $(".info-input ").attr('readonly', true).css("border", "2px solid #ffffff");
    $(".info-input:focus").css("outline", "2px solid #ffffff");

});
// update build device function
function updateBuildDevice(id) {

    var buildDevice = {
        id: id,
        serial: encodeURIComponent($('#dialog-serial').val()),
        address: encodeURIComponent($('#dialog-address').val()),
        desc : encodeURIComponent($('#dialog-description').val()),
    };
    var jsonString = JSON.stringify(buildDevice);

    $.ajax({
        url: '../../../build-device/update/' + encodeURIComponent(jsonString),
        type: 'POST',
        dataType: 'json',
        async: true,

        success: function (data) {

            // update row
            var device = JSON.parse(data['device']);
            $('#dialog-id').text(device.id);
            $('#dialog-name').text(device.name);
            $('#dialog-serial').val(device.serial);
            $('#dialog-address').val(device.address);
            $('#dialog-description').val(device.desc);
            //update row in datatable
            $('#i-serial-' + device.id).val(device.serial);
            $('#i-address-' + device.id).val(device.address);

        },
        error: function () {
            alert('Błąd update device');
        }
    });
};
// update fire protection device after change input
$(document).on('change', '.info-input', function () {

    var id = $('#dialog-id').text();
    updateBuildDevice(id);
});
