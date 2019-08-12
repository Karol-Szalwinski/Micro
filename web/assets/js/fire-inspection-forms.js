//change type of input from text to date for run datepicker
$("#microbundle_fireinspection_inspectionDate").get(0).type = 'date';
$("#microbundle_fireinspection_nextInspectionDate").get(0).type = 'date';

//change date in add form

function changeNextDate() {
    var setDateVal = $('#microbundle_fireinspection_inspectionDate').val();
    var setDate = new Date(setDateVal);
    var nextDate = $('#microbundle_fireinspection_nextInspectionDate');
    switch ($("input[type='radio']:checked").val()) {
        case '' :
            nextDate.prop("readonly", false);
            break;
        case '3' :
            setDate.setMonth(setDate.getMonth() + 3);
            nextDate.val(setDate.toISOString().split('T')[0]);
            nextDate.prop("readonly", true);
            break;
        case '6' :
            setDate.setMonth(setDate.getMonth() + 6);
            nextDate.val(setDate.toISOString().split('T')[0]);
            nextDate.prop("readonly", true);
            break;
        case '12' :
            setDate.setMonth(setDate.getMonth() + 12);
            nextDate.val(setDate.toISOString().split('T')[0]);
            nextDate.prop("readonly", true);
            break;
    }

}

$(document).ready(changeNextDate());

$(document).on('change', 'input[type=radio]', function (event) {
    changeNextDate()
});
$(document).on('change', '#microbundle_fireinspection_inspectionDate', function (event) {
    changeNextDate()
});

//autosize textarea
$("textarea").keyup(function (e) {
    while ($(this).outerHeight() < this.scrollHeight + parseFloat($(this).css("borderTopWidth"))
    + parseFloat($(this).css("borderBottomWidth"))) {
        $(this).height($(this).height() + 1);
    }
    ;
});


$(':checkbox').each(function () {  //autocheck all checkboxes
    this.checked = true;
});


