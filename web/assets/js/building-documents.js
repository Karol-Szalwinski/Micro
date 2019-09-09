
// activate input in device row
$(document).on('click', '.edit-name-btn ', function () {
    //clear another open input
    $("input ").attr('readonly', true).css("border", "2px solid #ffffff");

    // add to tr status active

    $(this).closest('p').find(':input').removeAttr("readonly");
    $(this).closest('p').find(':input').css("border", "2px solid #00bfff");


    window.getSelection().removeAllRanges();
});

// deactivate input in info modal except another info in this row
$(document).on('blur', '.name-input', function (e) {
    var id = $(this).data("id");
    $("input ").attr('readonly', true).css("border", "2px solid #ffffff");
    updatePdfName(id);


});


// update build device function
function updatePdfName(id) {

    var pdfDocument = {
        id: id,
        name: encodeURIComponent($('#pdf-name-' + id).val()),
    };
    var jsonString = JSON.stringify(pdfDocument);

    $.ajax({
        url: '../../../building/pdf-update/' + encodeURIComponent(jsonString),
        type: 'POST',
        dataType: 'json',
        async: true,

        success: function (data) {

            // update row
            var pdfDocument = JSON.parse(data['pdfDocument']);
            $('#pdf-name-' + id).val(pdfDocument.name);

        },
        error: function () {
            alert('Błąd update pdf name');
        }
    });
};

