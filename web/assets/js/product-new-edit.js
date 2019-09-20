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
        url: getRootUrl() + '/category/get-path/' + id,
        type: 'POST',
        dataType: 'json',
        async: true,

        success: function (data) {
            var id = data['id'];
            var path = data['path'];
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
                var last = data['last'];

                //$("#sub-category-name").html(name);
                $('#header-name-level-' + childListLevel).html(name);
                children.forEach(addToSubCategories);

                function addToSubCategories(value, index, array) {
                    var category = JSON.parse(value);
                    var categoryLi = "<li class='category-li'><a class='category-name info'" +
                        " data-id='" + category.id + "' " +
                        " data-parent-id='" + id + "' " +
                        "data-last='" + last + "' " +
                        ">" + category.name + "</a></li>";

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
        var $tempListOl =  $("#ol-level-" + childListLevel);
        console.log("clearChildList Opróżniam element " + $tempListOl);
        $('#ol-level-' + childListLevel).empty();
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

// todo ----------------NEW---------------------- //
function setCategoryOrLoadChildren(categoryId, level, isLast) {
    //alert("setCategoryOrLoadChildren " + categoryId + " " + level + " " + isLast );
    if (isLast) {
        setCategory(categoryId);
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


// collection dynamic form
var $collectionHolder;

// setup an "add a Parameter" link
var $addParameterButton = $('<a class ="btn btn-outline-info info"><i class="la la-plus"></i> Dodaj parametr</a>');
var $newLinkLi = $('<li></li>').append($addParameterButton);

$(document).ready(function () {
    // Get the ul that holds the collection of parameters
    $collectionHolder = $('ul.parameters');

    // add a delete link to all of the existing tag form li elements
    $collectionHolder.find('li').each(function () {
        addParameterFormDeleteLink($(this));
    });

    // add the "add a parameter" anchor and li to the parameters ul
    $collectionHolder.append($newLinkLi);

    // count the current form inputs we have (e.g. 2), use that as the new
    // index when inserting a new item (e.g. 2)
    $collectionHolder.data('index', $collectionHolder.find(':input').length);

    $addParameterButton.on('click', function (e) {
        // add a new parameter form (see next code block)
        addParameterForm($collectionHolder, $newLinkLi);
    });

});

function addParameterForm($collectionHolder, $newLinkLi) {
    // Get the data-prototype explained earlier
    var prototype = $collectionHolder.data('prototype');

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
    var $newFormLi = $('<li></li>').append(newForm);
    $newLinkLi.before($newFormLi);

    // add a delete link to the new form
    addParameterFormDeleteLink($newFormLi);

}

function addParameterFormDeleteLink($parameterFormLi) {
    var $removeFormButton = $('<a class ="col-md-1 danger"><i class="la la-close"></i></a>');
    $parameterFormLi.find('input').parent().append($removeFormButton);

    $removeFormButton.on('click', function (e) {
        // remove the li for the tag form
        $parameterFormLi.remove();
    });
}