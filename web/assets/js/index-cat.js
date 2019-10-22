/****************************************************
 *                Intiate document                 *
 ****************************************************/
jQuery(document).ready(function () {
    updateProductsQuantityInputs();
    updateTotalValuesCart()
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

function getRootUrl() {
    return window.location.origin;
}

/****************************************************
 *                Info modal               *
 ****************************************************/

$(".product-modal-trigger").on("click", function () {
    var id = $(this).data('id');
    loadProduct(id);
    $("#product-modal").modal();
});

function loadProduct(id) {
    $.ajax({
        url: getRootUrl() + '/product/get/' + id,
        type: 'POST',
        dataType: 'json',
        async: true,

        success: function (data) {
            var productView = data['productView'];
            $('#product-modal-body').html(productView);
        },
        error: function () {
        }
    });
}

/****************************************************
 *                Cart               *
 ****************************************************/

function updateProductsQuantityInputs() {
    var $listProductPositions = $("#product-table-body").find('tr');
    var $cartPositions = $("#cart-table-body").find('tr');
    $listProductPositions.each(function () {
        var idProduct = $(this).data('product-id');
        var newVal = "";


        $cartPositions.each(function () {
            var idCart = $(this).data('product-id');
            var amountCart = $(this).find('.cart-pos-amount').html();

            if (idCart === idProduct) {
                newVal = amountCart;
            }
        });
        $(this).find('input.quantity-input').val(newVal);

    });
}

function updateTotalValuesCart() {
    var $cartPositions = $("#cart-table-body").find('tr');
    var total = 0;
    $cartPositions.each(function () {
        var amountPositionString = $(this).find('.cart-pos-amount').html();
        var pricePositionString = $(this).find('.cart-pos-price').html();

        var amountInt = parseInt(amountPositionString);
        var priceFloat = parseFloat(pricePositionString.substr(0,pricePositionString.length - 3).replace(",", "."));
        var priceInt = parseInt(priceFloat * 100);
        total += amountInt * priceInt;
    })
    totalToUpdate = (total/100).toLocaleString('pl-PL', {
        style: 'currency',
        currency: 'PLN',
    });
$('#total-cart').html(totalToUpdate);
}

function updateCart(id, quantity) {
    $.ajax({
        url: getRootUrl() + '/product/update-cart/' + id + '/' + quantity,
        type: 'POST',
        dataType: 'json',
        async: true,

        success: function (data) {
            var cartPosition = JSON.parse(data['cartPosition']);
            var type = data['type'];
            var $row = $('#cart-table-body').find('tr[data-product-id="' + id + '"]');
            if (type == 'delete') {
                $row.remove();
            }
            else if (type == 'change') {
                $row.find('.cart-pos-amount').html(quantity);
            } else if (type == 'new') {
                var price = (cartPosition.price /100).toLocaleString('pl-PL', {
                    style: 'currency',
                    currency: 'PLN',
                });
                var newRow = "<tr data-product-id='" + cartPosition.productId + "' data-amount='" + cartPosition.amount + "'>" +
                    "<td>" + cartPosition.name + "<br>" +
                    "    <span class='red'><span class='cart-pos-amount'>" + cartPosition.amount + "</span><span> X </span>" +
                    "<span class='cart-pos-price'>" +
                    price + "</span></span>" +
                    "</td><td><a class='text-danger delete-cart-position'><i class='la la-close'></i></a></td></tr>";

                $('#cart-table-body').append(newRow);
            }
            updateProductsQuantityInputs();
            updateTotalValuesCart();
        },
        error: function (xhr, ajaxOptions, thrownError) {
            alert(xhr.thrownError);
        },
    });
}

$(document).on('click', '.quantity-input-btn', function () {
    var $tr = $(this).closest('tr');
    var id = $tr.data('product-id');
    var quantity = $tr.find('input').val();
    updateCart(id, quantity);

});
$(document).on('click', '#quantity-submit-modal-btn', function () {
    var id = $(this).data('product-id');
    var quantity = $('#quantity-input-modal').val();
    updateCart(id, quantity);

});

$(document).on('click', '.delete-cart-position', function () {
    var $tr = $(this).closest('tr');
    var id = $tr.data('product-id');
    updateCart(id, 0);
});

