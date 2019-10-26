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
$('#table-products').DataTable({
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
    setNewClientLabelAndButton();
});

function updateTotalValue(totalProducts, totalServices) {
    $totalValueInput = $('#microbundle_offert_totalValue');

    $totalValueInput.val((totalProducts + totalServices).toFixed(2) * 100);

}

function updateAllValues() {
    var totalProductArr = refreshTotalValues();
    var totalPurchase = totalProductArr[0];
    var totalProducts = totalProductArr[1];
    var totalServices = refreshServicesTotalValues();
    updateFooter(totalPurchase, totalProducts, totalServices);
    updateTotalValue(totalProducts, totalServices);
}

//refresh total column and footer after blur input
$(document).on('change', 'input ', function () {
    updateAllValues();
});


/****************************************************
 *                Prepare Inputs                    *
 ****************************************************/
//change type of input from text to date for run datepicker
$("#microbundle_offert_addDate").get(0).type = 'date';
$("#microbundle_offert_expireDate").get(0).type = 'date';


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
    $collectionHolder.find('tr').each(function () {
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
        var purchasePrice = $(this).data("price") / 100;
        var priceCount = purchasePrice + purchasePrice * 15 / 100;
        var price = priceCount.toLocaleString('pl-PL', {
            style: 'currency',
            currency: 'PLN',
        });
        var imageData = $(this).data("image");
        var image = (typeof imageData !== 'undefined') ? imageData : "";

        var product =
            {
                id: $(this).data("id"),
                name: $(this).data("name"),
                image: image,
                thumb: makeThumbFromImage(image),
                purchasePrice: purchasePrice,
                price: price,
            }
        console.log(product);
        // add a new tag form (see next code block)
        addTagForm($collectionHolder, $newLinkTr, product);
        updateAllValues();
    });
});

function makeThumbFromImage(image) {

    return image.replace(".", "thumb70.");
}

function addTagForm($collectionHolder, $newLinkTr, product) {
    // Get the data-prototype explained earlier
//            var prototype = $collectionHolder.data('prototype');

    var prototype =
        "<td><input type='hidden' id='microbundle_offert_offPositions___name___image'" +
        "           name='microbundle_offert[offPositions][__name__][image]' value='" + product.image + "'>" +
        "    <a href='/uploads/images/" + product.image + "'>" +
        "        <img src='/uploads/images/thumb70/" + product.thumb + "' alt='Desktop' height='70'>" +
        "    </a></td>" +
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
            var purchasePriceInput = tr.find(".purchasePrice input");
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
    setSlider(roundTo2Decimal(percentProfit));

    return [summaryPurchaseValue, summaryValue];
}

function roundTo2Decimal(float) {
    return Math.round(float * 100) / 100;
}

function refreshInputValuesAfterChangeSlider(profit) {


    $('tbody.positions').find('tr').each(function () {
        var tr = $(this);
        var purchasePriceInput = tr.find(".purchasePrice input");
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
 *                Slider Scales / Pips                *
 ****************************************************/

var range_all_sliders = {
    'min': 0,
    '10%': 3,
    '20%': 6,
    '30%': 9,
    '40%': 12,
    '50%': 15,
    '60%': 18,
    '70%': 21,
    '80%': 24,
    '90%': 27,
    'max': 30
};

function filter(value) {

    return value % 3 ? 2 : 1;

}

var pipsStepsFilter = document.getElementById('pips-steps-filter');
var profit = document.getElementById('profit');
noUiSlider.create(pipsStepsFilter, {
    range: range_all_sliders,
    start: 15,
    pips: {
        mode: 'steps',
        density: 4,
        filter: filter,
        format: wNumb({
            decimals: 0,
            prefix: '%'
        })
    }
});

pipsStepsFilter.noUiSlider.on('change', function (values) {
    if (isNaN(values)) {
        values = 0.00;
    }
    profit.innerHTML = values;
    refreshInputValuesAfterChangeSlider(values);
    updateAllValues();
});

function setSlider(value) {
    if (isNaN(value)) {
        value = 0.00;
    }
    pipsStepsFilter.noUiSlider.set(value);
    profit.innerHTML = value;
}

/****************************************************
 *    Services Collection forms dynamically add     *
 ****************************************************/
var $servicesCollectionHolder;


// setup an "add a tag" link
// var $addProductButton = $('<button type="button" class="add_tag_link">Dodaj nowy</button>');
var $newLinkTrService = $('<tr></tr>');

var $addServicesButton = $('#add-service-btn');

jQuery(document).ready(function () {
    // Get the ul that holds the collection of tags
    $servicesCollectionHolder = $('tbody.services');

    // add a delete link to all of the existing tag form li elements
    $servicesCollectionHolder.find('tr').each(function () {
        addTagFormDeleteLinkServices($(this));
    });

    // add the "add a tag" anchor and li to the tags ul
    // $servicesCollectionHolder.append($newLinkTrService);

    // count the current form inputs we have (e.g. 2), use that as the new
    // index when inserting a new item (e.g. 2)
    $servicesCollectionHolder.data('index', $servicesCollectionHolder.find('tr').length);

    $addServicesButton.on('click', function (e) {
        // add a new tag form (see next code block)
        addTagFormService($servicesCollectionHolder, $newLinkTrService);
    });

});

function addTagFormService($servicesCollectionHolder, $newLinkTrService) {
    // Get the data-prototype explained earlier
//            var prototype = $servicesCollectionHolder.data('prototype');

    var prototype =
        "<td><input type='text' id='microbundle_offert_offServices___name___name' name='microbundle_offert[offServices][__name__][name]'" +
        " required='required' maxlength='255' class='form-control' placeholder='np.Koszt instalcji' value=''></td>\n" +
        "<td>\n" +
        "    <div class='touchspin-input-size-1 mx-auto '><div class='input-group bootstrap-touchspin bootstrap-touchspin-injected'>" +
        "<input type='text' id='microbundle_offert_offServices___name___amount' name='microbundle_offert[offServices][__name__][amount]'" +
        " required='required' class='touchspin-info form-control' data-bts-min='1' data-bts-max='100000' value='1'>" +
        "</div>\n" +
        "    </div>\n" +
        "</td>\n" +
        "<td>\n" +
        "    <div class='touchspin-input-size-2 mx-auto input-group'><input type='text' id='microbundle_offert_offServices___name___price' " +
        "name='microbundle_offert[offServices][__name__][price]' required='required'" +
        " class=' form-control' data-bts-button-down-class='btn btn-outline-info'" +
        " data-bts-button-up-class='btn btn-outline-info' data-bts-min='1'" +
        " data-bts-max='100000' data-bts-decimal='2' data-bts-step='1' data-bts-postfix='<b class=&quot;info&quot;>&amp;#122;&amp;#322;</b>' " +
        " value='0,00'>" +
        "        <div class='input-group-append info'>\n" +
        "            <span class='input-group-text'><b class='info'>zł</b></span>\n" +
        "        </div>\n" +
        "    </div>\n" +
        "</td>\n" +
        "<td class='summary-row'>\n" +
        "    <div>0,00 zł</div>\n" +
        "</td>\n" +
        "<td></td>\n";

    // get the new index
    var index = $servicesCollectionHolder.data('index');

    var newForm = prototype;
    // You need this only if you didn't set 'label' => false in your tags field in TaskType
    // Replace '__name__label__' in the prototype's HTML to
    // instead be a number based on how many items we have
    // newForm = newForm.replace(/__name__label__/g, index);

    // Replace '__name__' in the prototype's HTML to
    // instead be a number based on how many items we have
    newForm = newForm.replace(/__name__/g, index);

    // increase the index with one for the next item
    $servicesCollectionHolder.data('index', index + 1);

    // Display the form in the page in an li, before the "Add a tag" link li
    var $newFormTr = $('<tr></tr>').append(newForm);
    $servicesCollectionHolder.append($newFormTr);


    addTagFormDeleteLinkServices($newFormTr);
    //refresh touchspin
    $(".touchspin-info").TouchSpin({
        buttondown_class: "btn btn-outline-info",
        buttonup_class: "btn btn-outline-info",
        buttondown_txt: '<i class="ft-minus"></i>',
        buttonup_txt: '<i class="ft-plus"></i>'
    });
}

function addTagFormDeleteLinkServices($tagFormTr) {
    var $removeFormButton = $('<a class="text-danger"><i class="la la-close"></i></a>');
    $tagFormTr.find('td:last').html($removeFormButton);

    $removeFormButton.on('click', function (e) {
        // remove the li for the tag form
        $tagFormTr.remove();
        updateAllValues();
    });
}

/****************************************************
 *        Refreshing values in services table        *
 ****************************************************/

function refreshServicesTotalValues() {
    var summaryValue = 0.00;
    $('tbody.services').find('tr').each(function () {
        var tr = $(this);
        var amount = parseInt(tr.find("input[id$='amount']").val());


        if (!isNaN(amount)) {

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
    return summaryValue;
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

/****************************************************
 *        Choose Client                             *
 ****************************************************/

var $chooseClientBtn = $('.choose-client-btn');
var $newClientBtn = $('#new-client-btn');
var nullClient =
    {
        id: null,
        name: null,
        street: null,
        houseNo: null,
        flatNo: null,
        city: null,
        postalCode: null,

    }

$chooseClientBtn.on('click', function (e) {

    var client =
        {
            id: $(this).data("id"),
            name: $(this).data("name"),
            street: $(this).data("street"),
            houseNo: $(this).data("houseno"),
            flatNo: $(this).data("flatno"),
            city: $(this).data("city"),
            postalCode: $(this).data("postalCode")

        }
    console.log(client);
    updateClientInput(client);
    setNewClientLabelAndButton();


    $('#choose-client').modal("hide");
});

function updateClientInput(client) {

    $('#microbundle_offert_clients_0_id').val(client.id);
    $('#microbundle_offert_clients_0_name').val(client.name);
    $('#microbundle_offert_clients_0_street').val(client.street);
    $('#microbundle_offert_clients_0_houseNo').val(client.houseNo);
    $('#microbundle_offert_clients_0_flatNo').val(client.flatNo);
    $('#microbundle_offert_clients_0_city').val(client.city);
    $('#microbundle_offert_clients_0_postalCode').val(client.postalCode);

}


$newClientBtn.on('click', function (e) {
    updateClientInput(nullClient);
    setNewClientLabelAndButton()
});

function setNewClientLabelAndButton() {
    var clientId = $('#microbundle_offert_clients_0_id').val();
    var isNew = (clientId === '');
    if (isNew) {
        $('#new-client-label').show();
        $('#new-client-btn').hide();
    } else {
        $('#new-client-label').hide();
        $('#new-client-btn').show();
    }

}
