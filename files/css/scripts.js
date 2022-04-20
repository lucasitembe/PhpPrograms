$('.addParameter').click(function (e) {
    e.stopImmediatePropagation();
    e.preventDefault();
    $('#newparameter').dialog({
        modal: true,
        title: 'ADD NEW PARAMETER',
        width: 600,
        resizable: false,
        draggable: false
    });
});

$('#saveParameterSave').click(function (e) {
    e.stopImmediatePropagation();
    e.preventDefault();
    var ParameterName = $('#ParameterName').val();
    var unitofmeasure = $('#unitofmeasure').val();
    var lowervalue = $('#lowervalue').val();
    var highervalue = $('#highervalue').val();
    var Operator = $('#Operator').val();
    var results = $('#results').val();

    // alert('I am here');
    // exit();
    $.ajax({
        type: 'POST',
        url: 'requests/Save_Parameters.php',
        data: 'saveparameter=true&ParameterName=' + ParameterName + '&unitofmeasure=' + unitofmeasure + '&lowervalue=' + lowervalue + '&highervalue=' + highervalue + '&Operator=' + Operator + '&results=' + results,
        success: function (html) {
            $('#parameterstatus').html(html);
            $('#showParameters').load('requests/getParameters.php');
        }
    });

});

$('.editParameter').click(function (e) {
    e.stopImmediatePropagation();
    e.preventDefault();
    var id = $(this).attr('id');
    var name = $(this).val();
    $.ajax({
        type: 'POST',
        url: 'requests/editParameters.php',
        data: 'id=' + id,
        success: function (html) {
            $('#showParameterlist').html(html);
        }
    });

    $('#editthisParameter').dialog({
        modal: true,
        title: 'Edit ' + name,
        width: 600,
        resizable: false,
        draggable: false,
    });
});


//delete parameter
$('.deleteParameter').click(function (e) {
    e.stopImmediatePropagation();
    e.preventDefault();
    if (confirm('Are you sure you want to delete this parameter?')) {
        var id = $(this).attr('id');
        $.ajax({
            type: 'POST',
            url: 'requests/editDelete.php',
            data: 'action=delete&id=' + id,
            success: function () {
                // alert(html);
                $('#showParameters').load('requests/getParameters.php');

            }
        });
    } else {
        return false;
    }
});

//assign parameters to items
$('#assignsubmit').click(function (e) {
    e.stopImmediatePropagation();
    e.preventDefault();
    var item = $('.itemId').attr('id');
    var parameter = $('#Laboratory_Parameter_ID').val();
    $.ajax({
        type: 'POST',
        url: 'requests/saveTestParameter.php',
        data: 'item=' + item + '&parameter=' + parameter,
        success: function (html) {
            // alert(html);
            $('#relodParameter').html(html);
        }
    });
});

$('.removeParameter').click(function (e) {
    e.stopImmediatePropagation();

    var id = $(this).attr('id');
    var itemID = $(this).attr('name');
    $.ajax({
        type: 'POST',
        url: 'requests/Save_Parameters.php',
        data: 'action=delete&id=' + id + '&itemID=' + itemID,
        success: function (html) {
//          alert(html);
            $('#relodParameter').html(html);
        }
    });
});

//assign specimen to tests
$('.checkSpecimen').click(function (e) {
    e.stopImmediatePropagation();
    var specimen = $(this).val();
    $('#hidetd' + specimen).fadeOut(1000);
    var itemId = $('.specimenItemID').attr('id');
    $.ajax({
        type: 'POST',
        url: 'requests/SaveTestsSpecimen.php',
        data: 'asign=asignspcmn&specimen=' + specimen + '&itemId=' + itemId,
        cache: false,
        success: function (html) {
            // alert(html);
            $('#addedParameter').html(html);
        }
    });
});


//unset specimen
$('.Specimen_ID').click(function (e) {
    e.stopImmediatePropagation();
    var id = $(this).attr('id');
    var itemId = $(this).val();
    $('#remove' + id).fadeOut(1000);
    $.ajax({
        type: 'POST',
        url: 'requests/SaveTestsSpecimen.php',
        data: 'action=delete&id=' + id + '&itemId=' + itemId,
        cache: false,
        success: function (html) {
            $('#assignParameter').html(html);
        }
    });
});


$(document).on('click', '.results', function (e) {
    e.stopImmediatePropagation();
    var barcode = $('#searchbarcode').val();
    // alert(barcode);
    var patient = $(this).attr('name');
    var id = $(this).attr('id');
    var filter = $(this).attr('filter');
    var payment_id = $(this).attr('payment_id');
    // alert(payment_id);
    $.ajax({
        type: 'GET',
        url: 'requests/testResults.php',
        data: 'action=getResult&id=' + id + '&payment_id=' + payment_id + '&barcode=' + barcode + '&filter=' + filter,
        cache: false,
        beforeSend: function (xhr) {
            $('#progressDialogStatus').show();
        },
        success: function (html) {
            // alert(html);
            $('#showLabResultsHere').html(html);
        }, complete: function (jqXHR, textStatus) {
            $('#progressDialogStatus').hide();
        }
    });

    $('#labResults').dialog({
        modal: true,
        width: '98%',
        height: 550,
        resizable: true,
        draggable: true
    });

    $("#labResults").dialog('option', 'title', patient + '  ' + 'No.' + id);
});



$('.general').click(function (e) {
    e.stopImmediatePropagation();
    var id = $(this).attr('id');
    $.ajax({
        type: 'POST',
        url: 'requests/testResults.php',
        data: 'generalResult=getGeneral&id=' + id,
        cache: false,
        success: function (html) {
            // alert(html);
            $('#showGeneral').html(html);
        }
    });

    $('#labGeneral').dialog({
        modal: true,
        width: '90%',
        minHeight: 450,
        resizable: true,
        draggable: true
    });
});

