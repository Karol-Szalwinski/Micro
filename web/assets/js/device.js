//sweet alert delete stamp

function runDeleteAction(href) {
    window.location.href = href;
}

$(document).on('click', '.delete-device-btn', function () {
    var href = $(this).data("href");
    swal({
        title: "Jesteś pewny?",
        text: "Chcesz usunąć to urządzenie z systemu",
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
                runDeleteAction(href);
                swal("Usunięte!", "Urządzenie zostało usunięty, ale pozostanie w dokumentach.", "success");
            } else {
                swal("Anulowano", "Urządzenie pozostało w systemie", "error"
                )
                ;
            }
        })

});