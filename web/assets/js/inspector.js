//sweet alert delete stamp

function runDeleteAction(href) {
    window.location.href = href;
}

$(document).on('click', '.delete-inspector-btn', function () {
    var href = $(this).data("href");
    swal({
        title: "Jesteś pewny?",
        text: "Chcesz usunąć tego inspektora z systemu",
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
                text: "Tak, usuń tego inspektora!",
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
                swal("Usunięte!", "Inspektor został usunięty, ale jego dokumenty pozostaną.", "success");
            } else {
                swal("Anulowano", "Inspektor pozostał w systemie", "error"
                )
                ;
            }
        })

});