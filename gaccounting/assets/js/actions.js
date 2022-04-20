$(document).ready(function () {
    var tokenname = $('#crsTokenName').val();
    /* Currency form submission*/

    var valid = $('#currencyform,#roleform,#defaultform,#assetcategoryform,#assetlocationform,#addAssetform,#supplierform,#clientform');

    valid.on('submit', function (e) {
        e.preventDefault(); // <-- important
        $(this).ajaxSubmit({
            beforeSubmit: function () {


                if (valid.valid()) {
                    //console.log(valid);
                    if ($('.journal_balance_total').length > 0) {
                        if (confirm("Are sure you want to save this journal entry?")) {
                            return true;
                        } else {
                            return false;
                        }
                    } else {
                        return true;
                    }
                } else {
                    return false;
                }
            },
            dataType: 'json',
            success: function (result) {
                if (result.status === '0') {
                    //there is an error occured
                    $('#errmsg').html(result.data);
                    alertManager('alertError');
                } else if (result.status === '1') {
                    //Everything is OK
                    $('#succmsg').html(result.data);
                    if ($('#journalTableCache').length > 0) {
                        getCurrentUserLedgerJournalEntry();
                    }
                    alertManager('alertSuccess');
                } else {
                    $('#errmsg').html(result.data);
                    alertManager('alertError');

                }

                clearFormInputs();

                $('[name="' + tokenname + '"]').val(result.tokenhash);
            }, complete: function (jqXHR, textStatus) {
                $('#progressDialogStatus').hide();


            }
        });
    });

    /* End*/
    var valid2 = $('#updateAssetform,#updateCategoryform,#updateInvoiceform,#updateLocationform,#updateSupplierForm');

    valid2.on('submit', function (e) {
        e.preventDefault(); // <-- important
        $(this).ajaxSubmit({
            beforeSubmit: function () {
            },
            dataType: 'json',
            success: function (result) {
                if (result.status === '0') {
                    //there is an error occured
                    $('#errmsg1').html(result.data);

                    alertManager('alertError1');
                } else if (result.status === '1') {
                    //Everything is OK
                    $('#succmsg1').html(result.data);

                    alertManager('alertSuccess1');
                    setInterval(function () {
                        $('#editCategoryModal,#editAssetModal,#editLocationModal,#editSupplierModal').modal('hide');

                    }, 5000);

                } else {
                    $('#errmsg1').html(result.data);

                    alertManager('alertError1');
                }






                $('[name="' + tokenname + '"]').val(result.tokenhash);
            }, complete: function (jqXHR, textStatus) {
                $('#progressDialogStatus').hide();


            }
        });
    });



    /**
     * 
     * Allow number only
     * **/

    $(".numberonly").keydown(function (e) {
        // Allow: backspace, delete, tab, escape, enter and .
        if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
                // Allow: Ctrl+A, Command+A
                        (e.keyCode === 65 && (e.ctrlKey === true || e.metaKey === true)) ||
                        // Allow: home, end, left, right, down, up
                                (e.keyCode >= 35 && e.keyCode <= 40)) {
                    // let it happen, don't do anything
                    return;
                }
                // Ensure that it is a number and stop the keypress
                if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
                    e.preventDefault();
                }
            });

    //disable input

    $('.readonlyinput').keydown(function (e) {
        e.preventDefault();

        return false;
    });

    /*
     * 
     * Date time picker initialization
     * 
     */
    jQuery('#start_date,#end_date,.depn_date,.date').datetimepicker({
        timepicker: false,
        format: 'Y-m-d'
    });


    /**
     * editAssetbutton action
     */
    $('.editAssetBtn').click(function (e) {
        e.preventDefault();
        var url = $(this).attr('href');
        $('#modalBody').html('');

        ajaxGetRequestForModal(url, '#modalBody', '#editAssetModal');
    });

    $('.editLocationBtn').click(function (e) {
        e.preventDefault();
        var url = $(this).attr('href');
        $('#modalBody').html('');

        ajaxGetRequestForModal(url, '#modalBody', '#editLocationModal');
    });

    $('.editCategoryBtn').click(function (e) {

        e.preventDefault();
        var url = $(this).attr('href');
        $('#modalBody').html('');

        ajaxGetRequestForModal(url, '#modalBody', '#editCategoryModal');
    });


    $('.editSupplierBtn').click(function (e) {

        e.preventDefault();
        var url = $(this).attr('href');
        $('#modalBody').html('');

        ajaxGetRequestForModal(url, '#modalBody', '#editSupplierModal');
    });

    $('.depn_date').change(function () {
        var asset_id = $(this).attr('name');
        var depn_date = $(this).val();
        $.get($('#url').val() + 'gassets/asset_depreciation', {asset_id: asset_id, depn_date: depn_date}, function (data) {
            $('#depn_info' + asset_id).html(data);
        });

    });

    //    view dokezo
    $('.viewmore').click(function (e) {
        e.preventDefault();
        $('#dokeziframe').html('');
        var datastring = $(this).attr('href');
        ajaxGetRequestForModal(datastring, '#dokeziframe', '#myModal');
    });
//    View fund allocations
    $('#alloc_yob').change(function ()
    {
        var alloc_yob = $('#alloc_yob').val();
        showFundAllocation(alloc_yob);

    });
//   for showing activies based on year and department when creating budget

    $('#yob,#dept').on('keyup change', function ()
    {
        var dept = $('#dept').val();
        var yob = $('#yob').val();
        getActivitiesForBudget(yob, dept);

    });
//for showing registered activities
    $('#yoa,#act_dept').on('change change', function ()
    {
        var dept = $('#act_dept').val();
        var yoa = $('#yoa').val();
        if (dept === '' || dept === null || yoa === '' || yoa === null) {
            exit;
        }
        showRegisteredActivities(yoa, dept);
    });
    //    view vouchers
    $('.viewmore').click(function (e) {
        e.preventDefault();
        $('#dokeziframe').html('');
        var datastring = $(this).attr('href');
        ajaxGetRequestForModal(datastring, '#vochaframe', '#myModal');
    });
    //showing roles and their permissions   
    $('#role_name').on('change', function ()
    {
        $('#assign_role').show();
        var role_name = $('#role_name').val();
        showRolesPermission(role_name);
    });

    $('.chosen-select').chosen({
        width: '100%'
    });

    //action for journal forms
    $(".db_cr").change(function () {
        var curr = $(this).attr('cur');
        var amount = $("#amount" + curr).val();
        if (amount != '') {
            if ($(this).val() == "0") {
                $("#total_amount" + curr).text(amount);
                $("#total_credit" + curr).text('');

                $("#grand_total_balance").text(getjouralTotalDebitCreditBalance());
            } else if (($(this).val() == "1")) {
                $("#total_credit" + curr).text(amount);
                $("#total_debit" + curr).text('');

                $("#grand_total_balance").text(getjouralTotalDebitCreditBalance());
            } else if ($(this).val() == "") {
                $("#total_amount" + curr).text('');
                $("#grand_total_balance").text('');
            }
        } else {
            alert("Please enter the amount");
            $(this).val('');
        }

    });

    //$('.chosen-single').css({ width: "100%"});
    $("input[name='acc_year']").change(function () {

        var str = $("input[name='acc_year']").val();
        var parts = str.split("-");
        var day = parts[2];
        var month = parts[1];
        var year = parseInt(parts[0]) + 1;

        $("#acc_end_year").text(year + "-" + month + "-" + day);
    });
});
$('#consultation_type').change(function () {
    var consultation_type = $('#consultation_type').val();
    $('#ledger_name').val(consultation_type);
    //alert(consultation_type);
});
$('#sponsor').change(function () {
    var sponsor = $('#sponsor').val();
    $('#ledger_name').val(sponsor);
    //alert(consultation_type);
});
$('#supp_name').change(function () {
    var supp_name = $('#supp_name').val();
    $('#supp_name').val(supp_name);
    //alert(consultation_type);
});

$('#sendDepreciation').click(function () {
    var c = confirm("are you sure you want to send depreciation of assets to accounting sysytem?");
    if (!c) {
        return;
    }
    $.ajax({
        method: "GET",
        url: $('#baseurl').val() + "Gassets/sendDepreciationToAccouting",
        data: {},
        dataType: 'json',
        beforeSend: function () {
            spinner('show');
            $('#sendDepreciation').html("Sending........");
        },
        success: function (data, textStatus, jqXHR) {
            if (data.status == '200') {
                alert(data.message);
                $('#sendDepreciation').html("Done!");
            } else {
                alert(data.message);
                $('#sendDepreciation').html("Submit To Accounting As Depreciation Amount");
            }

            console.log(data);
        }, complete: function (jqXHR, textStatus) {
            spinner('hide');
        }, error: function (jqXHR, textStatus, errorThrown) {
            spinner('hide');
        }
    });
});

////////////////// View Assets depreciation by asset ledger /////////////////////////
$(".getAssetsByLedger").click(function (e) {
    e.preventDefault();

    $.ajax({
        method: "GET",
        url: $('#baseurl').val() + "Gassets/getAssetsByLedgerId/" + $(".getAssetsByLedger").attr('href'),
        data: {ledger_name: $(".getAssetsByLedger").html()},
        //dataType:'json',
        beforeSend: function () {
            spinner('show');
        },
        success: function (data, textStatus, jqXHR) {
            $("#myDiag").dialog({
                title: $(".getAssetsByLedger").html(),
                width: '70%',
                height: 600,
                buttons: [
                    {
                        text: "CLOSE",
                        click: function () {
                            $(this).dialog('close');
                        },
                    }
                ],
            }).html(data);
            console.log(data);
        }, complete: function (jqXHR, textStatus) {
            spinner('hide');
        }, error: function (jqXHR, textStatus, errorThrown) {
            spinner('hide');
        }
    });

});


////////////////////////////////////////////


$("#saveAsset").click(function (e) {
    var c = confirm("are you sure you want to save this asset?");
    if (!c) {
        e.preventDefault();
    }

});


$('.editInvoiceBtn').click(function (e) {

        e.preventDefault();
        var url = $(this).attr('href');
        $('#modalBody').html('');

        ajaxGetRequestForModal(url, '#modalBody', '#editInvoiceModal');
    });
