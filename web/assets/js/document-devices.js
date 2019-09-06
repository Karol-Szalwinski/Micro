// change tables in DatataTable
$('#doc-devices-table').DataTable({
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
        "footer": true,
    }
});


// activate input in device row
$(document).on('click', '.edit-row-btn ', function () {
    //clear another open inpu
    $(".hidden-input ").attr('readonly', true).css("border", "2px solid #ffffff");
    $(".hidden-select ").attr('disabled', true).css("border", "2px solid #ffffff");

    // add to tr status active

    $(this).closest('tr').find('.hidden-input ').removeAttr("readonly");
    $(this).closest('tr').find('.hidden-input ').css("border", "2px solid #00bfff");
    $(this).closest('tr').find('.hidden-input ').addClass('active');

    $(this).closest('tr').find('select').removeAttr("disabled");
    $(this).closest('tr').find('select').css("border", "2px solid #00bfff");
    $(this).closest('tr').find('select').addClass('active');

    window.getSelection().removeAllRanges();
});

// deactivate input in info modal except another info in this row
$(document).on('blur', '.active', function (e) {
    var id = this.closest('tr').id.substring(4);
    setTimeout(function () {
        console.log(document.activeElement);
        if (!$(document.activeElement).hasClass('active')) {
            $(".hidden-input ").attr('readonly', true).css("border", "2px solid #ffffff");
            $(".hidden-select ").attr('disabled', true).css("border", "2px solid #ffffff");
            $(".hidden-op").removeClass('active');
            updateDocDevice(id);
        }

    }, 200)

});

//sweet alert change checkbox

$(document).on('change', '.change-checkbox', function () {
    var id = this.closest('tr').id.substring(4);
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
                updateDocDevice(id);
                swal("Zmieniono!", "Zmiany zostały zapisane.", "success");
            } else {
                this.click();
                swal("Anulowano", "Przywrócono poprzedni stan", "error"
                )
                ;
            }
        })

});


///////////////////////////////////////new///////////////////////////////




// update docDevice
function updateDocDevice(id) {

    var docDevice = {
        id: id,
        shortname: $('#i-shortname-' + id).val(),
        number: $('#i-number-' + id).val(),
        comment: $('#i-comment-' + id).val(),
        status: $('#i-status-' + id).prop('checked'),
        test: $('#i-test-' + id).prop('checked'),
    };
    var jsonString = JSON.stringify(docDevice);

    $.ajax({
        url: '../../../doc-device/update/' + encodeURIComponent(jsonString),
        type: 'POST',
        dataType: 'json',
        async: true,

        success: function (data) {

            // update row
            var docDevice = JSON.parse(data['doc_device']);
            $('#i-shortname-' + id).val(docDevice.shortname);
            $('#i-number-' + id).val(docDevice.number);
            $('#i-comment-' + id).val(docDevice.comment);
            $('#i-status-' + id).prop("checked", docDevice.status);
            $('#i-test-' + id).prop("checked", docDevice.test);

        },
        error: function () {
            alert('Błąd update docDevice');
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
                $(this).text(row++)
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

// fill info modal
$(".info-modal-btn").on("click", function () {
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
            $('#dialog-temp-date').text(device.tempServiceDate);


            $('#info-modal').modal('show');

        },
        error: function () {
            alert('Błąd przesyłania');
        }
    })

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


//sweet alert delete device from loop

$(document).on('click', '.delete-row-btn', function () {
    var id = this.id.substr(15);
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

// update docDevice
function changeInspectedDeviceVisible(id) {

    var docDevice = {
        id: id,
        visible: 'toggle',
    };

    console.log(id);
    var jsonString = JSON.stringify(docDevice);

    $.ajax({
        url: '../../../doc-device/update/' + encodeURIComponent(jsonString),
        type: 'POST',
        dataType: 'json',
        async: true,

        success: function (data) {

            // update row
            var docDevice = JSON.parse(data['doc_device']);
            $('#row-' + id).toggle();
            $('#mod-' + id).toggle();
            repairRowOrder();

        },
        error: function () {
            alert('Błąd update docDevice');
        }
    });
};

function repairRowOrder() {
    var row = 1;
    $('.order').each(function () {
        if($(this).is(':visible')) {
            $(this).text(row++);
        }
    })
}

$(document).on('click', '.device-restore', function () {
    var id = this.id.substring(4);
    changeInspectedDeviceVisible(id);
});