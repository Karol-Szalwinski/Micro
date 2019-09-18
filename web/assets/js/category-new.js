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

$(document).on('click', '.category-li', function () {

    var id = $(this).find('a').data('id');
    var last = $(this).find('a').data('last');

    $(this).siblings().removeClass('choosen');
    $('#sub-category-ol').find('li').removeClass('choosen');
    $(this).addClass('choosen');
    var parentCategoryId = $(this).find('a').data('parent-id')
    if (parentCategoryId) {
        $('a[data-id=' + parentCategoryId + ']').parent().addClass('choosen'); // main-category
    }
    if (last == false) {
        loadChildrenCategories(id);
    }
});

$(document).on('click', '#choose-button', function () {
    var choosenLi = $('li.choosen').last();

    var id = choosenLi.find('a').data('id');

    setCategory(id);


});