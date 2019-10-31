//sweet alert delete stamp

function runDeleteAction(href) {
    window.location.href = href;
}

$(document).on('click', '.delete-client-btn', function () {
    var href = $(this).data("href");
    swal({
        title: "Jesteś pewny?",
        text: "Chcesz usunąć tego klienta i jego wszystkie budynki i oferty z systemu",
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
                text: "Tak, usuń tego klienta!",
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
                swal("Usunięte!", "Klient i jego budynki oraz oferty zostały usunięte.", "success");
            } else {
                swal("Anulowano", "Klient pozostał w systemie", "error"
                )
                ;
            }
        })

});

$('#table-clients').DataTable({
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