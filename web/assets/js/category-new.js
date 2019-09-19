//open modal
$("#modal-trigger").on("click", function () {
    $("#category-modal").modal();
});

function setChoosenCategoryPathInForm(id) {

    $.ajax({
        url: '../category/get-path/' + id,
        type: 'POST',
        dataType: 'json',
        async: true,

        success: function (data) {
            var id = data['id'];
            var path = data['path'];
            $('#modal-trigger').val(path);

        },
        error: function () {
        }
    });
}

function setCategory(id) {
    //set select
    $("#microbundle_category_parent").val(id);
    //set div with category info
    setChoosenCategoryPathInForm(id);
    //close modal
    $("#category-modal").modal('hide');
}

function loadChildrenCategories(id) {
    $.ajax({
        url: '../category/get-children/' + id,
        type: 'POST',
        dataType: 'json',
        async: true,

        success: function (data) {
            var id = data['id'];
            var name = data['name'];
            var children = data['children'];
            $("#sub-category-name").html(name);
            $('#sub-category-ol').empty();
            children.forEach(addToSubCategories);

            function addToSubCategories(value, index, array) {
                var category = JSON.parse(value);
                var categoryLi = "<li class='category-li'><a class='category-name info'" +
                    " data-id='" + category.id + "' " +
                    " data-parent-id='" + id + "' " +
                    "data-last='true' " +
                    ">" + category.name + "</a></li>";

                $('#sub-category-ol').append(categoryLi);
            }
        },
        error: function () {
        }
    });
}

function markCategoryAsSelected(id) {
    //category li holder
    var categoryLi = $('a[data-id=' + id + ']').parent();
    //check parent category
    var parentCategoryId = categoryLi.find('a').data('parent-id')

    //clear sibling and subcategories class choosen
    categoryLi.siblings().removeClass('choosen');
    $('#sub-category-ol').find('li').removeClass('choosen');

    //add class choosen to catogero li and parent if exist
    categoryLi.addClass('choosen');

    if (parentCategoryId) {
        $('a[data-id=' + parentCategoryId + ']').parent().addClass('choosen'); // main-category
    }
    return parentCategoryId;
}

//click on single category
$(document).on('click', '.category-li', function () {

    var id = $(this).find('a').data('id');

    var parentCategoryId = markCategoryAsSelected(id);

    if (!parentCategoryId) {
        loadChildrenCategories(id);
    }
});

$(document).on('click', '#choose-button', function () {
    var choosenLi = $('li.choosen').last();

    var id = choosenLi.find('a').data('id');

    setCategory(id);


});

var $collectionHolder;

// setup an "add a Parameter" link
var $addParameterButton = $('<a class ="btn btn-outline-info info"><i class="la la-plus"></i> Dodaj parametr</a>');
var $newLinkLi = $('<li></li>').append($addParameterButton);

$(document).ready(function() {
    // Get the ul that holds the collection of parameters
    $collectionHolder = $('ul.parameters');

    // add a delete link to all of the existing tag form li elements
    $collectionHolder.find('li').each(function() {
        addParameterFormDeleteLink($(this));
    });

    // add the "add a parameter" anchor and li to the parameters ul
    $collectionHolder.append($newLinkLi);

    // count the current form inputs we have (e.g. 2), use that as the new
    // index when inserting a new item (e.g. 2)
    $collectionHolder.data('index', $collectionHolder.find(':input').length);

    $addParameterButton.on('click', function(e) {
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

    $removeFormButton.on('click', function(e) {
        // remove the li for the tag form
        $parameterFormLi.remove();
    });
}