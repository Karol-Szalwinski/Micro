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

$('#table-documents').DataTable({
    "language": {
        "lengthMenu": "Pokaż _MENU_ wierszy na stronie",
        "zeroRecords": "Niestety brak wyników",
        "info": "Stron _PAGE_ z _PAGES_",
        "infoEmpty": "Brak wierszy",
        "infoFiltered": "(znaleziono z _MAX_ wszystkich wierszy)",
        "search": "Szukaj"
    },
    "paging": false,
});