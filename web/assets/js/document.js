//updata row in Loop tables after change switch (Status and Test columns)

// $('.status-checkbox').change(function () {
function changeCheckbox(id, type){
    $.ajax({
        url: '/inspdev/' + id + '/changestatus/' + type,
        type: 'POST',
        dataType: 'json',
        async: true,

        success: function (data, status) {


        },
        error: function (xhr, textStatus, errorThrown) {
            alert('Błąd requestu ajax.');
        }
    });
};

//sweet alert change checkbox

$(document).on('change', '.change-checkbox', function () {
    var id = this.id.substring(1);
    var type = this.id.substring(0, 1)
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
                changeCheckbox(id, type);
                swal("Zmieniono!", "Zmiany zostały zapisane.", "success");
            } else {
                this.click();
                swal("Anulowano", "Przywrócono poprzedni stan", "error"
                )
                ;
            }
        })

});




//show hiden input in text cells in Loop tables ( Comment column)

$(document).ready(function () {
    $(".hidden-input").click(function () {

        var comment = $(this).find('p').text();

        $(this).find("input").attr('type', 'text').val(comment).focus();
        $(this).find('p').text('');
    });
});

//updata row in Loop tables after blur in input ( Comment column)
$(document).ready(function () {
    $(".hidden-input").find("input").blur(function () {
        $(this).attr('type', 'hidden');
        var comm = ($(this).val().toString() == "") ? null : $(this).val();

        $.ajax({
            url: '/inspdev/' + this.id.substring(1) + '/changecomment/' + comm,
            type: 'POST',
            dataType: 'json',
            async: true,

            success: function (data) {
                var id = data['id'];
                $("#p" + id).text(data['comm']);

            },
            error: function () {
                $(this).parent().find('p').text('Wprowadź ponownie');
            }
        });
    });
});


//Funcion to delete row in Loop tables
function changeInspectedDeviceVisible(id) {
    $.ajax({
        url: '/inspdev/' + id + '/changevisible',
        type: 'POST',
        dataType: 'json',
        async: true,

        success: function (data) {


            $('#row-' + id).toggle();
            $('#mod-' + id).toggle();
            ;


        },
        error: function () {
            alert('Błąd usuwania - spróbuj przeładować stronę');
        }
    });
};

$(document).on('click', '.device-restore', function () {
    var id = this.id.substring(4);
    changeInspectedDeviceVisible(id);
});


//Show hidden inputs in Test position table ( Comment column)

$(document).on('click', '.test-hidden-input', function () {

    var comment = $(this).find('p').text();

    $(this).find("input").attr('type', 'text').val(comment).focus();
    $(this).find('p').text('');
});


//Update Inputs after blur in Test position table ( Comment column)

$(document).on('blur', '.test-hidden-input input', function () {
//            $(".test-hidden-input").find("input").blur(function () {
    $(this).attr('type', 'hidden');
    var value = $(this).val().toString();
    updateAjax(value, this.id);
});


// update row in Test position table changing by switch ( Result column)
$(document).on('change', '.test-checkbox-switch', function () {
//            $('.test-checkbox-switch').change(function () {
    updateAjax(null, this.id);
});

// Function to update test position by Ajax ( Result column)

function updateAjax(value, id) {

    value = (value === "") ? null : value;

    $.ajax({
        url: '/testposition/' + id + '/update/' + value,
        type: 'POST',
        dataType: 'json',
        async: true,

        success: function (data) {
            var id = data['id'];
            var type = data['type'];
            var text = data['text'];

            switch (type) {
                case ('namei-'):
                    $("#namep-" + id).text(text);

                    break;

                case ('commi-'):

                    $("#commp-" + id).text(text);

            }
        },
        error: function () {
            alert('Niestety wystąpił błąd połączenia. Spróbuj przeładować stronę');
        }
    });
}

// add new row in Test position table
$(document).ready(function () {
    $('.add-test-position').click(function () {
        var id = this.id.substring(18);

        $.ajax({
            url: '/testposition/add/' + id,
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