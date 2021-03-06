//set same value name and shortname in form
$("#microbundle_fireprotectiondevice_name").on("click", function () {
    $("#microbundle_fireprotectiondevice_shortname").val($(this).val());
});

$("#microbundle_fireprotectiondevice_shortname").on("click", function () {
    $("#microbundle_fireprotectiondevice_name").val($(this).val());
});

$("#microbundle_fireprotectiondevice_edit_name").on("click", function () {
    $("#microbundle_fireprotectiondevice_edit_shortname").val($(this).val());
});

$("#microbundle_fireprotectiondevice_edit_shortname").on("click", function () {
    $("#microbundle_fireprotectiondevice_edit_name").val($(this).val());
});

//fill edit form for BuildDevice
$("a[data-target='#EditContactModal']").on("click", function () {

    var id = $(this).attr('id').substr(5);
    $('#microbundle_fireprotectiondevice_edit_id').val(id);

    var $name = $("#name-" + id).text();
    var $namevalue = $("option:contains('" + $name + "')").val();
    $('#microbundle_fireprotectiondevice_edit_name').val($namevalue);

    var $shortname = $("#shortname-" + id).text();
    var $shortnamevalue = $("option:contains('" + $shortname + "')").val();
    $('#microbundle_fireprotectiondevice_edit_shortname').val($shortnamevalue);

    var $loop = $("#loop-" + id).text();
    $('#microbundle_fireprotectiondevice_edit_loopNo').val($loop);

    var $number = $("#number-" + id).text();
    $('#microbundle_fireprotectiondevice_edit_number').val($number);

});

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


//BEGIN: AJAX get object device -->

$(document).on('click', '.edit-row', function () {
    var id = this.id.substring(5);
    $.ajax({
        url: '../fireprotectiondevice/get-device/' + id,
        type: 'POST',
        dataType: 'json',
        async: true,

        success: function (data) {

            var device = JSON.parse(data['device']);
            $('#microbundle_fireprotectiondevice_edit_id').val(device.id);
            $('#microbundle_fireprotectiondevice_edit_name').val(data['nameId']);
            $('#microbundle_fireprotectiondevice_edit_serial').val(device.serial);
            $('#microbundle_fireprotectiondevice_edit_address').val(device.address);
            $('#microbundle_fireprotectiondevice_edit_desc').val(device.desc);
            $('#edit-form-id').text(device.number);


        },
        error: function () {
            alert('Błąd przesyłania');
        }
    });
});


//END: AJAX get object device -->


//BEGIN: set loop id to add form -->

$(document).ready(function () {
    $('.add-device-button').click(function () {
        var loop = this.id.substring(11);
        $('#microbundle_fireprotectiondevice_loopDev').val(loop);
    });
});


//END: set loop id to add form -->

//BEGIN: AJAX add object device -->


$(document).ready(function () {
    $('#submit-add').click(function () {
        var id = null;
        var loop = $('#microbundle_fireprotectiondevice_loopDev').val();
        var number = $('#microbundle_fireprotectiondevice_number').val();
        var name = $('#microbundle_fireprotectiondevice_name').val();
        var serial = $('#microbundle_fireprotectiondevice_serial').val();
        var address = $('#microbundle_fireprotectiondevice_address').val();
        var desc = $('#microbundle_fireprotectiondevice_desc').val();


        loop = (loop !== "") ? loop : null;
        number = (number !== "") ? number : null;
        name = (name !== "") ? name : null;
        serial = (serial !== "") ? serial : null;
        address = (address !== "") ? address : null;
        desc = (desc !== "") ? encodeURIComponent(desc) : null;


        $.ajax({
            url: '../fireprotectiondevice/update-device/' +
            id + '/' + loop + '/' + number + '/' + name + '/' + serial + '/' + address + '/' + desc,
            type: 'POST',
            dataType: 'json',
            async: true,

            success: function (data) {
                var loop = data["loopid"];
                var device = JSON.parse(data['device']);
                var id = device.id;

                var row = "<tr id='row-" + loop + "-" + id + "' ><td class='text-center'><a class='text-center' id='number-" + id + "'></a></td>" +
                    "<td class='text-center'><a class='text-center' id='name-" + id + "'></a></td>" +
                    "<td class='text-center'><a class='text-center ' id='shortname-" + id + "'></a></td>" +
                    "<td class='text-center'><a class='text-center ' id='serial-" + id + "'></a></td>" +
                    "<td class='text-center'><a class='text-center ' id='address-" + id + "'></a></td>" +
                    "<td class='text-center'><a class='text-center ' id='desc-" + id + "'></a></td>" +
                    "<td class='text-center'><a class='timeline-date'></a></td>" +
                    "<td><a data-toggle='modal' data-target='#EditDeviceModal' id='edit-" + id + "'" +
                    "class='primary edit-row mr-1'><i class='la la-pencil'></i></a>" +
                    "<a id='delete-" + id + "' class='cancel-delete danger delete mr-1'><i class='la la-trash-o'></i></a></td></tr>";
                $("#tbody-" + loop).append(row);

                // update row

                $('#name-' + id).text(device.name);
                $('#number-' + id).text(device.number);
                $('#shortname-' + id).text(device.shortname);
                var serial = (device.serial !== null) ? device.serial : "";
                $('#serial-' + id).text(serial);
                var address = (device.address !== null) ? device.address : "";
                $('#address-' + id).text(address);
                var desc = (device.desc !== null) ? device.desc : "";
                $('#desc-' + id).text(desc);


            },
            error: function () {
                alert('Błąd add device');
            }
        });
    });
});

//END: AJAX add object device -->

//BEGIN: AJAX update object device -->


$(document).on('click', '#submit-edit', function () {

    var id = $('#microbundle_fireprotectiondevice_edit_id').val();
    var loop = null;
    var number = null;
    var name = $('#microbundle_fireprotectiondevice_edit_name').val();
    var serial = $('#microbundle_fireprotectiondevice_edit_serial').val();
    var address = $('#microbundle_fireprotectiondevice_edit_address').val();
    var desc = $('#microbundle_fireprotectiondevice_edit_desc').val();

    id = (id !== "") ? id : null;
    loop = (loop !== "") ? loop : null;
    name = (name !== "") ? name : null;
    serial = (serial !== "") ? serial : null;
    address = (address !== "") ? address : null;
    desc = (desc !== "") ? encodeURIComponent(desc) : null;


    $.ajax({
        url: '../fireprotectiondevice/update-device/' +
        id + '/' + loop + '/' + number + '/' + name + '/' + serial + '/' + address + '/' + desc,
        type: 'POST',
        dataType: 'json',
        async: true,

        success: function (data) {
            // update row
            var device = JSON.parse(data['device']);
            var id = device.id;
            $('#name-' + id).text(device.name);
            $('#shortname-' + id).text(device.shortname);
            var serial = (device.serial !== null) ? device.serial : "";
            $('#serial-' + id).text(serial);
            var address = (device.address !== null) ? device.address : "";
            $('#address-' + id).text(address);
            var desc = (device.desc !== null) ? device.desc : "";
            $('#desc-' + id).text(desc);


        },
        error: function () {
            alert('Błąd update device');
        }
    });
});


//END: AJAX update object device -->

//BEGIN: AJAX set device as deleted-->


function deleteDevice(id) {

    $.ajax({
        url: '../fireprotectiondevice/delete-device/' + id,
        type: 'POST',
        dataType: 'json',
        async: true,

        success: function (data) {
            var id = data['id'];
            var loop = data['loop'];
            $('#row-' + loop + '-' + id).remove();
        },
        error: function () {
            alert('Błąd delete device');
        }
    });
}

//END: AJAX set device as deleted-->

//BEGIN: Sweet alert delete device-->


$(document).on('click', '.cancel-delete', function () {
    var id = this.id.substr(7);
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

//BEGIN: AJAX set loop as deleted-->


function deleteLoopDev(id) {

    $.ajax({
        url: '../loopdev/delete-loopdev/' + id,
        type: 'POST',
        dataType: 'json',
        async: true,

        success: function (data) {
            if (data['success'] === true) {
                swal("Usunięte!", "Tej pętli już nie zobaczysz w budynku.", "success");
                $('#section-' + id).remove();

            } else {
                var errorlast = (data['error-last']) ? " Nie jest to ostatnia pętla w budynku." : "";
                var errorempty = (data['error-empty']) ? "  Pętla posiada urządzenia." : "";
                swal("Błąd", "Niestety nie udało się usunąć pętli. " + errorlast + errorempty, "error");
            }
        },
        error: function () {
            swal("Błąd", "Twoje pętla pozostała", "error");
        }

    });


}


//END: AJAX set device as deleted-->

//BEGIN: Sweet alert delete loop-->


$(document).on('click', '.delete-loop-button', function () {
    var id = this.id.substr(11);
    swal({
        title: "Jesteś pewny?",
        text: "Chcesz usunąć tą pętlę z budynku?",
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
                text: "Tak, usuń tą pętlę!",
                value: true,
                visible: true,
                className: "",
                closeModal: false,
            }
        }
    })
        .then((isConfirm) => {

            if (isConfirm) {
                deleteLoopDev(id);

            } else {
                swal("Anulowano", "Twoje pętla pozostała", "error");
            }
        })

});

//sweet alert delete stamp

function runDeleteAction(href) {
    window.location.href = href;
}

$(document).on('click', '.delete-document-btn', function () {
    var href = $(this).data("href");
    swal({
        title: "Jesteś pewny?",
        text: "Chcesz usunąć ten dokument z systemu",
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
                text: "Tak, usuń ten dokument!",
                value: true,
                visible: true,
                className: "",
                closeModal: false,
            }
        }
    })
        .then((isConfirm) => {
            if(isConfirm) {
                runDeleteAction(href);
                swal("Usunięte!", "Dokument został usunięty.", "success");
            } else {
                swal("Anulowano", "Dokument pozostał w systemie", "error"
                )
                ;
            }
        })

});