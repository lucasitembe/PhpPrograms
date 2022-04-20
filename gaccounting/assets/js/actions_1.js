$(document).ready(function () {
    var tokenname = $('#crsTokenName').val();
    /* Currency form submission*/

    var valid = $('#currencyform,#roleform,#defaultform,#assetcategoryform,#assetlocationform,#addAssetform,#supplierform');

    valid.on('submit', function (e) {
        e.preventDefault(); // <-- important
        $(this).ajaxSubmit({
            beforeSubmit: function () {
                if ($('.span_journal_text_debit').length > 0) {
                    
                    if(getjouralTotalDebitCreditBalance() == null){
                       alert('You cannot save empty fields!');
                        return false;  
                    }

                    if (getjouralTotalDebitCreditBalance() != 0) {
                        alert('Inorder to save this journal entry ,Debit must be equal to credit Amount');
                        return false;
                    }
                }

                if (valid.valid()) {
                    if (confirm("Are sure you want to save this journal entry?")) {
                        return true;
                    } else {
                        return false;
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
    var valid2 = $('#updateAssetform,#updateCategoryform,#updateLocationform,#updateSupplierForm');

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


});
