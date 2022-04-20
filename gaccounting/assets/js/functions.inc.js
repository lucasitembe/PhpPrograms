/*
 *Get profit and loss from the sepcified datatime
 */

var baseUrl = $('#baseurl').val();
function getProfitLoss() {
    var start_date = $('#start_date').val();
    var end_date = $('#end_date').val();
    var datastring = 'start_date=' + start_date + '&end_date=' + end_date;
     var url=baseUrl + "gledger/profitloss";
    $('#ajaxUpdateContainerPdf').attr('href',url+'?report&'+datastring).attr("target","_blank");
    $.ajax({
        method: "GET",
        url: url,
        data: datastring,
        beforeSend: function () {
            spinner('show');
        },
        success: function (data, textStatus, jqXHR) {
            $('#ajaxUpdateContainer').html(data);
        }, complete: function (jqXHR, textStatus) {
            spinner('hide');
        }, error: function (jqXHR, textStatus, errorThrown) {
            spinner('hide');
        }
    });
}

/*
 *Get balancesheer from the sepcified datatime
 */
function getBalanceSheet() {
    var start_date = $('#start_date').val();
    var end_date = $('#end_date').val();
    var datastring = 'start_date=' + start_date + '&end_date=' + end_date;
    
     var url=baseUrl + "/gledger/balancesheet";
    $('#ajaxUpdateContainerPdf').attr('href',url+'?report&'+datastring);
    
    $.ajax({
        method: "GET",
        url: url,
        data: datastring,
        beforeSend: function () {
            spinner('show');
        },
        success: function (data, textStatus, jqXHR) {
            $('#ajaxUpdateContainer').html(data);
        }, complete: function (jqXHR, textStatus) {
            spinner('hide');
        }, error: function (jqXHR, textStatus, errorThrown) {
            spinner('hide');
        }
    });
}
/*
 *Get budgeted and actual amount 
 */

function getBudgetedActual() {
    var yob = $('#alloc_yob').val();
    var start_date = $('#start_date').val();
    var end_date = $('#end_date').val();
    var datastring = 'start_date=' + start_date + '&end_date=' + end_date + '&yob=' + yob;
    $.ajax({
        method: "GET",
        url: baseUrl + "/fund/show_fundAlloc",
        data: datastring,
        beforeSend: function () {
            spinner('show');
        },
        success: function (data, textStatus, jqXHR) {
            $('#ajaxUpdateContainer').html(data);
        }, complete: function (jqXHR, textStatus) {
            spinner('hide');
        }, error: function (jqXHR, textStatus, errorThrown) {
            spinner('hide');
        }
    });
}
/*
 *Get budgeted and actual amount eHMS based
 */

function getBudgetedActualGacc() {
    var yob = $('#alloc_yob_gacc').val();
    var start_date = $('#start_date').val();
    var end_date = $('#end_date').val();
    var datastring = 'start_date=' + start_date + '&end_date=' + end_date + '&yob=' + yob;
    $.ajax({
        method: "GET",
        url: baseUrl + "/fund/show_fundAlloc_gacc",
        data: datastring,
        beforeSend: function () {
            spinner('show');
        },
        success: function (data, textStatus, jqXHR) {
            $('#ajaxUpdateContainergacc').html(data);
        }, complete: function (jqXHR, textStatus) {
            spinner('hide');
        }, error: function (jqXHR, textStatus, errorThrown) {
            spinner('hide');
        }
    });
}

/*
 *Get expeniture report from the sepcified datatime
 */

function getEpenditures() {
    var start_date = $('#start_date').val();
    var end_date = $('#end_date').val();
    var datastring = 'start_date=' + start_date + '&end_date=' + end_date;
    $.ajax({
        method: "GET",
        url: baseUrl + "/chop/exp_report",
        data: datastring,
        beforeSend: function () {
            spinner('show');
        },
        success: function (data, textStatus, jqXHR) {
            $('#ajaxUpdateContainer').html(data);
        }, complete: function (jqXHR, textStatus) {
            spinner('hide');
        }, error: function (jqXHR, textStatus, errorThrown) {
            spinner('hide');
        }
    });
}
/*
 *Get expeniture report from the sepcified datatime
 */

function getEpenditures() {
    var start_date = $('#start_date').val();
    var end_date = $('#end_date').val();
    var datastring = 'start_date=' + start_date + '&end_date=' + end_date;
    $.ajax({
        method: "GET",
        url: baseUrl + "/chop/exp_report",
        data: datastring,
        beforeSend: function () {
            spinner('show');
        },
        success: function (data, textStatus, jqXHR) {
            $('#ajaxUpdateContainer').html(data);
        }, complete: function (jqXHR, textStatus) {
            spinner('hide');
        }, error: function (jqXHR, textStatus, errorThrown) {
            spinner('hide');
        }
    });
}
/*
 *Get budget from the sepcified datatime
 */
function getBudget() {
    var yob = $('#yob').val();
    var c_center = $('#c_center').val();
    var datastring = 'yob=' + yob + '&c_center=' + c_center;
    $.ajax({
        method: "GET",
        url: baseUrl + "/budget/view_budget",
        data: datastring,
        beforeSend: function () {
            spinner('show');
        },
        success: function (data, textStatus, jqXHR) {
            $('#ajaxUpdateContainer').html(data);
        }, complete: function (jqXHR, textStatus) {
            spinner('hide');
        }, error: function (jqXHR, textStatus, errorThrown) {
            spinner('hide');
        }
    });
}

/*
 *Get debtors from the sepcified datatime
 */
function getDebtors() {
    var start_date = $('#start_date').val();
    var end_date = $('#end_date').val();
    var datastring = 'start_date=' + start_date + '&end_date=' + end_date;
    $.ajax({
        method: "GET",
        url: baseUrl + "/receivable/debtors",
        data: datastring,
        beforeSend: function () {
            spinner('show');
        },
        success: function (data, textStatus, jqXHR) {
            $('#ajaxUpdateContainer').html(data);
        }, complete: function (jqXHR, textStatus) {
            spinner('hide');
        }, error: function (jqXHR, textStatus, errorThrown) {
            spinner('hide');
        }
    });
}


/*
 *Get payables from the sepcified datatime
 */
function getPayables() {
    var start_date = $('#start_date').val();
    var end_date = $('#end_date').val();
    var datastring = 'start_date=' + start_date + '&end_date=' + end_date;
    $.ajax({
        method: "GET",
        url: baseUrl + "/payable/payable",
        data: datastring,
        beforeSend: function () {
            spinner('show');
        },
        success: function (data, textStatus, jqXHR) {
            $('#ajaxUpdateContainer').html(data);
        }, complete: function (jqXHR, textStatus) {
            spinner('hide');
        }, error: function (jqXHR, textStatus, errorThrown) {
            spinner('hide');
        }
    });
}



/*
 *Get purchases from the sepcified datatime
 */
function getPurchases() {
    var start_date = $('#start_date').val();
    var end_date = $('#end_date').val();
    var datastring = 'start_date=' + start_date + '&end_date=' + end_date;
    $.ajax({
        method: "GET",
        url: baseUrl + "/purchase/purchase",
        data: datastring,
        beforeSend: function () {
            spinner('show');
        },
        success: function (data, textStatus, jqXHR) {
            $('#ajaxUpdateContainer').html(data);
        }, complete: function (jqXHR, textStatus) {
            spinner('hide');
        }, error: function (jqXHR, textStatus, errorThrown) {
            spinner('hide');
        }
    });
}

function ajaxGetRequestForModal(datastring, frameToUpdate, modalId)
{
    $.ajax({
        method: "GET",
        url: datastring,
        beforeSend: function () {
            spinner('show');
        },
        success: function (data) {
            $(frameToUpdate).html(data);
            $(modalId).modal('show');
        },
        complete: function () {
            spinner('hide');
        },
        error: function (data) {
            spinner('hide');
        }
    });
}
/*
 *show fund allocation based on year of budget
 * 
 */
function showFundAllocation(alloc_yob)
{
    $.ajax({
        type: "GET",
        url: baseUrl + 'fund/show_fundAlloc',
        data: {
            input_alloc: alloc_yob

        },
        beforeSend: function ()
        {
            $('#progressStatus').show();
        },
        success: function (data) {

            $('#view_alloc').html(data);
        },
        complete: function ()
        {
            $('#progressStatus').hide();
        },
        error: function (data) {
            $('#progressStatus').hide();
        }
    });

}
//for showing activies based on year and department when creating budget
function getActivitiesForBudget(yob, dept)
{

    $.ajax({
        type: "GET",
        url: baseUrl + 'budget/ajax_activity',
        data: {
            input_yob: yob,
            input_dept: dept
        },
        success: function (data) {
            $('#jibu').html(data);


        }
    });


}
//show registered activities
function showRegisteredActivities(yoa, dept)
{
    $.ajax({
        type: "GET",
        url: baseUrl + 'activity/show_reg_activity',
        data: {
            input_yoa: yoa,
            input_dept: dept
        },
        beforeSend: function ()
        {
            spinner('show');
        },
        success: function (data) {
            $('#jibu').html(data);
        },
        complete: function ()
        {
            spinner('hide');
        },
        error: function (data) {
            $('#progressStatus').hide();
        }
    });

}
//show roles and their permissions
function showRolesPermission(role_name) {
    $.ajax({
        type: "GET",
        url: baseUrl + 'account/assign_access',
        data: {
            input_role: role_name
        },
        beforeSend: function ()
        {
            spinner('show');
        },
        success: function (data) {
            $('#access').html(data);
        },
        complete: function ()
        {
            spinner('hide');
        },
        error: function (data) {
            spinner('hide');
        }
    });
}

function addJournalRow() {
    var i = parseInt($('#current_row_index').val()) + 1;
    $.ajax({
        type: "GET",
        url: baseUrl + 'Helpers/getJougnalRow',
        data: {
            current_index: i
        },
        beforeSend: function ()
        {
            spinner('show');
        },
        success: function (data) {
            $('#journalTable tr:last').after(data);
            $('#current_row_index').val(i);
        },
        complete: function ()
        {
            spinner('hide');
        },
        error: function (data) {
            spinner('hide');
        }
    });

}

function getAccountSectionByLedgerID(id, index) {
    $.ajax({
        type: "GET",
        url: baseUrl + 'Helpers/getAccountSectionByLedgerID',
        data: {
            ledger_id: id
        },
        beforeSend: function ()
        {
            showLoader('.loader_' + index);
        },
        success: function (data) {
            $('#acc_sect' + index).text(data);
        },
        complete: function ()
        {
            hideLoader('.loader_' + index);
        },
        error: function (data) {
            hideLoader('.loader_' + index);
        }
    });
}

function showLoader(element) {
    $(element).show();
}

function hideLoader(element) {
    $(element).hide();
}

function getjouralTotalDebitCredit(type) {
    var grandTotal = 0;
    if (type == 'debit') {
        $('.span_journal_text_debit').each(function () {
            var amount = $(this).text().trim();

            if (amount == '') {
                amount = 0;
            }

            grandTotal += parseInt(amount);
        });
    } else if (type == 'credit') {
        $('.span_journal_text_credit').each(function () {
            var amount = $(this).text().trim();

            if (amount == '') {
                amount = 0;
            }

            grandTotal += parseInt(amount);
        });
    }

    return grandTotal;
}

function getjouralTotalDebitCreditBalance() {
    var grandTotal = 0;

    if ($('.journal_balance_total').length == 0) {
        return null;
    } else {
        $('.journal_balance_total').each(function () {
            var amount = $(this).text().trim();

            if (amount == '') {
                amount = 0;
            }

            grandTotal += parseInt(amount);
        });
    }

    return grandTotal;
}

function getLedgersBySecId(sec_id, container_id) {
    $.ajax({
        type: "GET",
        url: baseUrl + 'Helpers/getLedgersBySecId',
        data: {
            sec_id: sec_id
        },
        beforeSend: function ()
        {
            spinner('show');
        },
        success: function (data) {
            $('#' + container_id).html(data);
            $(".chosen-select").trigger("chosen:updated");
        },
        complete: function ()
        {
            spinner('hide');
        },
        error: function (data) {
            spinner('hide');
        }
    });
}


function getCurrentUserLedgerJournalEntry() {
    $.ajax({
        type: "GET",
        url: baseUrl + 'Helpers/getCurrentUserLedgerJournalEntry',
        beforeSend: function ()
        {
            spinner('show');
        },
        success: function (data) {
            $('#journalTableCache').html(data);
        },
        complete: function ()
        {
            spinner('hide');
        },
        error: function (data) {
            spinner('hide');
        }
    });
}

function deleteJournalEntrycache(id) {
    if (!confirm("Are you sure you want to delete this entry?")) {
        exit();
    }

    $.ajax({
        type: "GET",
        url: baseUrl + 'JournalEntry/deleteJournalEntrycache',
        data: {
            id: id
        },
        beforeSend: function ()
        {
            spinner('show');
        },
        success: function (data) {
            if (data == 'success') {
                $('#succmsg').html(data);
                getCurrentUserLedgerJournalEntry();
                alertManager('alertSuccess');
            } else {
                $('#errmsg').html(data);
                alertManager('alertError');
            }
        },
        complete: function ()
        {
            spinner('hide');
        },
        error: function (data) {
            spinner('hide');
        }
    });
}

function saveJournalEntrycache() {
    var comment = $('#journal_comments').val();
    var journal_date = $('#start_date').val();

    if (getjouralTotalDebitCreditBalance() == null) {
        alert('No journal entry to save');
        exit();
    } else if (comment == '' || comment == null) {
        alert('Add Comment');
        exit();
    } else if (journal_date == '' || journal_date == null) {
        alert('Enter Journal Date');
        exit();
    }

    if (getjouralTotalDebitCreditBalance() == 0) {
        if (confirm("Are you sure you want to save this entry?")) {
            $.ajax({
                type: "get",
                url: baseUrl + 'JournalEntry/saveJournalEntrycache',
                data: {
                    comment: comment,
                    journal_date: journal_date
                },
                beforeSend: function ()
                {
                    spinner('show');
                },
                success: function (data) {
                    if (data == 'success') {
                        $('#succmsg').html(data);
                        getCurrentUserLedgerJournalEntry();
                        alertManager('alertSuccess');
                        $('#journal_comments').val('');
                    } else if (data == 'invalid') {
                        alert('In order to save this journal entry ,Balance must be zero');
                    } else {
                        $('#errmsg').html(data);
                        alertManager('alertError');
                    }
                },
                complete: function ()
                {
                    spinner('hide');
                },
                error: function (data) {
                    spinner('hide');
                }
            });
        }
    } else {
        alert('In order to save this journal entry ,Balance must be zero');
    }
}//

function modifyYear(id, src) {
    if (!confirm("Are you sure you want to modify this account year?")) {
        exit();
    }

    $.ajax({
        type: "GET",
        url: baseUrl + 'Gledger/modifyYear',
        data: {
            id: id,
            src: src
        },
        beforeSend: function ()
        {
            spinner('show');
        },
        success: function (data) {
            if (data == 'success') {
                $('#succmsg').html(data);
                alertManager('alertSuccess');

                window.location = window.location.href;
            } else {
                $('#errmsg').html(data);
                alertManager('alertError');
            }
        },
        complete: function ()
        {
            spinner('hide');
        },
        error: function (data) {
            spinner('hide');
        }
    });
}

//Reports

function getJurnalReport() {
    var start_date = $('#start_date').val();
    var end_date = $('#end_date').val();
    var datastring = 'start_date=' + start_date + '&end_date=' + end_date;
    var url=baseUrl + "/gledger/journalReport";
    $('#ajaxUpdateContainerPdf').attr('href',url+'?report&'+datastring);
    $.ajax({
        method: "GET",
        url: url,
        data: datastring,
        beforeSend: function () {
            spinner('show');
        },
        success: function (data, textStatus, jqXHR) {
            $('#ajaxUpdateContainer').html(data);
        }, complete: function (jqXHR, textStatus) {
            spinner('hide');
        }, error: function (jqXHR, textStatus, errorThrown) {
            spinner('hide');
        }
    });
}

function agingStatementReport() {
    var start_date = $('#start_date').val();
    var end_date = $('#end_date').val();
    var suplier_id = $('#supplier_id').val();
    
    if(start_date =='' || end_date =='' || suplier_id ==''){
        alert('All field are required');
        exit;
    }
    
    var datastring = 'start_date=' + start_date + '&end_date=' + end_date+'&supplier_id=' + suplier_id;
    var url=baseUrl + "/gledger/agingStatmentReport";
    $('#ajaxUpdateContainerPdf').attr('href',url+'?report&'+datastring);
    
    $.ajax({
        method: "GET",
        url: url,
        data: datastring,
        beforeSend: function () {
            spinner('show');
        },
        success: function (data, textStatus, jqXHR) {
            $('#ajaxUpdateContainer').html(data);
        }, complete: function (jqXHR, textStatus) {
            spinner('hide');
        }, error: function (jqXHR, textStatus, errorThrown) {
            spinner('hide');
        }
    });
}


function sponsor_agingStatementReport() {
    var start_date = $('#start_date').val();
    var end_date = $('#end_date').val();
    var sponsor = $('#sponsor').val();
    
    if(start_date =='' || end_date =='' || sponsor ==''){
        alert('All field are required');
        exit;
    }
    
    var datastring = 'start_date=' + start_date + '&end_date=' + end_date+'&sponsor=' + sponsor;
    //var url=baseUrl + "/gledger/agingStatmentReport";
    var url="http://localhost/Final_One/files/sponsor_invoice_bydate.php";
    $('#ajaxUpdateContainerPdf').attr('href',url+'?report&'+datastring);
    
    $.ajax({
        method: "GET",
        url: url,
        data: datastring,
        beforeSend: function () {
            spinner('show');
        },
        success: function (data, textStatus, jqXHR) {
            $('#ajaxUpdateContainer').html(data);
        }, complete: function (jqXHR, textStatus) {
            spinner('hide');
        }, error: function (jqXHR, textStatus, errorThrown) {
            spinner('hide');
        }
    });
}



function getLedgerStatementReport() {
    var start_date = $('#start_date').val();
    var end_date = $('#end_date').val();
    var acc_ledger = $('#acc_ledgers').val();
    
    if(start_date =='' || end_date =='' || acc_ledger ==''){
        alert('All field are required');
        exit;
    }
    
    var datastring = 'start_date=' + start_date + '&end_date=' + end_date+'&acc_ledger=' + acc_ledger;
    var url=baseUrl + "/gledger/LedgerStatementReport";
    $('#ajaxUpdateContainerPdf').attr('href',url+'?report&'+datastring);
    
    $.ajax({
        method: "GET",
        url: url,
        data: datastring,
        beforeSend: function () {
            spinner('show');
        },
        success: function (data, textStatus, jqXHR) {
            $('#ajaxUpdateContainer').html(data);
        }, complete: function (jqXHR, textStatus) {
            spinner('hide');
        }, error: function (jqXHR, textStatus, errorThrown) {
            spinner('hide');
        }
    });
}

function getTrialBalanceReport(){
    var start_date = $('#start_date').val();
    var end_date = $('#end_date').val();
    var datastring = 'start_date=' + start_date + '&end_date=' + end_date;
    
    var url=baseUrl + "/gledger/trialBalance";
    $('#ajaxUpdateContainerPdf').attr('href',url+'?report&'+datastring);
    
    $.ajax({
        method: "GET",
        url: url,
        data: datastring,
        beforeSend: function () {
            spinner('show');
        },
        success: function (data, textStatus, jqXHR) {
            $('#ajaxUpdateContainer').html(data);
        }, complete: function (jqXHR, textStatus) {
            spinner('hide');
        }, error: function (jqXHR, textStatus, errorThrown) {
            spinner('hide');
        }
    }); 
}

function modifyJournalMonths(month, src) {
    if (!confirm("Are you sure you want to modify this account year?")) {
        exit();
    }

    $.ajax({
        type: "GET",
        url: baseUrl + 'Gledger/modifyJournalMonths',
        data: {
            month: month,
            src: src
        },
        beforeSend: function ()
        {
            spinner('show');
        },
        success: function (data) {
            if (data == 'success') {
                $('#succmsg').html(data);
                alertManager('alertSuccess');

                window.location = window.location.href;
            } else {
                $('#errmsg').html(data);
                alertManager('alertError');
            }
        },
        complete: function ()
        {
            spinner('hide');
        },
        error: function (data) {
            spinner('hide');
        }
    });
}




function getAssetDetails() {

    var search_key_word = $('#search_key_word').val();
   
    
    if(search_key_word ==''){
        alert('Please enter search term in search box');
        return;
    }
    
    var datastring = 'search_key_word=' + search_key_word;
    var url=baseUrl + "/Gassets/physicalCounting";
    //un comment this line if you want to do printing function
    //$('#ajaxUpdateContainerPdf').attr('href',url+'?report&'+datastring);
    
    $.ajax({
        method: "GET",
        url: url,
        data: datastring,
        beforeSend: function () {
            spinner('show');
        },
        success: function (data, textStatus, jqXHR) {
            $('#ajaxUpdateContainer').html(data);
        }, complete: function (jqXHR, textStatus) {
            spinner('hide');
        }, error: function (jqXHR, textStatus, errorThrown) {
            spinner('hide');
        }
    });
}

function submitAssetTracking(){

    var available = '';
    var asset_id = $("#asset_id").val();
    if($('#available').prop('checked')){
        var c = confirm("Are you sure this Asset is currently Available?");
        if(!c){
            return;
        }
        available = 'Yes';
        
    } else {
        var c = confirm("Are you sure this Asset is never found?");
        if(!c){
            return;
        }
         available = 'No';
    }
    
    //return;
    
    var datastring = 'available=' + available+'&asset_id='+asset_id;
    var url=baseUrl + "/Gassets/sendAssetTracking";
    //un comment this line if you want to do printing function
    //$('#ajaxUpdateContainerPdf').attr('href',url+'?report&'+datastring);
    
    $.ajax({
        method: "GET",
        url: url,
        data: datastring,
        beforeSend: function () {
            spinner('show');
        },
        success: function (data, textStatus, jqXHR) {
            //$('#ajaxUpdateContainer').html(data);
            alert(data);
        }, complete: function (jqXHR, textStatus) {
            spinner('hide');
        }, error: function (jqXHR, textStatus, errorThrown) {
            spinner('hide');
        }
    });
}

function gettrackingReport(){
    var start_date = $('#start_date').val();
    var end_date = $('#end_date').val();
    var datastring = 'start_date=' + start_date + '&end_date=' + end_date+'&search_key_word='+$("#search_key_word").val();

    if(start_date =='' || end_date ==''){
        alert('Please select Start and End Date');
       return;
    }

    
    
    
    var url=baseUrl + "/Gassets/assetTrackingReport";
    //$('#ajaxUpdateContainerPdf').attr('href',url+'?report&'+datastring);
    
    $.ajax({
        method: "GET",
        url: url,
        data: datastring,
        beforeSend: function () {
            spinner('show');
        },
        success: function (data, textStatus, jqXHR) {
            $('#ajaxUpdateContainer').html(data);
        }, complete: function (jqXHR, textStatus) {
            spinner('hide');
        }, error: function (jqXHR, textStatus, errorThrown) {
            spinner('hide');
        }
    }); 
}


/*
* asset tracking, another way
*/

function assetTracking(obj,asset_id){

    var available = '';
    if($(obj).prop('checked')){
        var c = confirm("Are you sure this Asset is currently Available?");
        if(!c){
            $(obj).prop('checked',false);
            return;
        }
        available = 'YES';
        
    } else {
        var c = confirm("Are you sure this Asset is never found?");
        if(!c){
            $(obj).prop('checked',true);
            return;
        }
         available = 'NO';
    }
//alert(asset_id);
    
   //return;
    
    var datastring = 'available=' + available+'&asset_id='+asset_id;
    var url=baseUrl + "/Gassets/assetTracking";
    //un comment this line if you want to do printing function
    //$('#ajaxUpdateContainerPdf').attr('href',url+'?report&'+datastring);
    
    $.ajax({
        method: "GET",
        url: url,
        data: datastring,
        beforeSend: function () {
            spinner('show');
        },
        success: function (data, textStatus, jqXHR) {
            //$('#ajaxUpdateContainer').html(data);
            //alert(data);
        }, complete: function (jqXHR, textStatus) {
            spinner('hide');
        }, error: function (jqXHR, textStatus, errorThrown) {
            spinner('hide');
        }
    });
}

function filterAssets(){
    var asset_catg = $('#asset_category').val();
    var asset_loc = $('#location_id').val();
    var key_word = $('#search_key_word').val();

    var datastring = 'asset_catg='+asset_catg+'&asset_loc='+asset_loc+'&key_word='+key_word;
    var url = baseUrl + "/Gassets/assetList";
    $.ajax({
        method: "GET",
        url: url,
        data: datastring,
        beforeSend: function () {
            spinner('show');
        },
        success: function (data, textStatus, jqXHR) {
            $('#ajaxUpdateContainer').html(data);
            //alert(data);
        }, complete: function (jqXHR, textStatus) {
            spinner('hide');
        }, error: function (jqXHR, textStatus, errorThrown) {
            spinner('hide');
        }
    });
}

/*
* getLedgerStatement for bank reconciliation purpose
*/
function getLedgerStatement() {
    var start_date = $('#start_date').val();
    var end_date = $('#end_date').val();
    var acc_ledger = $('#acc_ledgers').val();
    
    if(start_date =='' || end_date =='' || acc_ledger ==''){
        alert('All field are required');
        return;
    }
    
    var datastring = 'start_date=' + start_date + '&end_date=' + end_date+'&acc_ledger=' + acc_ledger;
    var url=baseUrl + "/gledger/LedgerStatement";
    $('#ajaxUpdateContainerPdf').attr('href',url+'?report&'+datastring);
    
    $.ajax({
        method: "GET",
        url: url,
        data: datastring,
        beforeSend: function () {
            spinner('show');
        },
        success: function (data, textStatus, jqXHR) {
            $('#ajaxUpdateContainer').html(data);
        }, complete: function (jqXHR, textStatus) {
            spinner('hide');
        }, error: function (jqXHR, textStatus, errorThrown) {
            spinner('hide');
        }
    });
}

function saveBankReconciliation(obj){
    var formData = $("form#saveBankReconciliationForm").serialize();

    //var datastring = 'start_date=' + start_date + '&end_date=' + end_date+'&acc_ledger=' + acc_ledger;
    var url = baseUrl + "/gledger/saveBankReconciliation";

    //$('#ajaxUpdateContainerPdf').attr('href',url+'?report&'+datastring);
    $.ajax({
        method: "GET",
        url: url,
        data: formData,
        dataType: 'json',
        beforeSend: function () {
            spinner('show');
        },
        success: function (data, textStatus, jqXHR) {
            alert(data.message);
            if(data.status=='200'){
                window.location = baseUrl + "/gledger/viewBankReconciliationPdf/"+data.bank_reconc_id;
            }
            
           // $('#ajaxUpdateContainer').html(data);
        }, complete: function (jqXHR, textStatus) {
            spinner('hide');
        }, error: function (jqXHR, textStatus, errorThrown) {

            spinner('hide');
        }
    });
}


function getBankeconciliationReport(){
    var start_date = $('#start_date').val();
    var end_date = $('#end_date').val();
    var datastring = 'start_date=' + start_date + '&end_date=' + end_date+'&search_key_word='+$("#search_key_word").val();

    if(start_date =='' || end_date ==''){
        alert('Please select Start and End Date');
       return;
    }
    
    var url=baseUrl + "/Gledger/bankReconciliationReport";
    //$('#ajaxUpdateContainerPdf').attr('href',url+'?report&'+datastring);
    
    $.ajax({
        method: "GET",
        url: url,
        data: datastring,
        beforeSend: function () {
            spinner('show');
        },
        success: function (data, textStatus, jqXHR) {
            $('#ajaxUpdateContainer').html(data);
        }, complete: function (jqXHR, textStatus) {
            spinner('hide');
        }, error: function (jqXHR, textStatus, errorThrown) {
            spinner('hide');
        }
    }); 
}

function getInvoiceDetails(invo_id) {
    var datastring='invo_id='+invo_id;
    var url = baseUrl + "/gledger/creditnote";
    $.ajax({
        method: "GET",
        url: url,
        data: datastring,
        dataType: 'json',
        beforeSend: function () {
            spinner('show');
        },
        success: function (data, textStatus, jqXHR) {
            $('#invo_date').val(data.invoice_date);
            $('#invo_amount').val(data.Amount);
        }, complete: function (jqXHR, textStatus) {
            spinner('hide');
        }, error: function (jqXHR, textStatus, errorThrown) {
            spinner('hide');
        }
    });   
}
function getInvoiceDetails(invo_id) {
    var datastring='invo_id='+invo_id;
    var url = baseUrl + "/gledger/debtnote";
    $.ajax({
        method: "GET",
        url: url,
        data: datastring,
        dataType: 'json',
        beforeSend: function () {
            spinner('show');
        },
        success: function (data, textStatus, jqXHR) {
            $('#invo_date').val(data.invoice_date);
            $('#invo_amount').val(data.Amount);
        }, complete: function (jqXHR, textStatus) {
            spinner('hide');
        }, error: function (jqXHR, textStatus, errorThrown) {
            spinner('hide');
        }
    });   
}


function createClientInvoice(el,client_id){
    var url=baseUrl + "/Clients/invoice/"+client_id;
    var datastring = "client_id="+client_id+"&action=create";
    $.ajax({
        method: "GET",
        url: url,
        data: datastring,
        beforeSend: function () {
            spinner('show');
        },
        success: function (data, textStatus, jqXHR) {
            $("#mainDialog").dialog({
                title: 'Create New Client Invoice',
                width: '60%',
                height: '600',
                modal:true,
                resizable: false,
            }).html(data);
        }, complete: function (jqXHR, textStatus) {
            spinner('hide');
        }, error: function (jqXHR, textStatus, errorThrown) {
            spinner('hide');
        }
    }); 
    
}

function getClientInvoiceList(client_id){
    //alert("client id : "+client_id);
            var url=baseUrl + "/Clients/getInvoiceListByClientId/"+client_id;
            var datastring = '';
            $.ajax({
            method: "GET",
            url: url,
            data: datastring,
            beforeSend: function () {
                spinner('show');
            },
            success: function (data, textStatus, jqXHR) {
                //refresh the details list
                //console.log(data);
                $("#client_invoice_list").html(data);
            }, complete: function (jqXHR, textStatus) {
                spinner('hide');
            }, error: function (jqXHR, textStatus, errorThrown) {
                spinner('hide');
            }
        });
}

function agingStatementReport(supplier_id,aging_type) {
    var datastring = "supplier_id="+supplier_id+"&aging_type="+aging_type;
    var url=baseUrl + "/gledger/agingStatmentReport1";
    $('#ajaxUpdateContainerPdf').attr('href',url+'?report&'+datastring);
    
    $.ajax({
        method: "GET",
        url: url,
        data: datastring,
        beforeSend: function () {
            spinner('show');
        },
        success: function (data, textStatus, jqXHR) {
            $('#ajaxUpdateContainer').html(data);
        }, complete: function (jqXHR, textStatus) {
            spinner('hide');
        }, error: function (jqXHR, textStatus, errorThrown) {
            spinner('hide');
        }
    });
}

function agingStatementReport1() {
    var suplier_id = $('#supplier_id').val();
    
    if(suplier_id ==''){
        alert('Supplier field required');
        exit;
    }
    
    var datastring = 'supplier_id=' + suplier_id;
    var url=baseUrl + "/gledger/agingStatmentReport";
    $('#ajaxUpdateContainerPdf').attr('href',url+'?report&'+datastring);
    
    $.ajax({
        method: "GET",
        url: url,
        data: datastring,
        beforeSend: function () {
            spinner('show');
        },
        success: function (data, textStatus, jqXHR) {
            $('#ajaxUpdateContainer').html(data);
        }, complete: function (jqXHR, textStatus) {
            spinner('hide');
        }, error: function (jqXHR, textStatus, errorThrown) {
            spinner('hide');
        }
    });
}


/*function alert(message){
    $("<div></div>").dialog({
        title: 'Alert',
        modal:true,
        resizable: false,
        buttons: [
            {
                text: 'Ok',
                click: function(){
                    $(this).dialog('close');
                }
            }
        ]
    }).html(message);
}*/

function getSupplierInvoiceDetails(supplier_id,aging_type){
   //aging_type is the number of days
   
   //alert("supplier id:  "+supplier_id + " , number of days : " + aging_type); 
   var url=baseUrl + "Gledger/getSupplierInvoices";
    var datastring = "supplier_id="+supplier_id+"&aging_type="+aging_type;
    $.ajax({
        method: "GET",
        url: url,
        data: datastring,
        beforeSend: function () {
            spinner('show');
        },
        success: function (data, textStatus, jqXHR) {
            $("<div></div>").dialog({
                title: 'Supplier Invoice List',
                width: '60%',
                height: '600',
                modal:true,
                resizable: false,
            }).html(data);
        }, complete: function (jqXHR, textStatus) {
            spinner('hide');
        }, error: function (jqXHR, textStatus, errorThrown) {
            spinner('hide');
        }
    }); 
}