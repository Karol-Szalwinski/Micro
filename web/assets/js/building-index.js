//sweet alert delete building

function runDeleteAction(href) {
    window.location.href = href;
}

$(document).on('click', '.delete-building-btn', function () {
    var href = $(this).data("href");
    swal({
        title: "Jesteś pewny?",
        text: "Chcesz usunąć ten budynek i jego wszystkie dokumenty z systemu",
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
                text: "Tak, usuń ten budynek!",
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
                swal("Usunięte!", "Budynek i jego dokumenty został usunięty.", "success");
            } else {
                swal("Anulowano", "Budynek pozostał w systemie", "error"
                )
                ;
            }
        })

});

$('#table-buildings').DataTable({
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