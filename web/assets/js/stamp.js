//sweet alert delete stamp

function runDeleteAction(href) {
    window.location.href = href;
}

$(document).on('click', '.delete-stamp-btn', function () {
    var href = $(this).data("href");
    swal({
        title: "Jesteś pewny?",
        text: "Chcesz usunąć tą pieczątkę z systemu",
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
                text: "Tak, usuń tą pieczątkę!",
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
                swal("Usunięte!", "Pieczątka została usunięta", "success");
            } else {
                swal("Anulowano", "Pieczątka pozostała w systemie", "error"
                )
                ;
            }
        })

});