// activate input in device row
$(document).on('click', '.edit-row-btn ', function () {
    //clear another open inpu
    $(".hidden-input ").attr('readonly', true).css("border", "solid 2px #ffffff");

    // add to tr status active

    $(this).closest('tr').find('.hidden-input ').removeAttr("readonly");
    $(this).closest('tr').find('.hidden-input ').css("border", "solid 2px #00bfff");
    $(this).closest('tr').find('.hidden-input ').addClass('active');

     window.getSelection().removeAllRanges();
});

// deactivate input in info modal except another info in this row
$(document).on('blur', '.active', function (e) {
    var id = (this).closest('tr').dataset.id;
    setTimeout(function () {
        console.log(document.activeElement);
        if (!$(document.activeElement).hasClass('active')) {
            $(".hidden-input ").attr('readonly', true).css("border", "solid 2px #ffffff");
            $(".hidden-op").removeClass('active');
            updateDocPosition(id);
        }

    }, 200)

});

//sweet alert change checkbox

$(document).on('change', '.change-checkbox', function () {
    var id = (this).closest('tr').dataset.id;
    swal({
        title: "Jesteś pewny?",
        text: "Każda zmiana musi zostać zatwierdzona!",
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
                text: "Tak, zmień!",
                value: true,
                visible: true,
                className: "",
                closeModal: false,
            }
        }
    })
        .then((isConfirm) => {
            if(isConfirm) {
                updateDocPosition(id);
                swal("Zmieniono!", "Zmiany zostały zapisane.", "success");
            } else {
                this.click();
                swal("Anulowano", "Przywrócono poprzedni stan", "error"
                )
                ;
            }
        })

});

// update docPosition
function updateDocPosition(id) {

    var docPosition = {
        id: id,
        name: $('#i-name-' + id).val(),
        comment: $('#i-comment-' + id).val(),
        test: $('#i-test-' + id).prop('checked'),
    };
    var jsonString = JSON.stringify(docPosition);

    $.ajax({
        url: '../../../doc-position/update/' + jsonString,
        type: 'POST',
        dataType: 'json',
        async: true,

        success: function (data) {

            // update row
            var docPosition = JSON.parse(data['doc_position']);
            $('#i-name-' + id).val(docPosition.name);
            $('#i-comment-' + id).val(docPosition.comment);
            $('#i-test-' + id).prop("checked", docPosition.test);

        },
        error: function () {
            alert('Błąd update docPosition');
        }
    });
};

// todo add new row in Test position table
$(document).ready(function () {
    $('#add-doc-position').click(function () {
        var id = $(this).data("id");

        $.ajax({
            url: '/doc-position/add/' + id,
            type: 'POST',
            dataType: 'json',
            async: true,

            success: function (data) {
                var id = data['id'];

                var row = "<tr id='terow-" + id + "'><td class='text-center test-hidden-input'>" +
                    "<p id='namep-" + id + "'></p>" +
                    "<input id='namei-" + id + "' type='hidden' value=''></td>" +
                    "<td class='text-center'>" +
                    "<input type='checkbox' class='column_filter test-checkbox-switch switch' checked " +
                    "id='testc-" + id + "' " +
                    "data-icon-cls='fa' data-off-icon-cls='fa ft-thumbs-down'" +
                    "data-on-icon-cls='fa ft-thumbs-up'" +
                    "data-off-label='NEG'" +
                    "data-on-label='POZ'" +
                    "data-group-cls='btn-group-sm'></td>" +

                    "<td class='text-center test-hidden-input'>" +
                    "<p id='commp-" + id + "'></p> " +
                    "<input id='commi-" + id + "' type='hidden' value=''></td>" +
                    "<td><a id='tedel-" + id + "'" +
                    "class='test-delete-row btn btn-sm btn-danger mr-1'>Usuń<i" +
                    "class='la la-add'></i></a></td></tr>";
                $("#tbody-tests").append(row);

                $('#testc-' + id).checkboxpicker();

            }

            ,
            error: function () {
                alert('Błąd dodawania pozycji');
            }
        })
        ;
    });
});

// deleting row in Test position table
$(document).on('click', '.test-delete-row ', function () {
    $.ajax({
        url: '/testposition/delete/' + this.id,
        type: 'POST',
        dataType: 'json',
        async: true,

        success: function (data) {
            var id = data['id'];
            $('#terow-' + id).remove();
        }
    });
});



// todo ----old-----------------------
//activate DataTable
$('.inspected-devices-table').DataTable({
    dom: 'Bfrtip',
    stateSave: true,
    buttons: ['colvis',
        {
            text: 'Dodaj urządzenie do pętli',
            className: 'btn-info',
            action: function (e, dt, node, config) {
                $('#add-device-modal').modal('show');
            }
        },
    ],
    language: {
        buttons: {
            colvis: 'Wybierz kolumnę'
        },
        "lengthMenu": "Pokaż _MENU_ wierszy na stronie",
        "zeroRecords": "Niestety brak wyników",
        "info": "Stron _PAGE_ z _PAGES_",
        "infoEmpty": "Brak wierszy",
        "search": "Szukaj w każdej kolumnie:",
        "infoFiltered": "(znaleziono z _MAX_ wszystkich wierszy)"
    }
    ,
    "paging": false,
});

// $( ".animation-dialog-btn" ).on("click",function(){
//     $( ".animation-dialog" ).dialog("open");
// });

// fill info modal
$(".info-modal-btn").on("click", function () {
    var id = this.id.substring(5);

    $.ajax({
        url: '../fireprotectiondevice/get-device/' + id,
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
            $('#dialog-number').text(device.number);
            $('#dialog-temp-date').text(device.tempServiceDate);


            $('#info-modal').modal('show');

        },
        error: function () {
            alert('Błąd przesyłania');
        }
    })


    $("#animation-dialog").dialog("open");
});

// animation dialog
$("#animation-dialog").dialog({
    autoOpen: false,
    width: 400,
    show: {
        effect: "fade",
        duration: 400
    },
    hide: {
        effect: "explode",
        duration: 1000
    },
    modal: true
});

// activate input in info modal
$(document).on('click', '.edit-info-btn ', function () {

    $(this).parent().prev().children().removeAttr("readonly");
    $(this).parent().prev().children().focus();
    $(this).parent().prev().children().css("border", "solid").css("border-color", "DeepSkyBlue");
    window.getSelection().removeAllRanges();
});

// deactivate input in info modal
$(document).on('blur', '.td-hidden-input ', function () {

    $(".td-hidden-input ").attr('readonly', true).css("border", "none");
    $(".td-hidden-input:focus").css("outline", "none");

});

// update fire protection device
$(document).on('change', '.td-hidden-input ', function () {

    var id = $('#dialog-id').text();
    var loop = null;
    var number = null;
    var name = $('#dialog-name').text();
    var serial = $('#dialog-serial').val();
    var address = $('#dialog-address').val();
    var desc = $('#dialog-description').val();


    id = (id !== "") ? id : null;
    loop = (loop !== "") ? loop : null;
    name = (name !== "") ? name : null;
    serial = (serial !== "") ? serial : null;
    address = (address !== "") ? address : null;
    desc = (desc !== "") ? encodeURIComponent(desc) : null;
    console.log(desc);

    $.ajax({
        url: '../fireprotectiondevice/update-device/' +
        id + '/' + loop + '/' + number + '/' + name + '/' + serial + '/' + address + '/' + desc,
        type: 'POST',
        dataType: 'json',
        async: true,

        success: function (data) {
            // update row
            var device = JSON.parse(data['device']);
            $('#dialog-name').text(device.name);
            $('#dialog-id').text(device.id);
            $('#dialog-serial').val(device.serial);
            $('#dialog-address').val(device.address);
            $('#dialog-description').val(device.desc);
            $('#dialog-number').text(device.number);


        },
        error: function () {
            alert('Błąd update device');
        }
    });
});

// update inspected Device
$(document).on('click', '.update-ins-device ', function () {
    var ins_device = this.id.substring(7);

    $.ajax({
        url: '../inspdev/' + ins_device + '/update',
        type: 'POST',
        dataType: 'json',
        async: true,

        success: function (data) {
            // update row
            var row_id = data['row_id'];
            var shortname = data['shortname'];
            $('#shortname-' + row_id).text(shortname);
            $('#update-' + row_id).removeClass('update-ins-device').toggleClass('warning muted');
            $('#update-' + row_id).prev('a').toggleClass('warning muted');

        },
        error: function () {
            alert('Błąd update inspected device');
        }
    });
});

$(function () {
    var loopNumber = $('#session-loop-number').val();
    if (loopNumber > 0) {
        $('html, body').animate({
            scrollTop: $('#loop-number-' + loopNumber).offset().top
        }, 1000);
    }
});

//sweet alert delete device from loop

$(document).on('click', '.loop-delete-row', function () {
    var id = this.id.substr(4);
    swal({
        title: "Jesteś pewny?",
        text: "To urządzenie nie będzie objęte przeglądem!",
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
        if(isConfirm) {
            changeInspectedDeviceVisible(id);
            swal("Usunięte!", "Możesz przywrócić to urządzenie.", "success");
        } else {
            swal("Anulowano", "Twoje urządzenie pozostało", "error"
)
    ;
}
})

});