// change tables in DatataTable
$('.doc-devices-table').DataTable({
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

// deactivate input in info modal except another info in this row
$(document).on('blur', '.active', function (e) {
    var id = this.closest('tr').id.substring(4);
    setTimeout(function () {
        console.log(document.activeElement);
        if (!$(document.activeElement).hasClass('active')) {
            $(".hidden-input ").attr('readonly', true).css("border", "none");
            $(".hidden-select ").attr('disabled', true).css("border", "none");
            $(".hidden-op").removeClass('active');
            updateDevice(id);
        }

    }, 200)

});




///////////////////////////////////////new///////////////////////////////

$(document).on('click', '#add-row-btn ', function () {
    addDevice($(this).data("building"), $(this).data("loopno"));
});


// add fire protection device
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
            var tdDesc = "<td class='text-center'><a class='text-center' id='desc-" + device.id + "'></a></td>";
            var tdDate = "<td class='text-center'>Brak</td>";
            var tdActions = "<td><a type='button' id='edit-row-btn-" + device.id + "' class='primary edit-row-btn mr-1'><i class='la la-pencil'></i></a>" +
                "<a data-device='" + device.id + "' class='danger delete-row-btn mr-1'><i class='la la-trash-o'></i></a></td>";

            var row =
                "<tr id='row-" + device.id + "'>" +
                tdOrder + tdNumber + tdName + tdShortname + tdSerial + tdAddress + tdDesc + tdDate + tdActions +
                "</tr>";

            $("tbody").append(row);



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
        url: '../../../build-device/update/' + jsonString,
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
            var row = 1;
            $('.order').each(function () {
                $(this).text(row++);
            })
        },
        error: function () {
            alert('Błąd delete device');
        }
    });
};

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