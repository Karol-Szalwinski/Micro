/****************************************************
 *                Intiate document                 *
 ****************************************************/
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
jQuery(document).ready(function () {
    updateAllValues();
});

function updateTotalValue(totalProducts) {
    var newValueCurrency = totalProducts.toLocaleString('pl-PL', {
        style: 'currency',
        currency: 'PLN',
    });
    $('#total-value').html(newValueCurrency);

}

function updateAllValues() {
    var totalProductArr = refreshTotalValues();
    var totalPurchase = totalProductArr[0];
    var totalProducts = totalProductArr[1];
    // updateFooter(totalPurchase, totalProducts, totalServices);
    updateTotalValue(totalProducts);
}

//refresh total column and footer after blur input
$(document).on('change', 'input ', function () {
    updateAllValues();
});


/****************************************************
 *                Prepare Inputs                    *
 ****************************************************/


//autosize textarea
$("textarea").keyup(function (e) {
    while ($(this).outerHeight() < this.scrollHeight + parseFloat($(this).css("borderTopWidth"))
    + parseFloat($(this).css("borderBottomWidth"))) {
        $(this).height($(this).height() + 1);
    }
    ;
});

$(".touchspin-info").TouchSpin({
    buttondown_class: "btn btn-outline-info",
    buttonup_class: "btn btn-outline-info",
    buttondown_txt: '<i class="ft-minus"></i>',
    buttonup_txt: '<i class="ft-plus"></i>'
});
/****************************************************
 *    Products Collection forms dynamically add     *
 ****************************************************/
var $collectionHolder;


// setup an "add a tag" link
// var $addProductButton = $('<button type="button" class="add_tag_link">Dodaj nowy</button>');
var $newLinkTr = $('<tr></tr>');

var $addProductsButton = $('.add-product-btn');

jQuery(document).ready(function () {
    // Get the ul that holds the collection of tags
    $collectionHolder = $('tbody.positions');

    // add a delete link to all of the existing tag form li elements
    $collectionHolder.find('tr:not(:last)').each(function () {
        addTagFormDeleteLink($(this));
    });

    // add the "add a tag" anchor and li to the tags ul
    // $collectionHolder.append($newLinkTr);

    // count the current form inputs we have (e.g. 2), use that as the new
    // index when inserting a new item (e.g. 2)
    $collectionHolder.data('index', $collectionHolder.find('tr').length);

    // $addProductButton.on('click', function(e) {
    //     // add a new tag form (see next code block)
    //     addTagForm($collectionHolder, $newLinkTr);
    // });
    $addProductsButton.on('click', function (e) {
        priceDec = $(this).data("price") / 100;
        var purchasePrice = priceDec;
        var price = priceDec + priceDec * 15 / 100;

        var product =
            {
                id: $(this).data("id"),
                name: $(this).data("name"),
                purchasePrice: purchasePrice,
                price: price,
            }
        console.log(product);
        // add a new tag form (see next code block)
        addTagForm($collectionHolder, $newLinkTr, product);
        updateAllValues();
    });
});

function addTagForm($collectionHolder, $newLinkTr, product) {
    // Get the data-prototype explained earlier
//            var prototype = $collectionHolder.data('prototype');

    var prototype =
        "<td><input type='text' id='microbundle_offert_offPositions___name___name' name='microbundle_offert[offPositions][__name__][name]'" +
        " required='required' maxlength='255' class='form-control' placeholder='Wprowadź nazwę' value='" + product.name + "'></td>\n" +
        "<td>\n" +
        "    <div class='touchspin-input-size-1 mx-auto '><div class='input-group bootstrap-touchspin bootstrap-touchspin-injected'>" +
        "<input type='text' id='microbundle_offert_offPositions___name___amount' name='microbundle_offert[offPositions][__name__][amount]'" +
        " required='required' class='touchspin-info form-control' data-bts-min='1' data-bts-max='100000' value='1'>" +
        "</div>\n" +
        "    </div>\n" +
        "</td>\n" +
        "<td>\n" +
        "<div class='purchasePrice'>" +
        "    <input type='text' id='microbundle_offert_offPositions___name___purchasePrice'" +
        "           name='microbundle_offert[offPositions][__name__][purchasePrice]' required='required' readonly='readonly'" +
        "           class='form-control hide-input ' value='" + product.purchasePrice + "'>" +
        "</div>" +
        "</td>\n" +
        "<td>\n" +
        "    <div class='touchspin-input-size-2 mx-auto input-group'><input type='text' id='microbundle_offert_offPositions___name___price' " +
        "name='microbundle_offert[offPositions][__name__][price]' required='required'" +
        " class=' form-control' data-bts-button-down-class='btn btn-outline-info'" +
        " data-bts-button-up-class='btn btn-outline-info' data-bts-min='1'" +
        " data-bts-max='100000' data-bts-decimal='2' data-bts-step='1' data-bts-postfix='<b class=&quot;info&quot;>&amp;#122;&amp;#322;</b>' " +
        " value=' " + product.price + "'>" +
        "        <div class='input-group-append info'>\n" +
        "            <span class='input-group-text'><b class='info'>zł</b></span>\n" +
        "        </div>\n" +
        "    </div>\n" +
        "</td>\n" +
        "<td class='summary-row'>\n" +
        "    <div>" + product.price + "</div>\n" +
        "</td>\n" +
        "<td></td>\n" +
        "<input type='hidden' id='microbundle_offert_offPositions___name___productId'" +
        " name='microbundle_offert[offPositions][__name__][productId]' " +
        " value='" + product.id + "'>"
    ;

    // get the new index
    var index = $collectionHolder.data('index');

    var newForm = prototype;
    // You need this only if you didn't set 'label' => false in your tags field in TaskType
    // Replace '__name__label__' in the prototype's HTML to
    // instead be a number based on how many items we have
    // newForm = newForm.replace(/__name__label__/g, index);

    // Replace '__name__' in the prototype's HTML to
    // instead be a number based on how many items we have
    newForm = newForm.replace(/__name__/g, index);

    // increase the index with one for the next item
    $collectionHolder.data('index', index + 1);

    // Display the form in the page in an li, before the "Add a tag" link li
    var $newFormTr = $('<tr></tr>').append(newForm);
    $collectionHolder.append($newFormTr);


    addTagFormDeleteLink($newFormTr);
    $(".touchspin-info").TouchSpin({
        buttondown_class: "btn btn-outline-info",
        buttonup_class: "btn btn-outline-info",
        buttondown_txt: '<i class="ft-minus"></i>',
        buttonup_txt: '<i class="ft-plus"></i>'
    });
}

function addTagFormDeleteLink($tagFormTr) {
    var $removeFormButton = $('<a class="text-danger"><i class="la la-close"></i></a>');
    $tagFormTr.find('td:last').html($removeFormButton);

    $removeFormButton.on('click', function (e) {
        // remove the li for the tag form
        $tagFormTr.remove();
        updateAllValues();
    });
}


/****************************************************
 *        Refreshing values in product table        *
 ****************************************************/

function refreshTotalValues() {
    var summaryValue = 0.00;
    var summaryPurchaseValue = 0.00;
    $('tbody.positions').find('tr').each(function () {
        var tr = $(this);
        var amount = parseInt(tr.find("input[id$='amount']").val());


        if (!isNaN(amount)) {
            var purchasePriceInput= tr.find(".purchasePrice input");
            var purchasePrice = parseFloat(purchasePriceInput.val().replace(",", "."));
            summaryPurchaseValue += purchasePrice * amount;

            var price = parseFloat(tr.find('input[id$="price"]').val().replace(",", "."));
            var newValue = amount * price;
            var newValueCurrency = newValue.toLocaleString('pl-PL', {
                style: 'currency',
                currency: 'PLN',
            });
            summaryValue += newValue;


            //update column
            tr.find('.summary-row').html(newValueCurrency);
        }


    });
    var percentProfit = (summaryValue - summaryPurchaseValue) / summaryPurchaseValue * 100;

    return [summaryPurchaseValue, summaryValue];
}

function roundTo2Decimal(float) {
    return Math.round(float * 100) / 100;
}

function refreshInputValuesAfterChangeSlider(profit) {


    $('tbody.positions').find('tr').each(function () {
        var tr = $(this);
        var purchasePriceInput= tr.find(".purchasePrice input");
        var purchasePrice = parseFloat(purchasePriceInput.val().replace(",", "."));
        var priceInput = tr.find('input[id$="price"]');

        var newValueFloat = purchasePrice + purchasePrice * profit / 100;
        var newValueCurrency = newValueFloat.toLocaleString('pl-PL', {
            style: 'currency',
            currency: 'PLN',
        });
        var newValue = newValueCurrency.substr(0, newValueCurrency.toString().length - 3);
        //update input
        priceInput.val(newValue);


    });

}








/****************************************************
 *        Refreshing footer values                  *
 ****************************************************/

function updateFooter(totalPurchase, totalProducts, totalServices) {
    var $totalPurchaseSpan = $('#total-purchase');
    var $totalSellSpan = $('#total-sell');
    var $totalProfitSpan = $('#total-profit');

    $totalPurchaseSpan.html(totalPurchase.toFixed(2));
    $totalSellSpan.html((totalProducts + totalServices).toFixed(2));
    $totalProfitSpan.html(roundTo2Decimal((totalProducts + totalServices - totalPurchase).toFixed(2)));


}