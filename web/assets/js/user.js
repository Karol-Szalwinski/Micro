// activate datatable
$('#table-users').DataTable({
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
//sweet alert delete stamp

function runDeleteAction(href) {
    alert("Odpala deleta");
    window.location.href = href;
}

$(document).on('click', '.delete-user-btn', function () {
    var href = $(this).data("href");
    alert(href);
    swal({
        title: "Jesteś pewny?",
        text: "Chcesz usunąć tego użytkownika z systemu?",
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
                text: "Tak, usuń tego użytkownika!",
                value: true,
                visible: true,
                className: "",
                closeModal: false,
            }
        }
    })
        .then((isConfirm) => {
            if (isConfirm) {
                runDeleteAction(href);
                swal("Usunięte!", "Użytkownik została oznaczony jako nieaktywny", "success");
            } else {
                swal("Anulowano", "Użytkonik pozostał w systemie", "error");
                alert("Anuluje deleta");
            }
        })

});
