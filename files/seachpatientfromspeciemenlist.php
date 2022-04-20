<?php
include ("./includes/laboratory_result_header.php");
$query = mysqli_query($conn, "SELECT Sponsor_ID,Guarantor_Name FROM tbl_sponsor") or die(mysqli_error($conn));
$dataSponsor = '';
$dataSponsor.= '<option value="All">All Sponsors</option>';
while ($row = mysqli_fetch_array($query)) {
    $dataSponsor.= '<option value="' . $row['Sponsor_ID'] . '">' . $row['Guarantor_Name'] . '</option>';
}
$query_sub_cat = mysqli_query($conn, "SELECT its.Item_Subcategory_ID,its.Item_Subcategory_Name FROM `tbl_items` i JOIN tbl_item_subcategory its ON its.Item_Subcategory_ID=i.Item_Subcategory_ID WHERE i.`Consultation_Type`='Laboratory' GROUP BY its.Item_Subcategory_ID ") or die(mysqli_error($conn));
$sub_category = '<option value="All">All Department</option>';
while ($row = mysqli_fetch_array($query_sub_cat)) {
    $sub_category.= '<option value="' . $row['Item_Subcategory_ID'] . '">' . $row['Item_Subcategory_Name'] . '</option>';
}
$sql_date_time = mysqli_query($conn, "select now() as Date_Time ") or die(mysqli_error($conn));
while ($date = mysqli_fetch_array($sql_date_time)) {
    $Current_Date_Time = $date['Date_Time'];
}
$Filter_Value = substr($Current_Date_Time, 0, 11);
$Start_Date = $Filter_Value . ' 00:00';
$End_Date = $Current_Date_Time;;
echo '
<style>
    .dates{
        color:#cccc00;
    }
</style>
<fieldset style="margin-top:15px;">
    <legend id="resultsconsultationLablist" style="font-weight: bold;background-color: #006400; padding:10px; color:white;width: auto"><b id="dateRange">LAB RESULTS - </b></legend>
    <script language="javascript" type="text/javascript">
    </script>

    <!--<form action="" method="POST">-->
    <center>
        <table  class="hiv_table" style="width:100%;margin-top:5px;">
            <tr> 
                <td style="width: 20px;text-align:center ">
                    <input type="text" autocomplete="off" style=\'text-align: center;width:15%;display:inline\' value="';
echo $Start_Date;
echo '" id="Date_From" placeholder="Start Date"/>
                    <input type="text" autocomplete="off" style=\'text-align: center;width:15%;display:inline\' value="';
echo $End_Date;
echo '" id="Date_To" placeholder="End Date"/>&nbsp;
                    <select id="sponsorID" style=\'text-align: center;padding:4px; width:15%;display:inline\'>
';
echo $dataSponsor;
echo '                    </select>
                    <select id="subcategory_ID" style=\'text-align: center;padding:4px; width:15%;display:inline\'>
';
echo $sub_category;
echo '                    </select>
                    <input type="button" value="Filter" class="art-button-green" onclick="filterLabpatient()">
                    <input type="text" autocomplete="off" style=\'text-align: center;width:11%;display:inline\' id="searchname"  oninput="filterLabpatient()" placeholder="Patient Name"/>
                    <input type="text" autocomplete="off" style=\'text-align: center;width:11%;display:inline\' id="seach_patient_id"  oninput="filterLabpatient()" placeholder="Patient Number"/>
                    <input type="text" autocomplete="off" style=\'text-align: center;width:8%;display:inline\' id="searchspecmen_id" placeholder="Specimen ID"  oninput="filterLabpatient()" /></td>
            <input type="text" autocomplete="off" class="hide" style=\'text-align: center;width:8%;display:inline\' id="searchbarcode" placeholder="Search BarCode"  oninput="searchbarcode(this.value)" /></td>

            </tr> 
        </table>
    </center>
    <!--</form>-->

    <center>
        <table  class="hiv_table" style="width:100%">
            <tr>
                <td>
                    <div align="center" style="display:none" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>
                    <div id=\'Search_Iframe\' style="width:100%;height:375px;overflow-x: hidden;overflow-y: auto">
';
require_once 'getPatientfromspeciemenlist.php';;
echo '                    </div>
                </td>
            </tr>
        </table>
    </center>
</fieldset>


<div id="labResults" style="display: none;height:340px;overflow-x:hidden;overflow-y:auto   ">
    <div align="center" style="display: none" id="progressDialogStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>
    <div id="container" style="display:none">
        <div id="default">
            <h1>#{title}</h1>
            <p>#{text}</p>
        </div>
    </div>
    <div id="showLabResultsHere"></div>

</div>

<div id="doctorReview" style="display: none;height:340px;overflow-x:hidden;overflow-y:scroll   ">
    <div align="center" style="display: none" id="progressDoctorReviewStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>

    <div id="doctorReviewData"></div>

</div>

<div id="labGeneral" style="display: none">
    <div id="showGeneral"></div>

</div>
<div id="historyResults1" style="display:none" >


</div>
<br/>
<center>
';
if (isset($_SESSION['userinfo'])) {
    if ($_SESSION['userinfo']['Laboratory_Works'] == 'yes') {
        echo "<a href='patientremovedfromspeciemenlist.php' class='art-button-green'>PATIENTS REMOVED FROM LIST</a>";
    }
};
echo '</center>
    ';
include ("./includes/footer.php");
?>
<!--<link rel="stylesheet" href="js/css/ui-lightness/jquery-ui-1.8.23.custom.css">-->

<link rel="stylesheet" href="css/uploadfile.css" media="screen">
<link rel="stylesheet" href="media/css/jquery.dataTables.css" media="screen">
<link rel="stylesheet" href="media/themes/smoothness/dataTables.jqueryui.css" media="screen">
<link rel="stylesheet" href="js/fancybox/jquery.fancybox.css" type="text/css" media="screen" />   
<script src="js/jquery-1.8.0.min.js"></script>
<script src="js/jquery-ui-1.8.23.custom.min.js"></script><script src="css/jquery.datetimepicker.js"></script>

<script src="css/jquery.js"></script>
<script src="css/jquery.datetimepicker.js"></script>
<script src="css/jquery-ui.js"></script>
<script src="css/scripts.js"></script>
<script src="js/jquery.form.js"></script>
<script type="text/javascript" src="js/fancybox/jquery.fancybox.pack.js"></script>
<script src="media/js/jquery.dataTables.js" type="text/javascript"></script>

<!--<link rel="stylesheet" href="css/dialog/zebra_dialog.css" media="screen">
<script src="js/zebra_dialog.js"></script>
<script src="js/ehms_zebra_dialog.js"></script>-->

<link rel="stylesheet" href="css/ui.notify.css" media="screen">
<script src="js/jquery.notify.min.js"></script> 

<script>
                        function create(template, vars, opts) {
                            return $container.notify("create", template, vars, opts);
                        }
</script>

<script>
    function saveInformation(product_name, ppil, id) {
        var filter = $("#filter").val();
        attachFIlesLabsave_ = '1';
        attachFIlesLabvaldate_ = $('#attachFIlesLabvaldate_').val();
        attachFIlesLabsubmit_ = $('#attachFIlesLabsubmit_').val();
        labfile_ = $("#labfile_").prop('files')[0];

        var selectedID = [];
        Comments = [];
        otherResults = [];
        $(':checkbox[name="cPatient_Payment_Item_List_ID_n_status[]"]:checked').each(function () {
            selectedID.push(this.id);
            Comments.push($('#overallRemarks_'+this.id).val());
            otherResults.push($('#otherResult_'+this.id).val());
        });

        var form_data = new FormData();
        form_data.append('attachFIlesLabsave', attachFIlesLabsave_);
        form_data.append('attachFIlesLabvaldate', attachFIlesLabvaldate_);
        form_data.append('attachFIlesLabsubmit', attachFIlesLabsubmit_);
        form_data.append('cache_ID', selectedID);
        form_data.append('otherResult', otherResults);
        form_data.append('Registration_id', id);
        form_data.append('overallRemarks', Comments);
        form_data.append('file', labfile_);

        // alert(labfile_);
        // exit();
        if(selectedID.length == 0){
            alert("Please Select Tests To Upload Results");
            exit();
        }else{
            if(confirm("Are you Sure you want to Save Selected Results?")){
                    $.ajax({
                        type: "POST",
                        url: "attachmentLabResult.php",
                        data: form_data,
                        cache:false,
                        processData: false,
                        contentType: false,
                        dataType: "script",
                        success: function (response) {
                            //alertMsg(response, "", 'information', 0, false, 3000, "right - 20,top + 20", true, false, false, 0, false);

                            if (response.includes("1")) {
                                // $('#progressDialogStatus').hide();
                                create("default", {title: 'Success', text: 'Results Saved successifully'});
                                //alertMsg("Saved successifullynnnn", "", 'information', 0, false, 3000, "right - 20,top + 20", true, false, false, 0, false);
                                updateDialog(product_name, ppil, id, filter);
                                test_connection_to_ehms_mr_online(product_name, ppil, id, filter);

                            }
                        }
                    // complete: function (jqXHR, textStatus) {
                    //     $('#progressDialogStatus').hide();
                    // }

                });
            }
        // return false;
        }
    }

    function validateInformation(product_name, ppil, id, filter) {
        var filter = $("#filter").val();
        attachFIlesLabsave_ = '';
        attachFIlesLabvaldate_ = '1';
        attachFIlesLabsubmit_ = $('#attachFIlesLabsubmit_').val();
        labfile_ = $("#labfile_").val();
        var selectedID = [];
        Comments = [];
        otherResults = [];
        $(':checkbox[name="cPatient_Payment_Item_List_ID_n_status[]"]:checked').each(function () {
            selectedID.push(this.id);
            Comments.push($('#overallRemarks_'+this.id).val());
            otherResults.push($('#otherResult_'+this.id).val());
        });

        if(selectedID.length == 0){
            alert("Please Select Tests To Upload Results");
            exit();
        }else{
            if(confirm("Are you Sure you want to Validate Selected Results?")){
                $.ajax({
                    type: "POST",
                    url: "attachmentLabResult.php",
                    data: {
                        attachFIlesLabsubmit:attachFIlesLabsubmit_,
                        attachFIlesLabvaldate:attachFIlesLabvaldate_,
                        attachFIlesLabsubmit:attachFIlesLabsubmit_,
                        cache_ID:selectedID,
                        attachFIlesLabsubmit:attachFIlesLabsubmit_,
                        otherResult:otherResults,
                        overallRemarks:Comments,
                        attachFIlesLabsave:attachFIlesLabsave_,
                        labfile:labfile_,
                        Registration_id:id
                    },
                    cache:false,
                    success: function (response) {
                        //alertMsg(response, "", 'information', 0, false, 3000, "right - 20,top + 20", true, false, false, 0, false);

                        if (response.includes("1")) {
                            // $('#progressDialogStatus').hide();
                            create("default", {title: 'Success', text: 'Results Validated Successifully'});
                            //alertMsg("Saved successifullynnnn", "", 'information', 0, false, 3000, "right - 20,top + 20", true, false, false, 0, false);
                            // updateDialog(product_name, ppil, id, filter);

                        }
                    }
                    // complete: function (jqXHR, textStatus) {
                    //     $('#progressDialogStatus').hide();
                    // }

                });
            }
        // return false;
        }
    }
    function SubmitInformation(product_name, ppil, id,filter) {
        var filter = $("#filter").val();
        attachFIlesLabsave_ = '';
        attachFIlesLabvaldate_ = '';
        attachFIlesLabsubmit_ = '1';
        labfile_ = $("#labfile_").val();
        var selectedID = [];
        Comments = [];
        otherResults = [];
        $(':checkbox[name="cPatient_Payment_Item_List_ID_n_status[]"]:checked').each(function () {
            selectedID.push(this.id);
            Comments.push($('#overallRemarks_'+this.id).val());
            otherResults.push($('#otherResult_'+this.id).val());
        });

        if(selectedID.length == 0){
            alert("Please Select Tests To Upload Results");
            exit();
        }else{
            if(confirm("Are you Sure you want to Submit Selected Results?")){
                $.ajax({
                    type: "POST",
                    url: "attachmentLabResult.php",
                    data: {
                        attachFIlesLabsubmit:attachFIlesLabsubmit_,
                        attachFIlesLabvaldate:attachFIlesLabvaldate_,
                        attachFIlesLabsubmit:attachFIlesLabsubmit_,
                        cache_ID:selectedID,
                        attachFIlesLabsubmit:attachFIlesLabsubmit_,
                        otherResult:otherResults,
                        overallRemarks:Comments,
                        attachFIlesLabsave:attachFIlesLabsave_,
                        labfile:labfile_,
                        Registration_id:id
                    },
                    cache:false,
                    success: function (response) {
                        //alertMsg(response, "", 'information', 0, false, 3000, "right - 20,top + 20", true, false, false, 0, false);

                        if (response.includes("1")) {
                            // $('#progressDialogStatus').hide();
                            create("default", {title: 'Success', text: 'Results Submitted successifully'});
                            //alertMsg("Saved successifullynnnn", "", 'information', 0, false, 3000, "right - 20,top + 20", true, false, false, 0, false);
                            updateDialog(product_name, ppil, id, filter);

                        }
                    }
                    // complete: function (jqXHR, textStatus) {
                    //     $('#progressDialogStatus').hide();
                    // }

                });
            }
        // return false;
        }
    }

</script>

<script>
    function filterLabpatient() {
        var Date_From = document.getElementById('Date_From').value;
        var Date_To = document.getElementById('Date_To').value;
        var Sponsor = document.getElementById('sponsorID').value;
        var subcategory_ID = document.getElementById('subcategory_ID').value;
        var patient_name = document.getElementById('searchname').value;
        var seach_patient_id = document.getElementById('seach_patient_id').value;
        var searchspecmen_id = document.getElementById('searchspecmen_id').value;

        if (Date_From != '' && Date_To == '') {
            alert('Please end date to filter');
            exit;
        }

        if (Date_From == '' && Date_To != '') {
            alert('Please start date to filter');
            exit;
        }

        if (Date_From != '' && Date_To != '') {
            document.getElementById('dateRange').innerHTML = "LAB RESULTS - UNCONSULTED PATIENTS FROM <span class='dates'>" + Date_From + "</span> TO <span class='dates'>" + Date_To + "</span>";
        }

        $.ajax({
            type: 'GET',
            url: 'getPatientfromspeciemenlist.php',
            data: 'filterlabpatientdate=true&Date_From=' + Date_From + '&Date_To=' + Date_To + '&Sponsor=' + Sponsor + '&subcategory_ID=' + subcategory_ID + '&patient_name=' + patient_name + '&seach_patient_id=' + seach_patient_id + '&searchspecmen_id=' + searchspecmen_id,
            beforeSend: function (xhr) {
                $('#progressStatus').show();
            },
            success: function (html) {
                $('#Search_Iframe').html(html);
                $.fn.dataTableExt.sErrMode = 'throw';
                $('#resultsPatientList').DataTable({
                    "bJQueryUI": true
                });
            }, complete: function (jqXHR, textStatus) {
                $('#progressStatus').hide();
            }
        });

    }
</script> 
<script>
    $('#Date_From').datetimepicker({
        dayOfWeekStart: 1,
        lang: 'en',
        //startDate:    'now'
    });
    $('#Date_From').datetimepicker({value: '', step: 1});
    $('#Date_To').datetimepicker({
        dayOfWeekStart: 1,
        lang: 'en',
        //startDate:'now'
    });
    $('#Date_To').datetimepicker({value: '', step: 1});
//    
</script>
<script>
    function searchbarcode(value) {
        $.ajax({
            type: 'POST',
            url: 'getPatientfromspeciemenlist.php',
            data: 'action=barcode&value=' + value,
            beforeSend: function (xhr) {
                document.getElementById('Search_Iframe').innerHTML = '<div align="center" style="" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>';

            },
            success: function (html) {
                if (html != '') {
                    $('#Search_Iframe').html(html);
                    $.fn.dataTableExt.sErrMode = 'throw';
                    $('#resultsPatientList').DataTable({
                        "bJQueryUI": true

                    });
                }
            }
        });
    }
</script>
<div id='intergrated_result_dialog'></div>
<div id='tb_result_dialog'></div>
<div id='Biopsy_Result_Form'></div>
<script type="text/javascript">
    function combine_sample(id) {
        var selectedID = [];
        $(':checkbox[name="cPatient_Payment_Item_List_ID_n_status[]"]:checked').each(function () {
            selectedID.push(this.id);
        });
         $.ajax({
            type: 'GET',
            url: 'combineResults.php',
            data: {id:id,payment_id:selectedID},
            cache: false,
            success: function (html) {
                alert(html);
            }
        });
    }

    $(document).on('click', '.searchresults', function () {
        var barcode = $('#searchbarcode').val();
        var patient = $(this).attr('name');
        var id = $(this).attr('id');
        var payment_id = $(this).attr('payment_id');
        // alert(payment_id);
        // exit();

        $.ajax({
            type: 'GET',
            url: 'requests/testResults.php',
            data: 'action=getResult&id=' + id + '&payment_id=' + payment_id + '&barcode=' + barcode,
            cache: false,
            success: function (html) {
                //alert(html);
                $('#showLabResultsHere').html(html);
            }
        });



        $('#labResults').dialog({
            modal: true,
            width: '90%',
            minHeight: 450,
            resizable: true,
            draggable: true
        });

        $("#labResults").dialog('option', 'title', patient + '  ' + 'No.' + id);
    });
    function get_result_from_integrated_machine(Product_Name, Payment_Item_Cache_List_ID,id) {
        $.ajax({
            type: 'POST',
            url: 'ajax_get_result_from_integrated_machine.php',
            data: {Payment_Item_Cache_List_ID: Payment_Item_Cache_List_ID, Product_Name: Product_Name,Registration_ID:id},
            success: function (data) {
                $("#intergrated_result_dialog").dialog({
                    title: Product_Name + ' PATIENT LAB RESULTS',
                    width: '90%',
                    height: '600',
                    modal: true,
                    resizable: false,
                }).html(data);
            }
        });
    }

    function send_patient_lab_result(Product_Name, Payment_Item_Cache_List_ID, result_date) {
        var selectedID = [];
        var selectedID = [];
        Comments = [];
        otherResults = [];
        $(':checkbox[name="cPatient_Payment_Item_List_ID_n_status[]"]:checked').each(function () {
            selectedID.push(this.id);
            Comments.push($('#overallRemarks_'+this.id).val());
            otherResults.push($('#otherResult_'+this.id).val());
        });
        if(selectedID.length == 0){
            alert("Please Select Tests To Validate");
            exit();
        }else{
            $.ajax({
                type: 'POST',
                url: 'ajax_send_patient_lab_result.php',
                data: {Payment_Item_Cache_List_ID: Payment_Item_Cache_List_ID, result_date: result_date, selectedID:selectedID},
                success: function (data) {
                    $("#intergrated_result_dialog").dialog("close");
                    get_result_from_integrated_machine(Product_Name, Payment_Item_Cache_List_ID)
                    console.log(data);
                }
            });
        }
    }
    function preview_intergrated_lab_result(Product_Name, Payment_Item_Cache_List_ID, result_date) {
        window.open("preview_ntergrated_lab_result.php?Product_Name=" + Product_Name + "&Payment_Item_Cache_List_ID=" + Payment_Item_Cache_List_ID + '&result_date=' + result_date, "_blank");
    }

    $('#resultsProvidedList').click(function () {
        $('#resultsconsultationLablist').text('CONSULTED LAB PATIENTS LIST')
        $.ajax({
            type: 'POST',
            url: 'getPatientfromspeciemenlist.php',
            data: 'action=consultedPatients&value=t',
            success: function (html) {
                $('#Search_Iframe').html(html);
            }
        });
    });


    $('#performanceReport').click(function () {
        $('#resultsconsultationLablist').text('PERFORMANCE REPORT');
        $('#Search_Iframe').html('performance report here');

    });




    $(document).on('click', '.removing', function () {
        var payID = $(this).attr('id');
        if (confirm('Are you sure you want to remove this patient from list?')) {
            $.ajax({
                type: 'POST',
                url: 'requests/removefromResultsList.php',
                data: 'removefromList=remove&payID=' + payID,
                success: function (html) {
                    //alert(html);     
                    window.location.href = 'seachpatientfromspeciemenlist.php';

                }
            });
        }
    });
</script>

<script>
    function doctorReview(Patient_Name, Registration_ID) {
        var filter = $('#reg_' + Registration_ID).attr('filter');
        // alert(filter);exit;

        var dataString = 'doctorReview=true&Registration_ID=' + Registration_ID + '&filter=' + filter;

        $('#doctorReview').dialog({
            modal: true,
            width: '70%',
            height: 440,
            resizable: true,
            draggable: true
        });

        //alert('doctorReview=true&Registration_ID=' + Registration_ID + '&filter=' + filter);
        $.ajax({
            type: 'GET',
            url: 'requests/labDoctorReview.php',
            data: dataString,
            beforeSend: function (xhr) {
                $('#progressDoctorReviewStatus').show();
            },
            success: function (result) {
                $('#doctorReviewData').html(result);
            }, complete: function (jqXHR, textStatus) {
                $('#progressDoctorReviewStatus').hide();
            }
        });



        $("#doctorReview").dialog('option', 'title', Patient_Name + '  ' + 'No.' + Registration_ID);
    }
</script>

<script>
    $(document).ready(function () {
        filterLabpatient()
        $('#searchbarcode').val('');
        $('#resultsPatientList').DataTable({
            "bJQueryUI": true
        });

        $container = $("#container").notify();
        //alert('am here');
    });
</script>

<!--End datetimepicker-->

<script>
    function tb_result_form(Product_Name,Payment_Item_Cache_List_ID,id,Employee_ID){
    $.ajax({
        type:'POST',
        url:'ajax_tb_result_form.php',
        data:{Payment_Item_Cache_List_ID:Payment_Item_Cache_List_ID,Product_Name:Product_Name,id:id,Employee_ID:Employee_ID},
        success:function(data){
                $("#tb_result_dialog").dialog({
                        title: Product_Name+' PATIENT TB RESULTS FORM',
                        width: '90%',
                        height: '800',
                        modal: true,
                        resizable: false,
                    }).html(data);
        }
    });
}


//TB FORM ENDS

function Open_Biopsy_Results(Product_Name,Payment_Item_Cache_List_ID,Registration_ID){

    $.ajax({
        type:'GET',
        url:'biopsy_results_form.php',
        data:{Registration_ID:Registration_ID,Payment_Item_Cache_List_ID:Payment_Item_Cache_List_ID,Product_Name:Product_Name},
        success:function(data){
                $("#Biopsy_Result_Form").dialog({
                        title:'BIOPSY/HISTOLOGICAL EXAMINATION RESULT FORM FOR:- '+Product_Name,
                        width: '70%',
                        height: '800',
                        modal: true,
                        resizable: false,
                    }).html(data);
        }
    });
}

function update_results_biopsy(Biopsy_ID){
        var New_Case = $("#New_Case").val();
        var relevant_clinical_data = $("#relevant_clinical_data").val();
        var Laboratory_Number = $("#Laboratory_Number").val();
        var Site_Biopsy = $("#Site_Biopsy").val();
        var Previous_Laboratory = $("#Previous_Laboratory").val();
        var Duration_Condition = $("#Duration_Condition").val();
        var Comments = $("#Comments").val();
        var Referred_From = $("#Referred_From").val();

            $.ajax({
                type: 'GET',
                url: 'update_save_biopsy_results.php',
                data: {Biopsy_ID:Biopsy_ID,New_Case:New_Case,relevant_clinical_data:relevant_clinical_data,Laboratory_Number:Laboratory_Number,Site_Biopsy:Site_Biopsy,Previous_Laboratory:Previous_Laboratory,Duration_Condition:Duration_Condition,Comments:Comments,Referred_From:Referred_From},
                success: function (responce){ 
                }
            });
        }

        function save_biopsy_results(Biopsy_ID,Employee_ID){
            if(confirm("Are you sure you want to Submit this Biopsy Results Form?")){
                $.ajax({
                    type: 'GET',
                    url: 'save_biopsy_results.php',
                    data: {Biopsy_ID:Biopsy_ID,Employee_ID:Employee_ID},
                    success: function (responce){ 
                        alert("Biopsy Results was Saved Successfully!");
                        $("#Biopsy_Result_Form").dialog("close");
                    }
                });
            }
        }
</script>