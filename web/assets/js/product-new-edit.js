// todo ----------------NEW---------------------- //
//open modal
$("#input-modal-trigger").on("click", function () {
    $("#category-modal").modal();
});

// todo ----------------NEW---------------------- //
function getRootUrl() {
    return window.location.origin;
}

// todo ----------------NEW---------------------- //
function setChoosenCategoryPathInForm(id) {

    $.ajax({
        url: getRootUrl() + '/category/get/' + id,
        type: 'POST',
        dataType: 'json',
        async: true,

        success: function (data) {
            var category = JSON.parse(data['category']);
            var path = category.fullPath;
            $('#input-modal-trigger').val(path);

        },
        error: function () {
        }
    });
}

// todo ----------------NEW---------------------- //
function setCategory(id) {
    //alert("setCategory " + id );
    //set hidden select in form
    $("#microbundle_product_category").val(id);
    //set input category as info
    setChoosenCategoryPathInForm(id);
    //close modal
    $("#category-modal").modal('hide');
}

// todo ----------------NEW---------------------- //
function loadChildrenCategories(id, level) {
    //alert("loadChildrenCategories " + id + " " + level );
    const lastListLevel = 2;
    if (level < lastListLevel) {
        var childListLevel = level + 1;
        $.ajax({
            url: getRootUrl() + '/category/get-children/' + id,
            type: 'POST',
            dataType: 'json',
            async: true,

            success: function (data) {
                var id = data['id'];
                var name = data['name'];
                var children = data['children'];

                $('#header-name-level-' + childListLevel).html(name);
                children.forEach(addToSubCategories);

                function addToSubCategories(value, index, array) {
                    var category = JSON.parse(value);
                    var last = (category.children.length === 0);
                    var sign = (last) ? "" : " >";
                    var categoryLi = "<li class='category-li'><a class='category-name info'" +
                        " data-id='" + category.id + "' " +
                        " data-parent-id='" + id + "' " +
                        "data-last='" + last + "' " +
                        ">" + category.name + sign + "</a></li>";

                    $('#ol-level-' + childListLevel).append(categoryLi);
                }
            },
            error: function () {
            }
        });
    }
}

// todo ----------------NEW---------------------- //
$(document).on('click', '.category-li', function () {

    var id = $(this).find('a').data('id');
    var level = $(this).parent().data('level');
    var isLast = $(this).find('a').data('last');

    clickCategoryAtLevel(id, level, isLast);
});

// todo ----------------NEW---------------------- //
function clearChildList(level) {
    //alert("clearChildList " + level );
    const firstListLevel = 0;
    const lastListLevel = 2;
    var childListLevel = level + 1;

    if (level !== lastListLevel) {
        var $tempListOl = $("#ol-level-" + childListLevel);
        $tempListOl.empty();
    }
    if (level === firstListLevel) {
        clearChildList(childListLevel);
    }
}

// todo ----------------NEW---------------------- //
function clearSelectedCategory(level) {
    //alert("clearSelectedCategory-begin " + level );
    $('#ol-level-' + level).find('li').removeClass('choosen');
}

// todo ----------------NEW---------------------- //
function markSelectCategory(id) {
    //alert("markSelectCategory-begin (id)" + id );
    var $categoryLi = $('a[data-id=' + id + ']').parent();
    $categoryLi.addClass('choosen');
}

function clearParameterList() {
    // $collectionHolder.find('li').remove();
    $collectionHolder.find('li').each(function () {
        $(this).remove();
    });
}

function loadCategoryParameters(id) {
    $.ajax({
        url: getRootUrl() + '/category/get/' + id,
        type: 'POST',
        dataType: 'json',
        async: true,

        success: function (data) {
            var category = JSON.parse(data['category']);
            var parameters = category.parameters;
            clearParameterList();
            for (var i = 0; i < category.parameters.length; i++) {
                addParameterForm($collectionHolder, parameters[i].name, parameters[i].id);
            }

        },
        error: function () {
        }
    });
}

function showProductParametersInputs(categoryId) {
    if (categoryId == backupCategoryId) {
        clearParameterList();
        //restore li inputs from backups
        $collectionHolder.html(backupStartParameters);
    } else {
        loadCategoryParameters(categoryId);
    }
}

// todo ----------------NEW---------------------- //
function setCategoryOrLoadChildren(categoryId, level, isLast) {
    //alert("setCategoryOrLoadChildren " + categoryId + " " + level + " " + isLast );
    if (isLast) {
        setCategory(categoryId);
        showProductParametersInputs(categoryId);
    } else {
        loadChildrenCategories(categoryId, level);
    }
}

// todo ----------------NEW---------------------- //
function clickCategoryAtLevel(categoryId, level, isLast) {
    //alert("clickCategoryAtLevel " + categoryId + " " + level + " " + isLast );
    clearChildList(level);
    clearSelectedCategory(level);
    markSelectCategory(categoryId);
    setCategoryOrLoadChildren(categoryId, level, isLast);

};

//input type price
var $inputPrice = $('#microbundle_product_price');
var inputValue = $inputPrice.val();
var newinputValue = inputValue.replace(",", ".");
$inputPrice.attr('type', 'number').attr('min', '0.00').attr('max', '100000').attr('step', '0.01').val(newinputValue);


// collection dynamic form
var $collectionHolder;

// setup an "add a Parameter" link
var $addParameterButton = $('<a class ="btn btn-outline-info info"><i class="la la-plus"></i> Dodaj parametr</a>');
var $newLinkLi = $('<li></li>').append($addParameterButton);

$(document).ready(function () {
    // Get the ul that holds the collection of parameters
    $collectionHolder = $('ul.parameters');

    // count the current form inputs we have (e.g. 2), use that as the new
    // index when inserting a new item (e.g. 2)
    $collectionHolder.data('index', $collectionHolder.find(':input').length);

    // $addParameterButton.on('click', function (e) {
    //     // add a new parameter form (see next code block)
    //     addParameterForm($collectionHolder, $newLinkLi);
    // });

});

function addInputValueToPrototype(stringExpression, inputName, inputValue) {
    var insertExpression = ' value="' + inputValue + '"';
    var searchExpression = 'microbundle_product_productParameters___name___' + inputName + '"';
    return stringExpression.replace(searchExpression, searchExpression + insertExpression);
}

function addParameterForm($collectionHolder, nameParameter, prototypeId) {
    // Get the data-prototype explained earlier
    var prototype = $collectionHolder.data('prototype');


    // get the new index
    var index = $collectionHolder.data('index');

    var newForm = prototype;
    // You need this only if you didn't set 'label' => false in your tags field in TaskType
    // Replace '__name__label__' in the prototype's HTML to
    // instead be a number based on how many items we have
    // newForm = newForm.replace(/__name__label__/g, index);

    newForm = addInputValueToPrototype(newForm, "name", nameParameter);
    newForm = addInputValueToPrototype(newForm, "prototypeId", prototypeId);

    // Replace '__name__' in the prototype's HTML to
    // instead be a number based on how many items we have
    newForm = newForm.replace(/__name__/g, index);

    // increase the index with one for the next item
    $collectionHolder.data('index', index + 1);

    // Display the form in the page in an li
    var $newFormLi = $('<li></li>').append(newForm);
    console.log($newFormLi);
    $collectionHolder.append($newFormLi);


}

var backupCategoryId = $('#microbundle_product_category').val();
var backupStartParameters = $(".parameters").html();


// Basic Summernote

$(document).ready(function () {
    $('#microbundle_product_description').summernote();
});