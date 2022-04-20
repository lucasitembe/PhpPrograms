<?php
include("./includes/laboratory_result_header.php");

$query = mysqli_query($conn,"SELECT Sponsor_ID,Guarantor_Name FROM tbl_sponsor") or die(mysqli_error($conn));
$dataSponsor = '';
$dataSponsor.='<option value="All">All Sponsors</option>';

while ($row = mysqli_fetch_array($query)) {
    $dataSponsor.= '<option value="' . $row['Sponsor_ID'] . '">' . $row['Guarantor_Name'] . '</option>';
}

//Lab subcategory
$query_sub_cat = mysqli_query($conn,"SELECT its.Item_Subcategory_ID,its.Item_Subcategory_Name FROM `tbl_items` i JOIN tbl_item_subcategory its ON its.Item_Subcategory_ID=i.Item_Subcategory_ID WHERE i.`Consultation_Type`='Laboratory' GROUP BY its.Item_Subcategory_ID ") or die(mysqli_error($conn));

$sub_category = '<option value="All">All Department</option>';

while ($row = mysqli_fetch_array($query_sub_cat)) {
    $sub_category.= '<option value="' . $row['Item_Subcategory_ID'] . '">' . $row['Item_Subcategory_Name'] . '</option>';
}
?>

<style>
    .dates{
        color:#cccc00;
    }
</style>
<fieldset style="margin-top:20px;">
    <legend id="resultsconsultationLablist" style="font-weight: bold;background-color: #006400; padding:10px; color:white;width: auto"><b id="dateRange">VIEW LAB RESULTS -  </b></legend>
    <script language="javascript" type="text/javascript">
    </script>


    <center>
        <table  class="hiv_table" style="width:100%;margin-top:5px;">
            <tr> 
                <td style="width: 20px;text-align:center ">
                    <input type="text" autocomplete="off" style='text-align: center;width:15%;display:inline' id="Date_From" placeholder="Start Date"/>
                    <input type="text" autocomplete="off" style='text-align: center;width:15%;display:inline' id="Date_To" placeholder="End Date"/>&nbsp;
                    <select id="sponsorID" style='text-align: center;padding:4px; width:15%;display:inline'>
                        <?php echo $dataSponsor ?>
                    </select>
                    <select id="subcategory_ID" style='text-align: center;padding:4px; width:15%;display:inline'>
                        <?php echo $sub_category ?>
                    </select>
                    <input type="button" value="Filter" class="art-button-green" onclick="filterLabpatient()">
                    <input type="text" autocomplete="off" style='text-align: center;width:11%;display:inline' id="searchname"  oninput="filterLabpatient()" placeholder=" Patient Name"/>
                    <input type="text" autocomplete="off" style='text-align: center;width:11%;display:inline' id="seach_patient_id"  oninput="filterLabpatient()" placeholder=" Patient Number"/>
                    <input type="text" autocomplete="off" style='text-align: center;width:8%;display:inline' id="searchspecmen_id" placeholder="Specimen ID"  oninput="filterLabpatient()" /></td>
            <input type="text" autocomplete="off" class="hide" id="searchbarcode" style='text-align: center;width:11%;display:inline' placeholder="Search BarCode"  oninput="searchbarcode(this.value)" /></td>

<!--                                                            <td style="text-align:right;width:10px"><b>Date From<b></td>
       <td width="70px"><input type='text' name='Date_From' id='date_From' required="required"></td>
       <td style="text-align:right;width:10px"><b>Date To<b></td>
       <td width="70px"><input type='text' name='Date_To' id='date_To' required="required"></td>
       <td width="30px"><input type="submit" name="submit" value="Filter" class="art-button-green" /></td>-->
            </tr> 
        </table>
    </center>


    <hr width="100%">
    <center>
        <table  class="hiv_table" style="width:100%">
            <tr>
                <td>
                    <div align="center" style="display:none" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>

                    <div id='Search_Iframe' style="width:100%;height:420px;overflow-x: hidden;overflow-y: auto">
                        <?php include 'getConsultedPatientfromspeciemenlist.php'; ?>
                    </div>
                </td>
            </tr>
        </table>
    </center>
</fieldset>


<div id="labResults" style="display: none">
    <div align="center" style="display: none" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>
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
    <div align="center" style="display: none" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>

    <div id="showGeneral"></div>

</div>
<div id="historyResults1" style="display:none">


</div>
<br/>
<?php
include("./includes/footer.php");
?>
<link rel="stylesheet" href="css/uploadfile.css" media="screen">
<link rel="stylesheet" href="media/css/jquery.dataTables.css" media="screen">
<link rel="stylesheet" href="media/themes/smoothness/dataTables.jqueryui.css" media="screen">
<link rel="stylesheet" href="js/fancybox/jquery.fancybox.css" type="text/css" media="screen" />


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
            document.getElementById('dateRange').innerHTML = "VIEW LAB RESULTS - CONSULTED PATIENTS FROM <span class='dates'>" + Date_From + "</span> TO <span class='dates'>" + Date_To + "</span>";
        }

        $.ajax({
            type: 'GET',
            url: 'getConsultedPatientfromspeciemenlist.php',
            data: 'filterlabpatientdate=true&Date_From=' + Date_From + '&Date_To=' + Date_To + '&Sponsor=' + Sponsor + '&subcategory_ID=' + subcategory_ID + '&patient_name=' + patient_name+'&seach_patient_id='+seach_patient_id+'&searchspecmen_id='+searchspecmen_id,
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
            type: 'GET',
            url: 'getConsultedPatientfromspeciemenlist.php',
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
<div id="lab_in_result_div"></div>
<script>
function preview_intergrated_lab_result(Product_Name,Payment_Item_Cache_List_ID,result_date){
    window.open("preview_ntergrated_lab_result.php?Product_Name="+Product_Name+"&Payment_Item_Cache_List_ID="+Payment_Item_Cache_List_ID+"&result_date="+result_date,"_blank");
 }
    function get_result_from_integrated_machine(Product_Name,Payment_Item_Cache_List_ID,result_date){
    $.ajax({
        type:'POST',
        url:'ajax_get_result_from_integrated_machine.php',
        data:{Payment_Item_Cache_List_ID:Payment_Item_Cache_List_ID,Product_Name:Product_Name,result_date:result_date},
        success:function(data){
                $("#lab_in_result_div").dialog({
                        title: Product_Name+' PATIENT LAB RESULTS',
                        width: '90%',
                        height: '600',
                        modal: true,
                        resizable: false,
                    }).html(data);
        }
    });
}
function validate_patient_lab_result(Product_Name,Payment_Item_Cache_List_ID,result_date){
   $.ajax({
        type:'POST',
        url:'ajax_validate_patient_lab_result.php',
        data:{Payment_Item_Cache_List_ID:Payment_Item_Cache_List_ID,result_date:result_date},
        success:function(data){
             get_result_from_integrated_machine(Product_Name,Payment_Item_Cache_List_ID,result_date) 
             console.log(data);
        }
    }); 
}
function send_patient_lab_result(Product_Name,Payment_Item_Cache_List_ID,result_date){
   $.ajax({
        type:'POST',
        url:'ajax_send_patient_lab_result.php',
        data:{Payment_Item_Cache_List_ID:Payment_Item_Cache_List_ID,result_date:result_date},
        success:function(data){
             get_result_from_integrated_machine(Product_Name,Payment_Item_Cache_List_ID,result_date) 
             console.log(data);
        }
    }); 
}
    function doctorReview(Patient_Name, Registration_ID) {
        var filter = $('#reg_' + Registration_ID).attr('filter');
        // alert(filter);exit;

        var dataString = 'doctorReview=true&consulted=true&Registration_ID=' + Registration_ID + '&filter=' + filter;

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
        $('.fancybox').fancybox();
        $('#resultsPatientList').DataTable({
            "bJQueryUI": true
        });

        $container = $("#container").notify();
    });
</script>
<!--End datetimepicker-->
